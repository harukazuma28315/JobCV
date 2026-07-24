<?php
/**
 * File: app/controllers/UserManagementController.php
 * Chức năng: Quản lý người dùng cho Admin
 */

require_once ROOT_PATH . '/models/AdminUserModel.php';   
require_once ROOT_PATH . '/helpers/AuthHelper.php';
require_once ROOT_PATH . '/helpers/ResponseHelper.php';

class UserManagementController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new AdminUserModel();  
    }

    public function showUserList()
    {
        // Giả lập Admin để test
        $_SESSION['role'] = 2;        
        $_SESSION['user_id'] = 'U005';

        // AuthHelper::requireRole(ROLE_ADMIN);  // Tạm comment khi test
        
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $role    = isset($_GET['role']) && $_GET['role'] !== '' ? (int)$_GET['role'] : null;
        $status  = isset($_GET['status']) && $_GET['status'] !== '' ? $_GET['status'] : null;

        $danhSachNguoiDung = $this->userModel->getUserListForAdmin($keyword, $role, $status);

        $thongBao = ResponseHelper::getFlash();
        
        require ROOT_PATH . '/views/page/admin/users.php';
    }

    public function lockUser()
    {
        $_SESSION['role'] = 2; // Giả lập Admin
        // AuthHelper::requireRole(ROLE_ADMIN);  // Tạm comment khi test

        $maUser = isset($_POST['maUser']) ? trim($_POST['maUser']) : '';

        if ($maUser && $this->userModel->lockUser($maUser)) {
            ResponseHelper::setFlash('success', 'Đã khóa tài khoản thành công.');
        } else {
            ResponseHelper::setFlash('error', 'Khóa tài khoản thất bại.');
        }
        AuthHelper::redirect(BASE_URL . '/index.php?route=admin/users');
    }

    public function unlockUser()
    {
        $_SESSION['role'] = 2; // Giả lập Admin
        // AuthHelper::requireRole(ROLE_ADMIN);  // Tạm comment khi test

        $maUser = isset($_POST['maUser']) ? trim($_POST['maUser']) : '';

        if ($maUser && $this->userModel->unlockUser($maUser)) {
            ResponseHelper::setFlash('success', 'Đã mở khóa tài khoản thành công.');
        } else {
            ResponseHelper::setFlash('error', 'Mở khóa tài khoản thất bại.');
        }
        AuthHelper::redirect(BASE_URL . '/index.php?route=admin/users');
    }

    public function approveUser()
    {
        $_SESSION['role'] = 2; // Giả lập Admin
        // AuthHelper::requireRole(ROLE_ADMIN);  // Tạm comment khi test

        $maUser = isset($_POST['maUser']) ? trim($_POST['maUser']) : '';

        if (!empty($maUser) && $this->userModel->approveCompany($maUser)) {
            ResponseHelper::setFlash('success', 'Đã duyệt thông tin nhà tuyển dụng thành công.');
        } else {
            ResponseHelper::setFlash('error', 'Duyệt thông tin thất bại.');
        }
        
        AuthHelper::redirect(BASE_URL . '/index.php?route=admin/users');
    }
}