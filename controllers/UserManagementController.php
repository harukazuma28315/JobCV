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
        AuthHelper::requireRole(ROLE_ADMIN);

        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $role    = isset($_GET['role']) && $_GET['role'] !== '' ? (int)$_GET['role'] : null;
        $status  = isset($_GET['status']) && $_GET['status'] !== '' ? $_GET['status'] : null;

        $danhSachNguoiDung = $this->userModel->getUserListForAdmin($keyword, $role, $status);

        $thongBao = ResponseHelper::getFlash();

        require ROOT_PATH . '/views/admin/users.php';
    }

    public function lockUser()
    {
        AuthHelper::requireRole(ROLE_ADMIN);
        $maUser = isset($_POST['maUser']) ? trim($_POST['maUser']) : '';

        if ($maUser && $this->userModel->lockUser($maUser)) {
            ResponseHelper::setFlash('success', 'Đã khóa tài khoản thành công.');
        } else {
            ResponseHelper::setFlash('error', 'Khóa tài khoản thất bại.');
        }
        AuthHelper::redirect(BASE_URL . '/index.php?action=adminUserList');
    }

    public function unlockUser()
    {
        AuthHelper::requireRole(ROLE_ADMIN);
        $maUser = isset($_POST['maUser']) ? trim($_POST['maUser']) : '';

        if ($maUser && $this->userModel->unlockUser($maUser)) {
            ResponseHelper::setFlash('success', 'Đã mở khóa tài khoản thành công.');
        } else {
            ResponseHelper::setFlash('error', 'Mở khóa tài khoản thất bại.');
        }
        AuthHelper::redirect(BASE_URL . '/index.php?action=adminUserList');
    }

    public function approveUser()
    {
        AuthHelper::requireRole(ROLE_ADMIN);
        $maUser = isset($_POST['maUser']) ? trim($_POST['maUser']) : '';

        if (!empty($maUser) && $this->userModel->approveCompany($maUser)) {
            ResponseHelper::setFlash('success', 'Đã duyệt thông tin nhà tuyển dụng thành công.');
        } else {
            ResponseHelper::setFlash('error', 'Duyệt thông tin thất bại.');
        }
        
        AuthHelper::redirect(BASE_URL . '/index.php?action=adminUserList');
    }
}