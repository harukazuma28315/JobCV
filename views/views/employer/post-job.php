<!-- Nhúng Header chung -->
<?php include_once '../../views/layouts/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            
            <!-- Điều hướng Tab giữa Quản lý và Đăng tin -->
            <ul class="nav nav-pills mb-4 gap-2 bg-white p-2 rounded-3 shadow-sm border" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold px-4 py-2.5" id="pills-manage-tab" data-bs-toggle="pill" data-bs-target="#pills-manage" type="button" role="tab">
                        <i class="fa-solid fa-list-check me-2"></i>Quản lý tin tuyển dụng
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold px-4 py-2.5 text-primary-blue" id="pills-post-tab" data-bs-toggle="pill" data-bs-target="#pills-post" type="button" role="tab">
                        <i class="fa-solid fa-file-signature me-2"></i>Đăng tin mới
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="pills-tabContent">
                
                <!-- TAB 1: DANH SÁCH QUẢN LÝ TIN ĐÃ ĐĂNG -->
                <div class="tab-pane fade show active" id="pills-manage" role="tabpanel">
                    <div class="card border-0 shadow-sm p-4 bg-white rounded-3">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-layer-group me-2 text-primary-blue"></i>Danh sách tin tuyển dụng</h5>
                            <span class="badge bg-light text-dark border">Tổng số tin: 2</span>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-middle table-hover">
                                <thead class="table-light text-secondary">
                                    <tr>
                                        <th scope="col" style="min-width: 250px;">Tin tuyển dụng</th>
                                        <th scope="col">Ngày đăng</th>
                                        <th scope="col">Hạn nộp</th>
                                        <th scope="col">Lượt ứng tuyển</th>
                                        <th scope="col">Trạng thái</th>
                                        <th scope="col" class="text-center">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Tin 1 -->
                                    <tr>
                                        <td>
                                            <h6 class="fw-bold text-dark mb-1">Thực Tập Sinh Web Developer (PHP/Laravel)</h6>
                                            <span class="text-muted small"><i class="fa-solid fa-money-bill-wave me-1 text-success"></i>Thỏa thuận | <i class="fa-solid fa-location-dot me-1"></i>Ninh Kiều</span>
                                        </td>
                                        <td class="small">15/06/2026</td>
                                        <td class="small text-danger fw-semibold">15/08/2026</td>
                                        <td>
                                            <a href="manage-candidates.php" class="badge bg-primary text-white text-decoration-none px-2.5 py-1.5" title="Xem danh sách ứng viên">
                                                <i class="fa-solid fa-users me-1"></i> 1 Ứng tuyển
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge bg-success"><i class="fa-solid fa-circle-check me-1"></i>Đang hoạt động</span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1 justify-content-center">
                                                <button class="btn btn-light btn-sm text-primary" title="Chỉnh sửa nội dung"><i class="fa-solid fa-pen-to-square"></i></button>
                                                <button class="btn btn-light btn-sm text-warning" title="Gia hạn tin"><i class="fa-regular fa-clock"></i></button>
                                                <button class="btn btn-light btn-sm text-danger" title="Tạm dừng/Đóng tuyển dụng"><i class="fa-solid fa-ban"></i></button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Tin 2 -->
                                    <tr>
                                        <td>
                                            <h6 class="fw-bold text-dark mb-1">Junior Frontend Developer</h6>
                                            <span class="text-muted small"><i class="fa-solid fa-money-bill-wave me-1 text-success"></i>10 - 15 triệu | <i class="fa-solid fa-location-dot me-1"></i>TP. HCM</span>
                                        </td>
                                        <td class="small">10/06/2026</td>
                                        <td class="small text-muted">10/07/2026</td>
                                        <td>
                                            <a href="manage-candidates.php" class="badge bg-secondary text-white text-decoration-none px-2.5 py-1.5">
                                                <i class="fa-solid fa-users me-1"></i> 1 Ứng tuyển
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge bg-danger"><i class="fa-solid fa-clock-rotate-left me-1"></i>Đã hết hạn</span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1 justify-content-center">
                                                <button class="btn btn-light btn-sm text-primary" title="Chỉnh sửa"><i class="fa-solid fa-pen-to-square"></i></button>
                                                <button class="btn btn-light btn-sm text-success" title="Kích hoạt lại / Gia hạn"><i class="fa-solid fa-rotate-left"></i></button>
                                                <button class="btn btn-light btn-sm text-danger" title="Xóa tin"><i class="fa-solid fa-trash-can"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- TAB 2: FORM ĐĂNG TIN TUYỂN DỤNG MỚI -->
                <div class="tab-pane fade" id="pills-post" role="tabpanel">
                    <div class="card border-0 shadow-sm p-4 bg-white rounded-3">
                        <div class="border-bottom pb-3 mb-4">
                            <h5 class="fw-bold text-dark mb-1"><i class="fa-solid fa-file-pen text-primary-blue me-2"></i>Đăng tin tuyển dụng mới</h5>
                            <p class="text-muted small mb-0">Vui lòng điền đầy đủ các tiêu chí lọc hồ sơ để hệ thống phân loại ứng viên tốt nhất.</p>
                        </div>

                        <form action="" method="POST">
                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-dark">Tiêu đề tin tuyển dụng <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control py-2" placeholder="Ví dụ: Thực Tập Sinh Web Developer (PHP/Laravel)" required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold text-dark">Lĩnh vực / Ngành nghề <span class="text-danger">*</span></label>
                                    <select class="form-select py-2" required>
                                        <option value="">-- Chọn ngành nghề --</option>
                                        <option value="it" selected>Công nghệ thông tin / Phần mềm</option>
                                        <option value="marketing">Marketing / Truyền thông</option>
                                        <option value="design">Thiết kế đồ họa / UI-UX</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold text-dark">Hình thức làm việc <span class="text-danger">*</span></label>
                                    <select class="form-select py-2" required>
                                        <option value="">-- Chọn hình thức --</option>
                                        <option value="Full-time">Toàn thời gian (Full-time)</option>
                                        <option value="Part-time">Bán thời gian (Part-time)</option>
                                        <option value="Internship" selected>Thực tập sinh (Internship)</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold text-dark">Mức lương mong muốn <span class="text-danger">*</span></label>
                                    <select class="form-select py-2" required>
                                        <option value="Thỏa thuận" selected>Thỏa thuận</option>
                                        <option value="Dưới 5 triệu">Dưới 5 triệu</option>
                                        <option value="5 - 10 triệu">5 - 10 triệu</option>
                                        <option value="10 - 20 triệu">10 - 20 triệu</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold text-dark">Hạn chót nhận hồ sơ <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control py-2" value="2026-08-15" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-dark">Địa điểm làm việc <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control py-2" placeholder="Ví dụ: Quận Ninh Kiều, Cần Thơ" required>
                                </div>
                            </div>

                            <h6 class="fw-bold text-primary-blue mb-3 text-uppercase border-top pt-4">Nội dung chi tiết & Tiêu chí lọc hồ sơ</h6>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-dark">Mô tả công việc (JD) <span class="text-danger">*</span></label>
                                    <textarea class="form-control" rows="4" placeholder="- Phát triển hệ thống Web PHP..." required></textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-dark">Yêu cầu ứng viên (Tiêu chí sơ tuyển) <span class="text-danger">*</span></label>
                                    <textarea class="form-control" rows="4" placeholder="- Kỹ năng chuyên môn, kỹ năng mềm..." required></textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-dark">Quyền lợi được hưởng <span class="text-danger">*</span></label>
                                    <textarea class="form-control" rows="3" placeholder="- Lương, thưởng, cơ hội thăng tiến..." required></textarea>
                                </div>
                            </div>

                            <div class="text-end pt-4 border-top mt-4">
                                <button type="reset" class="btn btn-light px-4 py-2 me-2">Nhập lại</button>
                                <button type="submit" class="btn btn-primary-blue fw-bold px-4 py-2">Đăng tuyển dụng ngay</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Nhúng Footer chung -->
<?php include_once '../../views/layouts/footer.php'; ?>