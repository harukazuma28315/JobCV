<?php
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../services/EmailService.php';

class ForgotPasswordController {
    private $conn;
    private $userModel;
    private $emailService;
    private $cooldownSeconds = 60;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->userModel = new UserModel($conn);
        $this->emailService = new EmailService();
    }

    public function handleRequest() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['action'])) {
            echo json_encode(['status' => 'error', 'message' => 'Yêu cầu không hợp lệ!']);
            exit();
        }

        $action = $_POST['action'];

        // 1. Gửi mã OTP
        if ($action === 'send_forgot_otp') {
            $email = trim($_POST['email'] ?? '');

            if (empty($email) || !$this->userModel->isValidEmail($email)) {
                echo json_encode(['status' => 'error', 'message' => 'Email không đúng định dạng!']);
                exit();
            }

            if (!$this->userModel->isUserExists($email)) {
                echo json_encode(['status' => 'error', 'message' => 'Email này chưa được đăng ký trong hệ thống!']);
                exit();
            }

            $now = time();
            $lastSentAt = isset($_SESSION['forgot_otp_last_sent_at']) ? (int) $_SESSION['forgot_otp_last_sent_at'] : 0;

            if ($lastSentAt > 0 && ($now - $lastSentAt) < $this->cooldownSeconds) {
                $remaining = $this->cooldownSeconds - ($now - $lastSentAt);
                echo json_encode([
                    'status' => 'cooldown',
                    'message' => "Vui lòng chờ {$remaining} giây trước khi lấy mã lại.",
                    'remaining' => $remaining
                ]);
                exit();
            }

            $otp = rand(100000, 999999);

            $_SESSION['forgot_otp'] = $otp;
            $_SESSION['forgot_email'] = $email;
            $_SESSION['forgot_expired'] = $now + (15 * 60);
            $_SESSION['forgot_otp_last_sent_at'] = $now;

            if ($this->emailService->sendOTPEmail($email, $otp)) {
                echo json_encode(['status' => 'success', 'message' => 'Mã OTP khôi phục đã được gửi vào email của bạn!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Không thể gửi email, vui lòng thử lại sau!']);
            }
            exit();
        }

        // 2. Xác nhận OTP
        if ($action === 'verify_otp') {
            $email = trim($_POST['email'] ?? '');
            $otp = trim($_POST['otp'] ?? '');

            if (!isset($_SESSION['forgot_otp']) || !isset($_SESSION['forgot_email'])) {
                echo json_encode(['status' => 'error', 'message' => 'Vui lòng nhấn lấy mã trước khi xác nhận!']);
                exit();
            }

            if (time() > $_SESSION['forgot_expired']) {
                echo json_encode(['status' => 'error', 'message' => 'Mã OTP đã hết hạn, vui lòng lấy mã mới!']);
                exit();
            }

            if ($email !== $_SESSION['forgot_email'] || (int) $otp !== (int) $_SESSION['forgot_otp']) {
                echo json_encode(['status' => 'error', 'message' => 'Mã OTP xác thực không chính xác!']);
                exit();
            }

            $_SESSION['forgot_verified'] = true;
            echo json_encode(['status' => 'success', 'message' => 'Xác thực OTP thành công!']);
            exit();
        }

        // 3. Đổi Mật Khẩu
        if ($action === 'reset_password') {
            $email = trim($_POST['email'] ?? '');
            $matKhau = $_POST['matKhau'] ?? $_POST['new_password'] ?? '';
            $matKhauConfirm = $_POST['matKhauConfirm'] ?? $_POST['confirm_password'] ?? '';

            if (!isset($_SESSION['forgot_verified']) || $_SESSION['forgot_verified'] !== true || $email !== ($_SESSION['forgot_email'] ?? '')) {
                echo json_encode(['status' => 'error', 'message' => 'Hành động không hợp lệ hoặc chưa qua bước xác thực OTP!']);
                exit();
            }

            if (empty($matKhau) || $matKhau !== $matKhauConfirm) {
                echo json_encode(['status' => 'error', 'message' => 'Mật khẩu nhập lại không khớp hoặc đang để trống!']);
                exit();
            }

            $matKhauHashed = password_hash($matKhau, PASSWORD_DEFAULT);
            if ($this->userModel->updatePassword($email, $matKhauHashed)) {
                unset($_SESSION['forgot_otp']);
                unset($_SESSION['forgot_email']);
                unset($_SESSION['forgot_expired']);
                unset($_SESSION['forgot_verified']);
                unset($_SESSION['forgot_otp_last_sent_at']);

                echo json_encode(['status' => 'success', 'message' => 'Đổi mật khẩu thành công! Hệ thống sẽ chuyển về trang đăng nhập.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Có lỗi xảy ra trong quá trình cập nhật mật khẩu!']);
            }
            exit();
        }
    }
}