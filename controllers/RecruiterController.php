<?php
/**
 * File: app/controllers/RecruiterController.php
 * Đường dẫn: Module5_UngTuyenVaQuanLyHoSo/controllers/RecruiterController.php
 * Chức năng: Điều phối nghiệp vụ phía Nhà tuyển dụng - xem danh sách hồ sơ ứng tuyển
 *            vào các tin của công ty mình, xem chi tiết, cập nhật trạng thái và
 *            tự động gửi email thông báo cho ứng viên khi trạng thái thay đổi.
 */

require_once ROOT_PATH . '/models/ApplicationModel.php';
require_once ROOT_PATH . '/models/JobModel.php';
require_once ROOT_PATH . '/helpers/AuthHelper.php';
require_once ROOT_PATH . '/helpers/ResponseHelper.php';
require_once ROOT_PATH . '/services/EmailService.php';

class RecruiterController
{
	/**
	 * @var ApplicationModel
	 */
	private $hoSoUngTuyenModel;

	/**
	 * @var JobModel
	 */
	private $tinTuyenDungModel;

	/**
	 * @var EmailService
	 */
	private $emailService;

	/**
	 * Danh sách trạng thái hợp lệ, dùng để validate input trước khi update.
	 *
	 * @var array
	 */
	private $danhSachTrangThaiHopLe = array(
		STATUS_MOI_NOP,
		STATUS_DA_XEM,
		STATUS_HEN_PHONG_VAN,
		STATUS_NHAN_VIEC,
		STATUS_TU_CHOI,
	);

	public function __construct()
	{
		$this->hoSoUngTuyenModel = new ApplicationModel();
		$this->tinTuyenDungModel = new JobModel();
		$this->emailService = new EmailService();
	}

	/**
	 * Hiển thị danh sách hồ sơ ứng tuyển vào các tin của Nhà tuyển dụng đang đăng nhập.
	 * Hỗ trợ lọc theo tin tuyển dụng và theo trạng thái qua query string.
	 *
	 * @return void
	 */
	public function showList()
	{
		AuthHelper::requireRole(ROLE_NHATUYENDUNG);

		$maNhaTuyenDung = AuthHelper::getCurrentUserId();
		
		$maTinLoc = isset($_GET['maTin']) && $_GET['maTin'] !== '' ? trim($_GET['maTin']) : null;
		$trangThaiLoc = isset($_GET['trangThai']) && $_GET['trangThai'] !== '' ? trim($_GET['trangThai']) : null;

		$danhSachHoSoUngTuyen = $this->hoSoUngTuyenModel->getListForRecruiter(
			$maNhaTuyenDung, 
			$maTinLoc, 
			$trangThaiLoc
		);
		
		$danhSachTinTuyenDung = $this->tinTuyenDungModel->getJobsByRecruiter($maNhaTuyenDung);
		$thongBao = ResponseHelper::getFlash();

		require ROOT_PATH . '/views/recruiter/applicationList.php';
	}

	/**
	 * Hiển thị chi tiết một hồ sơ ứng tuyển, chỉ khi hồ sơ đó thuộc tin
	 * tuyển dụng của chính Nhà tuyển dụng đang đăng nhập.
	 *
	 * @return void
	 */
	public function showDetail()
	{
		AuthHelper::requireRole(ROLE_NHATUYENDUNG);

		$maHoSo = isset($_GET['maHS']) ? trim($_GET['maHS']) : '';
		$maNhaTuyenDung = AuthHelper::getCurrentUserId();

		$hoSoUngTuyen = $this->hoSoUngTuyenModel->getDetailForRecruiter($maHoSo, $maNhaTuyenDung);

		if (!$hoSoUngTuyen) {
			ResponseHelper::setFlash('error', 'Ho so khong ton tai hoac khong thuoc cong ty ban.');
			AuthHelper::redirect(BASE_URL . '/index.php?action=recruiterList');
		}

		$thongBao = ResponseHelper::getFlash();

		require ROOT_PATH . '/views/recruiter/applicationDetail.php';
	}

	/**
	 * Xử lý cập nhật trạng thái hồ sơ ứng tuyển (POST) và tự động gửi email
	 * thông báo tương ứng cho ứng viên.
	 *
	 * @return void
	 */
	public function updateStatus()
	{
		AuthHelper::requireRole(ROLE_NHATUYENDUNG);

		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			AuthHelper::redirect(BASE_URL . '/index.php?action=recruiterList');
		}

		$maHoSo = isset($_POST['maHS']) ? trim($_POST['maHS']) : '';
		$trangThaiMoi = isset($_POST['trangThai']) ? trim($_POST['trangThai']) : '';
		$maNhaTuyenDung = AuthHelper::getCurrentUserId();

		try {
			if (!in_array($trangThaiMoi, $this->danhSachTrangThaiHopLe, true)) {
				throw new Exception('Trang thai khong hop le.');
			}

			// Xác thực hồ sơ thuộc về đúng Nhà tuyển dụng trước khi cho phép cập nhật
			$hoSoUngTuyen = $this->hoSoUngTuyenModel->getDetailForRecruiter($maHoSo, $maNhaTuyenDung);
			if (!$hoSoUngTuyen) {
				throw new Exception('Ho so khong ton tai hoac khong thuoc cong ty ban.');
			}

			$capNhatThanhCong = $this->hoSoUngTuyenModel->updateStatus($maHoSo, $trangThaiMoi);
			if (!$capNhatThanhCong) {
				throw new Exception('Cap nhat trang thai that bai, vui long thu lai.');
			}

			// Gửi email thông báo tương ứng. Lỗi gửi mail không làm rollback trạng thái
			// vì nghiệp vụ cập nhật trạng thái đã hoàn tất thành công.
			$guiEmailThanhCong = $this->guiEmailTheoTrangThai($trangThaiMoi, $hoSoUngTuyen);

			if ($guiEmailThanhCong) {
				ResponseHelper::setFlash(
					'success',
					'Cap nhat trang thai thanh cong. Email thong bao da duoc gui.'
				);
			} else {
				ResponseHelper::setFlash(
					'success',
					'Cap nhat trang thai thanh cong, nhung khong the gui email thong bao.'
				);
			}
		} catch (Exception $e) {
			ResponseHelper::setFlash('error', $e->getMessage());
		}

		AuthHelper::redirect(BASE_URL . '/index.php?action=recruiterDetail&maHS=' . urlencode($maHoSo));
	}

	/**
	 * Chọn hàm gửi mail phù hợp theo trạng thái mới của hồ sơ.
	 *
	 * @param string $trangThaiMoi
	 * @param array $hoSoUngTuyen Bản ghi chi tiết hồ sơ (đã JOIN thông tin ứng viên)
	 * @return bool
	 */
	private function guiEmailTheoTrangThai($trangThaiMoi, $hoSoUngTuyen)
	{
		$emailNguoiNhan = $hoSoUngTuyen['EmailUngVien'];
		$tenNguoiNhan = $hoSoUngTuyen['TenUngVien'];
		$tieuDeTin = $hoSoUngTuyen['TenTin'];

		switch ($trangThaiMoi) {

			case STATUS_DA_XEM:
				return $this->emailService->sendViewedMail($emailNguoiNhan, $tenNguoiNhan, $tieuDeTin);

			case STATUS_HEN_PHONG_VAN:
				return $this->emailService->sendInterviewMail($emailNguoiNhan, $tenNguoiNhan, $tieuDeTin);

			case STATUS_NHAN_VIEC:
				return $this->emailService->sendAcceptMail($emailNguoiNhan, $tenNguoiNhan, $tieuDeTin);

			case STATUS_TU_CHOI:
				return $this->emailService->sendRejectMail($emailNguoiNhan, $tenNguoiNhan, $tieuDeTin);

			default:
				return true;
		}
	}
}