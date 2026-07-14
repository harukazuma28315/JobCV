<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/UserModel.php';

/**
 * Điều hướng và xử lý các yêu cầu xác thực đăng nhập tài khoản
 */
class LoginController {
	private $userModel;
	private $conn;

	/**
	 * Khởi tạo Controller đăng nhập
	 * @param mysqli $conn Kết nối cơ sở dữ liệu hệ thống
	 */
	public function __construct($conn) {
		$this->conn = $conn;
		$this->userModel = new UserModel($conn);
	}

	/**
	 * Xử lý dữ liệu gửi lên từ Form Đăng nhập
	 */
	public function handleLogin() {
		if ($_SERVER["REQUEST_METHOD"] !== "POST") {
			return;
		}

		// Thu thập và làm sạch dữ liệu đầu vào
		$email = trim($_POST['Email']);
		$matKhau = $_POST['MatKhau'];

		// Kiểm tra định dạng Email hợp lệ trước khi truy vấn
		if (!$this->userModel->isValidEmail($email)) {
			echo "<script>alert('Định dạng địa chỉ Email không hợp lệ!'); window.history.back();</script>";
			return;
		}

		// Truy vấn thông tin người dùng từ cơ sở dữ liệu
		$user = $this->userModel->getUserByEmail($email);

		// Xác thực người dùng và đối chiếu mật khẩu băm
		if ($user && password_verify($matKhau, $user['MatKhau'])) {
			// Thiết lập phiên làm việc (Session) sau khi đăng nhập thành công
			$_SESSION['user_id'] = $user['MaUser'];
			$_SESSION['user_email'] = $user['Email'];
			$_SESSION['user_name'] = $user['HoTen'];
			$_SESSION['user_role'] = $user['Role'];

			// Định tuyến trang chủ hoặc bảng điều khiển tùy theo vai trò (Role)
			echo "<script>alert('Đăng nhập thành công! Chào mừng " . $user['HoTen'] . "'); window.location.href='../views/trangchu.php';</script>";
		} else {
			// Thông báo lỗi chung bảo mật (không chỉ rõ sai email hay mật khẩu)
			echo "<script>alert('Email hoặc mật khẩu không chính xác!'); window.history.back();</script>";
		}
	}
}

// Thực thi chạy và dọn dẹp kết nối sau khi đóng phiên làm việc
$loginCtrl = new LoginController($conn);
$loginCtrl->handleLogin();
$conn->close();
?>