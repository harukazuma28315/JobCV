<?php
/**
 * File: app/models/JobModel.php
 * Chức năng: Truy vấn dữ liệu bảng `tintuyendung` phục vụ cả Nhà tuyển dụng và Admin
 */

require_once ROOT_PATH . '/config/Database.php';

class JobModel
{
    private $link;

    public function __construct()
    {
        $this->link = Database::getConnection();
    }

    // ====================== PHẦN CODE NHÀ TUYỂN DỤNG - KHÔNG THAY ĐỔI ======================

    /**
     * Lấy thông tin tin tuyển dụng theo mã.
     */
    public function getById($maTinTuyenDung)
    {
        $sql = 'SELECT * FROM tintuyendung WHERE MaTinTuyenDung = ? LIMIT 1';
        $stmt = mysqli_prepare($this->link, $sql);
        mysqli_stmt_bind_param($stmt, 's', $maTinTuyenDung);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        return $row ? $row : null;
    }

    /**
     * Kiểm tra tin tuyển dụng đã hết hạn hay chưa.
     */
    public function isExpired($tinTuyenDung)
    {
        if (empty($tinTuyenDung['NgayHetHan'])) {
            return false;
        }

        $today = date('Y-m-d');
        return $tinTuyenDung['NgayHetHan'] < $today;
    }

    /**
     * Lấy tin tuyển dụng theo mã, kiểm tra thuộc nhà tuyển dụng.
     */
    public function getOwnedJob($maTinTuyenDung, $maNhaTuyenDung)
    {
        $sql = 'SELECT * FROM tintuyendung WHERE MaTinTuyenDung = ? AND MaNhaTuyenDung = ? LIMIT 1';
        $stmt = mysqli_prepare($this->link, $sql);
        mysqli_stmt_bind_param($stmt, 'ss', $maTinTuyenDung, $maNhaTuyenDung);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        return $row ? $row : null;
    }

    /**
     * Lấy danh sách tin tuyển dụng của một Nhà tuyển dụng.
     */
    public function getJobsByRecruiter($maNhaTuyenDung)
    {
        $sql = 'SELECT MaTinTuyenDung, TieuDe FROM tintuyendung WHERE MaNhaTuyenDung = ? ORDER BY NgayDang DESC';
        $stmt = mysqli_prepare($this->link, $sql);
        mysqli_stmt_bind_param($stmt, 's', $maNhaTuyenDung);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $danhSachTinTuyenDung = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $danhSachTinTuyenDung[] = $row;
        }
        mysqli_stmt_close($stmt);

        return $danhSachTinTuyenDung;
    }

    // ====================== PHẦN CHO ADMIN  ======================

    /**
     * Lấy danh sách tin tuyển dụng cho Admin (có lọc)
     */
    public function getJobListForAdmin($keyword = '', $status = null)
    {
        $sql = "SELECT t.MaTinTuyenDung, t.TieuDe, t.NgayDang, t.NgayHetHan, 
                       t.TrangThaiDuyet, ntd.TenCongTy
                FROM tintuyendung t
                JOIN nhatuyendung ntd ON t.MaNhaTuyenDung = ntd.MaNhaTuyenDung
                WHERE 1=1";

        $params = [];
        $types = '';

        if (!empty($keyword)) {
            $sql .= " AND (t.TieuDe LIKE ? OR ntd.TenCongTy LIKE ?)";
            $like = "%$keyword%";
            $params = [$like, $like];
            $types .= 'ss';
        }

        if (in_array($status, ['pending', 'ChoDuyet'])) {
            $sql .= " AND t.TrangThaiDuyet = 'ChoDuyet'";
        } elseif (in_array($status, ['approved', 'DaDuyet'])) {
            $sql .= " AND t.TrangThaiDuyet = 'DaDuyet'";
        } elseif (in_array($status, ['rejected', 'TuChoi'])) {
            $sql .= " AND t.TrangThaiDuyet = 'TuChoi'";
        }

        $sql .= " ORDER BY t.NgayDang DESC";

        $stmt = mysqli_prepare($this->link, $sql);
        if (!empty($params)) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $list = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $list[] = $row;
        }

        mysqli_stmt_close($stmt);
        return $list;
    }

    /**
     * Duyệt tin tuyển dụng
     */
    public function approveJob($maTin)
    {
        $sql = "UPDATE tintuyendung SET TrangThaiDuyet = 'DaDuyet' WHERE MaTinTuyenDung = ?";
        $stmt = mysqli_prepare($this->link, $sql);
        mysqli_stmt_bind_param($stmt, 's', $maTin);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $success;
    }

    /**
     * Từ chối tin tuyển dụng
     */
    public function rejectJob($maTin)
    {
        $sql = "UPDATE tintuyendung SET TrangThaiDuyet = 'TuChoi' WHERE MaTinTuyenDung = ?";
        $stmt = mysqli_prepare($this->link, $sql);
        mysqli_stmt_bind_param($stmt, 's', $maTin);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $success;
    }

    /**
     * Gỡ tin tuyển dụng
     */
    public function removeJob($maTin)
    {
        $sql = "UPDATE tintuyendung SET TrangThaiDuyet = 'DaGoi' WHERE MaTinTuyenDung = ?";
        $stmt = mysqli_prepare($this->link, $sql);
        mysqli_stmt_bind_param($stmt, 's', $maTin);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $success;
    }
}