<?php
/**
 * File: app/helpers/AuthHelper.php
 * Đường dẫn: Module5_UngTuyenVaQuanLyHoSo/helpers/AuthHelper.php
 * Chức năng: Quản lý session đăng nhập, kiểm tra quyền truy cập theo Role,
 *            và điều hướng (redirect) dùng chung cho toàn module.
 *
 * Quy ước session (đã được Module đăng nhập của nhóm thiết lập trước đó):
 *   $_SESSION['user_id']   -> MaUser hiện tại
 *   $_SESSION['role']      -> Role hiện tại (0/1/2)
 *   $_SESSION['ho_ten']    -> Họ tên hiển thị
 */

class AuthHelper
{
	/**
	 * Bắt buộc người dùng phải đăng nhập, nếu chưa thì chuyển về trang login.
	 *
	 * @return void
	 */
	public static function requireLogin()
	{
		if (session_status() !== PHP_SESSION_ACTIVE) {
			session_start();
		}

		if (empty($_SESSION['user_id'])) {
			ResponseHelper::setFlash('error', 'Vui long dang nhap de tiep tuc.');
			self::redirect(BASE_URL . '/login.php');
		}
	}

	/**
	 * Bắt buộc người dùng phải có đúng Role được cho phép.
	 *
	 * @param int|array $roles Một Role hoặc mảng nhiều Role được phép truy cập
	 * @return void
	 */
	public static function requireRole($roles)
	{
		self::requireLogin();

		$allowedRoles = is_array($roles) ? $roles : array($roles);

		if (!in_array((int) $_SESSION['role'], $allowedRoles, true)) {
			ResponseHelper::setFlash('error', 'Ban khong co quyen truy cap chuc nang nay.');
			self::redirect(BASE_URL . '/index.php');
		}
	}

	/**
	 * Điều hướng trình duyệt đến URL khác và dừng thực thi script.
	 *
	 * @param string $url
	 * @return void
	 */
	public static function redirect($url)
	{
		header('Location: ' . $url);
		exit;
	}

	/**
	 * Lấy MaUser của người dùng đang đăng nhập.
	 *
	 * @return string|null
	 */
	public static function getCurrentUserId()
	{
		return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
	}

	/**
	 * Lấy Role của người dùng đang đăng nhập.
	 *
	 * @return int|null
	 */
	public static function getCurrentRole()
	{
		return isset($_SESSION['role']) ? (int) $_SESSION['role'] : null;
	}
}