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
require_once ROOT_PATH . '/services/CvService.php';

class RecruiterController {
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
	 * @var CvService
	 */
	private $cvService;

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

	public function __construct() {
		$this->hoSoUngTuyenModel = new ApplicationModel();
		$this->tinTuyenDungModel = new JobModel();
		$this->emailService = new EmailService();
		$this->cvService = new CvService();
	}

	/**
	 * Hiển thị danh sách hồ sơ ứng tuyển vào các tin của Nhà tuyển dụng đang đăng nhập.
	 * Hỗ trợ lọc theo tin tuyển dụng và theo trạng thái qua query string.
	 *
	 * @return void
	 */
	public function showList() {
		AuthHelper::requireRole(ROLE_NHATUYENDUNG);

		$maNhaTuyenDung = AuthHelper::getCurrentUserId();

		if (!$maNhaTuyenDung) {
			exit('Bạn chưa đăng nhập.');
		}


		// ==================================================
		// 1. LẤY DANH SÁCH TIN TUYỂN DỤNG CỦA NTD
		// ==================================================

		if (method_exists($this->tinTuyenDungModel, 'getJobsByRecruiter')) {

			$danhSachTinTuyenDung =
				$this->tinTuyenDungModel->getJobsByRecruiter($maNhaTuyenDung);

		} else {

			require_once ROOT_PATH . '/models/JobPosting.php';

			$tinModel = new JobPosting();

			$resultTin =
				$tinModel->getByEmployer($maNhaTuyenDung);

			$danhSachTinTuyenDung = [];

			if ($resultTin) {
				while ($row = $resultTin->fetch_assoc()) {
					$danhSachTinTuyenDung[] = $row;
				}
			}
		}


		// ==================================================
		// 2. XÁC ĐỊNH TIN ĐANG ĐƯỢC CHỌN
		// ==================================================

		if (isset($_GET['maTin'])) {

			// Form filter đã được submit (kể cả khi người dùng chọn
			// "Tất cả tin tuyển dụng" -> giá trị rỗng). Không được coi
			// rỗng như "chưa chọn" nữa, nếu không sẽ luôn bị ép về tin
			// mới nhất mỗi khi bấm Áp dụng với lựa chọn Tất cả.
			$maTinLoc = trim($_GET['maTin']);

			if ($maTinLoc === '') {
				$maTinLoc = null; // Tất cả tin tuyển dụng
			}

		} else {

			// Truy cập lần đầu, chưa từng submit filter -> mặc định lấy tin mới nhất
			$maTinLoc = !empty($danhSachTinTuyenDung)
				? $danhSachTinTuyenDung[0]['MaTinTuyenDung']
				: null;
		}


		// ==================================================
		// 3. LỌC TRẠNG THÁI
		// ==================================================

		$trangThaiLoc = isset($_GET['trangThai']) && $_GET['trangThai'] !== ''
			? trim($_GET['trangThai'])
			: null;


		// ==================================================
		// 4. LẤY HỒ SƠ THEO TIN ĐANG CHỌN
		// ==================================================

		$danhSachHoSoUngTuyen =
			$this->hoSoUngTuyenModel->getListForRecruiter(
				$maNhaTuyenDung,
				$maTinLoc,
				$trangThaiLoc
			);


		// ==================================================
		// 5. FILTER HIỂN THỊ
		// ==================================================

		$currentFilters = [
			'maTin'     => $maTinLoc,
			'trangThai' => $trangThaiLoc
		];


		$thongBao = ResponseHelper::getFlash();


		require ROOT_PATH . '/views/page/employer/manage-candidates.php';
	}
	/**
	 * Hiển thị chi tiết một hồ sơ ứng tuyển, chỉ khi hồ sơ đó thuộc tin
	 * tuyển dụng của chính Nhà tuyển dụng đang đăng nhập.
	 *
	 * @return void
	 */
	public function showDetail() {
		AuthHelper::requireRole(ROLE_NHATUYENDUNG);

		$maHoSo = isset($_GET['maHS']) ? trim($_GET['maHS']) : '';
		$maNhaTuyenDung = AuthHelper::getCurrentUserId();

		$hoSoUngTuyen = $this->hoSoUngTuyenModel->getDetailForRecruiter($maHoSo, $maNhaTuyenDung);

		if (!$hoSoUngTuyen) {
			ResponseHelper::setFlash('error', 'Ho so khong ton tai hoac khong thuoc cong ty ban.');
			AuthHelper::redirect(BASE_URL . '/index.php?route=recruiter/list');
		}

		// Lấy toàn bộ dữ liệu con của CV (học vấn, kinh nghiệm, dự án, chứng
		// chỉ) để hiển thị trang xem CV chi tiết (chỉ xem, không cho sửa).
		// Luôn dùng $hoSoUngTuyen['MaCV'] lấy được từ getDetailForRecruiter ở
		// trên (đã xác thực hồ sơ thuộc công ty này) — không đọc maCV trực
		// tiếp từ query string để tránh IDOR (xem được CV của công ty khác).
		$fullDetail = $this->cvService->getFullDetail($hoSoUngTuyen['MaCV']);

		$cv = $fullDetail['cv'];
		$hocVanList = $fullDetail['hocVan'] ? $fullDetail['hocVan']->fetch_all(MYSQLI_ASSOC) : [];
		$kinhNghiemList = $fullDetail['kinhNghiem'] ? $fullDetail['kinhNghiem']->fetch_all(MYSQLI_ASSOC) : [];
		$duAnList = $fullDetail['duAn'] ? $fullDetail['duAn']->fetch_all(MYSQLI_ASSOC) : [];
		$chungChiList = $fullDetail['chungChi'] ? $fullDetail['chungChi']->fetch_all(MYSQLI_ASSOC) : [];

		$thongBao = ResponseHelper::getFlash();

		require ROOT_PATH . '/views/page/employer/candidate-detail.php';
	}

	/**
	 * Xử lý cập nhật trạng thái hồ sơ ứng tuyển (POST) và tự động gửi email
	 * thông báo tương ứng cho ứng viên.
	 *
	 * @return void
	 */
	public function updateStatus() {
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			AuthHelper::redirect(BASE_URL . '/index.php?route=recruiter/list');
		}

		AuthHelper::requireRole(ROLE_NHATUYENDUNG);

		$maNhaTuyenDung = AuthHelper::getCurrentUserId();

		$maHoSo = isset($_POST['maHS']) ? trim($_POST['maHS']) : '';
		$trangThaiMoi = isset($_POST['trangThai']) ? trim($_POST['trangThai']) : '';

		try {
			if ($maHoSo === '' || $trangThaiMoi === '') {
				throw new Exception('Thiếu thông tin hồ sơ hoặc trạng thái.');
			}

			if (!in_array($trangThaiMoi, $this->danhSachTrangThaiHopLe, true)) {
				throw new Exception('Trạng thái không hợp lệ.');
			}

			// Truyền kèm $maNhaTuyenDung để chặn IDOR: chỉ lấy được hồ sơ ứng tuyển
			// thuộc về tin tuyển dụng của chính công ty đang đăng nhập.
			$hoSoUngTuyen = $this->hoSoUngTuyenModel->getDetailForRecruiter($maHoSo, $maNhaTuyenDung);

			if (!$hoSoUngTuyen) {
				throw new Exception('Hồ sơ không tồn tại hoặc không thuộc công ty bạn.');
			}

			$capNhatThanhCong = $this->hoSoUngTuyenModel->updateStatus($maHoSo, $trangThaiMoi);

			if (!$capNhatThanhCong) {
				throw new Exception('Cập nhật thất bại.');
			}

			// Gửi email chỉ là thông báo phụ: nếu gửi thất bại vẫn giữ nguyên
			// kết quả cập nhật trạng thái đã lưu thành công trong DB, chỉ đổi nội dung thông báo.
			$guiMailThanhCong = $this->sendEmailByStatus($trangThaiMoi, $hoSoUngTuyen);

			if ($guiMailThanhCong) {
				ResponseHelper::setFlash(
					'success',
					'Cập nhật trạng thái thành công và đã gửi email thông báo cho ứng viên.'
				);
			} else {
				ResponseHelper::setFlash(
					'success',
					'Cập nhật trạng thái thành công, nhưng gửi email thông báo thất bại.'
				);
			}

		} catch (Exception $e) {
			ResponseHelper::setFlash('error', $e->getMessage());
		}

		AuthHelper::redirect(BASE_URL . '/index.php?route=recruiter/list');
	}
	/**
	 * Chọn hàm gửi mail phù hợp theo trạng thái mới của hồ sơ.
	 */
	private function sendEmailByStatus($trangThaiMoi, $hoSoUngTuyen) {
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