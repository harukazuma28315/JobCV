<?php
// views/admin/verify-job.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kiểm duyệt tin tuyển dụng - Admin</title>
    <link href="../../public/css/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root { --sidebar-width: 260px; --primary-blue: #1e5ba6; }
        body { background-color: #f4f7f6; font-family: 'Segoe UI', sans-serif; }
        .sidebar { width: var(--sidebar-width); height: 100vh; position: fixed; top: 0; left: 0; background-color: #fff; border-right: 1px solid #e5e5e5; }
        .main-content { margin-left: var(--sidebar-width); padding: 20px; }
        .nav-link-admin { display: flex; align-items: center; padding: 12px 20px; color: #555; text-decoration: none; font-weight: 500; border-radius: 8px; margin: 4px 15px; }
        .nav-link-admin.active { background-color: #f0f4f9; color: var(--primary-blue); }
        /* CSS Định dạng cột hành động thẳng hàng tăm tắp */
        .action-grid {
            display: grid;
            grid-template-columns: 75px 85px 85px; /* Chia cố định 3 cột: Xem (75px) | Duyệt (85px) | Từ chối (85px) */
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
        /* Nút phụ (Gỡ tin / Xem xét lại) sẽ tự động chiếm không gian của cả 2 cột Duyệt & Từ chối */
        .action-grid .btn-span-2 {
            grid-column: span 2;
            width: 100%;
        }
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
            <a href="jobs.php" class="nav-link-admin active"><i class="fa-solid fa-file-signature me-3"></i>Quản lý bài đăng</a>
            <a href="categories.php" class="nav-link-admin"><i class="fa-solid fa-folder-tree me-3"></i>Quản lý danh mục</a>
        </div>
    </div>
    <div class="px-4">
        <a href="../home/index.php" class="btn btn-outline-danger w-100"><i class="fa-solid fa-arrow-right-from-bracket me-2"></i>Thoát giao diện Admin</a>
    </div>
</div>

<div class="main-content">
    <h3 class="fw-bold mb-4">Quản lý bài đăng</h3>

    <!-- Bộ lọc tìm kiếm & Trạng thái bài đăng -->
    <div class="card border-0 shadow-sm p-3 mb-4 rounded-3">
        <div class="row g-3">
            <!-- Ô tìm kiếm chiếm 7 phần -->
            <div class="col-md-7">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" class="form-control border-start-0" placeholder="Tìm kiếm theo tên công ty hoặc vị trí tuyển dụng...">
                </div>
            </div>
            <!-- Bộ lọc trạng thái chiếm 3 phần -->
            <div class="col-md-3">
                <select class="form-select">
                    <option value="">-- Trạng thái --</option>
                    <option value="pending" selected>Chờ duyệt</option>
                    <option value="approved">Đã duyệt</option>
                    <option value="rejected">Từ chối duyệt</option>
                </select>
            </div>
            <!-- Nút Lọc chiếm 2 phần (Tổng cộng 7 + 3 + 2 = 12 cột đầy đặn) -->
            <div class="col-md-2">
                <button class="btn btn-secondary w-100"><i class="fa-solid fa-filter me-2"></i>Lọc tin</button>
            </div>
        </div>
    </div>
    <!-- Bảng danh sách bài tuyển dụng chờ xử lý -->
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
                    <!-- 1. Bài đăng chờ duyệt -->
                    <tr>
                        <td><strong>Vinamilk</strong></td>
                        <td>Marketing Manager</td>
                        <td>10/11/2023</td>
                        <td><span class="badge bg-warning-subtle text-warning py-1.5 px-3 rounded-pill"><i class="fa-solid fa-clock me-1"></i>Chờ duyệt</span></td>
                        <td>
                            <div class="action-grid">
                                <button class="btn btn-outline-primary" title="Xem chi tiết"><i class="fa-solid fa-eye me-1"></i>Xem</button>
                                <button class="btn btn-success" title="Duyệt bài đăng"><i class="fa-solid fa-check me-1"></i>Duyệt</button>
                                <button class="btn btn-danger" title="Từ chối duyệt"><i class="fa-solid fa-xmark me-1"></i>Từ chối</button>
                            </div>
                        </td>
                    </tr>
                    <!-- 2. Bài đăng chờ duyệt -->
                    <tr>
                        <td><strong>FPT Software</strong></td>
                        <td>Business Analyst</td>
                        <td>09/11/2023</td>
                        <td><span class="badge bg-warning-subtle text-warning py-1.5 px-3 rounded-pill"><i class="fa-solid fa-clock me-1"></i>Chờ duyệt</span></td>
                        <td>
                            <div class="action-grid">
                                <button class="btn btn-outline-primary" title="Xem chi tiết"><i class="fa-solid fa-eye me-1"></i>Xem</button>
                                <button class="btn btn-success" title="Duyệt bài đăng"><i class="fa-solid fa-check me-1"></i>Duyệt</button>
                                <button class="btn btn-danger" title="Từ chối duyệt"><i class="fa-solid fa-xmark me-1"></i>Từ chối</button>
                            </div>
                        </td>
                    </tr>
                    <!-- 3. Bài đăng đã duyệt (Nút Gỡ tin dàn đều 2 cột trống bên phải) -->
                    <tr>
                        <td><strong>VNG Corporation</strong></td>
                        <td>Senior Frontend Developer (React)</td>
                        <td>08/11/2023</td>
                        <td><span class="badge bg-success-subtle text-success py-1.5 px-3 rounded-pill"><i class="fa-solid fa-circle-check me-1"></i>Đã duyệt</span></td>
                        <td>
                            <div class="action-grid">
                                <button class="btn btn-outline-primary" title="Xem chi tiết"><i class="fa-solid fa-eye me-1"></i>Xem</button>
                                <button class="btn btn-outline-danger btn-span-2" title="Gỡ bài đăng"><i class="fa-solid fa-ban me-1"></i>Gỡ tin</button>
                            </div>
                        </td>
                    </tr>
                    <!-- 4. Bài đăng bị từ chối (Nút Xem xét lại dàn đều 2 cột trống bên phải) -->
                    <tr>
                        <td><strong>Cửa hàng Bách Hóa Xanh</strong></td>
                        <td>Nhân viên kho bán hàng cá nhân</td>
                        <td>07/11/2023</td>
                        <td><span class="badge bg-danger-subtle text-danger py-1.5 px-3 rounded-pill"><i class="fa-solid fa-circle-xmark me-1"></i>Từ chối duyệt</span></td>
                        <td>
                            <div class="action-grid">
                                <button class="btn btn-outline-primary" title="Xem lý do từ chối"><i class="fa-solid fa-eye me-1"></i>Xem</button>
                                <button class="btn btn-outline-success btn-span-2" title="Khôi phục & duyệt lại"><i class="fa-solid fa-rotate-left me-1"></i>Xem xét lại</button>
                            </div>
                        </td>
                    </tr>
                    <!-- 5. Bài đăng đã duyệt -->
                    <tr>
                        <td><strong>Shopee Việt Nam</strong></td>
                        <td>Customer Service Specialist</td>
                        <td>06/11/2023</td>
                        <td><span class="badge bg-success-subtle text-success py-1.5 px-3 rounded-pill"><i class="fa-solid fa-circle-check me-1"></i>Đã duyệt</span></td>
                        <td>
                            <div class="action-grid">
                                <button class="btn btn-outline-primary"><i class="fa-solid fa-eye me-1"></i>Xem</button>
                                <button class="btn btn-outline-danger btn-span-2"><i class="fa-solid fa-ban me-1"></i>Gỡ tin</button>
                            </div>
                        </td>
                    </tr>
                    <!-- 6. Bài đăng đã duyệt -->
                    <tr>
                        <td><strong>Viettel Group</strong></td>
                        <td>Data Engineer (Python/SQL)</td>
                        <td>05/11/2023</td>
                        <td><span class="badge bg-success-subtle text-success py-1.5 px-3 rounded-pill"><i class="fa-solid fa-circle-check me-1"></i>Đã duyệt</span></td>
                        <td>
                            <div class="action-grid">
                                <button class="btn btn-outline-primary"><i class="fa-solid fa-eye me-1"></i>Xem</button>
                                <button class="btn btn-outline-danger btn-span-2"><i class="fa-solid fa-ban me-1"></i>Gỡ tin</button>
                            </div>
                        </td>
                    </tr>
                    <!-- 7. Bài đăng chờ duyệt -->
                    <tr>
                        <td><strong>Momo Wallet</strong></td>
                        <td>Product Owner (Fintech)</td>
                        <td>04/11/2023</td>
                        <td><span class="badge bg-warning-subtle text-warning py-1.5 px-3 rounded-pill"><i class="fa-solid fa-clock me-1"></i>Chờ duyệt</span></td>
                        <td>
                            <div class="action-grid">
                                <button class="btn btn-outline-primary"><i class="fa-solid fa-eye me-1"></i>Xem</button>
                                <button class="btn btn-success"><i class="fa-solid fa-check me-1"></i>Duyệt</button>
                                <button class="btn btn-danger"><i class="fa-solid fa-xmark me-1"></i>Từ chối</button>
                            </div>
                        </td>
                    </tr>
                    <!-- 8. Bài đăng đã duyệt -->
                    <tr>
                        <td><strong>Grab Vietnam</strong></td>
                        <td>Backend Engineer (Go/Java)</td>
                        <td>03/11/2023</td>
                        <td><span class="badge bg-success-subtle text-success py-1.5 px-3 rounded-pill"><i class="fa-solid fa-circle-check me-1"></i>Đã duyệt</span></td>
                        <td>
                            <div class="action-grid">
                                <button class="btn btn-outline-primary"><i class="fa-solid fa-eye me-1"></i>Xem</button>
                                <button class="btn btn-outline-danger btn-span-2"><i class="fa-solid fa-ban me-1"></i>Gỡ tin</button>
                            </div>
                        </td>
                    </tr>
                    <!-- 9. Bài đăng bị từ chối -->
                    <tr>
                        <td><strong>Cty TNHH Công Nghệ ABC</strong></td>
                        <td>Tuyển CTV nhập liệu tại nhà lương cao</td>
                        <td>02/11/2023</td>
                        <td><span class="badge bg-danger-subtle text-danger py-1.5 px-3 rounded-pill"><i class="fa-solid fa-circle-xmark me-1"></i>Từ chối duyệt</span></td>
                        <td>
                            <div class="action-grid">
                                <button class="btn btn-outline-primary"><i class="fa-solid fa-eye me-1"></i>Xem</button>
                                <button class="btn btn-outline-success btn-span-2"><i class="fa-solid fa-rotate-left me-1"></i>Xem xét lại</button>
                            </div>
                        </td>
                    </tr>
                    <!-- 10. Bài đăng đã duyệt -->
                    <tr>
                        <td><strong>Tiki Corp</strong></td>
                        <td>UX/UI Designer</td>
                        <td>01/11/2023</td>
                        <td><span class="badge bg-success-subtle text-success py-1.5 px-3 rounded-pill"><i class="fa-solid fa-circle-check me-1"></i>Đã duyệt</span></td>
                        <td>
                            <div class="action-grid">
                                <button class="btn btn-outline-primary"><i class="fa-solid fa-eye me-1"></i>Xem</button>
                                <button class="btn btn-outline-danger btn-span-2"><i class="fa-solid fa-ban me-1"></i>Gỡ tin</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            </table>
        </div>
        <!-- Phân trang (Căn giữa tuyệt đối dưới chân bảng bài đăng) -->
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
                    <li class="page-item"><a class="page-link" href="#">30</a></li>
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