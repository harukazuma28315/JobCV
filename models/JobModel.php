<?php
/**
 * File: app/models/JobModel.php
 * Đường dẫn: Module5_UngTuyenVaQuanLyHoSo/app/models/JobModel.php
 * Chức năng: Truy vấn dữ liệu bảng `tintuyendung` phục vụ nghiệp vụ ứng tuyển
 *            (kiểm tra tin tồn tại, tin còn hạn, lấy danh sách tin của nhà tuyển dụng).
 */

require_once ROOT_PATH . '/config/Database.php';

class JobModel
{
	/**
	 * @var mysqli
	 */
	private $link;

	public function __construct()
	{
		$this->link = Database::getConnection();
	}

	/**
	 * Lấy thông tin tin tuyển dụng theo mã.
	 *
	 * @param string $maTinTuyenDung
	 * @return array|null
	 */
	public function getById($maTinTuyenDung)
	{
		$sql = 'SELECT * FROM tintuyendung WHERE MaTinTuyenDung = ? LIMIT 1';
		$stmt = mysqli_prepare($this->link, $sql);
		mysqli_stmt_bind_param($stmt, 's', $maTinTuyenDung);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$row = mysqli_fetch_assoc($result);
		mysqli_stmt_close($stmt);

		return $row ? $row : null;
	}

	/**
	 * Kiểm tra tin tuyển dụng đã hết hạn hay chưa dựa trên NgayHetHan.
	 * Tin không có NgayHetHan (NULL) được xem là còn hiệu lực vô thời hạn.
	 *
	 * @param array $tinTuyenDung Bản ghi tin tuyển dụng (đã lấy từ getById)
	 * @return bool true nếu đã hết hạn
	 */
	public function isExpired($tinTuyenDung)
	{
		if (empty($tinTuyenDung['NgayHetHan'])) {
			return false;
		}

		$today = date('Y-m-d');

		return $tinTuyenDung['NgayHetHan'] < $today;
	}

	/**
	 * Lấy tin tuyển dụng theo mã, kèm kiểm tra tin thuộc đúng nhà tuyển dụng
	 * đang đăng nhập (chống truy cập chéo dữ liệu công ty khác).
	 *
	 * @param string $maTinTuyenDung
	 * @param string $maNhaTuyenDung
	 * @return array|null
	 */
	public function getOwnedJob($maTinTuyenDung, $maNhaTuyenDung)
	{
		$sql = 'SELECT * FROM tintuyendung WHERE MaTinTuyenDung = ? AND MaNhaTuyenDung = ? LIMIT 1';
		$stmt = mysqli_prepare($this->link, $sql);
		mysqli_stmt_bind_param($stmt, 'ss', $maTinTuyenDung, $maNhaTuyenDung);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$row = mysqli_fetch_assoc($result);
		mysqli_stmt_close($stmt);

		return $row ? $row : null;
	}

	/**
	 * Lấy danh sách tin tuyển dụng của một Nhà tuyển dụng,
	 * dùng để hiển thị bộ lọc theo tin trong trang danh sách hồ sơ.
	 *
	 * @param string $maNhaTuyenDung
	 * @return array
	 */
	public function getJobsByRecruiter($maNhaTuyenDung)
	{
		$sql = 'SELECT MaTinTuyenDung, TieuDe FROM tintuyendung WHERE MaNhaTuyenDung = ? ORDER BY NgayDang DESC';
		$stmt = mysqli_prepare($this->link, $sql);
		mysqli_stmt_bind_param($stmt, 's', $maNhaTuyenDung);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);

		$danhSachTinTuyenDung = array();
		while ($row = mysqli_fetch_assoc($result)) {
			$danhSachTinTuyenDung[] = $row;
		}
		mysqli_stmt_close($stmt);

		return $danhSachTinTuyenDung;
	}
}