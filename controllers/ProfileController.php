<?php
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
			header("Location: /JobCV/views/page/auth/login.php");
			exit();
		}
	}

	/**
	 * Lấy và chuẩn hóa dữ liệu hồ sơ người dùng dựa theo phân quyền Role.
	 * Trả về cấu trúc dữ liệu tương ứng cho Ứng viên hoặc Nhà tuyển dụng.
	 * 
	 * @return array Mảng dữ liệu đã được xử lý và chuẩn hóa theo quy tắc camelCase.
	 */
	public function handleGetProfile() : array {
		if (!isset($_SESSION['user_id'])) {
			header("Location: /JobCV/views/page/auth/login.php");
			exit();
		}
		
		$maUser = $_SESSION['user_id'];
		$role = isset($_SESSION['user_role']) ? (int) $_SESSION['user_role'] : 0;

		// Phân tách dữ liệu lấy ra dựa trên Role của tài khoản
		if ($role === 1) {
			// Luồng xử lý cho NHÀ TUYỂN DỤNG
			$rawData = $this->userModel->getEmployerById($maUser);
			
			return [
				'role'        => 1,
				'companyName' => $rawData['TenCongTy'] ?? $rawData['HoTen'] ?? 'Chưa cập nhật tên công ty',
				'email'       => $rawData['Email'] ?? '',
				'phone'       => $rawData['SDT'] ?? '',
				'address'     => $rawData['DiaChi'] ?? '',
				'website'     => $rawData['Website'] ?? 'Chưa cập nhật',
				'industry'    => $rawData['LinhVuc'] ?? 'Chưa cập nhật',
				'taxCode'     => $rawData['MaSoThue'] ?? 'Chưa cập nhật'
			];
		}

		// Luồng xử lý cho ỨNG VIÊN (Mặc định hoặc Role = 0)
		$rawData = $this->userModel->getUserById($maUser);
		
		return [
			'role'      => 0,
			'fullname'  => $rawData['HoTen'] ?? $_SESSION['user_name'] ?? 'Người dùng',
			'email'     => $rawData['Email'] ?? '',
			'phone'     => $rawData['SDT'] ?? '',
			'address'   => $rawData['DiaChi'] ?? '',
			'birthDate' => $rawData['NgaySinh'] ?? 'Chưa cập nhật',
			'gender'    => $rawData['GioiTinh'] ?? null,
		];
	}

	public function handleUpdateProfile() {
		if (!isset($_SESSION['user_id'])) {
			header("Location: /JobCV/views/page/auth/login.php");
			exit();
		}

		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
			$maUser = $_SESSION['user_id'];
			$role = $_SESSION['user_role'] ?? 0;
			$currentProfile = $this->handleGetProfile();

			if ($role == 1) {
				$dataUpdate = [
					'sdt'      => trim($_POST['sdt'] ?? ''),
					'diaChi'   => trim($_POST['diaChi'] ?? ''),
					'website'  => trim($_POST['website'] ?? ''),
					'linhVuc'  => trim($_POST['linhVuc'] ?? '')
				];

				$hasChange = (
					($currentProfile['phone'] ?? '') !== $dataUpdate['sdt'] ||
					($currentProfile['address'] ?? '') !== $dataUpdate['diaChi'] ||
					($currentProfile['website'] ?? '') !== $dataUpdate['website'] ||
					($currentProfile['industry'] ?? '') !== $dataUpdate['linhVuc']
				);

				if (!$hasChange) {
					header("Location: /JobCV/views/page/employer/employerProfile.php?status=unchanged");
					exit();
				}

				$result = $this->userModel->updateEmployerProfile($maUser, $dataUpdate);
			} else {
				$dataUpdate = [
					'hoTen'    => trim($_POST['hoTen'] ?? ''),
					'ngaySinh' => !empty($_POST['ngaySinh']) ? $_POST['ngaySinh'] : null,
					'gioiTinh' => $_POST['gioiTinh'] ?? null,
					'sdt'      => trim($_POST['sdt'] ?? ''),
					'diaChi'   => trim($_POST['diaChi'] ?? '')
				];

				$hasChange = (
					($currentProfile['fullname'] ?? '') !== $dataUpdate['hoTen'] ||
					($currentProfile['birthDate'] ?? '') !== ($dataUpdate['ngaySinh'] ?? '') ||
					($currentProfile['gender'] ?? null) !== ($dataUpdate['gioiTinh'] ?? null) ||
					($currentProfile['phone'] ?? '') !== $dataUpdate['sdt'] ||
					($currentProfile['address'] ?? '') !== $dataUpdate['diaChi']
				);

				if (!$hasChange) {
					header("Location: /JobCV/views/page/candidate/candidateProfile.php?status=unchanged");
					exit();
				}

				$result = $this->userModel->updateUserProfile($maUser, $dataUpdate);
			}

			if ($result) {
				if(isset($dataUpdate['hoTen'])) $_SESSION['user_name'] = $dataUpdate['hoTen'];
				header("Location: /JobCV/views/page/" . ($role == 1 ? 'employer/employerProfile.php' : 'candidate/candidateProfile.php') . "?status=success");
				exit();
			} else {
				header("Location: /JobCV/views/page/" . ($role == 1 ? 'employer/employerProfile.php' : 'candidate/candidateProfile.php') . "?status=error");
				exit();
			}
		}
	}
}
?>