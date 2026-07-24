<?php
/**
 * File: app/controllers/AdminController.php
 * Chức năng: Xử lý logic cho các trang Admin (Dashboard, quản lý người dùng, tin tuyển dụng...)
 */

require_once ROOT_PATH . '/models/StatisticModel.php';
require_once ROOT_PATH . '/helpers/AuthHelper.php';
require_once ROOT_PATH . '/helpers/ResponseHelper.php';

class AdminController
{
    private $statisticModel;

    public function __construct()
    {
        $this->statisticModel = new StatisticModel();
    }

    /**
     * Hiển thị trang Dashboard Admin
     */
    public function showDashboard()
    {
        // echo "<h2>DEBUG: Đang vào showDashboard</h2>";
        // echo "ROOT_PATH = " . ROOT_PATH . "<br>";

        // AuthHelper::requireRole(ROLE_ADMIN);
        $_SESSION['role'] = 2;        // Giả lập quyền Admin
        $_SESSION['user_id'] = 'U005';

        // Lấy dữ liệu thống kê tổng quát
        $thongKe = array(
            'tongNguoiDung'     => $this->statisticModel->getTotalUsers(),
            'tongBaiDang'       => $this->statisticModel->getTotalJobs(),
            'tongDoanhNghiep'   => $this->statisticModel->getTotalCompanies()
        );

        // Lấy dữ liệu cho biểu đồ
        $loaiBieuDo = isset($_GET['chartType']) ? $_GET['chartType'] : 'users';
        $khoangThoiGian = isset($_GET['period']) ? $_GET['period'] : '30days';

        $duLieuBieuDo = $this->statisticModel->getChartData($loaiBieuDo, $khoangThoiGian);

        $thongBao = ResponseHelper::getFlash();

        // Truyền dữ liệu vào View
        require ROOT_PATH . '/views/page/admin/dashboard.php';
    }
}