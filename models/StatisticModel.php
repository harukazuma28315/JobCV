<?php
/**
 * File: app/models/StatisticModel.php
 * Chức năng: Lấy dữ liệu thống kê và biểu đồ cho Dashboard Admin từ CSDL
 */

require_once ROOT_PATH . '/config/db.php';

class StatisticModel
{
    private $link;

    public function __construct()
    {
        $this->link = Database::getConnection();
    }

    // ===================== THỐNG KÊ TỔNG QUÁT =====================
    public function getTotalUsers() {
        $sql = "SELECT COUNT(*) as total FROM user";
        $row = mysqli_fetch_assoc(mysqli_query($this->link, $sql));
        return (int)$row['total'];
    }

    public function getTotalJobs() {
        $sql = "SELECT COUNT(*) as total FROM tintuyendung";
        $row = mysqli_fetch_assoc(mysqli_query($this->link, $sql));
        return (int)$row['total'];
    }

    public function getTotalCompanies() {
        $sql = "SELECT COUNT(*) as total FROM nhatuyendung";
        $row = mysqli_fetch_assoc(mysqli_query($this->link, $sql));
        return (int)$row['total'];
    }

    // ===================== DỮ LIỆU BIỂU ĐỒ (THẬT) =====================
    public function getChartData($loai, $khoangThoiGian)
    {
        $dateCondition = $this->getDateCondition($khoangThoiGian);

        switch ($loai) {
            case 'users':
                $sql = "SELECT 
                            SUM(CASE WHEN Role = 0 THEN 1 ELSE 0 END) as ungVien,
                            SUM(CASE WHEN Role = 1 THEN 1 ELSE 0 END) as nhaTuyenDung,
                            SUM(CASE WHEN Role = 2 THEN 1 ELSE 0 END) as admin
                        FROM user";
                $row = mysqli_fetch_assoc(mysqli_query($this->link, $sql));

                return [
                    'labels' => ['Ứng viên', 'Nhà tuyển dụng', 'Admin'],
                    'data' => [(int)$row['ungVien'], (int)$row['nhaTuyenDung'], (int)$row['admin']],
                    'pieTitle' => 'Tỷ lệ người dùng theo vai trò (%)',
                    'barTitle' => 'Số lượng người dùng theo vai trò'
                ];

            case 'jobs':
                $sql = "SELECT 
                            COUNT(*) as tong,
                            SUM(CASE WHEN (NgayHetHan >= CURDATE() OR NgayHetHan IS NULL) $dateCondition THEN 1 ELSE 0 END) as conHan
                        FROM tintuyendung";
                $row = mysqli_fetch_assoc(mysqli_query($this->link, $sql));

                return [
                    'labels' => ['Còn hạn', 'Hết hạn'],
                    'data' => [(int)$row['conHan'], $row['tong'] - $row['conHan']],
                    'pieTitle' => 'Tỷ lệ tin tuyển dụng theo hạn (%)',
                    'barTitle' => 'Số lượng tin tuyển dụng'
                ];

            case 'efficiency':
                $sql = "SELECT 
                            SUM(CASE WHEN TrangThai = 'NhanViec' THEN 1 ELSE 0 END) as thanhCong,
                            SUM(CASE WHEN TrangThai = 'TuChoi' THEN 1 ELSE 0 END) as tuChoi,
                            SUM(CASE WHEN TrangThai = 'HenPhongVan' THEN 1 ELSE 0 END) as henPhongVan,
                            COUNT(*) as tong
                        FROM hosotuyendung";
                $row = mysqli_fetch_assoc(mysqli_query($this->link, $sql));

                return [
                    'labels' => ['Nhận việc (Thành công)', 'Từ chối', 'Hẹn phỏng vấn'],
                    'data' => [(int)$row['thanhCong'], (int)$row['tuChoi'], (int)$row['henPhongVan']],
                    'pieTitle' => 'Tỷ lệ tuyển dụng thành công (%)',
                    'barTitle' => 'Hiệu quả tuyển dụng'
                ];

            default:
                return $this->getChartData('users', $khoangThoiGian);
        }
    }

    private function getDateCondition($khoangThoiGian)
    {
        switch ($khoangThoiGian) {
            case '7days':  return "AND NgayDang >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
            case '30days': return "AND NgayDang >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
            case '1year':  return "AND NgayDang >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)";
            default:       return "";
        }
    }
}