<?php
// views/admin/categories.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý danh mục - Admin</title>
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
            <a href="jobs.php" class="nav-link-admin"><i class="fa-solid fa-file-signature me-3"></i>Quản lý bài đăng</a>
            <a href="categories.php" class="nav-link-admin active"><i class="fa-solid fa-folder-tree me-3"></i>Quản lý danh mục</a>
        </div>
    </div>
    <div class="px-4">
        <a href="../home/index.php" class="btn btn-outline-danger w-100">Thoát giao diện Admin</a>
    </div>
</div>

<div class="main-content">
    <!-- TIÊU ĐỀ & NÚT THÊM DANH MỤC -->
    <div class="mb-4">
        <h3 class="fw-bold mb-2">Quản lý Danh mục dữ liệu dùng chung</h3>
        <button class="btn btn-primary" style="background-color: var(--primary-blue); border:none;">
            <i class="fa-solid fa-plus me-2"></i>Thêm danh mục mới
        </button>
    </div>

    <!-- TABS ĐIỀU HƯỚNG DANH MỤC -->
    <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="job-tab" data-bs-toggle="tab" data-bs-target="#job-tab-pane" type="button" role="tab" aria-selected="true"><i class="fa-solid fa-briefcase me-2"></i>Ngành nghề</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="location-tab" data-bs-toggle="tab" data-bs-target="#location-tab-pane" type="button" role="tab" aria-selected="false"><i class="fa-solid fa-location-dot me-2"></i>Địa điểm</button>
        </li>
        <!-- Đổi từ Kỹ năng thành cấu hình Lọc nâng cao -->
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="filters-tab" data-bs-toggle="tab" data-bs-target="#filters-tab-pane" type="button" role="tab" aria-selected="false"><i class="fa-solid fa-sliders me-2"></i>Tiêu chí nâng cao</button>
        </li>
    </ul>

    <div class="tab-content card border-0 shadow-sm p-4 rounded-3" id="myTabContent">
        
        <!-- TAB 1: NGÀNH NGHỀ -->
        <div class="tab-pane fade show active" id="job-tab-pane" role="tabpanel" aria-labelledby="job-tab" tabindex="0">
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
    <tr>
        <td><strong>IT Phần mềm</strong></td>
        <td><span class="badge bg-light text-dark border">IT_SOFTWARE</span></td>
        <td>10/01/2023</td>
        <td class="text-end">
            <button class="btn btn-sm btn-outline-primary me-1"><i class="fa-solid fa-pen-to-square"></i> Sửa</button>
            <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i> Xóa</button>
        </td>
    </tr>
    <tr>
        <td><strong>IT Phần cứng - Mạng</strong></td>
        <td><span class="badge bg-light text-dark border">IT_HARDWARE</span></td>
        <td>11/01/2023</td>
        <td class="text-end">
            <button class="btn btn-sm btn-outline-primary me-1"><i class="fa-solid fa-pen-to-square"></i> Sửa</button>
            <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i> Xóa</button>
        </td>
    </tr>
    <tr>
        <td><strong>Tài chính - Đầu tư - Chứng khoán</strong></td>
        <td><span class="badge bg-light text-dark border">FINANCE</span></td>
        <td>12/01/2023</td>
        <td class="text-end">
            <button class="btn btn-sm btn-outline-primary me-1"><i class="fa-solid fa-pen-to-square"></i> Sửa</button>
            <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i> Xóa</button>
        </td>
    </tr>
    <tr>
        <td><strong>Ngân hàng</strong></td>
        <td><span class="badge bg-light text-dark border">BANK</span></td>
        <td>13/01/2023</td>
        <td class="text-end">
            <button class="btn btn-sm btn-outline-primary me-1"><i class="fa-solid fa-pen-to-square"></i> Sửa</button>
            <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i> Xóa</button>
        </td>
    </tr>
    <tr>
        <td><strong>Kiểm toán</strong></td>
        <td><span class="badge bg-light text-dark border">AUDIT</span></td>
        <td>14/01/2023</td>
        <td class="text-end">
            <button class="btn btn-sm btn-outline-primary me-1"><i class="fa-solid fa-pen-to-square"></i> Sửa</button>
            <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i> Xóa</button>
        </td>
    </tr>
    <tr>
        <td><strong>Thiết kế - Sáng tạo nghệ thuật</strong></td>
        <td><span class="badge bg-light text-dark border">ART_DESIGN</span></td>
        <td>15/01/2023</td>
        <td class="text-end">
            <button class="btn btn-sm btn-outline-primary me-1"><i class="fa-solid fa-pen-to-square"></i> Sửa</button>
            <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i> Xóa</button>
        </td>
    </tr>
    <tr>
        <td><strong>Kiến trúc - Thiết kế nội ngoại thất</strong></td>
        <td><span class="badge bg-light text-dark border">ARCHITECTURE</span></td>
        <td>16/01/2023</td>
        <td class="text-end">
            <button class="btn btn-sm btn-outline-primary me-1"><i class="fa-solid fa-pen-to-square"></i> Sửa</button>
            <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i> Xóa</button>
        </td>
    </tr>
    <tr>
        <td><strong>Y tế - Chăm sóc sức khỏe</strong></td>
        <td><span class="badge bg-light text-dark border">HEALTHCARE</span></td>
        <td>17/01/2023</td>
        <td class="text-end">
            <button class="btn btn-sm btn-outline-primary me-1"><i class="fa-solid fa-pen-to-square"></i> Sửa</button>
            <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i> Xóa</button>
        </td>
    </tr>
    <tr>
        <td><strong>Vận Tải - Lái xe - Giao nhận</strong></td>
        <td><span class="badge bg-light text-dark border">LOGISTICS</span></td>
        <td>18/01/2023</td>
        <td class="text-end">
            <button class="btn btn-sm btn-outline-primary me-1"><i class="fa-solid fa-pen-to-square"></i> Sửa</button>
            <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i> Xóa</button>
        </td>
    </tr>
    <tr>
        <td><strong>Thu mua - Kho Vận - Chuỗi cung ứng</strong></td>
        <td><span class="badge bg-light text-dark border">SUPPLY_CHAIN</span></td>
        <td>19/01/2023</td>
        <td class="text-end">
            <button class="btn btn-sm btn-outline-primary me-1"><i class="fa-solid fa-pen-to-square"></i> Sửa</button>
            <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i> Xóa</button>
        </td>
    </tr>
</tbody>
                </table>
            </div>
            <!-- Thanh chuyển trang nằm trung tâm -->
            <nav class="mt-4">
                <ul class="pagination justify-content-center mb-0">
                    <li class="page-item disabled"><a class="page-link" href="#">Trước</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">Sau</a></li>
                </ul>
            </nav>
        </div>

        <!-- TAB 2: ĐỊA ĐIỂM -->
        <div class="tab-pane fade" id="location-tab-pane" role="tabpanel" aria-labelledby="location-tab" tabindex="0">
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
                        <tr>
                            <td><strong>Thành phố Cần Thơ</strong></td>
                            <td>CT</td>
                            <td>10/01/2023</td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-primary me-1"><i class="fa-solid fa-pen-to-square"></i> Sửa</button>
                                <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i> Xóa</button>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Thành phố Hồ Chí Minh</strong></td>
                            <td>HCM</td>
                            <td>12/01/2023</td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-primary me-1"><i class="fa-solid fa-pen-to-square"></i> Sửa</button>
                                <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i> Xóa</button>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Thành phố Hà Nội</strong></td>
                            <td>HN</td>
                            <td>15/01/2023</td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-primary me-1"><i class="fa-solid fa-pen-to-square"></i> Sửa</button>
                                <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i> Xóa</button>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Thành phố Đà Nẵng</strong></td>
                            <td>DN</td>
                            <td>18/01/2023</td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-primary me-1"><i class="fa-solid fa-pen-to-square"></i> Sửa</button>
                                <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i> Xóa</button>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Tỉnh Bình Dương</strong></td>
                            <td>BD</td>
                            <td>20/01/2023</td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-primary me-1"><i class="fa-solid fa-pen-to-square"></i> Sửa</button>
                                <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i> Xóa</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- Thanh chuyển trang nằm trung tâm -->
            <nav class="mt-4">
                <ul class="pagination justify-content-center mb-0">
                    <li class="page-item disabled"><a class="page-link" href="#">Trước</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">Sau</a></li>
                </ul>
            </nav>
        </div>

        <!-- TAB 3: TIÊU CHÍ LỌC NÂNG CAO -->
        <div class="tab-pane fade" id="filters-tab-pane" role="tabpanel" aria-labelledby="filters-tab" tabindex="0">
            <h5 class="fw-bold mb-3 text-secondary">Danh mục Tiêu chí bộ lọc nâng cao (Trang chủ)</h5>
            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Tên giá trị lọc</th>
                            <th>Phân loại bộ lọc</th>
                            <th>Mã hệ thống</th>
                            <th class="text-end">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Nhóm mức lương -->
                        <tr>
                            <td><strong>Dưới 10 triệu</strong></td>
                            <td><span class="badge bg-primary-subtle text-primary">Mức lương</span></td>
                            <td>SALARY_UNDER_10M</td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-primary me-1"><i class="fa-solid fa-pen-to-square"></i> Sửa</button>
                                <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i> Xóa</button>
                            </td>
                        </tr>
                        <!-- Nhóm cấp bậc -->
                        <tr>
                            <td><strong>Nhân viên / Chuyên viên</strong></td>
                            <td><span class="badge bg-success-subtle text-success">Cấp bậc</span></td>
                            <td>LEVEL_STAFF</td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-primary me-1"><i class="fa-solid fa-pen-to-square"></i> Sửa</button>
                                <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i> Xóa</button>
                            </td>
                        </tr>
                        <!-- Nhóm hình thức -->
                        <tr>
                            <td><strong>Toàn thời gian (Full-time)</strong></td>
                            <td><span class="badge bg-info-subtle text-info">Hình thức</span></td>
                            <td>TYPE_FULL_TIME</td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-primary me-1"><i class="fa-solid fa-pen-to-square"></i> Sửa</button>
                                <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i> Xóa</button>
                            </td>
                        </tr>
                        <!-- Nhóm kinh nghiệm -->
                        <tr>
                            <td><strong>1 - 3 năm kinh nghiệm</strong></td>
                            <td><span class="badge bg-warning-subtle text-warning">Kinh nghiệm</span></td>
                            <td>EXP_1_TO_3_YEARS</td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-primary me-1"><i class="fa-solid fa-pen-to-square"></i> Sửa</button>
                                <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i> Xóa</button>
                            </td>
                        </tr>
                        <!-- Nhóm thời gian -->
                        <tr>
                            <td><strong>Trong vòng 3 ngày qua</strong></td>
                            <td><span class="badge bg-secondary-subtle text-secondary">Thời gian đăng</span></td>
                            <td>TIME_LAST_3_DAYS</td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-primary me-1"><i class="fa-solid fa-pen-to-square"></i> Sửa</button>
                                <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i> Xóa</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- Thanh chuyển trang nằm trung tâm -->
            <nav class="mt-4">
                <ul class="pagination justify-content-center mb-0">
                    <li class="page-item disabled"><a class="page-link" href="#">Trước</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">Sau</a></li>
                </ul>
            </nav>
        </div>
        
    </div>
    </div>
</div>

<script src="../../public/js/js/bootstrap.bundle.min.js"></script>
</body>
</html>