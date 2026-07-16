<?php
// views/admin/verify-employer.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Duyệt doanh nghiệp mới - Admin</title>
    <link href="../../public/css/css/bootstrap.min.css" rel="stylesheet">
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
            <a href="dashboard.php" class="nav-link-admin"><i class="fa-solid fa-house me-3"></i>Trang chủ</a>
            <a href="users.php" class="nav-link-admin"><i class="fa-solid fa-users me-3"></i>Quản lý người dùng</a>
            <a href="verify-job.php" class="nav-link-admin"><i class="fa-solid fa-file-signature me-3"></i>Duyệt tin đăng</a>
            <a href="verify-employer.php" class="nav-link-admin active"><i class="fa-solid fa-building-circle-check me-3"></i>Duyệt doanh nghiệp</a>
            <a href="categories.php" class="nav-link-admin"><i class="fa-solid fa-folder-tree me-3"></i>Quản lý danh mục</a>
        </div>
    </div>
    <div class="px-4">
        <a href="../home/index.php" class="btn btn-outline-danger w-100">Thoát Admin</a>
    </div>
</div>

<div class="main-content">
    <h3 class="fw-bold mb-4">Danh sách doanh nghiệp chờ duyệt hồ sơ</h3>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="table-responsive p-3">
            <table class="table align-middle table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tên doanh nghiệp</th>
                        <th>Email liên hệ</th>
                        <th>Website</th>
                        <th>Trạng thái</th>
                        <th class="text-center" style="width: 250px;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="https://api.dicebear.com/7.x/identicon/svg?seed=vng" class="rounded border" style="width: 35px; height: 35px;">
                                <strong class="text-dark">VNG Corporation</strong>
                            </div>
                        </td>
                        <td>hr@vng.com.vn</td>
                        <td><a href="https://vng.com.vn" target="_blank" class="text-decoration-none">vng.com.vn</a></td>
                        <td><span class="badge bg-warning-subtle text-warning py-1.5 px-3 rounded-pill">Chờ duyệt hồ sơ</span></td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary me-1">Hồ sơ giấy tờ</button>
                            <button class="btn btn-sm btn-success me-1">Duyệt</button>
                            <button class="btn btn-sm btn-danger">Từ chối</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="../../public/js/js/bootstrap.bundle.min.js"></script>
</body>
</html>