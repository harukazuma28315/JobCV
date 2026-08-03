<?php

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/UserModel.php';

class LoginController {
	private $userModel;
	private $conn;

	/**
	 * Khởi tạo LoginController với kết nối cơ sở dữ liệu.
	 * 
	 * @param PDO $conn Kết nối cơ sở dữ liệu.
	 */
	public function __construct($conn) {
		$this->conn = $conn;
		$this->userModel = new UserModel($conn);
	}

	/**
	 * Hiển thị giao diện trang đăng nhập.
	 * 
	 * @return void
	 */
	public function showLogin() {
		$content = __DIR__ . '/../views/page/auth/login-content.php';
		require_once __DIR__ . '/../views/page/layouts/main.php';
	}

	/**
	 * Hiển thị giao diện quên mật khẩu.
	 * 
	 * @return void
	 */
	public function showForgotPassword() {
		require_once __DIR__ . '/../views/page/auth/forgot-password.php';
	}

	/**
	 * Hiển thị giao diện đặt lại mật khẩu.
	 * 
	 * @return void
	 */
	public function showResetPassword() {
		require_once __DIR__ . '/../views/page/auth/reset-password.php';
	}

	/**
	 * Điều phối toàn bộ luồng xử lý đăng nhập.
	 * 
	 * @return void
	 */
	public function handleLogin() {
		if ($_SERVER["REQUEST_METHOD"] !== "POST") {
			return;
		}

		$this->checkRateLimit();

		$email = trim($_POST['Email'] ?? '');
		$matKhau = $_POST['MatKhau'] ?? '';

		if (!$this->userModel->isValidEmail($email)) {
			$this->respondWithError('Định dạng địa chỉ Email không hợp lệ!');
		}

		$user = $this->userModel->getUserByEmail($email);

		if ($user && password_verify($matKhau, $user['MatKhau'])) {
			$this->processSuccessfulLogin($user);
		} else {
			$this->processFailedLogin();
		}
	}

	/**
	 * Kiểm tra giới hạn số lần thử đăng nhập (Rate Limit).
	 * 
	 * @return void
	 */
	private function checkRateLimit() {
		if (!isset($_SESSION['lockout_time'])) {
			return;
		}

		$secondsRemaining = $_SESSION['lockout_time'] - time();

		if ($secondsRemaining > 0) {
			$this->respondWithError("Bạn đã nhập sai quá 5 lần! Vui lòng thử lại sau {$secondsRemaining} giây.");
		}

		// Khóa đã hết hạn: xóa cờ khóa và reset lại bộ đếm để không khóa nhầm ở lần đăng nhập tiếp theo.
		unset($_SESSION['lockout_time']);
		$_SESSION['login_attempts'] = 0;
	}

	/**
	 * Xử lý nghiệp vụ khi đăng nhập thành công.
	 * 
	 * @param array $user Dữ liệu người dùng từ database.
	 * @return void
	 */
	private function processSuccessfulLogin($user) {
		$isLocked = !empty($user['IsLocked']) || (($user['TrangThai'] ?? '') === 'BiKhoa');

		if ($isLocked) {
			$this->respondWithError('Tài khoản của bạn đã bị khóa. Vui lòng liên hệ Admin!');
		}

		// Đăng nhập thành công: xóa bộ đếm/khóa rate-limit để không ảnh hưởng
		// đến lần đăng nhập tiếp theo (nếu có).
		unset($_SESSION['login_attempts']);
		unset($_SESSION['lockout_time']);

		$_SESSION['user_id'] = $user['MaUser'];
		$_SESSION['user_email'] = $user['Email'];
		$_SESSION['user_name'] = $user['HoTen'];
		$_SESSION['user_role'] = $user['Role'];
		// Lưu thêm key 'role' vì AuthHelper::requireRole() đọc key này thay vì 'user_role'.
		$_SESSION['role'] = $user['Role'];

		$role = (int)$user['Role'];

		if ($role === ROLE_ADMIN) {
			header('Location: /JobCV/index.php?route=admin/dashboard');
		} else {
			header('Location: /JobCV/index.php?route=home');
		}
		exit;
	}

	/**
	 * Xử lý nghiệp vụ khi đăng nhập thất bại (tăng số lần đếm, khóa nếu quá hạn mức).
	 * 
	 * @return void
	 */
	private function processFailedLogin() {
		$_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;

		if ($_SESSION['login_attempts'] >= 5) {
			$_SESSION['lockout_time'] = time() + 30;
			$this->respondWithError(
				'Bạn đã nhập sai 5 lần liên tiếp! Tài khoản tạm thời bị khóa trong 30 giây.'
			);
		} else {
			$remainingAttempts = 5 - $_SESSION['login_attempts'];
			$this->respondWithError(
				"Email hoặc mật khẩu không chính xác! Bạn còn {$remainingAttempts} lần thử."
			);
		}

		// TODO: Chuyển đổi cơ chế lưu số lần thử sai từ Session sang Database/Redis
		// để quản lý chính xác theo Email hoặc IP.
	}

	/**
	 * Phản hồi lỗi về trình duyệt thông qua JavaScript Alert.
	 * 
	 * @param string $message Nội dung thông báo lỗi.
	 * @return void
	 */
	private function respondWithError($message) {
		$safeMessage = addslashes($message);
		echo "<script>
				alert('{$safeMessage}');
				window.history.back();
			  </script>";
		exit;
	}
}