<?php
/**
 * File: app/models/CvModel.php
 * Đường dẫn: Module5_UngTuyenVaQuanLyHoSo/app/models/CvModel.php
 * Chức năng: Truy vấn dữ liệu bảng `cv` phục vụ nghiệp vụ ứng tuyển
 *            (lấy danh sách CV của ứng viên, kiểm tra CV có tồn tại/thuộc sở hữu).
 *            Không xử lý HTML, không chứa business logic điều hướng.
 */

require_once ROOT_PATH . '/config/Database.php';

class CvModel
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
	 * Lấy toàn bộ CV đang hoạt động (TrangThai = 1) của một ứng viên,
	 * dùng để hiển thị danh sách lựa chọn CV khi nộp đơn.
	 *
	 * @param string $maUngVien
	 * @return array
	 */
	public function getActiveCvsByUngVien($maUngVien)
	{
		$sql = 'SELECT MaCV, TieuDe, ViTriMongMuon FROM cv WHERE MaUngVien = ? AND TrangThai = 1 ORDER BY TieuDe ASC';
		$stmt = mysqli_prepare($this->link, $sql);
		mysqli_stmt_bind_param($stmt, 's', $maUngVien);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);

		$danhSachCv = array();
		while ($row = mysqli_fetch_assoc($result)) {
			$danhSachCv[] = $row;
		}
		mysqli_stmt_close($stmt);

		return $danhSachCv;
	}

	/**
	 * Lấy thông tin 1 CV theo MaCV, đồng thời xác thực CV này thuộc về
	 * đúng ứng viên đang thao tác (chống truy cập chéo dữ liệu người khác).
	 *
	 * @param string $maCv
	 * @param string $maUngVien
	 * @return array|null
	 */
	public function getOwnedCv($maCv, $maUngVien)
	{
		$sql = 'SELECT * FROM cv WHERE MaCV = ? AND MaUngVien = ? LIMIT 1';
		$stmt = mysqli_prepare($this->link, $sql);
		mysqli_stmt_bind_param($stmt, 'ss', $maCv, $maUngVien);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$row = mysqli_fetch_assoc($result);
		mysqli_stmt_close($stmt);

		return $row ? $row : null;
	}

	/**
	 * Lấy thông tin CV theo MaCV (không kiểm tra chủ sở hữu),
	 * dùng cho phía Nhà tuyển dụng xem chi tiết hồ sơ ứng viên.
	 *
	 * @param string $maCv
	 * @return array|null
	 */
	public function getById($maCv)
	{
		$sql = 'SELECT * FROM cv WHERE MaCV = ? LIMIT 1';
		$stmt = mysqli_prepare($this->link, $sql);
		mysqli_stmt_bind_param($stmt, 's', $maCv);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$row = mysqli_fetch_assoc($result);
		mysqli_stmt_close($stmt);

		return $row ? $row : null;
	}
}