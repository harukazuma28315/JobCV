<?php
/**
 * File: app/controllers/CategoryController.php
 */

require_once ROOT_PATH . '/models/CategoryModel.php';
require_once ROOT_PATH . '/helpers/AuthHelper.php';
require_once ROOT_PATH . '/helpers/ResponseHelper.php';

class CategoryController
{
    private $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
    }

    public function showCategories()
    {
        $_SESSION['role'] = 2; // Giả lập Admin

        $nganhNgheList = $this->categoryModel->getNganhNgheList();
        $diaDiemList   = $this->categoryModel->getDiaDiemList();

        $thongBao = ResponseHelper::getFlash();

        $data = [
            'nganhNgheList' => $nganhNgheList,
            'diaDiemList'   => $diaDiemList,
            'thongBao'      => $thongBao
        ];
        extract($data);

        require ROOT_PATH . '/views/page/admin/categories.php';
    }

    public function updateCategory()
    {
        $_SESSION['role'] = 2;

        $oldMa = isset($_POST['old_ma']) ? trim($_POST['old_ma']) : '';
        $newMa = isset($_POST['ma']) ? trim($_POST['ma']) : '';
        $ten   = isset($_POST['ten']) ? trim($_POST['ten']) : '';
        $loai  = isset($_POST['loai']) ? trim($_POST['loai']) : '';

        if ($oldMa && $newMa && $ten && in_array($loai, ['nganhnghe', 'diadiem'])) {
            if ($this->categoryModel->updateDanhMuc($oldMa, $newMa, $ten, $loai)) {
                ResponseHelper::setFlash('success', 'Cập nhật danh mục thành công!');
            } else {
                ResponseHelper::setFlash('error', 'Cập nhật thất bại (Mã danh mục mới có thể đã tồn tại).');
            }
        } else {
            ResponseHelper::setFlash('error', 'Dữ liệu không hợp lệ.');
        }

        AuthHelper::redirect(BASE_URL . '/index.php?route=admin/categories');
    }

    // Xóa danh mục (dùng chung cho ngành nghề và địa điểm)
    public function deleteCategory()
    {
        $_SESSION['role'] = 2;

        $maDanhMuc = isset($_POST['maDanhMuc']) ? trim($_POST['maDanhMuc']) : '';
        $loai      = isset($_POST['loai']) ? trim($_POST['loai']) : '';

        if ($maDanhMuc && $this->categoryModel->deleteDanhMuc($maDanhMuc)) {
            ResponseHelper::setFlash('success', 'Xóa danh mục thành công.');
        } else {
            ResponseHelper::setFlash('error', 'Xóa danh mục thất bại.');
        }

        AuthHelper::redirect(BASE_URL . '/index.php?route=admin/categories');
    }

    public function addCategory()
    {
        $_SESSION['role'] = 2;

        $loai = isset($_POST['loai']) ? trim($_POST['loai']) : '';
        $ten  = isset($_POST['ten']) ? trim($_POST['ten']) : '';
        $ma   = isset($_POST['ma']) ? trim($_POST['ma']) : '';

        if ($ten && $ma && in_array($loai, ['nganhnghe', 'diadiem'])) {
            if ($this->categoryModel->addDanhMuc($ten, $ma, $loai)) {
                ResponseHelper::setFlash('success', 'Thêm danh mục thành công!');
            } else {
                ResponseHelper::setFlash('error', 'Thêm thất bại (Mã danh mục đã tồn tại?)');
            }
        } else {
            ResponseHelper::setFlash('error', 'Dữ liệu không hợp lệ.');
        }

        AuthHelper::redirect(BASE_URL . '/index.php?route=admin/categories');
    }
}

