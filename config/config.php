<?php
/**
 * File: config/config.php
 * Đường dẫn: Module5_UngTuyenVaQuanLyHoSo/config/config.php
 * Chức năng: Khai báo toàn bộ hằng số cấu hình dùng chung cho module
 *            (kết nối CSDL, upload file, gửi mail, đường dẫn hệ thống).
 * Tương thích: PHP 5.6 + MariaDB 10.1 (XAMPP)
 */

// Bật hiển thị lỗi khi phát triển. 
// TODO: Tắt (0) khi deploy production thật.
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ---------- Cấu hình kết nối CSDL ----------
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'cvdb');
define('DB_CHARSET', 'utf8mb4');

// ---------- Đường dẫn hệ thống ----------
// Thư mục gốc trên ổ đĩa của module (dùng cho require/include)
define('ROOT_PATH', dirname(__DIR__));

// Đường dẫn URL gốc trên trình duyệt. 
// TODO: Sửa lại cho đúng tên thư mục khi triển khai project thật.
define('BASE_URL', '/test/Module5_UngTuyenVaQuanLyHoSo');

// ---------- Cấu hình upload Cover Letter ----------
define('UPLOAD_COVER_LETTER_DIR', ROOT_PATH . '/uploads/coverLetter/');
define('UPLOAD_COVER_LETTER_URL', BASE_URL . '/uploads/coverLetter/');
define('MAX_COVER_LETTER_SIZE', 5 * 1024 * 1024); // 5MB
define('MAX_COVER_LETTER_TEXT_LENGTH', 3000);     // Giới hạn ký tự Cover Letter dạng text

// Các định dạng file Cover Letter được phép upload
$GLOBALS['ALLOWED_COVER_LETTER_EXT'] = array('pdf', 'doc', 'docx');
$GLOBALS['ALLOWED_COVER_LETTER_MIME'] = array(
	'application/pdf',
	'application/msword',
	'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
);

// ---------- Cấu hình gửi mail (PHPMailer - SMTP) ----------
// TODO: Thay thông tin SMTP thật của nhóm (Gmail + App Password)
define('MAIL_SMTP_HOST', 'smtp.gmail.com');
define('MAIL_SMTP_PORT', 587);
define('MAIL_SMTP_USERNAME', 'mailnhomjobcv2005@gmail.com');
define('MAIL_SMTP_PASSWORD', 'uyux pvvd htlx lquv');
define('MAIL_FROM_ADDRESS', 'mailnhomjobcv2005@gmail.com');
define('MAIL_FROM_NAME', 'Hệ Thống Tuyển Dụng');

// ---------- Vai trò người dùng (Role) ----------
define('ROLE_UNGVIEN', 0);
define('ROLE_NHATUYENDUNG', 1);
define('ROLE_ADMIN', 2);

// ---------- Trạng thái hồ sơ ứng tuyển ----------
define('STATUS_MOI_NOP', 'MoiNop');
define('STATUS_DA_XEM', 'DaXem');
define('STATUS_HEN_PHONG_VAN', 'HenPhongVan');
define('STATUS_NHAN_VIEC', 'NhanViec');
define('STATUS_TU_CHOI', 'TuChoi');