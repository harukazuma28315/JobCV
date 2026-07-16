<?php

require_once __DIR__ . "/../config/db.php";

/**
 * Model quản lý thông tin nhà tuyển dụng.
 */
class NhaTuyenDung
{
	private $conn;

	public function __construct()
	{
		global $conn;
		$this->conn = $conn;
	}

	/**
	 * Lấy thông tin doanh nghiệp theo mã.
	 *
	 * @param string $maNhaTuyenDung
	 * @return array|null
	 */
	public function getById($maNhaTuyenDung)
	{
		$sql = "SELECT *
				FROM NhaTuyenDung
				WHERE MaNhaTuyenDung = ?";

		$statement = $this->conn->prepare($sql);

		$statement->bind_param(
			"s",
			$maNhaTuyenDung
		);

		$statement->execute();

		$result = $statement->get_result();

		return $result->fetch_assoc();
	}

	/**
	 * Cập nhật thông tin doanh nghiệp.
	 *
	 * @param array $nhaTuyenDungData
	 * @return bool
	 */
	public function update(array $nhaTuyenDungData)
	{
		$sql = "UPDATE NhaTuyenDung
				SET
					TenCongTy = ?,
					Website = ?,
					LinhVuc = ?,
					MaSoThue = ?,
					MoTa = ?,
					QuyMo = ?,
					DiaChi = ?
				WHERE MaNhaTuyenDung = ?";

		$statement = $this->conn->prepare($sql);

		$statement->bind_param(
			"ssssssss",
			$nhaTuyenDungData["tenCongTy"],
			$nhaTuyenDungData["website"],
			$nhaTuyenDungData["linhVuc"],
			$nhaTuyenDungData["maSoThue"],
			$nhaTuyenDungData["moTa"],
			$nhaTuyenDungData["quyMo"],
			$nhaTuyenDungData["diaChi"],
			$nhaTuyenDungData["maNhaTuyenDung"]
		);

		return $statement->execute();
	}

	/**
	 * Cập nhật logo doanh nghiệp.
	 *
	 * @param string $maNhaTuyenDung
	 * @param string $logo
	 * @return bool
	 */
	public function updateLogo($maNhaTuyenDung, $logo)
	{
		$sql = "UPDATE NhaTuyenDung
				SET
					Logo = ?
				WHERE MaNhaTuyenDung = ?";

		$statement = $this->conn->prepare($sql);

		$statement->bind_param(
			"ss",
			$logo,
			$maNhaTuyenDung
		);

		return $statement->execute();
	}

	/**
	 * Cập nhật ảnh bìa doanh nghiệp.
	 *
	 * @param string $maNhaTuyenDung
	 * @param string $cover
	 * @return bool
	 */
	public function updateCover($maNhaTuyenDung, $cover)
	{
		$sql = "UPDATE NhaTuyenDung
				SET
					AnhBia = ?
				WHERE MaNhaTuyenDung = ?";

		$statement = $this->conn->prepare($sql);

		$statement->bind_param(
			"ss",
			$cover,
			$maNhaTuyenDung
		);

		return $statement->execute();
	}
}