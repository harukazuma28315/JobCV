<!-- Nhúng Header chung -->
<?php include_once '../../views/layouts/header.php'; ?>

<div class="container py-5">
    <div class="row g-4">
        <!-- Cột trái: Tóm tắt thông tin nhanh -->
        <div class="col-12 col-lg-3">
            <div class="card border-0 shadow-sm p-4 text-center bg-white">
                <img src="https://api.dicebear.com/7.x/adventurer/svg?seed=ntl" alt="Avatar" class="rounded-circle border border-3 border-primary mx-auto mb-3" style="width: 90px; height: 90px; object-fit: cover;">
                <h5 class="fw-bold text-dark mb-1">Nguyễn Thị Liễu</h5> <!-- -->
                <p class="text-muted small mb-3">Ứng viên</p>
                <hr>
                <div class="text-start">
                    <p class="small text-muted mb-2"><i class="fa-solid fa-briefcase me-2 text-primary-blue"></i> Đã nộp: <strong>3 chiến dịch</strong></p>
                    <p class="small text-muted mb-0"><i class="fa-solid fa-circle-check me-2 text-success"></i> Được gọi: <strong>1 cuộc hẹn</strong></p>
                </div>
                <a href="profile.php" class="btn btn-outline-primary border-primary text-primary-blue btn-sm w-100 mt-3 fw-semibold">Quay lại hồ sơ</a>
            </div>
        </div>

        <!-- Cột phải: Danh sách tin tuyển dụng đã nộp -->
        <div class="col-12 col-lg-9">
            <div class="card border-0 shadow-sm p-4 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <h4 class="fw-bold text-dark mb-0"><i class="fa-solid fa-paper-plane text-success me-2"></i>Lịch Sử Ứng Tuyển</h4>
                    <span class="badge bg-primary-blue px-3 py-2">3 Hồ sơ đã gửi</span>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light text-secondary">
                            <tr>
                                <th scope="col" style="min-width: 250px;">Công việc / Công ty</th>
                                <th scope="col">Ngày nộp</th>
                                <th scope="col">CV sử dụng</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col" class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Công việc 1 -->
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-light p-2 rounded border" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fa-solid fa-building text-primary fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-1">Thực Tập Sinh Web Developer (PHP/Laravel)</h6>
                                            <span class="text-muted small">FPT Software</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-muted small">14/07/2026</td>
                                <td>
                                    <a href="#" class="text-decoration-none text-danger small fw-semibold">
                                        <i class="fa-solid fa-file-pdf me-1"></i>CV_ThiLieu_Dev.pdf
                                    </a>
                                </td>
                                <td>
                                    <span class="badge bg-warning text-dark px-2.5 py-1.5"><i class="fa-regular fa-clock me-1"></i>Chờ phản hồi</span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-light btn-sm text-primary-blue" title="Xem chi tiết tin đăng"><i class="fa-solid fa-eye"></i></button>
                                </td>
                            </tr>

                            <!-- Công việc 2 -->
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-light p-2 rounded border" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fa-solid fa-building text-danger fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-1">Junior Frontend Developer</h6>
                                            <span class="text-muted small">VNG Corporation</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-muted small">10/07/2026</td>
                                <td>
                                    <a href="#" class="text-decoration-none text-danger small fw-semibold">
                                        <i class="fa-solid fa-file-pdf me-1"></i>CV_ThiLieu_Dev.pdf
                                    </a>
                                </td>
                                <td>
                                    <span class="badge bg-success px-2.5 py-1.5"><i class="fa-solid fa-circle-check me-1"></i>Đã duyệt hồ sơ</span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-light btn-sm text-primary-blue" title="Xem chi tiết tin đăng"><i class="fa-solid fa-eye"></i></button>
                                </td>
                            </tr>

                            <!-- Công việc 3 -->
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-light p-2 rounded border" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fa-solid fa-building text-success fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-1">Python Backend Trainee</h6>
                                            <span class="text-muted small">KMS Technology</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-muted small">01/07/2026</td>
                                <td>
                                    <a href="#" class="text-decoration-none text-danger small fw-semibold">
                                        <i class="fa-solid fa-file-pdf me-1"></i>CV_Fullstack_2026.pdf
                                    </a>
                                </td>
                                <td>
                                    <span class="badge bg-danger px-2.5 py-1.5"><i class="fa-solid fa-circle-xmark me-1"></i>Chưa phù hợp</span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-light btn-sm text-primary-blue" title="Xem chi tiết tin đăng"><i class="fa-solid fa-eye"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Nhúng Footer chung -->
<?php include_once '../../views/layouts/footer.php'; ?>