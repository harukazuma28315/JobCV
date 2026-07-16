<?php
/**
 * File: app/controllers/ApplicationController.php
 * Đường dẫn: Module5_UngTuyenVaQuanLyHoSo/app/controllers/ApplicationController.php
 * Chức năng: Điều phối nghiệp vụ phía Ứng viên - chọn CV, viết/upload Cover Letter,
 *            nộp hồ sơ vào tin tuyển dụng, xem lịch sử và chi tiết hồ sơ đã nộp.
 *            Toàn bộ validate + business logic nằm ở đây, Model chỉ thao tác CSDL,
 *            View chỉ render HTML.
 */

require_once ROOT_PATH . '/models/ApplicationModel.php';
require_once ROOT_PATH . '/models/CvModel.php';
require_once ROOT_PATH . '/models/JobModel.php';
require_once ROOT_PATH . '/helpers/AuthHelper.php';
require_once ROOT_PATH . '/helpers/ResponseHelper.php';
require_once ROOT_PATH . '/helpers/UploadHelper.php';
require_once ROOT_PATH . '/helpers/IdHelper.php';

class ApplicationController
{
	/**
	 * @var ApplicationModel
	 */
	private $hoSoUngTuyenModel;

	/**
	 * @var CvModel
	 */
	private $cvModel;

	/**
	 * @var JobModel
	 */
	private $tinTuyenDungModel;

	public function __construct()
	{
		$this->hoSoUngTuyenModel = new ApplicationModel();
		$this->cvModel = new CvModel();
		$this->tinTuyenDungModel = new JobModel();
	}

	/**
	 * Hiển thị form nộp hồ sơ ứng tuyển cho một tin tuyển dụng cụ thể.
	 * Chỉ Ứng viên (Role 0) mới được truy cập.
	 *
	 * @return void
	 */
	public function showApplyForm()
	{
		AuthHelper::requireRole(ROLE_UNGVIEN);

		$maTinTuyenDung = isset($_GET['maTin']) ? trim($_GET['maTin']) : '';

		if ($maTinTuyenDung === '') {
			ResponseHelper::setFlash('error', 'Thieu thong tin tin tuyen dung.');
			AuthHelper::redirect(BASE_URL . '/index.php?action=history');
		}

		$tinTuyenDung = $this->tinTuyenDungModel->getById($maTinTuyenDung);

		if (!$tinTuyenDung) {
			ResponseHelper::setFlash('error', 'Tin tuyen dung khong ton tai.');
			AuthHelper::redirect(BASE_URL . '/index.php?action=history');
		}

		if ($this->tinTuyenDungModel->isExpired($tinTuyenDung)) {
			ResponseHelper::setFlash('error', 'Tin tuyen dung nay da het han ung tuyen.');
			AuthHelper::redirect(BASE_URL . '/index.php?action=history');
		}

		$maUngVien = AuthHelper::getCurrentUserId();
		$danhSachCv = $this->cvModel->getActiveCvsByUngVien($maUngVien);

		$thongBao = ResponseHelper::getFlash();

		require ROOT_PATH . '/views/applicant/applyJob.php';
	}

	/**
	 * Xử lý submit form nộp hồ sơ ứng tuyển (POST).
	 * Thực hiện đầy đủ validate nghiệp vụ trước khi lưu CSDL.
	 *
	 * @return void
	 */
	public function submitApplication()
	{
		AuthHelper::requireRole(ROLE_UNGVIEN);

		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			AuthHelper::redirect(BASE_URL . '/index.php?action=history');
		}

		$maUngVien = AuthHelper::getCurrentUserId();
		$maCv = isset($_POST['maCV']) ? trim($_POST['maCV']) : '';
		$maTinTuyenDung = isset($_POST['maTinTuyenDung']) ? trim($_POST['maTinTuyenDung']) : '';
		$noiDungCoverLetter = isset($_POST['coverLetter']) ? trim($_POST['coverLetter']) : '';

		try {
			// 1. Kiểm tra CV tồn tại và thuộc về đúng ứng viên đang nộp
			$hoSoCv = $this->cvModel->getOwnedCv($maCv, $maUngVien);
			if (!$hoSoCv) {
				throw new Exception('CV khong ton tai hoac khong thuoc ve ban.');
			}

			// 2. Kiểm tra tin tuyển dụng tồn tại
			$tinTuyenDung = $this->tinTuyenDungModel->getById($maTinTuyenDung);
			if (!$tinTuyenDung) {
				throw new Exception('Tin tuyen dung khong ton tai.');
			}

			// 3. Kiểm tra tin chưa hết hạn
			if ($this->tinTuyenDungModel->isExpired($tinTuyenDung)) {
				throw new Exception('Tin tuyen dung nay da het han ung tuyen.');
			}

			// 4. Kiểm tra không nộp trùng (dựa trên UNIQUE KEY MaCV + MaTinTuyenDung)
			if ($this->hoSoUngTuyenModel->isDuplicate($maCv, $maTinTuyenDung)) {
				throw new Exception('Ban da nop CV nay vao tin tuyen dung nay roi, khong the nop trung.');
			}

			// 5. Kiểm tra độ dài Cover Letter dạng văn bản
			if (mb_strlen($noiDungCoverLetter) > MAX_COVER_LETTER_TEXT_LENGTH) {
				throw new Exception('Noi dung Cover Letter qua dai (toi da ' . MAX_COVER_LETTER_TEXT_LENGTH . ' ky tu).');
			}

			// 6. Upload file Cover Letter (nếu có) - validate định dạng và dung lượng bên trong Helper
			$tepCoverLetter = null;
			if (isset($_FILES['coverLetterFile'])) {
				$tepCoverLetter = UploadHelper::uploadCoverLetter($_FILES['coverLetterFile']);
			}

			// 7. Lưu hồ sơ ứng tuyển
			$maHoSo = IdHelper::generate('HS');
			$taoThanhCong = $this->hoSoUngTuyenModel->create(array(
				'maHS' => $maHoSo,
				'maCV' => $maCv,
				'maTinTuyenDung' => $maTinTuyenDung,
				'coverLetter' => $noiDungCoverLetter !== '' ? $noiDungCoverLetter : null,
				'coverLetterFile' => $tepCoverLetter,
			));

			if (!$taoThanhCong) {
				throw new Exception('Luu ho so ung tuyen that bai, vui long thu lai.');
			}

			ResponseHelper::setFlash('success', 'Nop ho so ung tuyen thanh cong.');
			AuthHelper::redirect(BASE_URL . '/index.php?action=history');
		} catch (Exception $e) {
			ResponseHelper::setFlash('error', $e->getMessage());
			AuthHelper::redirect(BASE_URL . '/index.php?action=applyForm&maTin=' . urlencode($maTinTuyenDung));
		}
	}

	/**
	 * Hiển thị lịch sử ứng tuyển của ứng viên đang đăng nhập.
	 *
	 * @return void
	 */
	public function showHistory()
	{
		AuthHelper::requireRole(ROLE_UNGVIEN);

		$maUngVien = AuthHelper::getCurrentUserId();
		$danhSachHoSoUngTuyen = $this->hoSoUngTuyenModel->getHistoryByUngVien($maUngVien);
		$thongBao = ResponseHelper::getFlash();

		require ROOT_PATH . '/views/applicant/applicationHistory.php';
	}

	/**
	 * Hiển thị chi tiết một hồ sơ ứng tuyển của ứng viên đang đăng nhập.
	 *
	 * @return void
	 */
	public function showDetail()
	{
		AuthHelper::requireRole(ROLE_UNGVIEN);

		$maHoSo = isset($_GET['maHS']) ? trim($_GET['maHS']) : '';
		$maUngVien = AuthHelper::getCurrentUserId();

		$hoSoUngTuyen = $this->hoSoUngTuyenModel->getDetailForApplicant($maHoSo, $maUngVien);

		if (!$hoSoUngTuyen) {
			ResponseHelper::setFlash('error', 'Ho so khong ton tai hoac ban khong co quyen xem.');
			AuthHelper::redirect(BASE_URL . '/index.php?action=history');
		}

		require ROOT_PATH . '/views/applicant/applicationDetail.php';
	}
}