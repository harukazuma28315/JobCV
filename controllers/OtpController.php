<?php
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../services/EmailService.php';

/**
 * Controller chuyên trách xử lý tạo và gửi mã xác thực OTP qua Email cho quy trình đăng ký.
 */
class OtpController {
    /** @var mixed Kết nối cơ sở dữ liệu */
    private $conn;

    /** @var UserModel Đối tượng làm việc với bảng dữ liệu người dùng */
    private $userModel;

    /** @var EmailService Dịch vụ gửi email hệ thống */
    private $emailService;

    /** @var int Thời gian giãn cách giữa các lần yêu cầu lấy OTP (tính bằng giây) */
    private $cooldownSeconds = 60;

    /**
     * Khởi tạo OtpController với kết nối CSDL và các dependency cần thiết.
     * 
     * @param mixed $conn Đối tượng kết nối CSDL.
     */
    public function __construct($conn) {
        $this->conn = $conn;
        $this->userModel = new UserModel($conn);
        $this->emailService = new EmailService();
    }

    /**
     * Điều phối xử lý yêu cầu gửi OTP từ phía Client.
     * Trả về kết quả dưới định dạng JSON cho AJAX fetch.
     * 
     * @return void
     */
    public function handleRequest() {
        // Đảm bảo Session đã được khởi tạo trước khi thao tác
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json; charset=utf-8');

        // Bắt buộc yêu cầu gửi qua phương thức POST và phải đúng action 'send_otp'
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'send_otp') {
            $this->handleSendOtp();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Yêu cầu không hợp lệ.']);
            exit();
        }
    }

    /**
     * Xử lý logic kiểm tra thông tin email, thời gian cooldown và thực hiện gửi OTP qua email.
     * 
     * @return void
     */
    private function handleSendOtp() {
        $email = trim($_POST['email'] ?? '');

        // 1. Kiểm tra tính hợp lệ về cấu trúc của Email
        if (empty($email) || !$this->userModel->isValidEmail($email)) {
            echo json_encode(['status' => 'error', 'message' => 'Email không đúng định dạng!']);
            exit();
        }

        // 2. Kiểm tra xem Email đã đăng ký trong hệ thống chưa (Đăng ký mới thì email phải chưa tồn tại)
        if ($this->userModel->isUserExists($email)) {
            echo json_encode(['status' => 'error', 'message' => 'Email này đã tồn tại trong hệ thống!']);
            exit();
        }

        $now = time();
        $lastSentAt = isset($_SESSION['register_otp_last_sent_at']) ? (int) $_SESSION['register_otp_last_sent_at'] : 0;

        // 3. Kiểm tra Cooldown để ngăn chặn hành vi spam request
        if ($lastSentAt > 0 && ($now - $lastSentAt) < $this->cooldownSeconds) {
            $remaining = $this->cooldownSeconds - ($now - $lastSentAt);
            echo json_encode([
                'status' => 'cooldown',
                'message' => "Vui lòng chờ {$remaining} giây trước khi lấy mã lại.",
                'remaining' => $remaining
            ]);
            exit();
        }

        // 4. Sinh mã OTP ngẫu nhiên an toàn theo tiêu chuẩn mã hóa
        $otp = random_int(100000, 999999);

        // 5. Lưu thông tin OTP vào Session
        $_SESSION['register_otp'] = $otp;
        $_SESSION['register_email'] = $email;
        $_SESSION['otp_expired'] = $now + (15 * 60); // OTP có thời hạn 15 phút
        $_SESSION['register_otp_last_sent_at'] = $now;

        // 6. Thực hiện gửi OTP qua Email Service
        if ($this->emailService->sendOTPEmail($email, $otp)) {
            echo json_encode(['status' => 'success', 'message' => 'Mã OTP đã được gửi vào email của bạn!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Không thể gửi email, vui lòng thử lại sau!']);
        }
        exit();
    }
}