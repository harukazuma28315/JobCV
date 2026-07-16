<!-- views/jobs/search.php -->
<?php include_once '../../views/layouts/header.php'; ?>
<?php
// Giả lập dữ liệu để test giao diện khi chưa kết nối Database thực tế, khi kết nối Database, Controller sẽ truyền biến $jobs qua và ta chỉ cần xóa đoạn khởi tạo này đi.
if (!isset($jobs) || empty($jobs)) {
    $jobs = [
        [
            'title' => 'Lập Trình Viên PHP (Laravel / MVC)',
            'company_name' => 'Công Nghệ Số Cần Thơ (CTTech)',
            'company_logo' => 'https://placehold.co/80x80/0d6efd/ffffff?text=CTTech',
            'salary_text' => '12 - 18 Triệu',
            'location_text' => 'Ninh Kiều, Cần Thơ',
            'experience_text' => '1 - 3 năm kinh nghiệm'
        ],
        [
            'title' => 'Chuyên Viên Thiết Kế Giao Diện UI/UX',
            'company_name' => 'Nippon Design Agency',
            'company_logo' => 'https://placehold.co/80x80/e11d48/ffffff?text=NDA',
            'salary_text' => 'Thỏa thuận',
            'location_text' => 'Quận 1, TP. Hồ Chí Minh',
            'experience_text' => 'Dưới 1 năm kinh nghiệm'
        ],
        [
            'title' => 'Thực Tập Sinh Lập Trình Web (Front-end)',
            'company_name' => 'Sông Hậu Media & Software',
            'company_logo' => 'https://placehold.co/80x80/16a34a/ffffff?text=SHM',
            'salary_text' => 'Hỗ trợ 3 - 5 Triệu',
            'location_text' => 'Cần Thơ',
            'experience_text' => 'Không yêu cầu kinh nghiệm'
        ]
    ];
}
?>

<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            
            <form action="" method="GET" class="p-4 bg-primary-blue rounded-4 shadow-lg" autocomplete="off">
                <!-- Các input ẩn để không mất routing của MVC khi submit GET -->
                <input type="hidden" name="controller" value="job">
                <input type="hidden" name="action" value="search">
                
                <!-- 1: NHẬP TỪ KHÓA -->
                <div class="row mb-3 justify-content-center">
                    <div class="col-12 col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-0 py-2"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                            <input type="text" name="keyword" class="form-control border-0 py-2 text-center" 
                                   value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>" 
                                   placeholder="Nhập từ khóa (Vị trí, kỹ năng, tên công ty...)">
                        </div>
                    </div>
                </div>

                <!-- 2: BỘ LỌC ĐỊA ĐIỂM & NGÀNH NGHỀ -->
                <div class="row g-3 mb-3">
                    <!-- Chọn địa điểm -->
                    <div class="col-12 col-md-6">
                        <select name="location" class="form-select border-0 py-2 fw-semibold">
                            <option value="">Chọn Địa Điểm</option>
                            <?php 
                            $locations = ['can-tho' => 'Cần Thơ', 'hcm' => 'TP. Hồ Chí Minh', 'ha-noi' => 'Hà Nội', 'da-nang' => 'Đà Nẵng'];
                            foreach ($locations as $val => $lbl): 
                                $selected = (isset($_GET['location']) && $_GET['location'] == $val) ? 'selected' : '';
                            ?>
                                <option value="<?= $val ?>" <?= $selected ?>><?= $lbl ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!-- Chọn ngành nghề -->
                    <div class="col-12 col-md-6">
                        <select name="category" class="form-select border-0 py-2 fw-semibold">
                            <option value="">Chọn Ngành Nghề</option>
                            <?php 
                            $categories = ['it' => 'Công nghệ thông tin', 'marketing' => 'Marketing', 'finance' => 'Tài chính / Kế toán', 'sales' => 'Kinh doanh / Bán hàng'];
                            foreach ($categories as $val => $lbl): 
                                $selected = (isset($_GET['category']) && $_GET['category'] == $val) ? 'selected' : '';
                            ?>
                                <option value="<?= $val ?>" <?= $selected ?>><?= $lbl ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- 3: NÚT TÌM VIỆC & BỘ LỌC NÂNG CAO -->
                <div class="row">
                    <div class="col-12 d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-warning fw-bold text-dark px-4 py-2">Tìm Việc</button>
                        <button type="button" class="btn btn-outline-light py-2 px-3" data-bs-toggle="collapse" data-bs-target="#advancedFilter" aria-expanded="false" title="Bộ lọc nâng cao">
                            <i class="fa-solid fa-sliders"></i>
                        </button>
                    </div>
                </div>

                <!-- 4: BỘ LỌC NÂNG CAO -->
                <div class="collapse <?= (isset($_GET['salary']) || isset($_GET['level']) || isset($_GET['job_type']) || isset($_GET['experience']) || isset($_GET['posted_date'])) ? 'show' : '' ?>" id="advancedFilter">
                    <div class="row g-3 mt-2 pt-3 border-top border-white-50">
                        <!-- Lọc mức lương -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <label class="form-label text-white-50 small fw-semibold mb-1">Mức Lương</label>
                            <select name="salary" class="form-select border-0 py-2 small text-dark fw-semibold">
                                <option value="">Tất cả mức lương</option>
                                <option value="under-10" <?= (isset($_GET['salary']) && $_GET['salary'] == 'under-10') ? 'selected' : '' ?>>Dưới 10 triệu</option>
                                <option value="10-15" <?= (isset($_GET['salary']) && $_GET['salary'] == '10-15') ? 'selected' : '' ?>>10 - 15 triệu</option>
                                <option value="15-20" <?= (isset($_GET['salary']) && $_GET['salary'] == '15-20') ? 'selected' : '' ?>>15 - 20 triệu</option>
                                <option value="over-20" <?= (isset($_GET['salary']) && $_GET['salary'] == 'over-20') ? 'selected' : '' ?>>Trên 20 triệu</option>
                            </select>
                        </div>

                        <!-- Lọc cấp bậc -->
                        <div class="col-6 col-sm-3 col-md-2">
                            <label class="form-label text-white-50 small fw-semibold mb-1">Cấp Bậc</label>
                            <select name="level" class="form-select border-0 py-2 small text-dark fw-semibold">
                                <option value="">Tất cả cấp bậc</option>
                                <option value="intern" <?= (isset($_GET['level']) && $_GET['level'] == 'intern') ? 'selected' : '' ?>>Thực tập sinh</option>
                                <option value="fresher" <?= (isset($_GET['level']) && $_GET['level'] == 'fresher') ? 'selected' : '' ?>>Mới tốt nghiệp</option>
                                <option value="junior" <?= (isset($_GET['level']) && $_GET['level'] == 'junior') ? 'selected' : '' ?>>Nhân viên</option>
                                <option value="senior" <?= (isset($_GET['level']) && $_GET['level'] == 'senior') ? 'selected' : '' ?>>Trưởng nhóm</option>
                            </select>
                        </div>

                        <!-- Lọc hình thức làm việc -->
                        <div class="col-6 col-sm-3 col-md-2">
                            <label class="form-label text-white-50 small fw-semibold mb-1">Hình Thức</label>
                            <select name="job_type" class="form-select border-0 py-2 small text-dark fw-semibold">
                                <option value="">Tất cả hình thức</option>
                                <option value="onsite" <?= (isset($_GET['job_type']) && $_GET['job_type'] == 'onsite') ? 'selected' : '' ?>>Tại văn phòng</option>
                                <option value="remote" <?= (isset($_GET['job_type']) && $_GET['job_type'] == 'remote') ? 'selected' : '' ?>>Từ xa (Remote)</option>
                                <option value="hybrid" <?= (isset($_GET['job_type']) && $_GET['job_type'] == 'hybrid') ? 'selected' : '' ?>>Linh hoạt (Hybrid)</option>
                            </select>
                        </div>

                        <!-- Lọc kinh nghiệm -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <label class="form-label text-white-50 small fw-semibold mb-1">Kinh Nghiệm</label>
                            <select name="experience" class="form-select border-0 py-2 small text-dark fw-semibold">
                                <option value="">Tất cả kinh nghiệm</option>
                                <option value="no-experience" <?= (isset($_GET['experience']) && $_GET['experience'] == 'no-experience') ? 'selected' : '' ?>>Không yêu cầu</option>
                                <option value="under-1" <?= (isset($_GET['experience']) && $_GET['experience'] == 'under-1') ? 'selected' : '' ?>>Dưới 1 năm</option>
                                <option value="1-3" <?= (isset($_GET['experience']) && $_GET['experience'] == '1-3') ? 'selected' : '' ?>>1 - 3 năm</option>
                            </select>
                        </div>

                        <!-- Lọc thời gian đăng tin -->
                        <div class="col-12 col-sm-6 col-md-2">
                            <label class="form-label text-white-50 small fw-semibold mb-1">Thời Gian</label>
                            <select name="posted_date" class="form-select border-0 py-2 small text-dark fw-semibold">
                                <option value="">Mọi thời gian</option>
                                <option value="24h" <?= (isset($_GET['posted_date']) && $_GET['posted_date'] == '24h') ? 'selected' : '' ?>>Trong 24 giờ</option>
                                <option value="1week" <?= (isset($_GET['posted_date']) && $_GET['posted_date'] == '1week') ? 'selected' : '' ?>>Trong 1 tuần</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- DANH SÁCH VIỆC LÀM -->
<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            
            <!-- XỬ LÝ ĐIỀU KIỆN HIỂN THỊ -->
            <?php if (!empty($jobs)): ?>
                <!-- TĐề kết quả tìm kiếm -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">Tìm thấy <span class="text-primary-blue" style="color: var(--primary-blue, #0d6efd);"><?= count($jobs) ?></span> việc làm phù hợp</h5>
                    <select class="form-select form-select-sm w-auto border-0 shadow-sm fw-semibold">
                        <option>Tin mới nhất</option>
                        <option>Lương cao nhất</option>
                    </select>
                </div>

                <!-- danh sách công việc -->
                <div class="d-flex flex-column gap-3">
                    <?php foreach ($jobs as $job): ?>
                        <div class="card job-card border-0 shadow-sm p-3 rounded-3 bg-white">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <img src="<?= !empty($job['company_logo']) ? htmlspecialchars($job['company_logo']) : 'assets/images/default-logo.png' ?>" class="company-logo" alt="Logo" style="width: 60px; height: 60px; object-fit: contain; border-radius: 8px;">
                                </div>
                                <div class="col">
                                    <h5 class="fw-bold mb-1 text-dark"><?= htmlspecialchars($job['title']) ?></h5>
                                    <p class="text-muted small mb-2"><i class="fa-regular fa-building me-1"></i> <?= htmlspecialchars($job['company_name']) ?></p>
                                    <div class="d-flex flex-wrap gap-2">
                                        <span class="badge bg-light text-success fw-semibold border"><i class="fa-solid fa-money-bill-wave me-1"></i> <?= htmlspecialchars($job['salary_text']) ?></span>
                                        <span class="badge bg-light text-secondary fw-semibold border"><i class="fa-solid fa-location-dot me-1"></i> <?= htmlspecialchars($job['location_text']) ?></span>
                                        <span class="badge bg-light text-secondary fw-semibold border"><i class="fa-solid fa-briefcase me-1"></i> <?= htmlspecialchars($job['experience_text']) ?></span>
                                    </div>
                                </div>
                                <div class="col-md-auto text-end d-flex flex-md-column align-items-center justify-content-between gap-3 mt-3 mt-md-0">
                                    <button class="btn btn-light btn-heart border-0 rounded-circle py-2 px-2" onclick="alertLoginRequirement()" title="Lưu việc làm">
                                        <i class="fa-regular fa-heart fs-5"></i>
                                    </button>
                                    <a href="detail.php?id=<?= isset($job['id']) ? $job['id'] : '' ?>" class="btn btn-outline-primary btn-sm px-3 fw-bold">Xem Chi Tiết</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <!-- TRƯỜNG HỢP KHÔNG CÓ DỮ LIỆU (EMPTY STATE) -->
                <div class="text-center py-5 bg-white rounded-4 shadow-sm">
                    <img src="https://placehold.co/150x150/f3f4f6/6b7280?text=Empty" class="mb-3 opacity-75" style="max-width: 120px;" alt="Không có dữ liệu">
                    <h5 class="fw-bold text-dark mb-2">Chưa có công việc nào được đăng tải</h5>
                    <p class="text-muted small">Vui lòng quay lại sau hoặc thử thay đổi điều kiện lọc tìm kiếm của bạn.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<script>
    function alertLoginRequirement() {
        alert("Bạn cần Đăng Nhập với tài khoản Ứng viên để thực hiện chức năng này!");
    }
</script>
<?php include_once '../../views/layouts/footer.php'; ?>