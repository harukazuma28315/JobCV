<?php

require_once __DIR__ . "/../config/db.php";

class TinTuyenDung
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    //==================================================
    // TẠO TIN TUYỂN DỤNG
    //==================================================

    public function create(array $data)
    {
        $maTinTuyenDung = uniqid("TD");

        $sql = "
        INSERT INTO TinTuyenDung
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
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?
        )
        ";

        $stmt = $this->conn->prepare($sql);

        $viTriTuyenDung = $data['tieuDe'];
        $capBac = "Intern";
        $soNamKinhNghiem = 0;
        $doTuoiYeuCau = "";
        $soLuongTuyen = 1;
        $thoiGianThuViec = 0;
        $trangThai = "DangMo";

        $stmt->bind_param(
            "ssssssssidssiiss",

            $maTinTuyenDung,
            $data['maNhaTuyenDung'],
            $data['tieuDe'],
            $data['moTaCongViec'],
            $data['ngayHetHan'],
            $data['yeuCauCongViec'],
            $viTriTuyenDung,
            $capBac,
            $soNamKinhNghiem,
            $data['mucLuong'],
            $data['diaChiLamViec'],
            $data['hinhThucLamViec'],
            $doTuoiYeuCau,
            $soLuongTuyen,
            $thoiGianThuViec,
            $trangThai
        );

        return $stmt->execute();
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

	public function getLocations()
	{
		$sql = "SELECT DISTINCT DiaChiLamViec
				FROM tintuyendung
				WHERE DiaChiLamViec IS NOT NULL
				AND DiaChiLamViec != ''
				ORDER BY DiaChiLamViec ASC";

		$result = $this->conn->query($sql);

		$locations = [];

		while ($row = $result->fetch_assoc()) {
			$locations[] = $row['DiaChiLamViec'];
		}

		return $locations;
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
	public function getFeaturedJobs($limit = 8)
	{
		$limit = (int) $limit;

		$sql = "
			SELECT 
				t.MaTinTuyenDung,
				t.TieuDe,
				t.MucLuong,
				t.DiaChiLamViec,
				t.MoTaCongViec,
				t.NgayDang,
				t.SoNamKinhNghiem,
				t.ViTriTuyenDung,
				n.MaNhaTuyenDung,
				n.TenCongTy,
				n.Logo
			FROM tintuyendung t
			INNER JOIN nhatuyendung n
				ON t.MaNhaTuyenDung = n.MaNhaTuyenDung
			WHERE t.TrangThai = 'DangMo'
			ORDER BY t.NgayDang DESC
			LIMIT $limit
		";

		return $this->conn->query($sql);
	}
	public function getTopCompanies($limit = 4)
	{
		$limit = (int) $limit;

		$sql = "
			SELECT 
				n.MaNhaTuyenDung,
				n.TenCongTy,
				n.LinhVuc,
				n.MoTa,
				n.Logo,
				COUNT(t.MaTinTuyenDung) AS SoLuongTin
			FROM nhatuyendung n
			LEFT JOIN tintuyendung t
				ON n.MaNhaTuyenDung = t.MaNhaTuyenDung
				AND t.TrangThai = 'DangMo'
			GROUP BY 
				n.MaNhaTuyenDung,
				n.TenCongTy,
				n.LinhVuc,
				n.MoTa,
				n.Logo
			ORDER BY SoLuongTin DESC
			LIMIT $limit
		";

		return $this->conn->query($sql);
	}
	public function getPopularCategories($limit = 8)
	{
		$limit = (int) $limit;

		$sql = "
			SELECT 
				dm.MaDanhMuc,
				dm.TenDanhMuc,
				COUNT(ctdm.MaTinTuyenDung) AS SoLuongTin
			FROM danhmuc dm
			LEFT JOIN chitietdanhmuc ctdm
				ON dm.MaDanhMuc = ctdm.MaDanhMuc
			LEFT JOIN tintuyendung t
				ON ctdm.MaTinTuyenDung = t.MaTinTuyenDung
				AND t.TrangThai = 'DangMo'
			WHERE dm.LoaiDanhMuc = 'NganhNghe'
			GROUP BY 
				dm.MaDanhMuc,
				dm.TenDanhMuc
			ORDER BY SoLuongTin DESC
			LIMIT $limit
		";

		return $this->conn->query($sql);
	}
	public function getByNhaTuyenDung($maNhaTuyenDung)
	{
		$sql = "
			SELECT *
			FROM TinTuyenDung
			WHERE MaNhaTuyenDung = ?
			ORDER BY NgayDang DESC
		";

		$statement = $this->conn->prepare($sql);

		$statement->bind_param(
			"s",
			$maNhaTuyenDung
		);

		$statement->execute();

		return $statement->get_result();
	}
}