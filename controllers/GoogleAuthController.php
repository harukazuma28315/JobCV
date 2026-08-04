<?php
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/UserModel.php';

/**
 * Điều hướng xử lý xác thực tài khoản thông qua bên thứ ba (Google Auth)
 */
class GoogleAuthController {
	private $userModel;
	private $conn;

	/**
	 * Khởi tạo Controller xử lý dữ liệu Google
	 * @param mysqli $conn Kết nối cơ sở dữ liệu hệ thống
	 */
	public function __construct($conn) {
		$this->conn = $conn;
		$this->userModel = new UserModel($conn);
	}

	/**
	 * Tiếp nhận JWT Token từ Google, giải mã cấu trúc dữ liệu và xử lý phiên
	 */
	public function handleGoogleAuth() {
		if ($_SERVER["REQUEST_METHOD"] !== "POST" || empty($_POST['credential'])) {
			echo "<script>alert('Yêu cầu không hợp lệ!');"
				. " window.location.href='/JobCV/index.php?route=auth/login';</script>";
			return;
		}

		$idToken = $_POST['credential'];

		$tokenParts = explode('.', $idToken);
		if (count($tokenParts) !== 3) {
			echo "<script>alert('Mã xác thực không đúng định dạng!'); window.history.back();</script>";
			return;
		}

		$payload = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $tokenParts[1])), true);

		if (!$payload || empty($payload['email'])) {
			echo "<script>alert('Không thể xác thực danh tính với Google!'); window.history.back();</script>";
			return;
		}

		$email = trim($payload['email']);
		$hoTen = trim($payload['name']);
		$user = $this->userModel->getUserByEmail($email);

		if ($user) {
			$this->loginUserSession($user);
		} else {
			$randomPassword = bin2hex(random_bytes(16));
			$matKhauHashed = password_hash($randomPassword, PASSWORD_DEFAULT);
			$maUser = "USR" . time() . rand(10, 99);

			$userData = [
				'maUser' => $maUser,
				'email' => $email,
				'matKhauHashed' => $matKhauHashed,
				'role' => 0,
				'hoTen' => $hoTen,
				'ngaySinh' => null,
				'gioiTinh' => null,
				'sdt' => null,
				'diaChi' => null
			];

			$isRegistered = $this->userModel->registerCandidate($userData);

			if ($isRegistered) {
				$newUser = $this->userModel->getUserByEmail($email);
				$this->loginUserSession($newUser);
			} else {
				echo "<script>alert('Tự động tạo tài khoản liên kết Google thất bại!');"
					. " window.location.href='../views/trangchu.html';</script>";
			}
		}
	}

	/**
	 * Thiết lập phiên làm việc và chuyển hướng người dùng vào hệ thống
	 * @param array $user Mảng dữ liệu thông tin User
	 */
	private function loginUserSession($user) {
		$_SESSION['user_id'] = $user['MaUser'];
		$_SESSION['user_email'] = $user['Email'];
		$_SESSION['user_name'] = $user['HoTen'];
		$_SESSION['user_role'] = $user['Role'];
		$_SESSION['role'] = $user['Role'];

		echo "<script>alert('Đăng nhập liên kết Google thành công!');"
			. " window.location.href='../views/trangchu.php';</script>";
	}
}

?>