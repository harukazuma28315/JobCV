<?php
/**
 * File: app/services/EmailService.php
 * Đường dẫn: Module5_UngTuyenVaQuanLyHoSo/app/services/EmailService.php
 * Chức năng: Gửi email tự động thông báo cho ứng viên khi Nhà tuyển dụng
 *            thay đổi trạng thái hồ sơ ứng tuyển (DaXem, HenPhongVan, NhanViec, TuChoi).
 *            Sử dụng thư viện PHPMailer tải trực tiếp (không qua Composer).
 */

// Require truc tiep 3 file nguon cua PHPMailer theo huong dan "Without Composer".
// TODO: Dam bao 3 file nay da duoc tai va dat dung vi tri (xem lib/PHPMailer/HUONG_DAN_CAI_DAT.txt)
require_once ROOT_PATH . '/lib/PHPMailer/src/Exception.php';
require_once ROOT_PATH . '/lib/PHPMailer/src/PHPMailer.php';
require_once ROOT_PATH . '/lib/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService
{
	/**
	 * Gửi email khi Nhà tuyển dụng đánh dấu "Đã xem" hồ sơ.
	 *
	 * @param string $emailNguoiNhan
	 * @param string $tenNguoiNhan
	 * @param string $tieuDeTin
	 * @return bool
	 */
	public function sendViewedMail($emailNguoiNhan, $tenNguoiNhan, $tieuDeTin)
	{
		$tieuDeEmail = 'Ho so ung tuyen cua ban da duoc xem';
		$noiDungEmail = $this->taoNoiDungEmail(
			$tenNguoiNhan,
			'Nha tuyen dung da xem ho so ung tuyen cua ban cho vi tri "' . htmlspecialchars($tieuDeTin) . '". '
			. 'Vui long theo doi email de cap nhat cac buoc tiep theo.'
		);

		return $this->guiEmailSMTP($emailNguoiNhan, $tenNguoiNhan, $tieuDeEmail, $noiDungEmail);
	}

	/**
	 * Gửi email mời phỏng vấn.
	 *
	 * @param string $emailNguoiNhan
	 * @param string $tenNguoiNhan
	 * @param string $tieuDeTin
	 * @return bool
	 */
	public function sendInterviewMail($emailNguoiNhan, $tenNguoiNhan, $tieuDeTin)
	{
		$tieuDeEmail = 'Thu moi phong van';
		$noiDungEmail = $this->taoNoiDungEmail(
			$tenNguoiNhan,
			'Chuc mung! Ban da duoc moi phong van cho vi tri "' . htmlspecialchars($tieuDeTin) . '". '
			. 'Nha tuyen dung se lien he voi ban de sap xep thoi gian cu the.'
		);

		return $this->guiEmailSMTP($emailNguoiNhan, $tenNguoiNhan, $tieuDeEmail, $noiDungEmail);
	}

	/**
	 * Gửi email thông báo trúng tuyển / nhận việc.
	 *
	 * @param string $emailNguoiNhan
	 * @param string $tenNguoiNhan
	 * @param string $tieuDeTin
	 * @return bool
	 */
	public function sendAcceptMail($emailNguoiNhan, $tenNguoiNhan, $tieuDeTin)
	{
		$tieuDeEmail = 'Chuc mung ban da trung tuyen';
		$noiDungEmail = $this->taoNoiDungEmail(
			$tenNguoiNhan,
			'Chuc mung ban da duoc nhan vao vi tri "' . htmlspecialchars($tieuDeTin) . '". '
			. 'Nha tuyen dung se som lien he de huong dan cac buoc tiep theo.'
		);

		return $this->guiEmailSMTP($emailNguoiNhan, $tenNguoiNhan, $tieuDeEmail, $noiDungEmail);
	}

	/**
	 * Gửi email từ chối hồ sơ.
	 *
	 * @param string $emailNguoiNhan
	 * @param string $tenNguoiNhan
	 * @param string $tieuDeTin
	 * @return bool
	 */
	public function sendRejectMail($emailNguoiNhan, $tenNguoiNhan, $tieuDeTin)
	{
		$tieuDeEmail = 'Ket qua ung tuyen';
		$noiDungEmail = $this->taoNoiDungEmail(
			$tenNguoiNhan,
			'Cam on ban da quan tam ung tuyen vi tri "' . htmlspecialchars($tieuDeTin) . '". '
			. 'Rat tiec hien tai ho so cua ban chua phu hop. Chuc ban som tim duoc cong viec ung y.'
		);

		return $this->guiEmailSMTP($emailNguoiNhan, $tenNguoiNhan, $tieuDeEmail, $noiDungEmail);
	}

	/**
	 * Tạo nội dung email HTML dùng chung một khuôn mẫu cho các loại thông báo.
	 *
	 * @param string $tenNguoiNhan
	 * @param string $noiDungChinh
	 * @return string
	 */
	private function taoNoiDungEmail($tenNguoiNhan, $noiDungChinh)
	{
		$tenAnToan = htmlspecialchars($tenNguoiNhan);

		return '<p>Xin chao ' . $tenAnToan . ',</p>'
			. '<p>' . $noiDungChinh . '</p>'
			. '<p>Tran trong,<br>' . htmlspecialchars(MAIL_FROM_NAME) . '</p>';
	}

	/**
	 * Gửi email qua SMTP bằng PHPMailer.
	 * Bắt Exception nội bộ để 1 lỗi gửi mail không làm sập luồng cập nhật trạng thái.
	 *
	 * @param string $emailNguoiNhan
	 * @param string $tenNguoiNhan
	 * @param string $tieuDeEmail
	 * @param string $noiDungEmail
	 * @return bool
	 */
	private function guiEmailSMTP($emailNguoiNhan, $tenNguoiNhan, $tieuDeEmail, $noiDungEmail)
	{
		$mail = new PHPMailer(true);

		try {
			$mail->isSMTP();

			$mail->Host = MAIL_SMTP_HOST;
			$mail->SMTPAuth = true;
			$mail->Username = MAIL_SMTP_USERNAME;
			$mail->Password = MAIL_SMTP_PASSWORD;
			$mail->SMTPSecure = 'tls';
			$mail->Port = MAIL_SMTP_PORT;
			$mail->CharSet = 'UTF-8';

			$mail->setFrom(MAIL_FROM_ADDRESS, MAIL_FROM_NAME);
			$mail->addAddress($emailNguoiNhan, $tenNguoiNhan);

			$mail->isHTML(true);
			$mail->Subject = $tieuDeEmail;
			$mail->Body = $noiDungEmail;

			$mail->send();

			return true;
		} catch (Exception $e) {
			// Chỉ ghi log, không throw ra ngoài
			error_log('Gui email that bai: ' . $mail->ErrorInfo);

			return false;
		}
	}
}