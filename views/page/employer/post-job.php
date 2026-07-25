<!-- Nhúng Header chung -->
<?php 
$total = $jobs->num_rows;


include_once __DIR__ . '/../layouts/header.php'; 
?>

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
                            <span class="badge bg-light text-dark">

                            Tổng số tin:

                            <?= $total ?>

                            </span>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-middle table-hover">
                                <thead>
                                    <tr>
                                        <th>Tiêu đề</th>
                                        <th>Ngày đăng</th>
                                        <th>Hạn nộp</th>
                                        <th>Ứng viên</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($job = $jobs->fetch_assoc()): ?>
                                            <tr>
                                                <td class="fw-semibold"><?= htmlspecialchars($job['TieuDe']) ?></td>
                                                <td><?= date('d/m/Y', strtotime($job['NgayDang'])) ?></td>
                                                <td><?= date('d/m/Y', strtotime($job['NgayHetHan'])) ?></td>
                                                <td>
                                                    <span class="badge bg-primary">
                                                        <?= (int)$job['SoUngVien'] ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if ($job['TrangThai'] === 'DangMo'): ?>
                                                        <span class="badge bg-success">Đang mở</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary"><?= htmlspecialchars($job['TrangThai']) ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-outline-primary">Sửa</a>
                                                    <!-- Thêm nút đóng/xóa sau -->
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
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

                        <form action="/JobCV/index.php?route=jobs/create" method="POST">
                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-dark">Tiêu đề tin tuyển dụng <span class="text-danger">*</span></label>
                                    <input type="text" name="tieuDe" class="form-control py-2" placeholder="Ví dụ: Thực Tập Sinh Web Developer (PHP/Laravel)" required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold text-dark">Lĩnh vực / Ngành nghề <span class="text-danger">*</span></label>
                                    <select name="category" class="form-select py-2" required>
                                        <option value="">-- Chọn ngành nghề --</option>
                                        <?php if (!empty($categories)): ?>
                                            <?php foreach ($categories as $category): ?>
                                                <option value="<?= htmlspecialchars($category['MaDanhMuc']) ?>">
                                                    <?= htmlspecialchars($category['TenDanhMuc']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold text-dark">Hình thức làm việc <span class="text-danger">*</span></label>
                                    <select name="hinhThucLamViec" class="form-select py-2" required>
                                        <option value="">-- Chọn hình thức --</option>
                                        <option value="Full-time">Toàn thời gian (Full-time)</option>
                                        <option value="Part-time">Bán thời gian (Part-time)</option>
                                        <option value="Internship" selected>Thực tập sinh (Internship)</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold text-dark">Mức lương mong muốn <span class="text-danger">*</span></label>
                                    <input type="number"
                                            name="mucLuong"
                                            class="form-control py-2"
                                            placeholder="Ví dụ: 15000000"
                                            min="0"
                                            required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold text-dark">Hạn chót nhận hồ sơ <span class="text-danger">*</span></label>
                                        <input type="date"
                                                name="ngayHetHan"
                                                class="form-control py-2"
                                                required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-dark">Địa điểm làm việc <span class="text-danger">*</span></label>
                                    <input type="text"
                                            name="diaChiLamViec"
                                            class="form-control py-2"
                                            placeholder="Ví dụ: Quận Ninh Kiều, Cần Thơ"
                                            required>
                                </div>
                            </div>

                            <h6 class="fw-bold text-primary-blue mb-3 text-uppercase border-top pt-4">Nội dung chi tiết & Tiêu chí lọc hồ sơ</h6>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-dark">Mô tả công việc (JD) <span class="text-danger">*</span></label>
                                    <textarea name="moTaCongViec"
                                            class="form-control"
                                            rows="4"
                                            required></textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-dark">Yêu cầu ứng viên (Tiêu chí sơ tuyển) <span class="text-danger">*</span></label>
                                    <textarea name="yeuCauCongViec"
                                            class="form-control"
                                            rows="4"
                                            required></textarea>
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
<?php include_once __DIR__ . '/../layouts/footer.php'; ?>