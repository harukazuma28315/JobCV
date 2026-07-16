<!-- Nhúng Header chung -->
<?php include_once '../../views/layouts/header.php'; ?>

<div class="container py-5">
    <!-- Header chức năng -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">
                <i class="fa-solid fa-users-gear text-primary-blue me-2"></i>Hệ thống Quản lý Hồ sơ Ứng viên (ATS)
            </h4>
            <p class="text-muted small mb-0">Theo dõi, sơ loại và tương tác tự động với ứng viên nhanh chóng.</p>
        </div>
        <span class="badge bg-primary-blue px-3 py-2">Hệ thống lọc tự động đang bật</span>
    </div>

    <!-- Bộ lọc theo chiến dịch -->
    <div class="card border-0 shadow-sm p-3 mb-4 bg-white rounded-3">
        <form class="row g-2 align-items-center">
            <div class="col-12 col-md-5">
                <label class="form-label small fw-semibold text-muted mb-1">Lọc theo chiến dịch tuyển dụng</label>
                <select class="form-select form-select-sm">
                    <option value="">-- Tất cả chiến dịch đang tuyển --</option>
                    <option value="1" selected>Thực Tập Sinh Web Developer (PHP/Laravel)</option>
                    <option value="2">Junior Frontend Developer</option>
                </select>
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label small fw-semibold text-muted mb-1">Bộ lọc nhanh trạng thái</label>
                <select class="form-select form-select-sm">
                    <option value="">Tất cả trạng thái</option>
                    <option value="1">Vừa ứng tuyển (Chờ sơ loại)</option>
                    <option value="2">Đã hẹn Phỏng vấn</option>
                    <option value="3">Đồng ý nhận việc (Offer Sent)</option>
                    <option value="4">Từ chối hồ sơ</option>
                </select>
            </div>
            <div class="col-12 col-md-3 d-flex align-items-end h-100 mt-md-4">
                <button type="button" class="btn btn-primary-blue btn-sm w-100 fw-bold py-2"><i class="fa-solid fa-filter me-1"></i> Áp dụng</button>
            </div>
        </form>
    </div>

    <!-- Danh sách Hồ sơ ATS -->
    <div class="card border-0 shadow-sm p-4 bg-white rounded-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light text-secondary">
                    <tr>
                        <th scope="col" style="min-width: 220px;">Ứng viên</th>
                        <th scope="col">CV Đính kèm</th>
                        <th scope="col">Điểm đánh giá</th>
                        <th scope="col">Trạng thái ATS</th>
                        <th scope="col" class="text-center" style="min-width: 200px;">Hành động quy trình</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Ứng viên 1: Nguyễn Thị Liễu -->
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <img src="https://api.dicebear.com/7.x/adventurer/svg?seed=ntl" alt="Avatar" class="rounded-circle border" style="width: 45px; height: 45px; object-fit: cover;">
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">Nguyễn Thị Liễu</h6>
                                    <span class="text-muted small">thilieu.student@gmail.com</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="#" class="text-decoration-none text-danger small fw-semibold" title="Tải xuống CV">
                                <i class="fa-solid fa-file-pdf me-1"></i>CV_ThiLieu_Dev.pdf
                            </a>
                        </td>
                        <td>
                            <span class="badge bg-light text-success border border-success px-2 py-1"><i class="fa-solid fa-star me-1 text-warning"></i> 8.5/10</span>
                        </td>
                        <td>
                            <span class="badge bg-warning text-dark px-2.5 py-1.5" id="status-1"><i class="fa-regular fa-clock me-1"></i>Vừa nộp - Chờ sơ loại</span>
                        </td>
                        <td>
                            <div class="d-flex gap-1 justify-content-center">
                                <!-- Nút Sơ loại nhanh -->
                                <button class="btn btn-outline-success btn-sm px-2 fw-semibold" title="Duyệt sơ loại & Hẹn phỏng vấn" onclick="alert('Đã gửi email hẹn lịch phỏng vấn tự động đến ứng viên Nguyễn Thị Liễu!')">
                                    <i class="fa-solid fa-calendar-check me-1"></i> Hẹn Phỏng Vấn
                                </button>
                                <!-- Nút Từ chối nhanh -->
                                <button class="btn btn-outline-danger btn-sm px-2" title="Chuyển trạng thái Từ Chối & gửi Mail cảm ơn" onclick="alert('Đã gửi email thông báo từ chối lịch sự tự động.')">
                                    <i class="fa-solid fa-user-xmark"></i> Từ Chối
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Ứng viên 2 -->
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <img src="https://api.dicebear.com/7.x/adventurer/svg?seed=anhduc" alt="Avatar" class="rounded-circle border" style="width: 45px; height: 45px; object-fit: cover;">
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">Trần Anh Đức</h6>
                                    <span class="text-muted small">anhduc.dev@gmail.com</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="#" class="text-decoration-none text-danger small fw-semibold" title="Tải xuống CV">
                                <i class="fa-solid fa-file-pdf me-1"></i>CV_AnhDuc_Frontend.pdf
                            </a>
                        </td>
                        <td>
                            <span class="badge bg-light text-muted border px-2 py-1"><i class="fa-solid fa-star me-1"></i> 6.0/10</span>
                        </td>
                        <td>
                            <span class="badge bg-success px-2.5 py-1.5"><i class="fa-solid fa-calendar-days me-1"></i>Đã hẹn Phỏng vấn</span>
                        </td>
                        <td>
                            <div class="d-flex gap-1 justify-content-center">
                                <!-- Nhận việc / Gửi Offer -->
                                <button class="btn btn-success btn-sm px-2 fw-semibold" onclick="alert('Đã chuyển sang vòng Nhận việc, chuẩn bị gửi Mail Offer!')">
                                    <i class="fa-solid fa-circle-check me-1"></i> Nhận Việc (Offer)
                                </button>
                                <button class="btn btn-outline-danger btn-sm px-2" title="Từ chối">
                                    <i class="fa-solid fa-user-xmark"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Nhúng Footer chung -->
<?php include_once '../../views/layouts/footer.php'; ?>