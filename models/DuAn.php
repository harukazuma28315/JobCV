<?php

require_once __DIR__ . "/../config/db.php";

/**
 * Model quản lý thông tin dự án.
 */
class DuAn
{
	private $conn;

	public function __construct()
	{
		global $conn;
		$this->conn = $conn;
	}

	/**
	 * Thêm dự án cho CV.
	 *
	 * @param array $duAnData Thông tin dự án.
	 * @return bool True nếu thêm thành công.
	 */
	public function create(array $duAnData)
	{
		$sql = "INSERT INTO DuAn
				(
					MaDuAn,
					MaCV,
					TenDuAn,
					ViTri,
					SoLuongThanhVien,
					CongNgheSuDung,
					MoTa
				)
				VALUES
				(
					?, ?, ?, ?, ?, ?, ?
				)";

		$statement = $this->conn->prepare($sql);

		$statement->bind_param(
			"ssssiss",
			$duAnData["maDuAn"],
			$duAnData["maCV"],
			$duAnData["tenDuAn"],
			$duAnData["viTri"],
			$duAnData["soLuongThanhVien"],
			$duAnData["congNgheSuDung"],
			$duAnData["moTa"]
		);

		return $statement->execute();
	}

	/**
	 * Lấy danh sách dự án theo mã CV.
	 *
	 * @param string $maCV
	 * @return mysqli_result
	 */
	public function getByCV($maCV)
	{
		$sql = "SELECT *
				FROM DuAn
				WHERE MaCV = ?";

		$statement = $this->conn->prepare($sql);

		$statement->bind_param("s", $maCV);

		$statement->execute();

		return $statement->get_result();
	}

	/**
	 * Cập nhật thông tin dự án.
	 *
	 * @param array $duAnData Thông tin dự án.
	 * @return bool True nếu cập nhật thành công.
	 */
	public function update(array $duAnData)
	{
		$sql = "UPDATE DuAn
				SET
					TenDuAn = ?,
					ViTri = ?,
					SoLuongThanhVien = ?,
					CongNgheSuDung = ?,
					MoTa = ?
				WHERE MaDuAn = ?";

		$statement = $this->conn->prepare($sql);

		$statement->bind_param(
			"ssisss",
			$duAnData["tenDuAn"],
			$duAnData["viTri"],
			$duAnData["soLuongThanhVien"],
			$duAnData["congNgheSuDung"],
			$duAnData["moTa"],
			$duAnData["maDuAn"]
		);

		$statement->execute();

		return $statement->execute();
	}

	/**
	 * Xóa dự án theo mã.
	 *
	 * @param string $maDuAn
	 * @return bool True nếu xóa thành công.
	 */
	public function delete($maDuAn)
	{
		$sql = "DELETE
				FROM DuAn
				WHERE MaDuAn = ?";

		$statement = $this->conn->prepare($sql);

		$statement->bind_param(
			"s",
			$maDuAn
		);

		$statement->execute();

		return $statement->affected_rows > 0;
	}
}