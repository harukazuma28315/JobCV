<?php
/**
 * File: app/views/admin/users.php
 * Chức năng: Quản lý người dùng - Admin
 */
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}

// Khởi tạo biến an toàn
$keyword = $keyword ?? '';
$role    = $role ?? null;
$status  = $status ?? null;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý người dùng - Admin</title>
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
            <a href="<?= BASE_URL ?>/index.php?route=admin/users" class="nav-link-admin active">
                <i class="fa-solid fa-users me-3"></i>Quản lý người dùng
            </a>
            <a href="<?= BASE_URL ?>/index.php?route=admin/jobs" class="nav-link-admin">
                <i class="fa-solid fa-file-signature me-3"></i>Quản lý bài đăng
            </a>
            <a href="<?= BASE_URL ?>/index.php?route=admin/categories" class="nav-link-admin">
                <i class="fa-solid fa-folder-tree me-3"></i>Quản lý danh mục
            </a>
        </div>
    </div>
    <div class="px-4">
        <a href="<?= BASE_URL ?>/index.php?route=home" class="btn btn-outline-danger w-100">Thoát giao diện Admin</a>
    </div>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">Quản lý người dùng</h3>
    </div>

    <!-- Thông báo -->
    <?php if (isset($thongBao) && !empty($thongBao)): 
        $alertClass = (isset($thongBao['type']) && $thongBao['type'] === 'success') ? 'success' : 'danger';
    ?>
        <div class="alert alert-<?= $alertClass ?> alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($thongBao['message'] ?? '') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Bộ lọc -->
    <form method="GET" action="<?= BASE_URL ?>/index.php">
        <input type="hidden" name="route" value="admin/users">
        <div class="card border-0 shadow-sm p-3 mb-4 rounded-3">
            <div class="row g-3">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" name="keyword" class="form-control border-start-0" 
                               placeholder="Tìm kiếm theo tên hoặc email..." 
                               value="<?= htmlspecialchars($keyword) ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="role" class="form-select">
                        <option value="">-- Loại tài khoản --</option>
                        <option value="0" <?= $role === 0 ? 'selected' : '' ?>>Ứng viên</option>
                        <option value="1" <?= $role === 1 ? 'selected' : '' ?>>Nhà tuyển dụng</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">-- Trạng thái --</option>
                        <option value="active"   <?= $status === 'active'   ? 'selected' : '' ?>>Đang hoạt động</option>
                        <option value="blocked"  <?= $status === 'blocked'  ? 'selected' : '' ?>>Bị khóa</option>
                        <option value="pending"  <?= $status === 'pending'  ? 'selected' : '' ?>>Chờ duyệt</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-secondary w-100">Lọc</button>
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
                        <th>Tên người dùng</th>
                        <th>Email</th>
                        <th>Loại tài khoản</th>
                        <th>Trạng thái</th>
                        <th class="text-end">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($danhSachNguoiDung)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Không tìm thấy người dùng nào.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($danhSachNguoiDung as $user): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="https://api.dicebear.com/7.x/identicon/svg?seed=<?= htmlspecialchars($user['MaUser']) ?>" 
                                        class="rounded-circle border" style="width: 35px; height: 35px;">
                                    <span class="fw-semibold"><?= htmlspecialchars($user['HoTen'] ?? $user['TaiKhoan'] ?? 'Không tên') ?></span>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($user['Email']) ?></td>
                            <td>
                                <span class="badge <?= $user['Role'] == 0 ? 'bg-info-subtle text-info' : 'bg-primary-subtle text-primary' ?> px-3 py-2 rounded-pill">
                                    <?= $user['Role'] == 0 ? 'Ứng viên' : 'Nhà tuyển dụng' ?>
                                </span>
                            </td>
                            <td>
                                <?php
                                $statusText = 'Đang hoạt động';
                                $statusClass = 'bg-success-subtle text-success';
                                if ($user['TrangThai'] === 'ChoDuyet') {
                                    $statusText = 'Chờ duyệt';
                                    $statusClass = 'bg-warning-subtle text-warning';
                                } elseif ($user['TrangThai'] === 'BiKhoa') {
                                    $statusText = 'Bị khóa';
                                    $statusClass = 'bg-danger-subtle text-danger';
                                }
                                ?>
                                <span class="badge <?= $statusClass ?> px-2 py-1"><?= $statusText ?></span>
                            </td>
                            <td class="text-end">
                                <div class="btn-action-group d-inline-flex gap-1">
                                    <?php if ($user['Role'] == 1 && $user['TrangThai'] === 'ChoDuyet'): ?>
                                        <form method="POST" action="<?= BASE_URL ?>/index.php?route=admin/users/approve" style="display:inline;" 
                                              onsubmit="return confirm('Xác nhận duyệt?')">
                                            <input type="hidden" name="maUser" value="<?= htmlspecialchars($user['MaUser']) ?>">
                                            <button type="submit" class="btn btn-warning text-dark fw-semibold">
                                                <i class="fa-solid fa-user-shield"></i> Duyệt
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if ($user['TrangThai'] !== 'BiKhoa'): ?>
                                        <form method="POST" action="<?= BASE_URL ?>/index.php?route=admin/users/lock" style="display:inline;" 
                                              onsubmit="return confirm('Xác nhận khóa?')">
                                            <input type="hidden" name="maUser" value="<?= htmlspecialchars($user['MaUser']) ?>">
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fa-solid fa-user-slash"></i>
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <form method="POST" action="<?= BASE_URL ?>/index.php?route=admin/users/unlock" style="display:inline;">
                                            <input type="hidden" name="maUser" value="<?= htmlspecialchars($user['MaUser']) ?>">
                                            <button type="submit" class="btn btn-success">
                                                <i class="fa-solid fa-user-check"></i>
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
    </div>
</div>

<script src="<?= BASE_URL ?>/assets/js/jsbs/bootstrap.bundle.min.js"></script>
</body>
</html>