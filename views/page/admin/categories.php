<?php
/**
 * File: views/admin/categories.php
 */
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}

$nganhNgheList = $nganhNgheList ?? [];
$diaDiemList   = $diaDiemList ?? [];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý danh mục - Admin</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root { --sidebar-width: 260px; --primary-blue: #1e5ba6; }
        body { background-color: #f4f7f6; font-family: 'Segoe UI', sans-serif; }
        .sidebar { width: var(--sidebar-width); height: 100vh; position: fixed; top: 0; left: 0; background-color: #fff; border-right: 1px solid #e5e5e5; }
        .main-content { margin-left: var(--sidebar-width); padding: 20px; }
        .nav-link-admin { display: flex; align-items: center; padding: 12px 20px; color: #555; text-decoration: none; font-weight: 500; border-radius: 8px; margin: 4px 15px; }
        .nav-link-admin.active { background-color: #f0f4f9; color: var(--primary-blue); }
    </style>
</head>
<body>

<div class="sidebar d-flex flex-column justify-content-between py-3">
    <div>
        <div class="px-4 py-3 border-bottom mb-3">
            <h4 class="text-primary-blue fw-bold mb-0">JobHub Admin</h4>
        </div>
        <div class="nav flex-column">
            <a href="<?= BASE_URL ?>/index.php?route=admin/dashboard" class="nav-link-admin">
                <i class="fa-solid fa-house me-3"></i>Trang chủ
            </a>
            <a href="<?= BASE_URL ?>/index.php?route=admin/users" class="nav-link-admin">
                <i class="fa-solid fa-users me-3"></i>Quản lý người dùng
            </a>
            <a href="<?= BASE_URL ?>/index.php?route=admin/jobs" class="nav-link-admin">
                <i class="fa-solid fa-file-signature me-3"></i>Quản lý bài đăng
            </a>
            <a href="<?= BASE_URL ?>/index.php?route=admin/categories" class="nav-link-admin active">
                <i class="fa-solid fa-folder-tree me-3"></i>Quản lý danh mục
            </a>
        </div>
    </div>
    <div class="px-4">
        <a href="<?= BASE_URL ?>/index.php?route=home" class="btn btn-outline-danger w-100">Thoát giao diện Admin</a>
    </div>
</div>

<div class="main-content">
    <div class="mb-4">
        <h3 class="fw-bold mb-2">Quản lý Danh mục dữ liệu dùng chung</h3>
        <button class="btn btn-primary" style="background-color: var(--primary-blue); border:none;" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="fa-solid fa-plus me-2"></i>Thêm danh mục mới
        </button>
    </div>

    <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="job-tab" data-bs-toggle="tab" data-bs-target="#job-tab-pane" type="button" role="tab">
                <i class="fa-solid fa-briefcase me-2"></i>Ngành nghề
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="location-tab" data-bs-toggle="tab" data-bs-target="#location-tab-pane" type="button" role="tab">
                <i class="fa-solid fa-location-dot me-2"></i>Địa điểm
            </button>
        </li>
    </ul>

    <div class="tab-content card border-0 shadow-sm p-4 rounded-3" id="myTabContent">
        
        <!-- TAB NGÀNH NGHỀ -->
        <div class="tab-pane fade show active" id="job-tab-pane" role="tabpanel">
            <h5 class="fw-bold mb-3 text-secondary">Danh sách Ngành nghề tuyển dụng</h5>
            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Tên ngành nghề</th>
                            <th>Mã ngành</th>
                            <th>Ngày tạo</th>
                            <th class="text-end">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($nganhNgheList)): ?>
                            <tr><td colspan="4" class="text-center py-4">Chưa có dữ liệu.</td></tr>
                        <?php else: ?>
                            <?php foreach ($nganhNgheList as $item): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($item['TenDanhMuc']) ?></strong></td>
                                <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($item['MaDanhMuc']) ?></span></td>
                                <td><?= date('d/m/Y', strtotime($item['NgayTao'] ?? 'now')) ?></td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-primary me-1" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editModal"
                                            data-ma="<?= htmlspecialchars($item['MaDanhMuc']) ?>"
                                            data-ten="<?= htmlspecialchars($item['TenDanhMuc']) ?>"
                                            data-loai="nganhnghe">
                                        <i class="fa-solid fa-pen-to-square"></i> Sửa
                                    </button>
                                    <form method="POST" action="<?= BASE_URL ?>/index.php?route=admin/categories/delete" style="display:inline;">
                                        <input type="hidden" name="maDanhMuc" value="<?= htmlspecialchars($item['MaDanhMuc']) ?>">
                                        <input type="hidden" name="loai" value="nganhnghe">
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Xóa?')">
                                            <i class="fa-solid fa-trash"></i> Xóa
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TAB ĐỊA ĐIỂM -->
        <div class="tab-pane fade" id="location-tab-pane" role="tabpanel">
            <h5 class="fw-bold mb-3 text-secondary">Danh sách Tỉnh thành / Địa điểm tuyển dụng</h5>
            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Tên địa điểm</th>
                            <th>Mã vùng</th>
                            <th>Ngày tạo</th>
                            <th class="text-end">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($diaDiemList)): ?>
                            <tr><td colspan="4" class="text-center py-4">Chưa có dữ liệu.</td></tr>
                        <?php else: ?>
                            <?php foreach ($diaDiemList as $item): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($item['TenDanhMuc']) ?></strong></td>
                                <td><?= htmlspecialchars($item['MaDanhMuc']) ?></td>
                                <td><?= date('d/m/Y', strtotime($item['NgayTao'] ?? 'now')) ?></td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-primary me-1" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editModal"
                                            data-ma="<?= htmlspecialchars($item['MaDanhMuc']) ?>"
                                            data-ten="<?= htmlspecialchars($item['TenDanhMuc']) ?>"
                                            data-loai="diadiem">
                                        <i class="fa-solid fa-pen-to-square"></i> Sửa
                                    </button>
                                    <form method="POST" action="<?= BASE_URL ?>/index.php?route=admin/categories/delete" style="display:inline;">
                                        <input type="hidden" name="maDanhMuc" value="<?= htmlspecialchars($item['MaDanhMuc']) ?>">
                                        <input type="hidden" name="loai" value="diadiem">
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Xóa?')">
                                            <i class="fa-solid fa-trash"></i> Xóa
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Thêm -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?= BASE_URL ?>/index.php?route=admin/categories/add">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm Danh Mục Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Loại danh mục</label>
                        <select name="loai" class="form-select" required>
                            <option value="nganhnghe">Ngành nghề</option>
                            <option value="diadiem">Địa điểm</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Tên danh mục</label>
                        <input type="text" name="ten" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Mã danh mục</label>
                        <input type="text" name="ma" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Sửa (Sửa cả Tên và Mã) -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?= BASE_URL ?>/index.php?route=admin/categories/update">
                <input type="hidden" name="old_ma" id="edit_old_ma">
                <input type="hidden" name="loai" id="edit_loai">
                <div class="modal-header">
                    <h5 class="modal-title">Sửa Danh Mục</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Tên danh mục</label>
                        <input type="text" name="ten" id="edit_ten" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Mã danh mục</label>
                        <input type="text" name="ma" id="edit_ma" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?= BASE_URL ?>/assets/js/jsbs/bootstrap.bundle.min.js"></script>
<script>
    // Xử lý modal sửa
    const editModal = document.getElementById('editModal');
    editModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        document.getElementById('edit_old_ma').value = button.getAttribute('data-ma');
        document.getElementById('edit_ma').value = button.getAttribute('data-ma');
        document.getElementById('edit_ten').value = button.getAttribute('data-ten');
        document.getElementById('edit_loai').value = button.getAttribute('data-loai');
    });
</script>
</body>
</html>