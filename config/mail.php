<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../libs/PHPMailer/src/Exception.php';
require __DIR__ . '/../libs/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/../libs/PHPMailer/src/SMTP.php';

function sendOTPEmail($userEmail, $otpCode) {
	$mail = new PHPMailer(true);
	try {
		$mail->isSMTP();
		$mail->Host       = 'smtp.gmail.com';
		$mail->SMTPAuth   = true;
		$mail->Username   = 'dn1078847@gmail.com';     // Email gửi đi
		$mail->Password   = 'dmrn hcpi dlap mfnc';          // Mật khẩu ứng dụng 16 ký tự
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
		$mail->Port       = 587;
		$mail->CharSet    = 'UTF-8';

		$mail->setFrom('dn1078847@gmail.com', 'Hệ Thống Tuyển Dụng');
		$mail->addAddress($userEmail);

		$mail->isHTML(true);
		$mail->Subject = 'Mã xác thực đăng ký tài khoản';
		$mail->Body    = "
			<h3>Mã xác thực (OTP) của bạn là:</h3>
			<h1 style='color: #0d6efd; letter-spacing: 5px;'>$otpCode</h1>
			<p>Vui lòng không chia sẻ mã này với bất kỳ ai. Mã có hiệu lực trong vòng 15 phút.</p>
		";

		$mail->send();
		return true;
	} catch (Exception $e) {
		return false;
	}
}
?>