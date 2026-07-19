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
			echo "<script>alert('Yêu cầu không hợp lệ!'); window.location.href='/JobCV/index.php?route=auth/login';</script>";
			return;
		}

		$idToken = $_POST['credential'];

		// Phân tách cấu trúc mã hóa chuỗi JWT (Header.Payload.Signature)
		$tokenParts = explode('.', $idToken);
		if (count($tokenParts) !== 3) {
			echo "<script>alert('Mã xác thực không đúng định dạng!'); window.history.back();</script>";
			return;
		}

		// Giải mã vùng dữ liệu Payload của JWT để lấy thông tin cá nhân
		$payload = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $tokenParts[1])), true);

		if (!$payload || empty($payload['email'])) {
			echo "<script>alert('Không thể xác thực danh tính với Google!'); window.history.back();</script>";
			return;
		}

		$email = trim($payload['email']);
		$hoTen = trim($payload['name']); // Lấy họ tên hiển thị của tài khoản Google

		// Truy vấn đối chiếu kiểm tra sự tồn tại trong CSDL qua Model
		$user = $this->userModel->getUserByEmail($email);

		if ($user) {
			// TRƯỜNG HỢP 1: Tài khoản đã tồn tại -> Tiến hành tự động Đăng nhập trực tiếp
			$this->loginUserSession($user);
		} else {
			// TRƯỜNG HỢP 2: Chưa tồn tại tài khoản -> Tự động tạo mới (Đăng ký nhanh)
			// Vì đăng nhập bằng Google nên đặt mật khẩu ngẫu nhiên hoặc băm bảo mật
			$randomPassword = bin2hex(random_bytes(16));
			$matKhauHashed = password_hash($randomPassword, PASSWORD_DEFAULT);
			$maUser = "USR" . time() . rand(10, 99);

			$userData = [
				'maUser' => $maUser,
				'email' => $email,
				'matKhauHashed' => $matKhauHashed,
				'role' => 0, // Mặc định tự động đăng ký là Ứng viên (Role = 0)
				'hoTen' => $hoTen,
				'ngaySinh' => null,
				'gioiTinh' => null,
				'sdt' => null,
				'diaChi' => null
			];

			// Thực thi ghi dữ liệu đồng thời vào bảng user và ungvien dựa trên Single Responsibility
			$isRegistered = $this->userModel->registerCandidate($userData);

			if ($isRegistered) {
				$newUser = $this->userModel->getUserByEmail($email);
				$this->loginUserSession($newUser);
			} else {
				echo "<script>alert('Tự động tạo tài khoản liên kết Google thất bại!'); window.location.href='../views/trangchu.html';</script>";
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

		echo "<script>alert('Đăng nhập liên kết Google thành công!'); window.location.href='../views/trangchu.php';</script>";
	}
}

?>