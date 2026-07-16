<?php

require_once __DIR__ . "/../config/db.php";

class CV
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    /**
     * Tạo mới một CV.
     *
     * Sử dụng Prepared Statement để tránh SQL Injection.
     *
     * @param array $cvData Thông tin CV cần thêm.
     * @return bool True nếu thêm thành công, ngược lại False.
     */
    public function create(array $cvData)
    {
        $sql = "INSERT INTO CV (
                    MaCV,
                    MaUngVien,
                    TieuDe,
                    KyNang,
                    SoThich,
                    MucTieu,
                    TrangThai,
                    ViTriMongMuon,
                    Email,
                    SDT
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $statement = $this->conn->prepare($sql);

        $statement->bind_param(
            "sssssissss",
            $cvData["maCV"],
            $cvData["maUngVien"],
            $cvData["tieuDe"],
            $cvData["kyNang"],
            $cvData["soThich"],
            $cvData["mucTieu"],
            $cvData["trangThai"],
            $cvData["viTriMongMuon"],
            $cvData["email"],
            $cvData["sdt"]
        );

        return $statement->execute();
    }

    /**
     * Lấy danh sách tất cả CV.
     *
     * @return mysqli_result
     */
    public function getAll()
    {
        $sql = "SELECT * FROM CV";

        return $this->conn->query($sql);
    }

    /**
     * Lấy thông tin CV theo mã.
     *
     * @param string $maCV
     * @return array|null
     */
    public function getById($maCV)
    {
        $sql = "SELECT * FROM CV WHERE MaCV = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $maCV);
        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    /**
     * Cập nhật thông tin CV.
     *
     * @param array $cvData Thông tin CV cần cập nhật.
     * @return bool True nếu cập nhật thành công.
     */
    public function update(array $cvData)
    {
        $sql = "UPDATE CV
                SET
                    TieuDe = ?,
                    KyNang = ?,
                    SoThich = ?,
                    MucTieu = ?,
                    TrangThai = ?,
                    ViTriMongMuon = ?,
                    Email = ?,
                    SDT = ?
                WHERE MaCV = ?";

        $statement = $this->conn->prepare($sql);

        $statement->bind_param(
            "ssssissss",
            $cvData["tieuDe"],
            $cvData["kyNang"],
            $cvData["soThich"],
            $cvData["mucTieu"],
            $cvData["trangThai"],
            $cvData["viTriMongMuon"],
            $cvData["email"],
            $cvData["sdt"],
            $cvData["maCV"]
        );

        return $statement->execute();
    }

    /**
     * Xóa một CV theo mã.
     *
     * Sử dụng Prepared Statement để đảm bảo an toàn khi xóa dữ liệu.
     *
     * @param string $maCV Mã CV cần xóa.
     * @return bool True nếu xóa thành công, ngược lại False.
     */
    public function delete(string $maCV)
    {
        $sql = "DELETE FROM CV WHERE MaCV = ?";

        $statement = $this->conn->prepare($sql);

        $statement->bind_param("s", $maCV);

        return $statement->execute();
    }

    /**
     * Cập nhật file CV.
     *
     * @param array $fileData
     * @return bool
     */
    public function updateFile(array $fileData)
    {
        $sql = "UPDATE CV
                SET
                    TenFileCV = ?,
                    DuongDanFileCV = ?,
                    LoaiFile = ?
                WHERE MaCV = ?";

        $statement = $this->conn->prepare($sql);

        $statement->bind_param(
            "ssss",
            $fileData["tenFileCV"],
            $fileData["duongDanFileCV"],
            $fileData["loaiFile"],
            $fileData["maCV"]
        );

        $statement->execute();

        return $statement->execute();
    }

}