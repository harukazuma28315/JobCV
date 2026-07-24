<?php
/**
 * File: app/models/CategoryModel.php
 * Sử dụng bảng danhmuc có sẵn trong CSDL
 */

require_once ROOT_PATH . '/config/db.php';

class CategoryModel
{
    private $link;

    public function __construct()
    {
        $this->link = Database::getConnection();
    }

    // Lấy danh sách Ngành nghề
    public function getNganhNgheList()
    {
        $sql = "SELECT * FROM danhmuc WHERE LoaiDanhMuc = 'nganhnghe' ORDER BY NgayTao DESC";
        $result = mysqli_query($this->link, $sql);
        $list = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $list[] = $row;
        }
        return $list;
    }

    // Lấy danh sách Địa điểm
    public function getDiaDiemList()
    {
        $sql = "SELECT * FROM danhmuc WHERE LoaiDanhMuc = 'diadiem' ORDER BY NgayTao DESC";
        $result = mysqli_query($this->link, $sql);
        $list = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $list[] = $row;
        }
        return $list;
    }

    // Thêm ngành nghề
    public function addNganhNghe($tenNganh, $maNganh)
    {
        $sql = "INSERT INTO danhmuc (MaDanhMuc, TenDanhMuc, LoaiDanhMuc, NgayTao) 
                VALUES (?, ?, 'nganhnghe', NOW())";
        $stmt = mysqli_prepare($this->link, $sql);
        mysqli_stmt_bind_param($stmt, 'ss', $maNganh, $tenNganh);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $success;
    }

    // Thêm địa điểm
    public function addDiaDiem($tenDiaDiem, $maVung)
    {
        $sql = "INSERT INTO danhmuc (MaDanhMuc, TenDanhMuc, LoaiDanhMuc, NgayTao) 
                VALUES (?, ?, 'diadiem', NOW())";
        $stmt = mysqli_prepare($this->link, $sql);
        mysqli_stmt_bind_param($stmt, 'ss', $maVung, $tenDiaDiem);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $success;
    }

    // Xóa danh mục
    public function deleteDanhMuc($maDanhMuc)
    {
        $sql = "DELETE FROM danhmuc WHERE MaDanhMuc = ?";
        $stmt = mysqli_prepare($this->link, $sql);
        mysqli_stmt_bind_param($stmt, 's', $maDanhMuc);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $success;
    }

    public function addDanhMuc($ten, $ma, $loai)
    {
        $sql = "INSERT INTO danhmuc (MaDanhMuc, TenDanhMuc, LoaiDanhMuc, NgayTao) 
                VALUES (?, ?, ?, NOW())";
        $stmt = mysqli_prepare($this->link, $sql);
        mysqli_stmt_bind_param($stmt, 'sss', $ma, $ten, $loai);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $success;
    }

        public function updateDanhMuc($oldMa, $newMa, $ten, $loai)
    {
        $sql = "UPDATE danhmuc 
                SET MaDanhMuc = ?, TenDanhMuc = ?, LoaiDanhMuc = ? 
                WHERE MaDanhMuc = ?";
        $stmt = mysqli_prepare($this->link, $sql);
        mysqli_stmt_bind_param($stmt, 'ssss', $newMa, $ten, $loai, $oldMa);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $success;
    }
}

