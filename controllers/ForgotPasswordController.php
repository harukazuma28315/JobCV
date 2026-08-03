<?php
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../services/EmailService.php';

/**
 * Controller quản lý toàn bộ quy trình Khôi phục mật khẩu (Quên mật khẩu).
 * Xử lý các yêu cầu AJAX từ phía client bao gồm: Gửi mã OTP, Xác thực mã OTP và Đặt lại mật khẩu mới.
 */
class ForgotPasswordController {
	/** @var mixed Kết nối cơ sở dữ liệu */
	private $conn;

	/** @var UserModel Đối tượng thao tác dữ liệu người dùng */
	private $userModel;

	/** @var EmailService Dịch vụ gửi email thông báo */
	private $emailService;

	/** @var int Thời gian chờ giữa 2 lần yêu cầu gửi OTP (tính bằng giây) */
	private $cooldownSeconds = 60;

	/**
	 * Khởi tạo Controller với kết nối CSDL và khởi tạo các Service phụ thuộc.
	 * 
	 * @param mixed $conn Đối tượng kết nối CSDL.
	 */
	public function __construct($conn) {
		$this->conn = $conn;
		$this->userModel = new UserModel($conn);
		$this->emailService = new EmailService();
	}

	/**
	 * Điều phối và xử lý yêu cầu HTTP Request dựa trên tham số 'action'.
	 * Phản hồi dữ liệu dạng JSON cho các thao tác AJAX phía Client.
	 * 
	 * @return void
	 */
	public function handleRequest() {
		header('Content-Type: application/json');

		// Bắt buộc request phải thông qua phương thức POST và có hành động cụ thể để bảo mật
		if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['action'])) {
			echo json_encode(['status' => 'error', 'message' => 'Yêu cầu không hợp lệ!']);
			exit();
		}

		$action = $_POST['action'];

		if ($action === 'send_forgot_otp') {
			$this->handleSendForgotOtp();
		} elseif ($action === 'verify_otp') {
			$this->handleVerifyOtp();
		} elseif ($action === 'reset_password') {
			$this->handleResetPassword();
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Hành động không được hỗ trợ!']);
			exit();
		}
	}

	/**
	 * Xử lý gửi mã OTP khôi phục mật khẩu qua Email.
	 * Kiểm tra định dạng email, tồn tại tài khoản và giới hạn tần suất gửi (Anti-spam / Cooldown).
	 * 
	 * @return void
	 */
	private function handleSendForgotOtp() {
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

		// Tránh bị người dùng spam gửi email liên tục gây nghẽn mail server
		if ($lastSentAt > 0 && ($now - $lastSentAt) < $this->cooldownSeconds) {
			$remaining = $this->cooldownSeconds - ($now - $lastSentAt);
			echo json_encode([
				'status' => 'cooldown',
				'message' => "Vui lòng chờ {$remaining} giây trước khi lấy mã lại.",
				'remaining' => $remaining
			]);
			exit();
		}

		// Dùng random_int thay cho rand() để đảm bảo an toàn mật mã theo tiêu chuẩn SQA
		$otp = random_int(100000, 999999);

		$_SESSION['forgot_otp'] = $otp;
		$_SESSION['forgot_email'] = $email;
		$_SESSION['forgot_expired'] = $now + (15 * 60); // OTP có hiệu lực trong 15 phút
		$_SESSION['forgot_otp_last_sent_at'] = $now;

		if ($this->emailService->sendOTPEmail($email, $otp)) {
			echo json_encode(['status' => 'success', 'message' => 'Mã OTP khôi phục đã được gửi vào email của bạn!']);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Không thể gửi email, vui lòng thử lại sau!']);
		}
		exit();
	}

	/**
	 * Xử lý đối chiếu mã OTP nhập vào với mã đã lưu trữ trong Session.
	 * 
	 * @return void
	 */
	private function handleVerifyOtp() {
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

		// Đối chiếu cả email lẫn mã OTP: đề phòng người dùng đổi email trên form
		// sau khi đã nhận OTP, tránh xác thực nhầm OTP của email khác.
		if ($email !== $_SESSION['forgot_email'] || (int) $otp !== (int) $_SESSION['forgot_otp']) {
			echo json_encode(['status' => 'error', 'message' => 'Mã OTP xác thực không chính xác!']);
			exit();
		}

		$_SESSION['forgot_verified'] = true;
		echo json_encode(['status' => 'success', 'message' => 'Xác thực OTP thành công!']);
		exit();
	}

	/**
	 * Xử lý mã hóa và cập nhật mật khẩu mới vào cơ sở dữ liệu.
	 * Xóa sạch các biến Session liên quan đến OTP sau khi hoàn tất để tránh tái sử dụng token/session.
	 * 
	 * @return void
	 */
	private function handleResetPassword() {
		// Đảm bảo session đã được load
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}

		$email = trim($_POST['email'] ?? '');
		$password = $_POST['matKhau'] ?? '';
		$confirmPassword = $_POST['matKhauConfirm'] ?? '';

		// Kiểm tra Session xác thực OTP
		if (
			!isset($_SESSION['forgot_verified']) || 
			$_SESSION['forgot_verified'] !== true || 
			strtolower($email) !== strtolower($_SESSION['forgot_email'] ?? '')
		) {
			echo json_encode(['status' => 'error', 'message' => 'Hành động không hợp lệ hoặc chưa qua bước xác thực OTP!']);
			exit();
		}

		if (empty($password) || $password !== $confirmPassword) {
			echo json_encode(['status' => 'error', 'message' => 'Mật khẩu nhập lại không khớp hoặc đang để trống!']);
			exit();
		}

		// Kiểm tra định dạng mật khẩu ở phía server (không được chỉ dựa vào validate của client,
		// vì client có thể bị bypass bằng cách tắt JS hoặc gọi thẳng API bằng Postman/cURL).
		// Phải khớp đúng rule đã khai báo ở input pattern="^\S{6,32}$" bên reset-password.php
		if (!preg_match('/^\S{6,32}$/', $password)) {
			echo json_encode(['status' => 'error', 'message' => 'Mật khẩu phải từ 6 đến 32 ký tự và không chứa khoảng trắng!']);
			exit();
		}

		$passwordHashed = password_hash($password, PASSWORD_DEFAULT);
		if ($this->userModel->updatePassword($email, $passwordHashed)) {
			// Xóa sạch session sau khi đổi thành công
			unset(
				$_SESSION['forgot_otp'],
				$_SESSION['forgot_email'],
				$_SESSION['forgot_expired'],
				$_SESSION['forgot_verified'],
				$_SESSION['forgot_otp_last_sent_at']
			);

			echo json_encode([
				'status'  => 'success',
				'message' => 'Đổi mật khẩu thành công! Hệ thống sẽ chuyển về trang đăng nhập.'
			]);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Có lỗi xảy ra trong quá trình cập nhật mật khẩu!']);
		}
		exit();
	}
}