<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/UserModel.php';

/**
 * Điều hướng hiển thị thông tin nghiệp vụ trên Trang chủ hệ thống
 */
class HomeController {
	private $userModel;
	private $conn;

	/**
	 * Khởi tạo Controller Trang chủ
	 * @param mysqli $conn Kết nối cơ sở dữ liệu hệ thống
	 */
	public function __construct($conn) {
		$this->conn = $conn;
		$this->userModel = new UserModel($conn);
	}

	/**
	 * Lấy thông tin chi tiết của người dùng đang đăng nhập để hiển thị ra View
	 * 
	 * @return array|null Mảng thông tin cá nhân chi tiết.
	 */
	public function getProfileData() {
		// Ngăn chặn truy cập nếu chưa đăng nhập thông qua Session
		if (!isset($_SESSION['user_id'])) {
			header("Location: ../views/dangnhap.html");
			exit();
		}

		$maUser = $_SESSION['user_id'];
		return $this->userModel->getUserById($maUser);
	}
}
?>