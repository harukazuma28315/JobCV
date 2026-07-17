<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../config/mail.php';

$userModel = new UserModel($conn);

// Kiểm tra nếu có yêu cầu gửi OTP qua phương thức POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'send_otp') {
	$email = trim($_POST['email']);

	// 1. Kiểm tra email trống hoặc sai định dạng
	if (empty($email) || !$userModel->isValidEmail($email)) {
		echo json_encode(['status' => 'error', 'message' => 'Email không đúng định dạng!']);
		exit();
	}

	// 2. Kiểm tra xem email này đã đăng ký tài khoản trước đó chưa
	if ($userModel->isUserExists($email)) {
		echo json_encode(['status' => 'error', 'message' => 'Email này đã tồn tại trong hệ thống!']);
		exit();
	}

	// 3. Tạo mã OTP ngẫu nhiên gồm 6 chữ số
	$otp = rand(100000, 999999);

	// 4. Lưu OTP và Email vào Session để đối chiếu lúc sau, kèm thời gian hết hạn (15 phút)
	$_SESSION['register_otp'] = $otp;
	$_SESSION['register_email'] = $email;
	$_SESSION['otp_expired'] = time() + (15 * 60); 

	// 5. Tiến hành gửi mail
	if (sendOTPEmail($email, $otp)) {
		echo json_encode(['status' => 'success', 'message' => 'Mã OTP đã được gửi vào email của bạn!']);
	} else {
		echo json_encode(['status' => 'error', 'message' => 'Không thể gửi email, vui lòng thử lại sau!']);
	}
	$conn->close();
	exit();
}
?>