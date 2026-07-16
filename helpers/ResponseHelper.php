<?php
/**
 * File: app/helpers/ResponseHelper.php
 * Đường dẫn: Module5_UngTuyenVaQuanLyHoSo/app/helpers/ResponseHelper.php
 * Chức năng: Chuẩn hóa việc phản hồi cho người dùng: thông báo flash message
 *            (hiển thị 1 lần sau redirect) và trả JSON cho AJAX nếu cần.
 */

class ResponseHelper
{
	/**
	 * Lưu thông báo flash vào session để hiển thị ở trang kế tiếp sau redirect.
	 * Dùng session thay vì query string để tránh lộ nội dung thông báo trên URL.
	 *
	 * @param string $type     Loại thông báo: success | error | info
	 * @param string $message  Nội dung thông báo
	 * @return void
	 */
	public static function setFlash($type, $message)
	{
		if (session_status() !== PHP_SESSION_ACTIVE) {
			session_start();
		}

		$_SESSION['flash'] = array(
			'type' => $type,
			'message' => $message,
		);
	}

	/**
	 * Lấy thông báo flash hiện có và xóa khỏi session (chỉ hiển thị 1 lần).
	 *
	 * @return array|null
	 */
	public static function getFlash()
	{
		if (session_status() !== PHP_SESSION_ACTIVE) {
			session_start();
		}

		if (empty($_SESSION['flash'])) {
			return null;
		}

		$thongBao = $_SESSION['flash'];
		unset($_SESSION['flash']);

		return $thongBao;
	}

	/**
	 * Trả dữ liệu dạng JSON và dừng script, dùng cho các request AJAX.
	 *
	 * @param array $data
	 * @param int $httpCode
	 * @return void
	 */
	public static function jsonResponse($data, $httpCode = 200)
	{
		http_response_code($httpCode);
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($data);
		exit;
	}
}