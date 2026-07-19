<?php

require_once __DIR__ . "/../config/db.php";

/**
 * Model quản lý thông tin chứng chỉ.
 */
class ChungChi
{
	private $conn;

	public function __construct()
	{
		global $conn;
		$this->conn = $conn;
	}

	/**
	 * Thêm chứng chỉ cho CV.
	 *
	 * @param array $chungChiData Thông tin chứng chỉ.
	 * @return bool True nếu thêm thành công.
	 */
	public function create(array $chungChiData)
	{
		$sql = "INSERT INTO ChungChi
				(
					MaChungChi,
					MaCV,
					TenChungChi,
					ToChucCap,
					NgayCap,
					NgayHetHan,
					MaSoChungChi,
					DuongLinkChungChi
				)
				VALUES
				(
					?, ?, ?, ?, ?, ?, ?, ?
				)";

		$statement = $this->conn->prepare($sql);

		$statement->bind_param(
			"ssssssss",
			$chungChiData["maChungChi"],
			$chungChiData["maCV"],
			$chungChiData["tenChungChi"],
			$chungChiData["toChucCap"],
			$chungChiData["ngayCap"],
			$chungChiData["ngayHetHan"],
			$chungChiData["maSoChungChi"],
			$chungChiData["duongLinkChungChi"]
		);

		return $statement->execute();
	}

	/**
	 * Lấy danh sách chứng chỉ theo mã CV.
	 *
	 * @param string $maCV
	 * @return mysqli_result
	 */
	public function getByCV($maCV)
	{
		$sql = "SELECT *
				FROM ChungChi
				WHERE MaCV = ?";

		$statement = $this->conn->prepare($sql);

		$statement->bind_param(
			"s",
			$maCV
		);

		$statement->execute();

		return $statement->get_result();
	}

	/**
	 * Cập nhật thông tin chứng chỉ.
	 *
	 * @param array $chungChiData Thông tin chứng chỉ.
	 * @return bool True nếu cập nhật thành công.
	 */
	public function update(array $chungChiData)
	{
		$sql = "UPDATE ChungChi
				SET
					TenChungChi = ?,
					ToChucCap = ?,
					NgayCap = ?,
					NgayHetHan = ?,
					MaSoChungChi = ?,
					DuongLinkChungChi = ?
				WHERE MaChungChi = ?";

		$statement = $this->conn->prepare($sql);

		$statement->bind_param(
			"sssssss",
			$chungChiData["tenChungChi"],
			$chungChiData["toChucCap"],
			$chungChiData["ngayCap"],
			$chungChiData["ngayHetHan"],
			$chungChiData["maSoChungChi"],
			$chungChiData["duongLinkChungChi"],
			$chungChiData["maChungChi"]
		);

		$statement->execute();

		return $statement->affected_rows > 0;
	}

	/**
	 * Xóa chứng chỉ theo mã.
	 *
	 * @param string $maChungChi
	 * @return bool True nếu xóa thành công.
	 */
	public function delete($maChungChi)
	{
		$sql = "DELETE
				FROM ChungChi
				WHERE MaChungChi = ?";

		$statement = $this->conn->prepare($sql);

		$statement->bind_param(
			"s",
			$maChungChi
		);

		$statement->execute();

		return $statement->affected_rows > 0;
	}
}