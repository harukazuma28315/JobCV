<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$baseUrl = '/JobCV';

include_once __DIR__ . '/../layouts/header.php';

$role = $_SESSION['user_role'] ?? 0;
?>

<?php if ($role == 0): ?>

<section class="position-relative py-5 d-flex align-items-center"
    style="background: url('<?= $baseUrl ?>/assets/images/city-bg.png') no-repeat center center; background-size: cover; min-height: 450px;">

    <!-- Lớp phủ nền -->
    <div class="position-absolute top-0 start-0 w-100 h-100 bg-white opacity-75" style="z-index: 1;"></div>

    <div class="container position-relative text-center" style="z-index: 2;">
        <h1 class="fw-bold text-dark mb-3" style="font-size: 2.5rem; letter-spacing: -0.5px;">
            Kết Nối Nhân Tài, Xây Dựng Tương Lai
        </h1>

        <p class="text-secondary fs-5 mb-5">
            Hàng Ngàn Cơ Hội Việc Làm Hấp Dẫn Đang Chờ Bạn.
        </p>

        <!-- Thanh Tìm -->
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

                                <?php if (!empty($locations)): ?>
                                    <?php foreach ($locations as $location): ?>

                                        <?php 
                                        $selected = (($_GET['location'] ?? '') === $location['MaDanhMuc'])
                                            ? 'selected'
                                            : '';
                                        ?>

                                        <option
                                            value="<?= htmlspecialchars($location['MaDanhMuc']) ?>"
                                            <?= $selected ?>
                                        >
                                            <?= htmlspecialchars($location['TenDanhMuc']) ?>
                                        </option>

                                    <?php endforeach; ?>
                                <?php endif; ?>
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
                        <div class="row g-3 mt-2 border-top">
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
                                <label class="form-label text-white-50 small fw-semibold mb-1">
                                    Kinh Nghiệm
                                </label>

                                <select name="experience" class="form-select border-0 py-2 small text-dark fw-semibold">

                                    <option value="">Tất cả kinh nghiệm</option>

                                    <option value="0" <?= ($_GET['experience'] ?? '') === '0' ? 'selected' : '' ?>>
                                        Không yêu cầu kinh nghiệm
                                    </option>

                                    <option value="1-3" <?= ($_GET['experience'] ?? '') === '1-3' ? 'selected' : '' ?>>
                                        1 - 3 năm
                                    </option>

                                    <option value="3-5" <?= ($_GET['experience'] ?? '') === '3-5' ? 'selected' : '' ?>>
                                        3 - 5 năm
                                    </option>

                                    <option value="5+" <?= ($_GET['experience'] ?? '') === '5+' ? 'selected' : '' ?>>
                                        Trên 5 năm
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
</section>


<!-- 2. VIỆC LÀM NỔI BẬT -->
<section class="py-5 bg-light">

    <div class="container">

        <div class="mb-4">

            <span class="badge bg-primary-blue mb-2 px-3 py-2">
                Dành Cho Bạn
            </span>

            <h2 class="fw-bold">
                Việc Làm Nổi Bật
            </h2>

        </div>

        <div class="row g-4">

            <!-- Sẽ lập trình vòng lặp để hiển thị từ DB sau này -->

            <?php

            foreach ($jobs as $job):

            ?>

            <div class="col-12 col-md-6 col-lg-3">

                <div class="card h-100 border-0 shadow-sm card-hover p-4 d-flex flex-column justify-content-between">

                    <div>

                        <!-- Logo & Tên công ty -->

                        <div class="d-flex align-items-center mb-3">

                            <div class="bg-light rounded p-2 me-3"
                                 style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">

                                <i class="fa-solid fa-briefcase text-secondary fs-4"></i>

                            </div>

                            <div class="overflow-hidden">

                                <h6 class="fw-bold mb-0 text-truncate">
                                    <?= htmlspecialchars($job['TieuDe']) ?>
                                </h6>

                                <small class="text-muted text-truncate d-block">
                                    <?= htmlspecialchars($job['TenCongTy']) ?>
                                </small>

                            </div>

                        </div>

                        <!-- Thông tin phụ -->

                        <div class="mb-3">

                            <div class="small text-secondary mb-1">
                                <i class="fa-solid fa-location-dot me-2 text-primary-blue"></i>
                                <?= htmlspecialchars($job['DiaChiLamViec']) ?>
                            </div>

                            <div class="small fw-semibold text-success">
                                <i class="fa-solid fa-money-bill-wave me-2"></i>
                                <?= number_format($job['MucLuong'], 0, ',', '.') ?> VNĐ
                            </div>

                        </div>

                        <p class="small text-muted mb-4 line-clamp-3">
                            <?= htmlspecialchars($job['MoTaCongViec']) ?>
                        </p>

                    </div>

                    <!-- Nút hành động -->

                    <div class="row g-2">

                        <div class="col-6">

                            <a href="<?= $baseUrl ?>/index.php?route=jobs/detail&maTinTuyenDung=<?= urlencode($job['MaTinTuyenDung']) ?>"
                               class="btn btn-outline-primary btn-sm w-100 py-2">

                                Chi Tiết

                            </a>

                        </div>

                        <div class="col-6">

                            <!-- Gọi hàm kiểm tra quyền khi ứng tuyển -->
                            <?php if (isset($_SESSION['user_id'])): ?>

                                <a href="<?= $baseUrl ?>/index.php?route=jobs/apply&maTinTuyenDung=<?= urlencode($job['MaTinTuyenDung']) ?>"
                                class="btn btn-primary-blue btn-sm w-100 py-2">
                                    Ứng Tuyển
                                </a>

                            <?php else: ?>

                                <button type="button"
                                        class="btn btn-primary-blue btn-sm w-100 py-2 btn-apply">
                                    Ứng Tuyển
                                </button>

                            <?php endif; ?>

                        </div>

                    </div>

                </div>

            </div>

            <?php endforeach; ?>

        </div>

    </div>

</section>


<!-- 3. CÁC CÔNG TY HÀNG ĐẦU & BANNER -->

<section class="py-5 bg-white">

    <div class="container">

        <?php if (!isset($_SESSION['user_id'])): ?>

            <!-- Banner đăng ký nhanh -->

            <div class="p-5 rounded-4 text-white mb-5 d-flex align-items-center justify-content-between flex-wrap gap-4"
                style="background: linear-gradient(135deg, #0b2239 0%, #1a3c5c 100%);">

                <div>

                    <h3 class="fw-bold mb-2">
                        Bạn Đã Sẵn Sàng Nâng Tầm Sự Nghiệp?
                    </h3>

                    <p class="mb-0 text-white-50">
                        Tạo tài khoản ngay hôm nay để nhận thông báo từ nhà tuyển dụng tốt nhất!
                    </p>

                </div>

                <a href="<?= $baseUrl ?>/index.php?route=auth/register"
                class="btn btn-warning fw-bold px-4 py-3 text-dark shadow">

                    Đăng Ký Ngay

                </a>

            </div>

        <?php endif; ?>

        <div class="mb-4 text-center text-md-start">

            <h2 class="fw-bold">
                Các Công Ty Hàng Đầu
            </h2>

            <p class="text-muted">
                Nhà tuyển dụng uy tín hàng đầu trong và ngoài nước đang săn đón bạn.
            </p>

        </div>

        <!-- Danh sách công ty -->

        <div class="row g-4 justify-content-center">

            <?php

            foreach ($companies as $comp):

            ?>

            <div class="col-12 col-sm-6 col-lg-3">

                <div class="card h-100 border-0 shadow-sm p-4 text-center card-hover">

                    <div class="bg-light rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                         style="width: 70px; height: 70px;">

                        <i class="fa-solid fa-building text-primary-blue fs-2"></i>

                    </div>

                    <h5 class="fw-bold mb-1">
                        <?= htmlspecialchars($comp['TenCongTy'] ?? '') ?>
                    </h5>

                    <span class="badge bg-light text-primary-blue mb-2">
                        <?= htmlspecialchars($comp['LinhVuc'] ?? '') ?>
                    </span>

                    <p class="small text-muted mb-0">
                        <?= htmlspecialchars($comp['MoTa'] ?? '') ?>
                    </p>
                    <small class="text-muted">
                        <?= (int)($comp['SoLuongTin'] ?? 0) ?> tin tuyển dụng
                    </small>

                </div>

            </div>

            <?php endforeach; ?>

        </div>

    </div>

</section>


<!-- 4. NGÀNH NGHỀ PHỔ BIẾN -->

<section class="py-5 bg-light">

    <div class="container">

        <div class="text-center mb-5">

            <h2 class="fw-bold">
                Ngành Nghề Phổ Biến
            </h2>

            <p class="text-muted">
                Khám phá các vị trí công việc theo từng nhóm lĩnh vực chuyên môn.
            </p>

        </div>

        <div class="row g-3">

            <?php
            $icons = [
                'Công nghệ thông tin' => 'fa-laptop-code',
                'Marketing' => 'fa-bullhorn',
                'Tài chính / Kế toán' => 'fa-coins',
                'Kinh doanh / Bán hàng' => 'fa-handshake',
                'Thiết kế' => 'fa-pen-nib',
                'Nhân sự' => 'fa-users',
                'Chăm sóc khách hàng' => 'fa-headset',
                'Logistics / Xuất nhập khẩu' => 'fa-truck-fast',
                'Kỹ thuật / Cơ khí' => 'fa-gears',
                'Y tế / Dược' => 'fa-kit-medical'
            ];
                
            foreach ($categories as $cat):

                            $icon = $icons[$cat['TenDanhMuc']] ?? 'fa-briefcase';
            ?>

            <div class="col-12 col-sm-6 col-md-4 col-lg-3">

                <div class="card border-0 shadow-sm p-3 card-hover d-flex flex-row align-items-center">

                    <div class="bg-primary-blue text-white rounded p-3 me-3">

                        <i class="fa-solid <?= $icon ?> fs-4"></i>

                    </div>

                    <div>

                        <h6 class="fw-bold mb-0">
                            <?= htmlspecialchars($cat['TenDanhMuc']) ?>
                        </h6>

                        <small class="text-muted">
                            <?= $cat['SoLuongTin'] ?> việc làm
                        </small>

                    </div>

                </div>

            </div>

            <?php endforeach; ?>

        </div>

        <!-- Đường kẻ phân cách mờ nhẹ căn giữa tuyệt đối -->

        <div class="col-12 my-4">

            <hr class="border-secondary opacity-25 w-25 mx-auto">

        </div>

        <!-- Câu trích dẫn -->

        <div class="row mt-5 text-center g-0 justify-content-center">

            <div class="col-12 col-lg-8 mb-4">

                <blockquote class="blockquote fs-6 text-secondary fst-italic">

                    <i class="fa-solid fa-quote-left text-primary-blue me-2"></i>

                    Hàng triệu ứng viên đã tìm thấy bến đỗ sự nghiệp mơ ước thông qua các bộ lọc việc làm thông minh của JobHub!

                </blockquote>

            </div>

            <div class="col-12 col-lg-8">

                <blockquote class="blockquote fs-6 text-secondary fst-italic">

                    <i class="fa-solid fa-quote-left text-primary-blue me-2"></i>

                    Nền tảng hỗ trợ nhà tuyển dụng tiếp cận đúng tài năng công nghệ chất lượng cao nhanh gấp 2 lần.

                </blockquote>

            </div>

        </div>

    </div>

</section>


<!-- ==================== MODAL THÔNG BÁO YÊU CẦU ĐĂNG NHẬP ==================== -->

<div class="modal fade"
     id="applyNoticeModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 shadow">

            <div class="modal-header bg-primary-blue text-white">

                <h5 class="modal-title fw-bold">

                    <i class="fa-solid fa-circle-exclamation me-2 text-warning"></i>

                    Yêu cầu đăng nhập

                </h5>

                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                        aria-label="Close">

                </button>

            </div>

            <div class="modal-body text-center p-4">

                <div class="text-danger fs-1 mb-3">

                    <i class="fa-solid fa-user-shield"></i>

                </div>

                <h5 class="fw-bold text-dark mb-2">

                    Chức năng dành cho Ứng viên

                </h5>

                <p class="text-secondary small">

                    Vui lòng đăng nhập bằng tài khoản
                    <strong>Ứng viên</strong>
                    để có thể ứng tuyển trực tiếp vào các bài tuyển dụng này nhé!

                </p>

            </div>

            <div class="modal-footer justify-content-center border-0 gap-2 pb-4">

                <a href="<?= $baseUrl ?>/index.php?route=auth/login"
                   class="btn btn-primary-blue px-4 fw-semibold">

                    Đăng Nhập

                </a>

                <a href="<?= $baseUrl ?>/index.php?route=auth/register"
                   class="btn btn-outline-secondary px-4 fw-semibold">

                    Đăng Ký

                </a>

            </div>

        </div>

    </div>

</div>


<!-- Script xử lý sự kiện click ứng tuyển -->

<script>

document.addEventListener("DOMContentLoaded", function() {

    // Tìm tất cả các nút ứng tuyển trên trang

    const applyButtons = document.querySelectorAll('.btn-apply');

    const applyModal = new bootstrap.Modal(
        document.getElementById('applyNoticeModal')
    );

    applyButtons.forEach(button => {

        button.addEventListener('click', function(e) {

            e.preventDefault();

            // Kích hoạt hiển thị Modal thông báo

            applyModal.show();

        });

    });

});

</script>


<style>

    .card-hover {

        transition: transform 0.3s ease, box-shadow 0.3s ease;

    }

    .card-hover:hover {

        transform: translateY(-5px);

        box-shadow: 0 10px 20px rgba(11, 34, 57, 0.1) !important;

    }

    .line-clamp-3 {

        display: -webkit-box;

        -webkit-line-clamp: 3;

        -webkit-box-orient: vertical;

        overflow: hidden;

    }

</style>

<?php elseif ($role == 1): ?>
<!-- ================= NHÀ TUYỂN DỤNG ================= -->

<!-- HERO -->

<section class="employer-hero">

    <div class="container">

        <div class="row align-items-center">

            <!-- Nội dung -->

            <div class="col-lg-7">

                <span class="employer-badge">
                    DÀNH CHO NHÀ TUYỂN DỤNG
                </span>

                <h1 class="employer-title">
                    Tìm kiếm nhân tài
                    <br>
                    <span>phù hợp với doanh nghiệp</span>
                </h1>

                <p class="employer-description">
                    Đăng tin tuyển dụng, tiếp cận ứng viên tiềm năng
                    và xây dựng đội ngũ nhân sự chất lượng cho doanh nghiệp.
                </p>

                <div class="d-flex gap-3 flex-wrap">

                    <a href="<?= $baseUrl ?>/index.php?route=jobs/create"
                       class="btn btn-warning btn-lg px-4 fw-bold">

                        <i class="fa-solid fa-plus me-2"></i>

                        Đăng tin tuyển dụng

                    </a>

                    <a href="<?= $baseUrl ?>/index.php?route=jobs/manage"
                       class="btn btn-outline-light btn-lg px-4">

                        Quản lý tin đăng

                    </a>

                </div>

            </div>


            <!-- Khối minh họa -->

            <div class="col-lg-5 mt-5 mt-lg-0">

                <div class="employer-dashboard-card">

                    <div class="d-flex justify-content-between align-items-center mb-4">

                        <div>

                            <small class="text-muted">
                                TỔNG QUAN TUYỂN DỤNG
                            </small>

                            <h4 class="fw-bold mb-0">
                                Hoạt động của bạn
                            </h4>

                        </div>

                        <div class="dashboard-icon">
                            <i class="fa-solid fa-chart-line"></i>
                        </div>

                    </div>


                    <div class="row g-3">

                        <div class="col-6">

                            <div class="stat-box">

                                <i class="fa-solid fa-briefcase text-primary-blue"></i>

                                <h3 class="fw-bold mb-0">
                                    <?= $totalJobs ?? 0 ?>
                                </h3>

                                <small class="text-muted">
                                    Tin tuyển dụng
                                </small>

                            </div>

                        </div>


                        <div class="col-6">

                            <div class="stat-box">

                                <i class="fa-solid fa-users text-success"></i>

                                <h3 class="fw-bold mb-0">
                                    <?= $totalApplications ?? 0 ?>
                                </h3>

                                <small class="text-muted">
                                    Ứng viên
                                </small>

                            </div>

                        </div>

                    </div>


                    <div class="candidate-progress mt-4">

                        <div class="d-flex justify-content-between mb-2">

                            <span class="small fw-semibold">
                                Hiệu quả tuyển dụng
                            </span>

                            <span class="small text-success fw-bold">
                                Đang hoạt động
                            </span>

                        </div>

                        <div class="progress">

                            <div class="progress-bar bg-success"
                                 style="width: 75%;">

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- ================= CHỨC NĂNG ================= -->

<section class="py-5 bg-light">

    <div class="container">

        <div class="section-heading mb-4">

            <span>
                QUẢN LÝ TUYỂN DỤNG
            </span>

            <h2>
                Mọi thứ bạn cần để tuyển dụng hiệu quả
            </h2>

        </div>


        <div class="row g-4">


            <!-- Đăng tin -->

            <div class="col-lg-4">

                <a href="<?= $baseUrl ?>/index.php?route=jobs/create"
                   class="feature-card">

                    <div class="feature-number">
                        01
                    </div>

                    <div>

                        <h4>
                            Đăng tin tuyển dụng
                        </h4>

                        <p>
                            Tạo tin tuyển dụng và tiếp cận những ứng viên
                            phù hợp với nhu cầu của doanh nghiệp.
                        </p>

                        <span class="feature-link">
                            Đăng tin ngay
                            <i class="fa-solid fa-arrow-right ms-2"></i>
                        </span>

                    </div>

                </a>

            </div>


            <!-- Quản lý ứng viên -->

            <div class="col-lg-4">

                <a href="<?= $baseUrl ?>/index.php?route=applications/manage"
                   class="feature-card">

                    <div class="feature-number">
                        02
                    </div>

                    <div>

                        <h4>
                            Quản lý ứng viên
                        </h4>

                        <p>
                            Theo dõi hồ sơ, đánh giá ứng viên và quản lý
                            toàn bộ quá trình tuyển dụng.
                        </p>

                        <span class="feature-link">
                            Xem ứng viên
                            <i class="fa-solid fa-arrow-right ms-2"></i>
                        </span>

                    </div>

                </a>

            </div>


            <!-- Quản lý tin -->

            <div class="col-lg-4">

                <a href="<?= $baseUrl ?>/index.php?route=jobs/manage"
                   class="feature-card">

                    <div class="feature-number">
                        03
                    </div>

                    <div>

                        <h4>
                            Quản lý tin đăng
                        </h4>

                        <p>
                            Chỉnh sửa, cập nhật và theo dõi hiệu quả
                            các tin tuyển dụng của bạn.
                        </p>

                        <span class="feature-link">
                            Quản lý tin
                            <i class="fa-solid fa-arrow-right ms-2"></i>
                        </span>

                    </div>

                </a>

            </div>

        </div>

    </div>

</section>


<!-- ================= CTA ================= -->

<section class="py-5 bg-white">

    <div class="container">

        <div class="employer-cta">

            <div>

                <h3 class="fw-bold mb-2">
                    Đang tìm kiếm nhân tài cho đội ngũ của bạn?
                </h3>

                <p class="mb-0 text-white-50">
                    Hãy bắt đầu bằng việc đăng tin tuyển dụng đầu tiên.
                </p>

            </div>

            <a href="<?= $baseUrl ?>/index.php?route=jobs/create"
               class="btn btn-warning fw-bold px-4 py-3">

                Đăng tin ngay

            </a>

        </div>

    </div>

</section>


<style>

.employer-hero {

    background:
        linear-gradient(
            110deg,
            #0b2239 0%,
            #123d63 65%,
            #1e5ba6 100%
        );

    padding: 90px 0;

    color: white;

}


.employer-badge {

    display: inline-block;

    background: rgba(255,255,255,0.12);

    border: 1px solid rgba(255,255,255,0.25);

    border-radius: 30px;

    padding: 8px 18px;

    font-size: 13px;

    font-weight: 700;

    letter-spacing: .5px;

    margin-bottom: 22px;

}


.employer-title {

    font-size: 3.4rem;

    line-height: 1.15;

    font-weight: 800;

    margin-bottom: 25px;

}


.employer-title span {

    color: #ffc107;

}


.employer-description {

    max-width: 570px;

    font-size: 1.1rem;

    line-height: 1.7;

    color: rgba(255,255,255,.75);

    margin-bottom: 32px;

}


.employer-dashboard-card {

    background: white;

    color: #172b4d;

    border-radius: 22px;

    padding: 30px;

    box-shadow: 0 25px 60px rgba(0,0,0,.25);

}


.dashboard-icon {

    width: 48px;

    height: 48px;

    border-radius: 14px;

    background: #eaf2fb;

    color: #1e5ba6;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 20px;

}


.stat-box {

    background: #f5f7fa;

    border-radius: 14px;

    padding: 18px;

}


.stat-box i {

    font-size: 20px;

    margin-bottom: 8px;

}


.progress {

    height: 8px;

    border-radius: 20px;

}


.section-heading span {

    font-size: 13px;

    font-weight: 700;

    color: #1e5ba6;

    letter-spacing: 1px;

}


.section-heading h2 {

    font-weight: 800;

    margin-top: 8px;

}


.feature-card {

    display: flex;

    gap: 20px;

    height: 100%;

    padding: 30px;

    background: white;

    border-radius: 18px;

    text-decoration: none;

    color: #172b4d;

    border: 1px solid #e9edf2;

    transition: all .25s ease;

}


.feature-card:hover {

    transform: translateY(-5px);

    box-shadow: 0 15px 35px rgba(11,34,57,.12);

    color: #172b4d;

}


.feature-number {

    font-size: 18px;

    font-weight: 800;

    color: #1e5ba6;

    min-width: 35px;

}


.feature-card h4 {

    font-weight: 700;

    margin-bottom: 12px;

}


.feature-card p {

    color: #6c757d;

    line-height: 1.6;

    margin-bottom: 20px;

}


.feature-link {

    color: #1e5ba6;

    font-weight: 700;

    font-size: 14px;

}


.employer-cta {

    background: linear-gradient(135deg, #0b2239, #1d446c);

    border-radius: 20px;

    padding: 38px 45px;

    color: white;

    display: flex;

    justify-content: space-between;

    align-items: center;

    gap: 25px;

}


@media (max-width: 768px) {

    .employer-title {

        font-size: 2.4rem;

    }

    .employer-cta {

        flex-direction: column;

        align-items: flex-start;

    }

}

</style>

<?php endif; ?>


<?php include_once __DIR__ . '/../layouts/footer.php'; ?>