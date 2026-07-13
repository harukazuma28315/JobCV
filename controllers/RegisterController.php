<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/UserModel.php';

/**
 * Điều hướng và xử lý các yêu cầu đăng ký tài khoản thành viên
 */
class RegisterController {
	private $userModel;
	private $conn;

	/**
	 * Khởi tạo Controller đăng ký
	 * @param mysqli $conn Kết nối cơ sở dữ liệu kế thừa từ cấu hình hệ thống
	 */
	public function __construct($conn) {
		$this->conn = $conn;
		$this->userModel = new UserModel($conn);
	}

	/**
	 * Xử lý dữ liệu gửi lên từ Form Đăng ký
	 */
	public function handleRegister() {
		if ($_SERVER["REQUEST_METHOD"] !== "POST") {
			return;
		}

		// Định danh vai trò tài khoản (0: Ứng viên, 1: Nhà tuyển dụng)
		$role = isset($_POST['Role']) ? intval($_POST['Role']) : 0;

		// Thu thập và làm sạch dữ liệu chung
		$email = trim($_POST['Email']);
		$matKhau = $_POST['MatKhau'];
		$hoTen = trim($_POST['HoTen']);
		$sdt = !empty($_POST['SDT']) ? trim($_POST['SDT']) : null;
		$diaChi = !empty($_POST['DiaChi']) ? trim($_POST['DiaChi']) : null;

		// Chỉ lấy dữ liệu cá nhân nếu vai trò là Ứng viên (Nhà tuyển dụng sẽ nhận giá trị null mặc định)
		$ngaySinh = ($role === 0 && !empty($_POST['NgaySinh'])) ? $_POST['NgaySinh'] : null;
		$gioiTinh = ($role === 0 && isset($_POST['GioiTinh'])) ? intval($_POST['GioiTinh']) : null;

		// Tạo mã định danh duy nhất và mã hóa mật khẩu bảo mật
		$maUser = "USR" . time() . rand(10, 99);
		$matKhauHashed = password_hash($matKhau, PASSWORD_DEFAULT);

		// Kiểm tra tính hợp lệ về định dạng Email cấu trúc trước
		if (!$this->userModel->isValidEmail($email)) {
			echo "<script>alert('Định dạng địa chỉ Email không hợp lệ!'); window.history.back();</script>";
			return;
		}

		// Kiểm tra trùng lặp tài khoản dựa trên Email
		if ($this->userModel->isUserExists($email)) {
			echo "<script>alert('Địa chỉ Email này đã được đăng ký!'); window.history.back();</script>";
			return;
		}

		// Đóng gói mảng dữ liệu tài khoản tổng quan
		$userData = [
			'maUser' => $maUser,
			'matKhauHashed' => $matKhauHashed,
			'role' => $role,
			'hoTen' => $hoTen,
			'ngaySinh' => $ngaySinh,
			'gioiTinh' => $gioiTinh,
			'email' => $email,
			'sdt' => $sdt,
			'diaChi' => $diaChi
		];

		$isSuccess = false;

		// Định tuyến xử lý nghiệp vụ theo vai trò cụ thể
		if ($role === 1) {
			$employerData = [
				'tenCongTy' => $hoTen,
				'website' => !empty($_POST['Website']) ? trim($_POST['Website']) : null,
				'linhVuc' => !empty($_POST['LinhVuc']) ? trim($_POST['LinhVuc']) : null,
				'maSoThue' => isset($_POST['MaSoThue']) ? intval($_POST['MaSoThue']) : 0
			];
			$isSuccess = $this->userModel->registerEmployer($userData, $employerData);
		} else {
			$isSuccess = $this->userModel->registerCandidate($userData);
		}

		// Điều hướng trả về kết quả hiển thị cho View
		if ($isSuccess) {
			echo "<script>alert('Đăng ký tài khoản thành công!'); window.location.href='../views/dangnhap.html';</script>";
		} else {
			echo "<script>alert('Có lỗi xảy ra trong quá trình ghi dữ liệu!'); window.history.back();</script>";
		}
	}
}

// Thực thi chạy và dọn dẹp kết nối sau khi đóng phiên làm việc
$registerCtrl = new RegisterController($conn);
$registerCtrl->handleRegister();
$conn->close();
?>