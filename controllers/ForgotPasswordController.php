<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../config/mail.php';

$userModel = new UserModel($conn);

// Kiểm tra phương thức xử lý yêu cầu
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    // 1. XỬ LÝ GỬI MÃ OTP QUA EMAIL
    if ($action === 'send_forgot_otp') {
        $email = trim($_POST['email']);

        if (empty($email) || !$userModel->isValidEmail($email)) {
            echo json_encode(['status' => 'error', 'message' => 'Email không đúng định dạng!']);
            exit();
        }

        // Kiểm tra xem email đã tồn tại hay chưa (Chỉ cấp OTP cho email ĐÃ đăng ký)
        if (!$userModel->isUserExists($email)) {
            echo json_encode(['status' => 'error', 'message' => 'Email này chưa được đăng ký trong hệ thống!']);
            exit();
        }

        $otp = rand(100000, 999999);

        // Lưu thông tin vào session riêng biệt cho tính năng quên mật khẩu
        $_SESSION['forgot_otp'] = $otp;
        $_SESSION['forgot_email'] = $email;
        $_SESSION['forgot_expired'] = time() + (15 * 60); 

        if (sendOTPEmail($email, $otp)) { // Sử dụng lại hàm sendOTPEmail cấu hình sẵn
            echo json_encode(['status' => 'success', 'message' => 'Mã OTP khôi phục đã được gửi vào email của bạn!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Không thể gửi email, vui lòng thử lại sau!']);
        }
        $conn->close();
        exit();
    }

    // 2. XỬ LÝ XÁC THỰC MÃ OTP
    if ($action === 'verify_otp') {
        $email = trim($_POST['email']);
        $otp = trim($_POST['otp']);

        if (!isset($_SESSION['forgot_otp']) || !isset($_SESSION['forgot_email'])) {
            echo json_encode(['status' => 'error', 'message' => 'Vui lòng nhấn lấy mã trước khi xác nhận!']);
            exit();
        }

        if (time() > $_SESSION['forgot_expired']) {
            echo json_encode(['status' => 'error', 'message' => 'Mã OTP đã hết hạn, vui lòng lấy mã mới!']);
            exit();
        }

        if ($email !== $_SESSION['forgot_email'] || (int)$otp !== (int)$_SESSION['forgot_otp']) {
            echo json_encode(['status' => 'error', 'message' => 'Mã OTP xác thực không chính xác!']);
            exit();
        }

        // Đánh dấu session đã xác thực OTP thành công
        $_SESSION['forgot_verified'] = true;
        echo json_encode(['status' => 'success', 'message' => 'Xác thực OTP thành công!']);
        exit();
    }

    // 3. XỬ LÝ CẬP NHẬT MẬT KHẨU MỚI
    if ($action === 'reset_password') {
        $email = trim($_POST['email']);
        $matKhau = $_POST['matKhau'];
        $matKhauConfirm = $_POST['matKhauConfirm'];

        if (!isset($_SESSION['forgot_verified']) || $_SESSION['forgot_verified'] !== true || $email !== $_SESSION['forgot_email']) {
            echo json_encode(['status' => 'error', 'message' => 'Hành động không hợp lệ hoặc chưa qua bước xác thực OTP!']);
            exit();
        }

        if ($matKhau !== $matKhauConfirm) {
            echo json_encode(['status' => 'error', 'message' => 'Mật khẩu nhập lại không khớp!']);
            exit();
        }

        // Tiến hành mã hóa mật khẩu mới và lưu lại
        $matKhauHashed = password_hash($matKhau, PASSWORD_DEFAULT);
        if ($userModel->updatePassword($email, $matKhauHashed)) {
            // Xóa sạch Session khôi phục sau khi thành công
            unset($_SESSION['forgot_otp']);
            unset($_SESSION['forgot_email']);
            unset($_SESSION['forgot_expired']);
            unset($_SESSION['forgot_verified']);

            echo json_encode(['status' => 'success', 'message' => 'Đổi mật khẩu thành công! Hệ thống sẽ chuyển về trang đăng nhập.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Có lỗi xảy ra trong quá trình cập nhật mật khẩu!']);
        }
        $conn->close();
        exit();
    }
}
?>