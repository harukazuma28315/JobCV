<?php

require_once __DIR__ . "/../config/db.php";

/**
 * Model quản lý thông tin học vấn.
 */
class HocVan
{
	private $conn;

	public function __construct()
	{
		global $conn;
		$this->conn = $conn;
	}
    /**
     * Thêm học vấn cho CV.
     *
     * @param array $hocVanData Thông tin học vấn.
     * @return bool True nếu thêm thành công.
     */
    public function create(array $hocVanData)
    {
        $sql = "INSERT INTO HocVan
                (
                    MaHocVan,
                    MaCV,
                    TenTruong,
                    ChuyenNganh,
                    GPA,
                    HocLuc,
                    NamBatDau,
                    NamKetThuc
                )
                VALUES
                (
                    ?, ?, ?, ?, ?, ?, ?, ?
                )";

        $statement = $this->conn->prepare($sql);

        $statement->bind_param(
            "ssssssss",
            $hocVanData["maHocVan"],
            $hocVanData["maCV"],
            $hocVanData["tenTruong"],
            $hocVanData["chuyenNganh"],
            $hocVanData["gpa"],
            $hocVanData["hocLuc"],
            $hocVanData["namBatDau"],
            $hocVanData["namKetThuc"]
        );

        return $statement->execute();
    }
    /**
     * Lấy danh sách học vấn theo mã CV.
     *
     * @param string $maCV
     * @return mysqli_result
     */
    public function getByCV($maCV)
    {
        $sql = "SELECT *
                FROM HocVan
                WHERE MaCV = ?";

        $statement = $this->conn->prepare($sql);

        $statement->bind_param("s", $maCV);

        $statement->execute();

        return $statement->get_result();
    }
    /**
     * Cập nhật thông tin học vấn.
     *
     * @param array $hocVanData
     * @return bool
     */
    public function update(array $hocVanData)
    {
        $sql = "UPDATE HocVan
                SET
                    TenTruong = ?,
                    ChuyenNganh = ?,
                    GPA = ?,
                    HocLuc = ?,
                    NamBatDau = ?,
                    NamKetThuc = ?
                WHERE MaHocVan = ?";

        $statement = $this->conn->prepare($sql);

        $statement->bind_param(
            "sssssss",
            $hocVanData["tenTruong"],
            $hocVanData["chuyenNganh"],
            $hocVanData["gpa"],
            $hocVanData["hocLuc"],
            $hocVanData["namBatDau"],
            $hocVanData["namKetThuc"],
            $hocVanData["maHocVan"]
        );

        $statement->execute();

        return $statement->execute();
    }
    /**
     * Xóa học vấn theo mã.
     *
     * @param string $maHocVan
     * @return bool
     */
    public function delete($maHocVan)
    {
        $sql = "DELETE FROM HocVan
                WHERE MaHocVan = ?";

        $statement = $this->conn->prepare($sql);

        $statement->bind_param("s", $maHocVan);

        $statement->execute();

        return $statement->affected_rows > 0;
    }
}