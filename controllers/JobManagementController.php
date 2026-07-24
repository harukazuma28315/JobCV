<?php
/**
 * File: app/controllers/JobManagementController.php
 * Chức năng: Quản lý tin tuyển dụng cho Admin
 */

require_once ROOT_PATH . '/models/JobModel.php';
require_once ROOT_PATH . '/helpers/AuthHelper.php';
require_once ROOT_PATH . '/helpers/ResponseHelper.php';

class JobManagementController
{
    private $jobModel;

    public function __construct()
    {
        $this->jobModel = new JobModel();
    }

    public function showJobList()
    {
         // Giả lập Admin để test
        $_SESSION['role'] = 2;        
        $_SESSION['user_id'] = 'U005';

        // AuthHelper::requireRole(ROLE_ADMIN);

        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $status  = isset($_GET['status']) ? $_GET['status'] : null;

        $danhSachTin = $this->jobModel->getJobListForAdmin($keyword, $status);

        $thongBao = ResponseHelper::getFlash();

        require ROOT_PATH . '/views/page/admin/jobs.php';
    }

    public function approveJob()
    {
         // Giả lập Admin để test
        $_SESSION['role'] = 2;        
        $_SESSION['user_id'] = 'U005';

        // AuthHelper::requireRole(ROLE_ADMIN);
        
        $maTin = isset($_POST['maTin']) ? trim($_POST['maTin']) : '';

        if ($this->jobModel->approveJob($maTin)) {
            ResponseHelper::setFlash('success', 'Đã duyệt tin tuyển dụng thành công.');
        } else {
            ResponseHelper::setFlash('error', 'Duyệt tin thất bại.');
        }
        AuthHelper::redirect(BASE_URL . '/index.php?route=admin/jobs');
    }

    public function rejectJob()
    {
         // Giả lập Admin để test
        $_SESSION['role'] = 2;        
        $_SESSION['user_id'] = 'U005';

        // AuthHelper::requireRole(ROLE_ADMIN);
        $maTin = isset($_POST['maTin']) ? trim($_POST['maTin']) : '';

        if ($this->jobModel->rejectJob($maTin)) {
            ResponseHelper::setFlash('success', 'Đã từ chối tin tuyển dụng.');
        } else {
            ResponseHelper::setFlash('error', 'Từ chối tin thất bại.');
        }
        AuthHelper::redirect(BASE_URL . '/index.php?route=admin/jobs');
    }

    public function removeJob()
    {
         // Giả lập Admin để test
        $_SESSION['role'] = 2;        
        $_SESSION['user_id'] = 'U005';

        // AuthHelper::requireRole(ROLE_ADMIN);
        $maTin = isset($_POST['maTin']) ? trim($_POST['maTin']) : '';

        if ($this->jobModel->removeJob($maTin)) {
            ResponseHelper::setFlash('success', 'Đã gỡ tin tuyển dụng.');
        } else {
            ResponseHelper::setFlash('error', 'Gỡ tin thất bại.');
        }
        AuthHelper::redirect(BASE_URL . '/index.php?route=admin/jobs');
    }
}