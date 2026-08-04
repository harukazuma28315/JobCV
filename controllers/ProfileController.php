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
		$shouldLogout = (isset($_GET['action']) && $_GET['action'] === 'logout')
			|| (isset($_GET['route']) && $_GET['route'] === 'auth/logout');

		if ($shouldLogout) {
			$_SESSION = array();

			if (ini_get("session.use_cookies")) {
				$params = session_get_cookie_params();
				setcookie(session_name(), '', time() - 42000,
					$params["path"], $params["domain"],
					$params["secure"], $params["httponly"]
				);
			}

			session_destroy();
			header("Location: /JobCV/index.php?route=auth/login");
			exit();
		}
	}

	/**
	 * Hiển thị trang hồ sơ cá nhân, tự chọn view theo vai trò
	 * (nhà tuyển dụng hoặc ứng viên).
	 *
	 * @return void
	 */
	public function showProfile() {
		if (!isset($_SESSION['user_id'])) {
			header("Location: /JobCV/index.php?route=auth/login");
			exit();
		}

		$profile = $this->handleGetProfile(); 

		$role = isset($_SESSION['user_role']) ? (int) $_SESSION['user_role'] : 0;
		$viewPath = $role === 1
			? __DIR__ . '/../views/page/employer/employerProfile.php'
			: __DIR__ . '/../views/page/candidate/candidateProfile.php';

		require_once $viewPath;
	}

	/**
	 * Lấy dữ liệu hồ sơ hiện tại của người dùng đang đăng nhập, định dạng
	 * theo cấu trúc phù hợp với view (khác nhau giữa nhà tuyển dụng và ứng viên).
	 *
	 * @return array
	 */
	public function handleGetProfile() : array {
		if (!isset($_SESSION['user_id'])) {
			header("Location: /JobCV/index.php?route=auth/login");
			exit();
		}
		
		$maUser = $_SESSION['user_id'];
		$role = isset($_SESSION['user_role']) ? (int) $_SESSION['user_role'] : 0;

		if ($role === 1) {
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

	/**
	 * Xử lý cập nhật hồ sơ (POST), validate dữ liệu rồi ghi xuống DB
	 * theo đúng bảng tương ứng với vai trò người dùng.
	 *
	 * @return void
	 */
	public function handleUpdateProfile() {
		if (!isset($_SESSION['user_id'])) {
			header("Location: /JobCV/index.php?route=auth/login");
			exit();
		}

		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
			$maUser = $_SESSION['user_id'];
			$role = $_SESSION['user_role'] ?? 0;
			$currentProfile = $this->handleGetProfile();

			$sdt = trim($_POST['sdt'] ?? '');

			if (!empty($sdt) && !preg_match('/^0[0-9]{9}$/', $sdt)) {
				echo "<script>alert('Số điện thoại phải bao gồm đúng 10 chữ số và bắt đầu bằng số 0!');"
					. " window.history.back();</script>";
				exit();
			}

			if ($role == 1) {
				$diaChi = trim($_POST['diaChi'] ?? '');
				$website = trim($_POST['website'] ?? '');
				$linhVuc = trim($_POST['linhVuc'] ?? '');

				$diaChiPattern = '/^[a-zA-Z0-9àáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđĐ\s,\/]+$/u';

				if (!empty($diaChi) && !preg_match($diaChiPattern, $diaChi)) {
					echo "<script>alert('Địa chỉ chứa ký tự không hợp lệ!');"
						. " window.history.back();</script>";
					exit();
				}

				$dataUpdate = [
					'sdt'      => $sdt,
					'diaChi'   => $diaChi,
					'website'  => $website,
					'linhVuc'  => $linhVuc
				];

				$hasChange = (
					($currentProfile['phone'] ?? '') !== $dataUpdate['sdt'] ||
					($currentProfile['address'] ?? '') !== $dataUpdate['diaChi'] ||
					($currentProfile['website'] ?? '') !== $dataUpdate['website'] ||
					($currentProfile['industry'] ?? '') !== $dataUpdate['linhVuc']
				);

				if (!$hasChange) {
					echo "<script>alert('Không có thông tin nào thay đổi.');"
						. " window.location.href='/JobCV/index.php?route=profile';</script>";
					exit();
				}

				$result = $this->userModel->updateEmployerProfile($maUser, $dataUpdate);
			} else {
				$hoTen = trim($_POST['hoTen'] ?? '');
				$diaChi = trim($_POST['diaChi'] ?? '');

				if (empty($hoTen)) {
					echo "<script>alert('Họ và tên không được bỏ trống!'); window.history.back();</script>";
					exit();
				}

				$hoTenPattern = '/^[a-zA-ZàáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđĐ\s\.]+$/u';

				if (!empty($hoTen) && !preg_match($hoTenPattern, $hoTen)) {
					echo "<script>alert('Họ và tên chỉ bao gồm chữ cái và khoảng trắng!');"
						. " window.history.back();</script>";
					exit();
				}

				$dataUpdate = [
					'hoTen'    => $hoTen,
					'ngaySinh' => !empty($_POST['ngaySinh']) ? $_POST['ngaySinh'] : null,
					'gioiTinh' => $_POST['gioiTinh'] ?? null,
					'sdt'      => $sdt,
					'diaChi'   => $diaChi
				];

				$hasChange = (
					($currentProfile['fullname'] ?? '') !== $dataUpdate['hoTen'] ||
					($currentProfile['birthDate'] ?? '') !== ($dataUpdate['ngaySinh'] ?? '') ||
					($currentProfile['gender'] ?? null) !== ($dataUpdate['gioiTinh'] ?? null) ||
					($currentProfile['phone'] ?? '') !== $dataUpdate['sdt'] ||
					($currentProfile['address'] ?? '') !== $dataUpdate['diaChi']
				);

				if (!$hasChange) {
					echo "<script>alert('Không có thông tin nào thay đổi.');"
						. " window.location.href='/JobCV/index.php?route=profile';</script>";
					exit();
				}

				$result = $this->userModel->updateUserProfile($maUser, $dataUpdate);
			}

			if ($result) {
				if (isset($dataUpdate['hoTen'])) {
					$_SESSION['user_name'] = $dataUpdate['hoTen'];
				}
				echo "<script>alert('Cập nhật thông tin hồ sơ thành công!');"
					. " window.location.href='/JobCV/index.php?route=profile';</script>";
				exit();
			} else {
				echo "<script>alert('Có lỗi xảy ra trong quá trình cập nhật dữ liệu!');"
					. " window.history.back();</script>";
				exit();
			}
		}
	}
}
?>