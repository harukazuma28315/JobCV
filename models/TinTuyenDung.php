<?php

require_once __DIR__ . "/../config/db.php";

/**
 * Model quản lý tin tuyển dụng.
 */
class TinTuyenDung
{
	private $conn;

	public function __construct()
	{
		global $conn;
		$this->conn = $conn;
	}

	/**
	 * Thêm tin tuyển dụng.
	 *
	 * @param array $tinTuyenDungData
	 * @return bool
	 */
	public function create(array $tinTuyenDungData)
	{
		$sql = "INSERT INTO TinTuyenDung
				(
					MaTinTuyenDung,
					MaNhaTuyenDung,
					TieuDe,
					MoTaCongViec,
					NgayHetHan,
					YeuCauCongViec,
					ViTriTuyenDung,
					CapBac,
					SoNamKinhNghiem,
					MucLuong,
					DiaChiLamViec,
					HinhThucLamViec,
					DoTuoiYeuCau,
					SoLuongTuyen,
					ThoiGianThuViec,
					TrangThai
				)
				VALUES
				(
					?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
				)";

		$statement = $this->conn->prepare($sql);

		$statement->bind_param(
			"sssssssssdsssiis",
			$tinTuyenDungData["maTinTuyenDung"],
			$tinTuyenDungData["maNhaTuyenDung"],
			$tinTuyenDungData["tieuDe"],
			$tinTuyenDungData["moTaCongViec"],
			$tinTuyenDungData["ngayHetHan"],
			$tinTuyenDungData["yeuCauCongViec"],
			$tinTuyenDungData["viTriTuyenDung"],
			$tinTuyenDungData["capBac"],
			$tinTuyenDungData["soNamKinhNghiem"],
			$tinTuyenDungData["mucLuong"],
			$tinTuyenDungData["diaChiLamViec"],
			$tinTuyenDungData["hinhThucLamViec"],
			$tinTuyenDungData["doTuoiYeuCau"],
			$tinTuyenDungData["soLuongTuyen"],
			$tinTuyenDungData["thoiGianThuViec"],
			$tinTuyenDungData["trangThai"]
		);

		return $statement->execute();
	}

	/**
	 * Lấy tất cả tin tuyển dụng.
	 *
	 * @return mysqli_result
	 */
	public function getAll()
	{
		$sql = "SELECT *
				FROM TinTuyenDung";

		return $this->conn->query($sql);
	}

	/**
	 * Lấy thông tin theo mã.
	 *
	 * @param string $maTinTuyenDung
	 * @return array|null
	 */
	public function getById($maTinTuyenDung)
	{
		$sql = "SELECT *
				FROM TinTuyenDung
				WHERE MaTinTuyenDung = ?";

		$statement = $this->conn->prepare($sql);

		$statement->bind_param(
			"s",
			$maTinTuyenDung
		);

		$statement->execute();

		$result = $statement->get_result();

		return $result->fetch_assoc();
	}

	/**
	 * Cập nhật tin tuyển dụng.
	 *
	 * @param array $tinTuyenDungData
	 * @return bool
	 */
	public function update(array $tinTuyenDungData)
	{
		$sql = "UPDATE TinTuyenDung
				SET
					TieuDe = ?,
					MoTaCongViec = ?,
					NgayHetHan = ?,
					YeuCauCongViec = ?,
					ViTriTuyenDung = ?,
					CapBac = ?,
					SoNamKinhNghiem = ?,
					MucLuong = ?,
					DiaChiLamViec = ?,
					HinhThucLamViec = ?,
					DoTuoiYeuCau = ?,
					SoLuongTuyen = ?,
					ThoiGianThuViec = ?
				WHERE MaTinTuyenDung = ?";

		$statement = $this->conn->prepare($sql);

		$statement->bind_param(
            "ssssssidsssiis",
            $tinTuyenDungData["tieuDe"],
            $tinTuyenDungData["moTaCongViec"],
            $tinTuyenDungData["ngayHetHan"],
            $tinTuyenDungData["yeuCauCongViec"],
            $tinTuyenDungData["viTriTuyenDung"],
			$tinTuyenDungData["capBac"],
			$tinTuyenDungData["soNamKinhNghiem"],
            $tinTuyenDungData["mucLuong"],
            $tinTuyenDungData["diaChiLamViec"],
            $tinTuyenDungData["hinhThucLamViec"],
            $tinTuyenDungData["doTuoiYeuCau"],
            $tinTuyenDungData["soLuongTuyen"],
            $tinTuyenDungData["thoiGianThuViec"],
            $tinTuyenDungData["maTinTuyenDung"]
        );

		$statement->execute();

		return $statement->affected_rows > 0;
	}

	/**
	 * Xóa tin tuyển dụng.
	 *
	 * @param string $maTinTuyenDung
	 * @return bool
	 */
	public function delete($maTinTuyenDung)
	{
		$sql = "DELETE
				FROM TinTuyenDung
				WHERE MaTinTuyenDung = ?";

		$statement = $this->conn->prepare($sql);

		$statement->bind_param(
			"s",
			$maTinTuyenDung
		);

		$statement->execute();

		return $statement->affected_rows > 0;
	}

	/**
	 * Gia hạn thời gian tuyển dụng.
	 *
	 * @param string $maTinTuyenDung
	 * @param string $ngayHetHan
	 * @return bool
	 */
	public function extendDeadline($maTinTuyenDung, $ngayHetHan)
	{
		$sql = "UPDATE TinTuyenDung
				SET
					NgayHetHan = ?
				WHERE MaTinTuyenDung = ?";

		$statement = $this->conn->prepare($sql);

		$statement->bind_param(
			"ss",
			$ngayHetHan,
			$maTinTuyenDung
		);

		$statement->execute();

		return $statement->affected_rows > 0;
	}

	/**
	 * Đóng tin tuyển dụng.
	 *
	 * @param string $maTinTuyenDung
	 * @return bool
	 */
	public function closeJob($maTinTuyenDung)
	{
		$sql = "UPDATE TinTuyenDung
				SET
					TrangThai = 'DaDong'
				WHERE MaTinTuyenDung = ?";

		$statement = $this->conn->prepare($sql);

		$statement->bind_param(
			"s",
			$maTinTuyenDung
		);

		$statement->execute();

		return $statement->affected_rows > 0;
	}
	/**
	 * Tìm kiếm tin tuyển dụng theo từ khóa.
	 *
	 * Tìm trong tiêu đề, mô tả công việc,
	 * yêu cầu công việc và vị trí tuyển dụng.
	 *
	 * @param string $keyword
	 * @return mysqli_result
	 */
	public function search($keyword)
	{
		$sql = "SELECT *
				FROM TinTuyenDung
				WHERE
					TieuDe LIKE ?
					OR MoTaCongViec LIKE ?
					OR YeuCauCongViec LIKE ?
					OR ViTriTuyenDung LIKE ?
					OR DiaChiLamViec LIKE ?
					OR CapBac LIKE ?
					OR HinhThucLamViec LIKE ?";

		$statement = $this->conn->prepare($sql);

		$keyword = "%" . $keyword . "%";

		$statement->bind_param(
			"sssssss",
			$keyword,
			$keyword,
			$keyword,
			$keyword,
			$keyword,
			$keyword,
			$keyword
		);

		$statement->execute();

		return $statement->get_result();
	}
	/**
	 * Lọc theo mức lương.
	 *
	 * @param float $min
	 * @param float $max
	 * @return mysqli_result
	 */
	public function filterSalary($min, $max)
	{
		$sql = "SELECT *
				FROM TinTuyenDung
				WHERE MucLuong BETWEEN ? AND ?";

		$statement = $this->conn->prepare($sql);

		$statement->bind_param(
			"dd",
			$min,
			$max
		);

		$statement->execute();

		return $statement->get_result();
	}
	/**
	 * Lọc theo địa điểm.
	 *
	 * @param string $diaChi
	 * @return mysqli_result
	 */
	public function filterLocation($diaChi)
	{
		$sql = "SELECT *
				FROM TinTuyenDung
				WHERE DiaChiLamViec LIKE ?";

		$statement = $this->conn->prepare($sql);

		$search = "%" . $diaChi . "%";

		$statement->bind_param(
			"s",
			$search
		);

		$statement->execute();

		return $statement->get_result();
	}
	/**
	 * Lọc theo hình thức làm việc.
	 *
	 * @param string $hinhThuc
	 * @return mysqli_result
	 */
	public function filterWorkType($hinhThuc)
	{
		$sql = "SELECT *
				FROM TinTuyenDung
				WHERE HinhThucLamViec = ?";

		$statement = $this->conn->prepare($sql);

		$statement->bind_param(
			"s",
			$hinhThuc
		);

		$statement->execute();

		return $statement->get_result();
	}
	/**
	 * Lọc theo cấp bậc.
	 *
	 * @param string $capBac
	 * @return mysqli_result
	 */
	public function filterLevel($capBac)
	{
		$sql = "SELECT *
				FROM TinTuyenDung
				WHERE CapBac = ?";

		$statement = $this->conn->prepare($sql);

		$statement->bind_param(
			"s",
			$capBac
		);

		$statement->execute();

		return $statement->get_result();
	}
	/**
	 * Lọc theo số năm kinh nghiệm.
	 *
	 * @param int $soNam
	 * @return mysqli_result
	 */
	public function filterExperience($soNam)
	{
		$sql = "SELECT *
				FROM TinTuyenDung
				WHERE SoNamKinhNghiem <= ?";

		$statement = $this->conn->prepare($sql);

		$statement->bind_param(
			"i",
			$soNam
		);

		$statement->execute();

		return $statement->get_result();
	}
	/**
	 * Lọc tin tuyển dụng theo thời gian đăng.
	 *
	 * @param string $fromDate
	 * @param string $toDate
	 * @return mysqli_result
	 */
	public function filterByPostedDate($fromDate, $toDate)
	{
		$sql = "SELECT *
				FROM TinTuyenDung
				WHERE DATE(NgayDang) BETWEEN ? AND ?";

		$statement = $this->conn->prepare($sql);

		$statement->bind_param(
			"ss",
			$fromDate,
			$toDate
		);

		$statement->execute();

		return $statement->get_result();
	}
	/**
	 * Lọc tin tuyển dụng theo nhiều tiêu chí.
	 *
	 * @param array $filters
	 * @return mysqli_result
	 */
	public function filter($filters)
	{
		$sql = "SELECT *
				FROM TinTuyenDung
				WHERE 1 = 1";

		$params = [];
		$types = "";

		if (!empty($filters["keyword"])) {
			$sql .= " AND (
						TieuDe LIKE ?
						OR MoTaCongViec LIKE ?
						OR YeuCauCongViec LIKE ?
						OR ViTriTuyenDung LIKE ?
					)";

			$keyword = "%" . $filters["keyword"] . "%";

			$params[] = $keyword;
			$params[] = $keyword;
			$params[] = $keyword;
			$params[] = $keyword;

			$types .= "ssss";
		}

		if (isset($filters["minSalary"]) && $filters["minSalary"] !== "") {
			$sql .= " AND MucLuong >= ?";
			$params[] = $filters["minSalary"];
			$types .= "d";
		}

		if (isset($filters["maxSalary"]) && $filters["maxSalary"] !== "") {
			$sql .= " AND MucLuong <= ?";
			$params[] = $filters["maxSalary"];
			$types .= "d";
		}

		if (!empty($filters["location"])) {
			$sql .= " AND DiaChiLamViec LIKE ?";
			$params[] = "%" . $filters["location"] . "%";
			$types .= "s";
		}

		if (!empty($filters["position"])) {
			$sql .= " AND ViTriTuyenDung = ?";
			$params[] = $filters["position"];
			$types .= "s";
		}
		
		if (!empty($filters["category"])) {
			$sql .= " AND EXISTS (
						SELECT 1
						FROM chitietdanhmuc ctdm
						WHERE ctdm.MaTinTuyenDung = TinTuyenDung.MaTinTuyenDung
						AND ctdm.MaDanhMuc = ?
					)";

			$params[] = $filters["category"];
			$types .= "s";
		}

		if (!empty($filters["capBac"])) {
			$sql .= " AND CapBac = ?";
			$params[] = $filters["capBac"];
			$types .= "s";
		}

		if (!empty($filters["hinhThucLamViec"])) {
			$sql .= " AND HinhThucLamViec = ?";
			$params[] = $filters["hinhThucLamViec"];
			$types .= "s";
		}

		if (
			isset($filters["soNamKinhNghiem"]) &&
			$filters["soNamKinhNghiem"] !== ""
		) {
			$sql .= " AND SoNamKinhNghiem <= ?";
			$params[] = $filters["soNamKinhNghiem"];
			$types .= "i";
		}

		$statement = $this->conn->prepare($sql);

		if (!empty($params)) {
			$bindParams = [];
			$bindParams[] = $types;

			foreach ($params as $key => $value) {
				$bindParams[] = &$params[$key];
			}

			call_user_func_array(
				[$statement, "bind_param"],
				$bindParams
			);
		}

		$statement->execute();

		return $statement->get_result();
	}
	public function getCategories()
		{
			$sql = "SELECT MaDanhMuc, TenDanhMuc
					FROM danhmuc
					WHERE LoaiDanhMuc = 'NganhNghe'
					ORDER BY TenDanhMuc ASC";

			$result = $this->conn->query($sql);

			$categories = [];

			while ($row = $result->fetch_assoc()) {
				$categories[] = $row;
			}

			return $categories;
		}
	/**
	 * Lấy danh sách vị trí tuyển dụng duy nhất.
	 *
	 * @return array
	 */
	public function getPositions()
	{
		$sql = "SELECT DISTINCT ViTriTuyenDung
				FROM TinTuyenDung
				WHERE ViTriTuyenDung IS NOT NULL
				AND ViTriTuyenDung != ''
				ORDER BY ViTriTuyenDung ASC";

		$result = $this->conn->query($sql);

		$positions = [];

		while ($row = $result->fetch_assoc()) {
			$positions[] = $row['ViTriTuyenDung'];
		}

		return $positions;
	}
}