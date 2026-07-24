<?php
/**
 * File: app/views/admin/jobs.php
 * Chức năng: Quản lý bài đăng - Admin
 */
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý bài đăng - Admin</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root { --sidebar-width: 260px; --primary-blue: #1e5ba6; }
        body { background-color: #f4f7f6; font-family: 'Segoe UI', sans-serif; }
        .sidebar { width: var(--sidebar-width); height: 100vh; position: fixed; top: 0; left: 0; background-color: #fff; border-right: 1px solid #e5e5e5; }
        .main-content { margin-left: var(--sidebar-width); padding: 20px; }
        .nav-link-admin { display: flex; align-items: center; padding: 12px 20px; color: #555; text-decoration: none; font-weight: 500; border-radius: 8px; margin: 4px 15px; }
        .nav-link-admin.active { background-color: #f0f4f9; color: var(--primary-blue); }
        .action-grid {
            display: grid;
            grid-template-columns: 75px 85px 85px;
            gap: 8px;
            justify-content: center;
        }
        .action-grid .btn {
            height: 31px;
            font-size: 0.85rem;
            padding: 4px 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
        }
        .btn-span-2 { grid-column: span 2; width: 100%; }
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
            <a href="<?= BASE_URL ?>/index.php?route=admin/jobs" class="nav-link-admin active">
                <i class="fa-solid fa-file-signature me-3"></i>Quản lý bài đăng
            </a>
            <a href="<?= BASE_URL ?>/index.php?route=admin/categories" class="nav-link-admin">
                <i class="fa-solid fa-folder-tree me-3"></i>Quản lý danh mục
            </a>
        </div>
    </div>
    <div class="px-4">
        <a href="<?= BASE_URL ?>/index.php?route=home" class="btn btn-outline-danger w-100">
            <i class="fa-solid fa-arrow-right-from-bracket me-2"></i>Thoát giao diện Admin
        </a>
    </div>
</div>

<div class="main-content">
    <h3 class="fw-bold mb-4">Quản lý bài đăng</h3>

    <!-- Bộ lọc -->
    <form method="GET" action="<?= BASE_URL ?>/index.php">
        <input type="hidden" name="route" value="admin/jobs">
        <div class="card border-0 shadow-sm p-3 mb-4 rounded-3">
            <div class="row g-3">
                <div class="col-md-7">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" name="keyword" class="form-control border-start-0" 
                               placeholder="Tìm kiếm theo tên công ty hoặc vị trí tuyển dụng..." 
                               value="<?= htmlspecialchars($keyword ?? '') ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">-- Trạng thái --</option>
                        <option value="ChoDuyet" <?= (isset($status) && $status === 'ChoDuyet') ? 'selected' : '' ?>>Chờ duyệt</option>
                        <option value="DaDuyet" <?= (isset($status) && $status === 'DaDuyet') ? 'selected' : '' ?>>Đã duyệt</option>
                        <option value="TuChoi" <?= (isset($status) && $status === 'TuChoi') ? 'selected' : '' ?>>Từ chối duyệt</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-secondary w-100"><i class="fa-solid fa-filter me-2"></i>Lọc tin</button>
                </div>
            </div>
        </div>
    </form>

    <!-- Bảng danh sách -->
    <div class="card border-0 shadow-sm rounded-3">
        <div class="table-responsive p-3">
            <table class="table align-middle table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Công ty</th>
                        <th>Vị trí</th>
                        <th>Ngày đăng</th>
                        <th>Trạng thái</th>
                        <th class="text-center" style="width: 250px;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($danhSachTin)): ?>
                        <tr><td colspan="5" class="text-center py-4">Không tìm thấy tin tuyển dụng nào.</td></tr>
                    <?php else: ?>
                        <?php foreach ($danhSachTin as $tin): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($tin['TenCongTy'] ?? '') ?></strong></td>
                                <td><?= htmlspecialchars($tin['TieuDe'] ?? '') ?></td>
                                <td><?= !empty($tin['NgayDang']) ? date('d/m/Y', strtotime($tin['NgayDang'])) : '' ?></td>
                                <td>
                                    <?php 
                                    $badgeClass = 'bg-secondary';
                                    $badgeText = $tin['TrangThaiDuyet'] ?? 'Không xác định';
                                    switch ($tin['TrangThaiDuyet']) {
                                        case 'ChoDuyet':
                                            $badgeClass = 'bg-warning-subtle text-warning';
                                            $badgeText = 'Chờ duyệt';
                                            break;
                                        case 'DaDuyet':
                                            $badgeClass = 'bg-success-subtle text-success';
                                            $badgeText = 'Đã duyệt';
                                            break;
                                        case 'TuChoi':
                                            $badgeClass = 'bg-danger-subtle text-danger';
                                            $badgeText = 'Từ chối duyệt';
                                            break;
                                    }
                                    ?>
                                    <span class="badge <?= $badgeClass ?> py-1.5 px-3 rounded-pill">
                                        <i class="fa-solid fa-circle me-1"></i><?= $badgeText ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-grid">
                                        <button class="btn btn-outline-primary" title="Xem chi tiết">
                                            <i class="fa-solid fa-eye me-1"></i>Xem
                                        </button>

                                        <?php if ($tin['TrangThaiDuyet'] === 'ChoDuyet'): ?>
                                            <form method="POST" action="<?= BASE_URL ?>/index.php?route=admin/jobs/approve" style="display:inline;">
                                                <input type="hidden" name="maTin" value="<?= htmlspecialchars($tin['MaTinTuyenDung']) ?>">
                                                <button type="submit" class="btn btn-success" onclick="return confirm('Duyệt tin này?')">
                                                    <i class="fa-solid fa-check me-1"></i>Duyệt
                                                </button>
                                            </form>
                                            <form method="POST" action="<?= BASE_URL ?>/index.php?route=admin/jobs/reject" style="display:inline;">
                                                <input type="hidden" name="maTin" value="<?= htmlspecialchars($tin['MaTinTuyenDung']) ?>">
                                                <button type="submit" class="btn btn-danger" onclick="return confirm('Từ chối tin này?')">
                                                    <i class="fa-solid fa-xmark me-1"></i>Từ chối
                                                </button>
                                            </form>
                                        <?php elseif ($tin['TrangThaiDuyet'] === 'DaDuyet'): ?>
                                            <form method="POST" action="<?= BASE_URL ?>/index.php?route=admin/jobs/remove" style="display:inline;">
                                                <input type="hidden" name="maTin" value="<?= htmlspecialchars($tin['MaTinTuyenDung']) ?>">
                                                <button type="submit" class="btn btn-outline-danger btn-span-2" onclick="return confirm('Gỡ tin này?')">
                                                    <i class="fa-solid fa-ban me-1"></i>Gỡ tin
                                                </button>
                                            </form>
                                        <?php elseif ($tin['TrangThaiDuyet'] === 'TuChoi'): ?>
                                            <form method="POST" action="<?= BASE_URL ?>/index.php?route=admin/jobs/approve" style="display:inline;">
                                                <input type="hidden" name="maTin" value="<?= htmlspecialchars($tin['MaTinTuyenDung']) ?>">
                                                <button type="submit" class="btn btn-outline-success btn-span-2" onclick="return confirm('Xem xét lại và duyệt?')">
                                                    <i class="fa-solid fa-rotate-left me-1"></i>Xem xét lại
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Phân trang -->
        <div class="card-footer bg-white border-0 px-3 py-3 d-flex justify-content-center align-items-center">
            <nav aria-label="Page navigation">
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1"><i class="fa-solid fa-chevron-left"></i></a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                    <li class="page-item"><a class="page-link" href="#">30</a></li>
                    <li class="page-item"><a class="page-link" href="#"><i class="fa-solid fa-chevron-right"></i></a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<script src="<?= BASE_URL ?>/assets/js/jsbs/bootstrap.bundle.min.js"></script>
</body>
</html>