<?php

require_once __DIR__ . "/../config/db.php";

/**
 * Model quản lý thông tin kinh nghiệm làm việc.
 */
class KinhNghiem
{
	private $conn;

	public function __construct()
	{
		global $conn;
		$this->conn = $conn;
	}

	/**
	 * Thêm kinh nghiệm làm việc cho CV.
	 *
	 * @param array $kinhNghiemData Thông tin kinh nghiệm.
	 * @return bool True nếu thêm thành công.
	 */
	public function create(array $kinhNghiemData)
	{
		$sql = "INSERT INTO KinhNghiemLamViec
				(
					MaCongViec,
					MaCV,
					TenCongTy,
					ViTri,
					ThoiGianLamViec,
					MoTa
				)
				VALUES
				(
					?, ?, ?, ?, ?, ?
				)";

		$statement = $this->conn->prepare($sql);

		$statement->bind_param(
			"ssssss",
			$kinhNghiemData["maCongViec"],
			$kinhNghiemData["maCV"],
			$kinhNghiemData["tenCongTy"],
			$kinhNghiemData["viTri"],
			$kinhNghiemData["thoiGianLamViec"],
			$kinhNghiemData["moTa"]
		);

		return $statement->execute();
	}

	/**
	 * Lấy danh sách kinh nghiệm theo mã CV.
	 *
	 * @param string $maCV
	 * @return mysqli_result
	 */
	public function getByCV($maCV)
	{
		$sql = "SELECT *
				FROM KinhNghiemLamViec
				WHERE MaCV = ?";

		$statement = $this->conn->prepare($sql);

		$statement->bind_param("s", $maCV);

		$statement->execute();

		return $statement->get_result();
	}

	/**
	 * Cập nhật thông tin kinh nghiệm.
	 *
	 * @param array $kinhNghiemData Thông tin kinh nghiệm.
	 * @return bool True nếu cập nhật thành công.
	 */
	public function update(array $kinhNghiemData)
	{
		$sql = "UPDATE KinhNghiemLamViec
				SET
					TenCongTy = ?,
					ViTri = ?,
					ThoiGianLamViec = ?,
					MoTa = ?
				WHERE MaCongViec = ?";

		$statement = $this->conn->prepare($sql);

		$statement->bind_param(
			"sssss",
			$kinhNghiemData["tenCongTy"],
			$kinhNghiemData["viTri"],
			$kinhNghiemData["thoiGianLamViec"],
			$kinhNghiemData["moTa"],
			$kinhNghiemData["maCongViec"]
		);

		$statement->execute();

		return $statement->execute();
	}

	/**
	 * Xóa kinh nghiệm theo mã công việc.
	 *
	 * @param string $maCongViec
	 * @return bool True nếu xóa thành công.
	 */
	public function delete($maCongViec)
	{
		$sql = "DELETE
				FROM KinhNghiemLamViec
				WHERE MaCongViec = ?";

		$statement = $this->conn->prepare($sql);

		$statement->bind_param(
			"s",
			$maCongViec
		);

		$statement->execute();

		return $statement->affected_rows > 0;
	}
}