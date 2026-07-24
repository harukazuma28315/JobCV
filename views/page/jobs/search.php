<!-- views/jobs/search.php -->
<?php
$baseUrl = '/JobCV';
include_once __DIR__ . '/../layouts/header.php';
?>

<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            
            <form action="<?= $baseUrl ?>/index.php" method="GET" class="p-4 bg-primary-blue rounded-4 shadow-lg" autocomplete="off">

                <!-- Giữ route MVC khi submit form -->
                <input type="hidden" name="route" value="jobs/list">
                
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

                <!-- 2: BỘ LỌC ĐỊA ĐIỂM, NGÀNH NGHỀ & VỊ TRÍ -->
                <div class="row g-3 mb-3">

                    <!-- Chọn địa điểm -->
                    <div class="col-12 col-md-4">
                        <select name="location" class="form-select border-0 py-2 fw-semibold">
                            <option value="">Chọn Địa Điểm</option>

                            <?php 
                            $locations = [
                                'Cần Thơ' => 'Cần Thơ',
                                'Hồ Chí Minh' => 'Hồ Chí Minh',
                                'Hà Nội' => 'Hà Nội',
                                'Đà Nẵng' => 'Đà Nẵng'
                            ];

                            foreach ($locations as $val => $lbl): 
                                $selected = (($_GET['location'] ?? '') === $val) ? 'selected' : '';
                            ?>
                                <option value="<?= htmlspecialchars($val) ?>" <?= $selected ?>>
                                    <?= htmlspecialchars($lbl) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Chọn ngành nghề -->
                    <div class="col-12 col-md-4">
                        <select name="category" class="form-select border-0 py-2 fw-semibold">
                            <option value="">Chọn Ngành Nghề</option>

                            <?php if (!empty($categories)): ?>
                                <?php foreach ($categories as $category): ?>

                                    <?php 
                                    $selected = (($_GET['category'] ?? '') === $category['MaDanhMuc'])
                                        ? 'selected'
                                        : '';
                                    ?>

                                    <option 
                                        value="<?= htmlspecialchars($category['MaDanhMuc']) ?>"
                                        <?= $selected ?>
                                    >
                                        <?= htmlspecialchars($category['TenDanhMuc']) ?>
                                    </option>

                                <?php endforeach; ?>
                            <?php endif; ?>

                        </select>
                    </div>

                    <!-- Chọn vị trí tuyển dụng -->
                    <div class="col-12 col-md-4">
                        <select name="position" class="form-select border-0 py-2 fw-semibold">

                            <option value="">Chọn Vị Trí Tuyển Dụng</option>

                            <?php if (!empty($positions)): ?>
                                <?php foreach ($positions as $position): ?>

                                    <?php 
                                    $selected = (($_GET['position'] ?? '') === $position)
                                        ? 'selected'
                                        : '';
                                    ?>

                                    <option 
                                        value="<?= htmlspecialchars($position) ?>"
                                        <?= $selected ?>
                                    >
                                        <?= htmlspecialchars($position) ?>
                                    </option>

                                <?php endforeach; ?>
                            <?php endif; ?>

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
                                <option value="Fresher" <?= ($_GET['level'] ?? '') == 'Fresher' ? 'selected' : '' ?>>
                                    Fresher
                                </option>

                                <option value="Junior" <?= ($_GET['level'] ?? '') == 'Junior' ? 'selected' : '' ?>>
                                    Junior
                                </option>

                                <option value="Middle" <?= ($_GET['level'] ?? '') == 'Middle' ? 'selected' : '' ?>>
                                    Middle
                                </option>

                                <option value="Senior" <?= ($_GET['level'] ?? '') == 'Senior' ? 'selected' : '' ?>>
                                    Senior
                                </option>
                            </select>
                        </div>

                        <!-- Lọc hình thức làm việc -->
                        <div class="col-6 col-sm-3 col-md-2">
                            <label class="form-label text-white-50 small fw-semibold mb-1">Hình Thức</label>
                            <select name="job_type" class="form-select border-0 py-2 small text-dark fw-semibold">
                                <option value="">Tất cả hình thức</option>
                                <option value="Full-time" <?= ($_GET['job_type'] ?? '') == 'Full-time' ? 'selected' : '' ?>>
                                    Full-time
                                </option>

                                <option value="Remote" <?= ($_GET['job_type'] ?? '') == 'Remote' ? 'selected' : '' ?>>
                                    Remote
                                </option>

                                <option value="Hybrid" <?= ($_GET['job_type'] ?? '') == 'Hybrid' ? 'selected' : '' ?>>
                                    Hybrid
                                </option>

                                <option value="Part-time" <?= ($_GET['job_type'] ?? '') == 'Part-time' ? 'selected' : '' ?>>
                                    Part-time
                                </option>
                            </select>
                        </div>

                        <!-- Lọc kinh nghiệm -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <label class="form-label text-white-50 small fw-semibold mb-1">Kinh Nghiệm</label>
                            <select name="experience" class="form-select border-0 py-2 small text-dark fw-semibold">
                                <option value="">Tất cả kinh nghiệm</option>

                                <option value="0" <?= ($_GET['experience'] ?? '') === '0' ? 'selected' : '' ?>>
                                    Không yêu cầu kinh nghiệm
                                </option>

                                <option value="1" <?= ($_GET['experience'] ?? '') === '1' ? 'selected' : '' ?>>
                                    Tối đa 1 năm
                                </option>

                                <option value="3" <?= ($_GET['experience'] ?? '') === '3' ? 'selected' : '' ?>>
                                    Tối đa 3 năm
                                </option>

                                <option value="5" <?= ($_GET['experience'] ?? '') === '5' ? 'selected' : '' ?>>
                                    Tối đa 5 năm
                                </option>
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
                                    <img src="<?= !empty($job['company_logo']) ? htmlspecialchars($job['company_logo']) : 'assets//images/default-logo.png' ?>" class="company-logo" alt="Logo" style="width: 60px; height: 60px; object-fit: contain; border-radius: 8px;">
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
<?php include_once __DIR__ . '/../layouts/footer.php'; ?>