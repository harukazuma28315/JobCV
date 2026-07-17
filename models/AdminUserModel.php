<?php
/**
 * File: app/models/AdminUserModel.php
 * Chức năng: Xử lý dữ liệu người dùng cho phần Admin 
 *            (Lấy danh sách, khóa/mở khóa, duyệt tài khoản)
 */

require_once ROOT_PATH . '/config/Database.php';

class AdminUserModel
{
    private $link;

    public function __construct()
    {
        $this->link = Database::getConnection();
    }

    /**
     * Lấy danh sách người dùng cho Admin
     */
    public function getUserListForAdmin($keyword = '', $role = null, $status = null)
    {
        $sql = "SELECT u.MaUser, u.HoTen, u.Email, u.Role, u.TaiKhoan, u.IsLocked, u.TrangThai,
                       COALESCE(ntd.TenCongTy, 'Cá nhân') as TenCongTy
                FROM user u
                LEFT JOIN nhatuyendung ntd ON u.MaUser = ntd.MaNhaTuyenDung
                WHERE 1=1";

        $params = [];
        $types = '';

        if (!empty($keyword)) {
            $sql .= " AND (u.HoTen LIKE ? OR u.Email LIKE ?)";
            $like = "%$keyword%";
            $params = [$like, $like];
            $types .= 'ss';
        }

        if ($role !== null) {
            $sql .= " AND u.Role = ?";
            $params[] = $role;
            $types .= 'i';
        }

        if ($status === 'active' || $status === 'HoatDong') {
            $sql .= " AND u.TrangThai = 'HoatDong'";
        } elseif ($status === 'blocked' || $status === 'BiKhoa') {
            $sql .= " AND u.TrangThai = 'BiKhoa'";
        } elseif ($status === 'pending' || $status === 'ChoDuyet') {
            $sql .= " AND u.TrangThai = 'ChoDuyet'";
        }

        $sql .= " ORDER BY u.MaUser DESC";

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

    public function lockUser($maUser)
    {
        $sql = "UPDATE user SET TrangThai = 'BiKhoa', IsLocked = 1 WHERE MaUser = ?";
        $stmt = mysqli_prepare($this->link, $sql);
        mysqli_stmt_bind_param($stmt, 's', $maUser);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $success;
    }

    public function unlockUser($maUser)
    {
        $sql = "UPDATE user SET TrangThai = 'HoatDong', IsLocked = 0 WHERE MaUser = ?";
        $stmt = mysqli_prepare($this->link, $sql);
        mysqli_stmt_bind_param($stmt, 's', $maUser);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $success;
    }

    public function approveCompany($maUser)
    {
        $sql = "UPDATE user SET TrangThai = 'HoatDong', IsLocked = 0 WHERE MaUser = ?";
        $stmt = mysqli_prepare($this->link, $sql);
        mysqli_stmt_bind_param($stmt, 's', $maUser);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $success;
    }
}