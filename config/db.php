<?php
require_once __DIR__ . '/../config/config.php';
/**
 * File: config/db.php
 * Đường dẫn: Module5_UngTuyenVaQuanLyHoSo/config/db.php
 * Chức năng: Cung cấp kết nối MySQLi duy nhất (Singleton) cho toàn bộ module,
 *            tránh mở nhiều kết nối CSDL không cần thiết.
 */

$host = "localhost";
$dbname = "cvdb";
$username = "root";
$password = "";

class Database
{
	/**
	 * @var mysqli|null Instance kết nối duy nhất
	 */
	private static $instance = null;

	/**
	 * Không cho phép khởi tạo trực tiếp (Singleton Pattern).
	 */
	private function __construct()
	{
	}

	/**
	 * Lấy kết nối MySQLi duy nhất trong toàn bộ vòng đời request.
	 * Dùng Singleton để đảm bảo chỉ 1 kết nối được mở, tái sử dụng cho mọi Model.
	 *
	 * @return mysqli
	 */
	public static function getConnection()
	{
		if (self::$instance === null) {
			$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

			if (!$link) {
				// Không lộ thông tin CSDL ra ngoài, chỉ ghi log nội bộ
				error_log('Ket noi CSDL that bai: ' . mysqli_connect_error());
				die('He thong dang gap su co, vui long thu lai sau.');
			}

			mysqli_set_charset($link, DB_CHARSET);
			self::$instance = $link;
		}

		return self::$instance;
	}
}

$conn = Database::getConnection();