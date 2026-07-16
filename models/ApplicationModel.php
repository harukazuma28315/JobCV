<?php
/**
 * File: app/models/ApplicationModel.php
 * Đường dẫn: Module5_UngTuyenVaQuanLyHoSo/app/models/ApplicationModel.php
 * Chức năng: Toàn bộ CRUD và truy vấn nghiệp vụ cho bảng `hosotuyendung`
 *            (nộp hồ sơ, kiểm tra trùng, lịch sử ứng tuyển, danh sách cho NTD,
 *            cập nhật trạng thái, lấy thông tin liên hệ ứng viên để gửi mail).
 */

require_once ROOT_PATH . '/config/Database.php';

class ApplicationModel
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
	 * Kiểm tra CV này đã nộp vào tin tuyển dụng này hay chưa,
	 * dựa trên UNIQUE KEY (MaCV, MaTinTuyenDung) đã có sẵn trong CSDL.
	 *
	 * @param string $maCv
	 * @param string $maTinTuyenDung
	 * @return bool true nếu đã tồn tại hồ sơ (trùng)
	 */
	public function isDuplicate($maCv, $maTinTuyenDung)
	{
		$sql = 'SELECT MaHS FROM hosotuyendung WHERE MaCV = ? AND MaTinTuyenDung = ? LIMIT 1';
		$stmt = mysqli_prepare($this->link, $sql);
		mysqli_stmt_bind_param($stmt, 'ss', $maCv, $maTinTuyenDung);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_store_result($stmt);
		$tonTai = mysqli_stmt_num_rows($stmt) > 0;
		mysqli_stmt_close($stmt);

		return $tonTai;
	}

	/**
	 * Tạo mới hồ sơ ứng tuyển.
	 *
	 * @param array $data Gồm: maHS, maCV, maTinTuyenDung, coverLetter, coverLetterFile
	 * @return bool
	 */
	public function create($data)
	{
		$sql = "INSERT INTO hosotuyendung
				(MaHS, MaCV, MaTinTuyenDung, NgayNop, CoverLetter, CoverLetterFile, TrangThai)
				VALUES (?, ?, ?, NOW(), ?, ?, ?)";

		$stmt = mysqli_prepare($this->link, $sql);

		if (!$stmt) {
			die("Prepare Error: " . mysqli_error($this->link));
		}

		$trangThaiMoi = STATUS_MOI_NOP;

		mysqli_stmt_bind_param(
			$stmt,
			"ssssss",
			$data['maHS'],
			$data['maCV'],
			$data['maTinTuyenDung'],
			$data['coverLetter'],
			$data['coverLetterFile'],
			$trangThaiMoi
		);

		if (!mysqli_stmt_execute($stmt)) {
			die("Execute Error: " . mysqli_stmt_error($stmt));
		}

		mysqli_stmt_close($stmt);

		return true;
	}

	/**
	 * Lấy hồ sơ ứng tuyển theo mã.
	 *
	 * @param string $maHoSo
	 * @return array|null
	 */
	public function getById($maHoSo)
	{
		$sql = 'SELECT * FROM hosotuyendung WHERE MaHS = ? LIMIT 1';
		$stmt = mysqli_prepare($this->link, $sql);
		mysqli_stmt_bind_param($stmt, 's', $maHoSo);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$row = mysqli_fetch_assoc($result);
		mysqli_stmt_close($stmt);

		return $row ? $row : null;
	}

	/**
	 * Lấy chi tiết hồ sơ ứng tuyển kèm thông tin CV, tin tuyển dụng, ứng viên,
	 * đồng thời xác thực hồ sơ thuộc về đúng ứng viên đang xem (chống truy cập chéo).
	 *
	 * @param string $maHoSo
	 * @param string $maUngVien
	 * @return array|null
	 */
	public function getDetailForApplicant($maHoSo, $maUngVien)
	{
		$sql = 'SELECT hs.*, cv.TieuDe AS CvTieuDe, cv.ViTriMongMuon,
					tin.TieuDe AS TenTin, tin.DiaChiLamViec, tin.MucLuong, tin.NgayHetHan,
					ntd.TenCongTy
				FROM hosotuyendung hs
				INNER JOIN cv ON hs.MaCV = cv.MaCV
				INNER JOIN tintuyendung tin ON hs.MaTinTuyenDung = tin.MaTinTuyenDung
				INNER JOIN nhatuyendung ntd ON tin.MaNhaTuyenDung = ntd.MaNhaTuyenDung
				WHERE hs.MaHS = ? AND cv.MaUngVien = ?
				LIMIT 1';
		$stmt = mysqli_prepare($this->link, $sql);
		mysqli_stmt_bind_param($stmt, 'ss', $maHoSo, $maUngVien);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$row = mysqli_fetch_assoc($result);
		mysqli_stmt_close($stmt);

		return $row ? $row : null;
	}

	/**
	 * Lấy lịch sử ứng tuyển của một ứng viên (toàn bộ hồ sơ đã nộp).
	 *
	 * @param string $maUngVien
	 * @return array
	 */
	public function getHistoryByUngVien($maUngVien)
	{
		$sql = 'SELECT hs.MaHS, hs.NgayNop, hs.TrangThai,
					cv.TieuDe AS CvTieuDe,
					tin.TieuDe AS TenTin, tin.DiaChiLamViec,
					ntd.TenCongTy
				FROM hosotuyendung hs
				INNER JOIN cv ON hs.MaCV = cv.MaCV
				INNER JOIN tintuyendung tin ON hs.MaTinTuyenDung = tin.MaTinTuyenDung
				INNER JOIN nhatuyendung ntd ON tin.MaNhaTuyenDung = ntd.MaNhaTuyenDung
				WHERE cv.MaUngVien = ?
				ORDER BY hs.NgayNop DESC';
		$stmt = mysqli_prepare($this->link, $sql);
		mysqli_stmt_bind_param($stmt, 's', $maUngVien);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);

		$danhSachHoSo = array();
		while ($row = mysqli_fetch_assoc($result)) {
			$danhSachHoSo[] = $row;
		}
		mysqli_stmt_close($stmt);

		return $danhSachHoSo;
	}

	/**
	 * Lấy danh sách hồ sơ ứng tuyển cho Nhà tuyển dụng, chỉ lấy các hồ sơ
	 * thuộc về tin tuyển dụng của chính nhà tuyển dụng đó (ràng buộc qua JOIN).
	 * Hỗ trợ lọc theo tin cụ thể và/hoặc theo trạng thái.
	 *
	 * @param string $maNhaTuyenDung
	 * @param string|null $maTinTuyenDung Lọc theo 1 tin cụ thể, null = tất cả
	 * @param string|null $trangThai Lọc theo trạng thái, null = tất cả
	 * @return array
	 */
	public function getListForRecruiter($maNhaTuyenDung, $maTinTuyenDung = null, $trangThai = null)
	{
		$sql = 'SELECT hs.MaHS, hs.NgayNop, hs.TrangThai,
					cv.MaCV, cv.TieuDe AS CvTieuDe,
					uUser.HoTen AS TenUngVien, uUser.Email AS EmailUngVien,
					tin.MaTinTuyenDung, tin.TieuDe AS TenTin
				FROM hosotuyendung hs
				INNER JOIN cv ON hs.MaCV = cv.MaCV
				INNER JOIN ungvien uv ON cv.MaUngVien = uv.MaUngVien
				INNER JOIN user uUser ON uv.MaUngVien = uUser.MaUser
				INNER JOIN tintuyendung tin ON hs.MaTinTuyenDung = tin.MaTinTuyenDung
				WHERE tin.MaNhaTuyenDung = ?';

		$types = 's';
		$params = array($maNhaTuyenDung);

		if (!empty($maTinTuyenDung)) {
			$sql .= ' AND tin.MaTinTuyenDung = ?';
			$types .= 's';
			$params[] = $maTinTuyenDung;
		}

		if (!empty($trangThai)) {
			$sql .= ' AND hs.TrangThai = ?';
			$types .= 's';
			$params[] = $trangThai;
		}

		$sql .= ' ORDER BY hs.NgayNop DESC';

		$stmt = mysqli_prepare($this->link, $sql);

		// Bind tham số động
		$bindParams = array($stmt, $types);
		foreach ($params as $key => $value) {
			$bindParams[] = &$params[$key];
		}
		call_user_func_array('mysqli_stmt_bind_param', $bindParams);

		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);

		$danhSachHoSoUngTuyen = array();
		while ($row = mysqli_fetch_assoc($result)) {
			$danhSachHoSoUngTuyen[] = $row;
		}
		mysqli_stmt_close($stmt);

		return $danhSachHoSoUngTuyen;
	}

	/**
	 * Lấy chi tiết hồ sơ ứng tuyển cho Nhà tuyển dụng, đồng thời xác thực
	 * hồ sơ thuộc về tin tuyển dụng của chính nhà tuyển dụng này.
	 *
	 * @param string $maHoSo
	 * @param string $maNhaTuyenDung
	 * @return array|null
	 */
	public function getDetailForRecruiter($maHoSo, $maNhaTuyenDung)
	{
		$sql = 'SELECT hs.*, cv.MaCV, cv.TieuDe AS CvTieuDe, cv.KyNang, cv.MucTieu,
					cv.ViTriMongMuon, cv.Email AS EmailCv, cv.SDT AS SdtCv,
					uUser.HoTen AS TenUngVien, uUser.Email AS EmailUngVien, uUser.SDT AS SdtUngVien,
					tin.MaTinTuyenDung, tin.TieuDe AS TenTin
				FROM hosotuyendung hs
				INNER JOIN cv ON hs.MaCV = cv.MaCV
				INNER JOIN ungvien uv ON cv.MaUngVien = uv.MaUngVien
				INNER JOIN user uUser ON uv.MaUngVien = uUser.MaUser
				INNER JOIN tintuyendung tin ON hs.MaTinTuyenDung = tin.MaTinTuyenDung
				WHERE hs.MaHS = ? AND tin.MaNhaTuyenDung = ?
				LIMIT 1';
		$stmt = mysqli_prepare($this->link, $sql);
		mysqli_stmt_bind_param($stmt, 'ss', $maHoSo, $maNhaTuyenDung);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$row = mysqli_fetch_assoc($result);
		mysqli_stmt_close($stmt);

		return $row ? $row : null;
	}

	/**
	 * Cập nhật trạng thái hồ sơ ứng tuyển.
	 *
	 * @param string $maHoSo
	 * @param string $trangThaiMoi
	 * @return bool
	 */
	public function updateStatus($maHoSo, $trangThaiMoi)
	{
		$sql = 'UPDATE hosotuyendung SET TrangThai = ?, NgayCapNhatTrangThai = NOW() WHERE MaHS = ?';
		$stmt = mysqli_prepare($this->link, $sql);
		mysqli_stmt_bind_param($stmt, 'ss', $trangThaiMoi, $maHoSo);
		$capNhatThanhCong = mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);

		return $capNhatThanhCong;
	}
}