<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/UserModel.php';

class ProfileController {
	private $userModel;
	private $conn;

	public function __construct($conn) {
		$this->conn = $conn;
		$this->userModel = new UserModel($conn);
	}

    /**
	 * Xử lý đăng xuất tài khoản an toàn
	 */
	public function handleLogout() {
		if (isset($_GET['action']) && $_GET['action'] === 'logout') {
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
			header("Location: dangnhap.html");
			exit();
		}
	}

	public function handleGetProfile() : ?array {
		if (!isset($_SESSION['user_id'])) {
			header("Location: ../views/dangnhap.html");
			exit();
		}
		$maUser = $_SESSION['user_id'];
		$role = $_SESSION['user_role'] ?? 0; // Giả định bạn đã lưu role vào session lúc đăng nhập

		// Nếu là Công ty (Role = 1), lấy dữ liệu liên kết bảng nhatuyendung
		if ($role == 1) {
			return $this->userModel->getEmployerById($maUser);
		}
		// Nếu là Ứng viên (Role = 0)
		return $this->userModel->getUserById($maUser);
	}

	public function handleUpdateProfile() {
		if (!isset($_SESSION['user_id'])) {
			header("Location: ../views/dangnhap.html");
			exit();
		}

		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
			$maUser = $_SESSION['user_id'];
			$role = $_SESSION['user_role'] ?? 0;

			if ($role == 1) {
				// Dữ liệu cập nhật cho CÔNG TY
				$dataUpdate = [
					'sdt'      => trim($_POST['sdt']),
					'diaChi'   => trim($_POST['diaChi']),
					'website'  => trim($_POST['website']),
					'linhVuc'  => trim($_POST['linhVuc'])
				];
				$result = $this->userModel->updateEmployerProfile($maUser, $dataUpdate);
			} else {
				// Dữ liệu cập nhật cho ỨNG VIÊN
				$dataUpdate = [
					'hoTen'    => trim($_POST['hoTen']),
					'ngaySinh' => !empty($_POST['ngaySinh']) ? $_POST['ngaySinh'] : null,
					'gioiTinh' => $_POST['gioiTinh'],
					'sdt'      => trim($_POST['sdt']),
					'diaChi'   => trim($_POST['diaChi'])
				];
				$result = $this->userModel->updateUserProfile($maUser, $dataUpdate);
			}

			if ($result) {
				if(isset($dataUpdate['hoTen'])) $_SESSION['user_name'] = $dataUpdate['hoTen'];
				header("Location: profile.php?status=success");
				exit();
			} else {
				header("Location: profile.php?status=error");
				exit();
			}
		}
	}
}
?>