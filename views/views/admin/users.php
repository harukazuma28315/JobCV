<?php
// views/admin/users.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý người dùng - Admin</title>
    <link href="../../public/css/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root { --sidebar-width: 260px; --primary-blue: #1e5ba6; }
        body { background-color: #f4f7f6; font-family: 'Segoe UI', sans-serif; }
        .sidebar { width: var(--sidebar-width); height: 100vh; position: fixed; top: 0; left: 0; background-color: #fff; border-right: 1px solid #e5e5e5; }
        .main-content { margin-left: var(--sidebar-width); padding: 20px; }
        .nav-link-admin { display: flex; align-items: center; padding: 12px 20px; color: #555; text-decoration: none; font-weight: 500; border-radius: 8px; margin: 4px 15px; }
        .nav-link-admin.active { background-color: #f0f4f9; color: var(--primary-blue); }
        .badge-pending { background-color: #ffeeba; color: #856404; }
        .btn-action-group .btn { padding: 5px 10px; font-size: 0.85rem; border-radius: 6px; }
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
            <a href="users.php" class="nav-link-admin active"><i class="fa-solid fa-users me-3"></i>Quản lý người dùng</a>
            <a href="jobs.php" class="nav-link-admin"><i class="fa-solid fa-file-signature me-3"></i>Quản lý bài đăng</a>
            <a href="categories.php" class="nav-link-admin"><i class="fa-solid fa-folder-tree me-3"></i>Quản lý danh mục</a>
        </div>
    </div>
    <div class="px-4">
        <a href="../home/index.php" class="btn btn-outline-danger w-100">Thoát giao diện Admin</a>
    </div>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">Quản lý người dùng</h3>
    </div>

    <!-- Bộ lọc tìm kiếm -->
    <div class="card border-0 shadow-sm p-3 mb-4 rounded-3">
        <div class="row g-3">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" class="form-control border-start-0" placeholder="Tìm kiếm theo tên hoặc email...">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select">
                    <option value="">-- Loại tài khoản --</option>
                    <option value="candidate">Ứng viên</option>
                    <option value="employer">Nhà tuyển dụng</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select">
                    <option value="">-- Trạng thái --</option>
                    <option value="active">Đang hoạt động</option>
                    <option value="blocked">Bị khóa</option>
                </select>
            </div>
            <div class="col-md-1">
                <button class="btn btn-secondary w-100">Lọc</button>
            </div>
        </div>
    </div>

    <!-- Bảng danh sách thành viên -->
    <div class="card border-0 shadow-sm rounded-3">
        <div class="table-responsive p-3">
            <table class="table align-middle table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tên người dùng</th>
                        <th>Email</th>
                        <th>Loại tài khoản</th>
                        <th>Trạng thái hoạt động</th>
                        <th class="text-end">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- 1. Nhà tuyển dụng cần duyệt thông tin -->
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="https://api.dicebear.com/7.x/identicon/svg?seed=fpt" class="rounded-circle border" style="width: 35px; height: 35px;">
                                <div>
                                    <span class="fw-semibold d-block">FPT Software</span>
                                    <small class="text-warning fw-bold" style="font-size: 11px;"><i class="fa-solid fa-clock-rotate-left me-1"></i>Vừa cập nhật thông tin</small>
                                </div>
                            </div>
                        </td>
                        <td>hr@fpt.com.vn</td>
                        <td><span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">Nhà tuyển dụng</span></td>
                        <td><span class="badge badge-pending px-2 py-1"><i class="fa-solid fa-spinner fa-spin me-1"></i>Chờ duyệt thông tin</span></td>
                        <td class="text-end">
                            <div class="btn-action-group d-inline-flex gap-1">
                                <button class="btn btn-warning text-dark fw-semibold" title="Xem & duyệt thông tin mới"><i class="fa-solid fa-user-shield me-1"></i>Duyệt</button>
                                <button class="btn btn-outline-secondary" title="Sửa chi tiết"><i class="fa-solid fa-pen"></i></button>
                                <button class="btn btn-danger" title="Khóa tài khoản"><i class="fa-solid fa-user-slash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <!-- 2. Nhà tuyển dụng đang hoạt động bình thường -->
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="https://api.dicebear.com/7.x/identicon/svg?seed=vng" class="rounded-circle border" style="width: 35px; height: 35px;">
                                <span class="fw-semibold">VNG Corporation</span>
                            </div>
                        </td>
                        <td>hr@vng.com.vn</td>
                        <td><span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">Nhà tuyển dụng</span></td>
                        <td><span class="badge bg-success-subtle text-success px-2 py-1">Đang hoạt động</span></td>
                        <td class="text-end">
                            <div class="btn-action-group d-inline-flex gap-1">
                                <button class="btn btn-outline-secondary" title="Sửa chi tiết"><i class="fa-solid fa-pen"></i></button>
                                <button class="btn btn-danger" title="Khóa tài khoản"><i class="fa-solid fa-user-slash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <!-- 3. Nhà tuyển dụng bị khóa -->
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="https://api.dicebear.com/7.x/identicon/svg?seed=viettel" class="rounded-circle border" style="width: 35px; height: 35px;">
                                <span class="fw-semibold">Viettel Post</span>
                            </div>
                        </td>
                        <td>recruitment@viettelpost.vn</td>
                        <td><span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">Nhà tuyển dụng</span></td>
                        <td><span class="badge bg-danger-subtle text-danger px-2 py-1">Bị khóa</span></td>
                        <td class="text-end">
                            <div class="btn-action-group d-inline-flex gap-1">
                                <button class="btn btn-outline-secondary" title="Sửa chi tiết"><i class="fa-solid fa-pen"></i></button>
                                <button class="btn btn-success" title="Mở khóa tài khoản"><i class="fa-solid fa-user-check"></i></button>
                            </div>
                        </td>
                    </tr>
                    <!-- 4. Ứng viên 1 -->
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="https://api.dicebear.com/7.x/adventurer/svg?seed=ntl" class="rounded-circle border" style="width: 35px; height: 35px;">
                                <span class="fw-semibold">Nguyễn Thị Liễu</span>
                            </div>
                        </td>
                        <td>ntl@gmail.com</td>
                        <td><span class="badge bg-info-subtle text-info px-3 py-2 rounded-pill">Ứng viên</span></td>
                        <td><span class="badge bg-success-subtle text-success px-2 py-1">Đang hoạt động</span></td>
                        <td class="text-end">
                            <div class="btn-action-group d-inline-flex gap-1">
                                <button class="btn btn-outline-secondary" title="Cập nhật hồ sơ"><i class="fa-solid fa-pen"></i></button>
                                <button class="btn btn-danger" title="Khóa tài khoản"><i class="fa-solid fa-user-slash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <!-- Các tài khoản ứng viên tiếp theo để đủ 10 dòng ở trang 1 -->
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="https://api.dicebear.com/7.x/adventurer/svg?seed=hieu" class="rounded-circle border" style="width: 35px; height: 35px;">
                                <span class="fw-semibold">Trần Minh Hiếu</span>
                            </div>
                        </td>
                        <td>hieutm@gmail.com</td>
                        <td><span class="badge bg-info-subtle text-info px-3 py-2 rounded-pill">Ứng viên</span></td>
                        <td><span class="badge bg-success-subtle text-success px-2 py-1">Đang hoạt động</span></td>
                        <td class="text-end">
                            <div class="btn-action-group d-inline-flex gap-1">
                                <button class="btn btn-outline-secondary"><i class="fa-solid fa-pen"></i></button>
                                <button class="btn btn-danger"><i class="fa-solid fa-user-slash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="https://api.dicebear.com/7.x/adventurer/svg?seed=nha" class="rounded-circle border" style="width: 35px; height: 35px;">
                                <span class="fw-semibold">Lê Thanh Nhã</span>
                            </div>
                        </td>
                        <td>nhalt@gmail.com</td>
                        <td><span class="badge bg-info-subtle text-info px-3 py-2 rounded-pill">Ứng viên</span></td>
                        <td><span class="badge bg-success-subtle text-success px-2 py-1">Đang hoạt động</span></td>
                        <td class="text-end">
                            <div class="btn-action-group d-inline-flex gap-1">
                                <button class="btn btn-outline-secondary"><i class="fa-solid fa-pen"></i></button>
                                <button class="btn btn-danger"><i class="fa-solid fa-user-slash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="https://api.dicebear.com/7.x/adventurer/svg?seed=thang" class="rounded-circle border" style="width: 35px; height: 35px;">
                                <span class="fw-semibold">Nguyễn Hữu Thắng</span>
                            </div>
                        </td>
                        <td>thangnh@gmail.com</td>
                        <td><span class="badge bg-info-subtle text-info px-3 py-2 rounded-pill">Ứng viên</span></td>
                        <td><span class="badge bg-success-subtle text-success px-2 py-1">Đang hoạt động</span></td>
                        <td class="text-end">
                            <div class="btn-action-group d-inline-flex gap-1">
                                <button class="btn btn-outline-secondary"><i class="fa-solid fa-pen"></i></button>
                                <button class="btn btn-danger"><i class="fa-solid fa-user-slash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="https://api.dicebear.com/7.x/adventurer/svg?seed=lan" class="rounded-circle border" style="width: 35px; height: 35px;">
                                <span class="fw-semibold">Phạm Thiên Lam</span>
                            </div>
                        </td>
                        <td>lampt@gmail.com</td>
                        <td><span class="badge bg-info-subtle text-info px-3 py-2 rounded-pill">Ứng viên</span></td>
                        <td><span class="badge bg-danger-subtle text-danger px-2 py-1">Bị khóa</span></td>
                        <td class="text-end">
                            <div class="btn-action-group d-inline-flex gap-1">
                                <button class="btn btn-outline-secondary"><i class="fa-solid fa-pen"></i></button>
                                <button class="btn btn-success"><i class="fa-solid fa-user-check"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="https://api.dicebear.com/7.x/adventurer/svg?seed=khai" class="rounded-circle border" style="width: 35px; height: 35px;">
                                <span class="fw-semibold">Trần Khải Hoàn</span>
                            </div>
                        </td>
                        <td>hoantk@gmail.com</td>
                        <td><span class="badge bg-info-subtle text-info px-3 py-2 rounded-pill">Ứng viên</span></td>
                        <td><span class="badge bg-success-subtle text-success px-2 py-1">Đang hoạt động</span></td>
                        <td class="text-end">
                            <div class="btn-action-group d-inline-flex gap-1">
                                <button class="btn btn-outline-secondary"><i class="fa-solid fa-pen"></i></button>
                                <button class="btn btn-danger"><i class="fa-solid fa-user-slash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="https://api.dicebear.com/7.x/adventurer/svg?seed=vy" class="rounded-circle border" style="width: 35px; height: 35px;">
                                <span class="fw-semibold">Đỗ Thúy Vy</span>
                            </div>
                        </td>
                        <td>vydt@gmail.com</td>
                        <td><span class="badge bg-info-subtle text-info px-3 py-2 rounded-pill">Ứng viên</span></td>
                        <td><span class="badge bg-success-subtle text-success px-2 py-1">Đang hoạt động</span></td>
                        <td class="text-end">
                            <div class="btn-action-group d-inline-flex gap-1">
                                <button class="btn btn-outline-secondary"><i class="fa-solid fa-pen"></i></button>
                                <button class="btn btn-danger"><i class="fa-solid fa-user-slash"></i></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        <!-- Phân trang (Căn giữa tuyệt đối) -->
        <div class="card-footer bg-white border-0 px-3 py-3 d-flex justify-content-center align-items-center">
            <nav aria-label="Page navigation">
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true"><i class="fa-solid fa-chevron-left"></i></a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                    <li class="page-item"><a class="page-link" href="#">18</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#"><i class="fa-solid fa-chevron-right"></i></a>
                    </li>
                </ul>
            </nav>
        </div> 
        </div>
    </div>
</div>

<script src="../../public/js/js/bootstrap.bundle.min.js"></script>
</body>
</html>