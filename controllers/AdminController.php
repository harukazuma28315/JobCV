<?php
/**
 * File: app/controllers/AdminController.php
 * Chức năng: Xử lý logic cho các trang Admin (Dashboard, quản lý người dùng, tin tuyển dụng...)
 */

require_once ROOT_PATH . '/app/models/StatisticModel.php';
require_once ROOT_PATH . '/app/helpers/AuthHelper.php';
require_once ROOT_PATH . '/app/helpers/ResponseHelper.php';

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
        AuthHelper::requireRole(ROLE_ADMIN);

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
        require ROOT_PATH . '/app/views/admin/dashboard.php';
    }
}