<?php
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

class LogoutController {
    private $conn;

	public function __construct($conn) {
		$this->conn = $conn;
	}

	/**
	 * Xử lý đăng xuất tài khoản an toàn từ mọi trang trong hệ thống.
	 * 
	 * @return void Điều hướng client trực tiếp về trang đăng nhập.
	 */
	public function handleLogout() {
		$shouldLogout = (isset($_GET['action']) && $_GET['action'] === 'logout') || (isset($_GET['route']) && $_GET['route'] === 'auth/logout');

		if ($shouldLogout) {
			// 1. Xóa bỏ tất cả các biến session
			$_SESSION = array();

			// 2. Xóa cookie session nếu có để bảo mật trình duyệt
			if (ini_get("session.use_cookies")) {
				$params = session_get_cookie_params();
				setcookie(session_name(), '', time() - 42000,
					$params["path"], $params["domain"],
					$params["secure"], $params["httponly"]
				);
			}

			// 3. Phá hủy hoàn toàn Session trên Server
			session_destroy();

			// 4. Chuyển hướng về trang đăng nhập
			header("Location: /JobCV/index.php?route=auth/login");
			exit();
		}
	}
}
?>