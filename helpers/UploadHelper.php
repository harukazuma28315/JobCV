<?php
/**
 * File: app/helpers/UploadHelper.php
 * Đường dẫn: Module5_UngTuyenVaQuanLyHoSo/app/helpers/UploadHelper.php
 * Chức năng: Xử lý upload file Cover Letter (PDF/DOC/DOCX), kiểm tra định dạng,
 *            dung lượng tối đa, và đổi tên file để tránh trùng lặp trên server.
 */

class UploadHelper
{
	/**
	 * Upload file Cover Letter vào thư mục uploads/coverLetter/.
	 * Validate kỹ định dạng (đuôi file + MIME type thực tế) và dung lượng
	 * trước khi di chuyển file, tránh upload file giả mạo đuôi mở rộng.
	 *
	 * @param array $file Phần tử $_FILES['xxx']
	 * @return string|null Tên file đã lưu trên server (không kèm đường dẫn). Trả về null nếu không có file.
	 * @throws Exception Khi file không hợp lệ hoặc lỗi khi lưu file
	 */
	public static function uploadCoverLetter($file)
	{
		if (!isset($file['error']) || is_array($file['error'])) {
			throw new Exception('Du lieu file khong hop le.');
		}

		if ($file['error'] === UPLOAD_ERR_NO_FILE) {
			// Không có file đính kèm - hợp lệ vì Cover Letter file là optional
			return null;
		}

		if ($file['error'] !== UPLOAD_ERR_OK) {
			throw new Exception('Upload file that bai (ma loi: ' . $file['error'] . ').');
		}

		if ($file['size'] > MAX_COVER_LETTER_SIZE) {
			throw new Exception('File Cover Letter vuot qua dung luong cho phep (toi da 5MB).');
		}

		$tenGoc = $file['name'];
		$phanMoRong = strtolower(pathinfo($tenGoc, PATHINFO_EXTENSION));

		if (!in_array($phanMoRong, $GLOBALS['ALLOWED_COVER_LETTER_EXT'], true)) {
			throw new Exception('Dinh dang file khong hop le. Chi chap nhan PDF, DOC, DOCX.');
		}

		// Kiểm tra MIME type thực tế của file (không chỉ dựa vào đuôi mở rộng)
		// để hạn chế người dùng đổi đuôi file giả mạo.
		if (function_exists('finfo_open')) {
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$mimeType = finfo_file($finfo, $file['tmp_name']);
			finfo_close($finfo);

			if (!in_array($mimeType, $GLOBALS['ALLOWED_COVER_LETTER_MIME'], true)) {
				throw new Exception('Noi dung file khong dung dinh dang cho phep.');
			}
		}

		if (!is_dir(UPLOAD_COVER_LETTER_DIR)) {
			mkdir(UPLOAD_COVER_LETTER_DIR, 0755, true);
		}

		// Đổi tên file để tránh trùng lặp giữa nhiều lượt upload
		$tenFileMoi = IdHelper::generate('CL') . '.' . $phanMoRong;
		$duongDanDich = UPLOAD_COVER_LETTER_DIR . $tenFileMoi;

		if (!move_uploaded_file($file['tmp_name'], $duongDanDich)) {
			throw new Exception('Khong the luu file len server.');
		}

		return $tenFileMoi;
	}
}