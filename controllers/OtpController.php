<?php
ob_start();
ini_set('display_errors', 0);
error_reporting(0);
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../services/EmailService.php';

header('Content-Type: application/json; charset=utf-8');

$emailService = new EmailService();
$userModel = new UserModel($conn);
$cooldownSeconds = 60;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'send_otp') {
    $email = trim($_POST['email'] ?? '');

    if (empty($email) || !$userModel->isValidEmail($email)) {
        echo json_encode(['status' => 'error', 'message' => 'Email không đúng định dạng!']);
        exit();
    }

    if ($userModel->isUserExists($email)) {
        echo json_encode(['status' => 'error', 'message' => 'Email này đã tồn tại trong hệ thống!']);
        exit();
    }

    $now = time();
    $lastSentAt = isset($_SESSION['register_otp_last_sent_at']) ? (int) $_SESSION['register_otp_last_sent_at'] : 0;

    if ($lastSentAt > 0 && ($now - $lastSentAt) < $cooldownSeconds) {
        $remaining = $cooldownSeconds - ($now - $lastSentAt);
        echo json_encode([
            'status' => 'cooldown',
            'message' => "Vui lòng chờ {$remaining} giây trước khi lấy mã lại.",
            'remaining' => $remaining
        ]);
        exit();
    }

    $otp = rand(100000, 999999);

    $_SESSION['register_otp'] = $otp;
    $_SESSION['register_email'] = $email;
    $_SESSION['otp_expired'] = $now + (15 * 60);
    $_SESSION['register_otp_last_sent_at'] = $now;

    if ($emailService->sendOTPEmail($email, $otp)) {
        echo json_encode(['status' => 'success', 'message' => 'Mã OTP đã được gửi vào email của bạn!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Không thể gửi email, vui lòng thử lại sau!']);
    }
    $conn->close();
    exit();
}

echo json_encode(['status' => 'error', 'message' => 'Yêu cầu không hợp lệ.']);
exit();
?>