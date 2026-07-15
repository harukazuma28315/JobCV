<?php
$baseUrl = '/JobCV';
include_once __DIR__ . '/../layouts/header.php';
?>

<section class="position-relative py-5 d-flex align-items-center" style="background: url('<?= $baseUrl ?>/public/images/city-bg.png') no-repeat center center; background-size: cover; min-height: 450px;">
    <!-- Lớp phủ nền -->
    <div class="position-absolute top-0 start-0 w-100 h-100 bg-white opacity-75" style="z-index: 1;"></div>
    
    <div class="container position-relative text-center" style="z-index: 2;">
        <h1 class="fw-bold text-dark mb-3" style="font-size: 2.5rem; letter-spacing: -0.5px;">Kết Nối Nhân Tài, Xây Dựng Tương Lai</h1>
        <p class="text-secondary fs-5 mb-5">Hàng Ngàn Cơ Hội Việc Làm Hấp Dẫn Đang Chờ Bạn.</p>
        
        <!-- Thanh Tìm -->
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <form action="../jobs/search.php" method="GET" class="p-4 bg-primary-blue rounded-4 shadow-lg">
                    <!-- 2 dòng input để test dl giả lập -->
                    <input type="hidden" name="controller" value="job">
                    <input type="hidden" name="action" value="search">
                    <!-- 1: NHẬP TỪ KHÓA  -->
                    <div class="row mb-3 justify-content-center">
                        <div class="col-12 col-md-8">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-0 py-2"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                                <input type="text" name="keyword" class="form-control border-0 py-2 text-center" placeholder="Nhập từ khóa (Vị trí, kỹ năng, tên công ty...)">
                            </div>
                        </div>
                    </div>

                    <!-- 2: BỘ LỌC ĐỊA ĐIỂM & NGÀNH NGHỀ  -->
                    <div class="row g-3 mb-3">
                        <!-- Chọn địa điểm -->
                        <div class="col-12 col-md-6">
                            <select name="location" class="form-select border-0 py-2 fw-semibold">
                                <option value="" selected>Chọn Địa Điểm</option>
                                <option value="can-tho">Cần Thơ</option>
                                <option value="hcm">TP. Hồ Chí Minh</option>
                                <option value="ha-noi">Hà Nội</option>
                                <option value="da-nang">Đà Nẵng</option>
                            </select>
                        </div>
                        <!-- Chọn ngành nghề -->
                        <div class="col-12 col-md-6">
                            <select name="category" class="form-select border-0 py-2 fw-semibold">
                                <option value="" selected>Chọn Ngành Nghề</option>
                                <option value="it">Công nghệ thông tin</option>
                                <option value="marketing">Marketing</option>
                                <option value="finance">Tài chính / Kế toán</option>
                                <option value="sales">Kinh doanh / Bán hàng</option>
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

                    <!-- 4: KHU VỰC SỔ BỘ LỌC NÂNG CAO  -->
                    <div class="collapse" id="advancedFilter">
                        <div class="row g-3 mt-2 pt-3 border-top border-white-50">
                            <!-- Lọc mức lương -->
                            <div class="col-12 col-sm-6 col-md-3">
                                <label class="form-label text-white-50 small fw-semibold mb-1">Mức Lương</label>
                                <select name="salary" class="form-select border-0 py-2 small text-dark fw-semibold">
                                    <option value="" selected>Tất cả mức lương</option>
                                    <option value="under-10">Dưới 10 triệu</option>
                                    <option value="10-15">10 - 15 triệu</option>
                                    <option value="15-20">15 - 20 triệu</option>
                                    <option value="over-20">Trên 20 triệu</option>
                                    <option value="negotiable">Thỏa thuận</option>
                                </select>
                            </div>

                            <!-- Lọc cấp bậc -->
                            <div class="col-6 col-sm-3 col-md-2">
                                <label class="form-label text-white-50 small fw-semibold mb-1">Cấp Bậc</label>
                                <select name="level" class="form-select border-0 py-2 small text-dark fw-semibold">
                                    <option value="" selected>Tất cả cấp bậc</option>
                                    <option value="intern">Thực tập sinh</option>
                                    <option value="fresher">Mới tốt nghiệp</option>
                                    <option value="junior">Nhân viên</option>
                                    <option value="senior">Trưởng nhóm</option>
                                    <option value="manager">Quản lý / Giám đốc</option>
                                </select>
                            </div>

                            <!-- Lọc hình thức làm việc -->
                            <div class="col-6 col-sm-3 col-md-2">
                                <label class="form-label text-white-50 small fw-semibold mb-1">Hình Thức</label>
                                <select name="job_type" class="form-select border-0 py-2 small text-dark fw-semibold">
                                    <option value="" selected>Tất cả hình thức</option>
                                    <option value="onsite">Tại văn phòng</option>
                                    <option value="remote">Từ xa (Remote)</option>
                                    <option value="hybrid">Linh hoạt (Hybrid)</option>
                                    <option value="parttime">Bán thời gian</option>
                                </select>
                            </div>

                            <!-- Lọc kinh nghiệm -->
                            <div class="col-12 col-sm-6 col-md-3">
                                <label class="form-label text-white-50 small fw-semibold mb-1">Kinh Nghiệm</label>
                                <select name="experience" class="form-select border-0 py-2 small text-dark fw-semibold">
                                    <option value="" selected>Tất cả kinh nghiệm</option>
                                    <option value="no-experience">Không yêu cầu</option>
                                    <option value="under-1">Dưới 1 năm</option>
                                    <option value="1-3">1 - 3 năm</option>
                                    <option value="3-5">3 - 5 năm</option>
                                    <option value="over-5">Trên 5 năm</option>
                                </select>
                            </div>

                            <!-- Lọc thời gian đăng tin -->
                            <div class="col-12 col-sm-6 col-md-2">
                                <label class="form-label text-white-50 small fw-semibold mb-1">Thời Gian Đăng</label>
                                <select name="posted_date" class="form-select border-0 py-2 small text-dark fw-semibold">
                                    <option value="" selected>Mọi thời gian</option>
                                    <option value="24h">Trong 24 giờ</option>
                                    <option value="1week">Trong 1 tuần</option>
                                    <option value="1month">Trong 1 tháng</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
</section>

<!-- 2. VIỆC LÀM NỔI BẬT -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="mb-4">
            <span class="badge bg-primary-blue mb-2 px-3 py-2">Dành Cho Bạn</span>
            <h2 class="fw-bold">Việc Làm Nổi Bật</h2>
        </div>
        
        <div class="row g-4">
            <!-- Sẽ lập trình vòng lặp để hiển thị từ DB sau này -->
            <?php
            $jobs = [
                ['title' => 'Senior Software Engineer', 'company' => 'VNG Corporation', 'logo' => 'vng-logo.png', 'loc' => 'Hồ Chí Minh', 'salary' => '1,500 - 2,500 USD', 'desc' => 'Yêu cầu kinh nghiệm Node.js, ReactJS, tư duy lập trình hệ thống tốt...'],
                ['title' => 'Marketing Manager', 'company' => 'Google Vietnam', 'logo' => 'google-logo.png', 'loc' => 'Hà Nội', 'salary' => '2,000 - 3,500 USD', 'desc' => 'Lên kế hoạch và thực thi chiến dịch marketing số cho các dòng sản phẩm mới...'],
                ['title' => 'Senior Software Engineer', 'company' => 'FPT Software', 'logo' => 'fpt-logo.png', 'loc' => 'Cần Thơ', 'salary' => '1,200 - 2,200 USD', 'desc' => 'Tham gia phát triển các dự án lớn cho đối tác Nhật Bản và Âu Mỹ...'],
                ['title' => 'Marketing Manager', 'company' => 'Shopee Vietnam', 'logo' => 'shopee-logo.png', 'loc' => 'Hồ Chí Minh', 'salary' => '1,500 - 2,500 USD', 'desc' => 'Quản lý nhóm vận hành chiến dịch sale lớn hàng tháng trên sàn thương mại điện tử...'],
                ['title' => 'UI/UX Designer', 'company' => 'VNG Corporation', 'logo' => 'vng-logo.png', 'loc' => 'Hồ Chí Minh', 'salary' => '1,000 - 1,800 USD', 'desc' => 'Thiết kế trải nghiệm người dùng tối ưu cho các sản phẩm web và di động...'],
                ['title' => 'Project Manager', 'company' => 'FPT Software', 'logo' => 'fpt-logo.png', 'loc' => 'Đà Nẵng', 'salary' => '2,500 - 4,000 USD', 'desc' => 'Quản lý tiến độ dự án phát triển phần mềm, chịu trách nhiệm chất lượng bàn giao...'],
                ['title' => 'Content Creator', 'company' => 'Shopee Vietnam', 'logo' => 'shopee-logo.png', 'loc' => 'Hà Nội', 'salary' => '800 - 1,500 USD', 'desc' => 'Sáng tạo nội dung video ngắn, kịch bản thu hút phân khúc người dùng trẻ...'],
                ['title' => 'Data Analyst', 'company' => 'Google Vietnam', 'logo' => 'google-logo.png', 'loc' => 'Hồ Chí Minh', 'salary' => '1,800 - 3,000 USD', 'desc' => 'Phân tích hành vi người dùng, dự đoán xu hướng tìm kiếm để đề xuất giải pháp...']
            ];
            
            foreach ($jobs as $job):
            ?>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm card-hover p-4 d-flex flex-column justify-content-between">
                    <div>
                        <!-- Logo & Tên công ty -->
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-light rounded p-2 me-3" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                <i class="fa-solid fa-briefcase text-secondary fs-4"></i>
                            </div>
                            <div class="overflow-hidden">
                                <h6 class="fw-bold mb-0 text-truncate"><?= $job['title'] ?></h6>
                                <small class="text-muted text-truncate d-block"><?= $job['company'] ?></small>
                            </div>
                        </div>
                        
                        <!-- Thông tin phụ -->
                        <div class="mb-3">
                            <div class="small text-secondary mb-1"><i class="fa-solid fa-location-dot me-2 text-primary-blue"></i><?= $job['loc'] ?></div>
                            <div class="small fw-semibold text-success"><i class="fa-solid fa-money-bill-wave me-2"></i><?= $job['salary'] ?></div>
                        </div>
                        
                        <p class="small text-muted mb-4 line-clamp-3"><?= $job['desc'] ?></p>
                    </div>
                    
                    <!-- Nút hành động -->
                    <div class="row g-2">
                        <div class="col-6">
                            <a href="../jobs/detail.php" class="btn btn-outline-primary btn-sm w-100 py-2">Chi Tiết</a>
                        </div>
                        <div class="col-6">
                            <!-- Gọi hàm kiểm tra quyền khi ứng tuyển -->
                            <button type="button" class="btn btn-primary-blue btn-sm w-100 py-2 btn-apply">Ứng Tuyển</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Nút xem thêm -->
        <div class="text-center mt-5">
            <button class="btn btn-primary-blue px-4 py-2 fw-semibold shadow-sm">Xem Thêm Việc Làm</button>
        </div>
    </div>
</section>

<!-- 3. CÁC CÔNG TY HÀNG ĐẦU & BANNER -->
<section class="py-5 bg-white">
    <div class="container">
        <!-- Banner đăng ký nhanh -->
        <div class="p-5 rounded-4 text-white mb-5 d-flex align-items-center justify-content-between flex-wrap gap-4" style="background: linear-gradient(135deg, #0b2239 0%, #1a3c5c 100%);">
            <div>
                <h3 class="fw-bold mb-2">Bạn Đã Sẵn Sàng Nâng Tầm Sự Nghiệp?</h3>
                <p class="mb-0 text-white-50">Tạo tài khoản ngay hôm nay để nhận thông báo từ nhà tuyển dụng tốt nhất!</p>
            </div>
            <a href="/JobCV/views/auth/register.php" class="btn btn-warning fw-bold px-4 py-3 text-dark shadow">Đăng Ký Ngay</a>
        </div>

        <div class="mb-4 text-center text-md-start">
            <h2 class="fw-bold">Các Công Ty Hàng Đầu</h2>
            <p class="text-muted">Nhà tuyển dụng uy tín hàng đầu trong và ngoài nước đang săn đón bạn.</p>
        </div>

        <!-- Danh sách công ty -->
        <div class="row g-4 justify-content-center">
            <?php
            $companies = [
                ['name' => 'VNG Corporation', 'sector' => 'Công nghệ hàng đầu', 'desc' => 'Dẫn đầu công nghệ số'],
                ['name' => 'Vinamilk', 'sector' => 'Sản xuất sữa', 'desc' => 'Thương hiệu sữa quốc gia'],
                ['name' => 'FPT Software', 'sector' => 'Công nghệ số', 'desc' => 'Phần mềm xuất khẩu số 1'],
                ['name' => 'Shopee', 'sector' => 'Thương mại điện tử', 'desc' => 'Chợ mua sắm lớn nhất']
            ];
            foreach ($companies as $comp):
            ?>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm p-4 text-center card-hover">
                    <div class="bg-light rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                        <i class="fa-solid fa-building text-primary-blue fs-2"></i>
                    </div>
                    <h5 class="fw-bold mb-1"><?= $comp['name'] ?></h5>
                    <span class="badge bg-light text-primary-blue mb-2"><?= $comp['sector'] ?></span>
                    <p class="small text-muted mb-0"><?= $comp['desc'] ?></p>
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
            <h2 class="fw-bold">Ngành Nghề Phổ Biến</h2>
            <p class="text-muted">Khám phá các vị trí công việc theo từng nhóm lĩnh vực chuyên môn.</p>
        </div>

        <div class="row g-3">
            <?php
            $cats = [
                ['icon' => 'fa-laptop-code', 'name' => 'IT / Phần mềm', 'jobs' => '500+ Jobs'],
                ['icon' => 'fa-bullhorn', 'name' => 'Marketing', 'jobs' => '300+ Jobs'],
                ['icon' => 'fa-coins', 'name' => 'Tài chính / Kế toán', 'jobs' => '200+ Jobs'],
                ['icon' => 'fa-chart-simple', 'name' => 'Phân tích dữ liệu', 'jobs' => '150+ Jobs'],
                ['icon' => 'fa-handshake', 'name' => 'Chăm sóc khách hàng', 'jobs' => '250+ Jobs'],
                ['icon' => 'fa-pen-nib', 'name' => 'Thiết kế đồ họa', 'jobs' => '280+ Jobs'],
                ['icon' => 'fa-box-open', 'name' => 'Logistics / Vận chuyển', 'jobs' => '230+ Jobs'],
                ['icon' => 'fa-hotel', 'name' => 'Du lịch / Khách sạn', 'jobs' => '210+ Jobs']
            ];
            foreach ($cats as $cat):
            ?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card border-0 shadow-sm p-3 card-hover d-flex flex-row align-items-center">
                    <div class="bg-primary-blue text-white rounded p-3 me-3">
                        <i class="fa-solid <?= $cat['icon'] ?> fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0"><?= $cat['name'] ?></h6>
                        <small class="text-muted"><?= $cat['jobs'] ?></small>
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
<div class="modal fade" id="applyNoticeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary-blue text-white">
                <h5 class="modal-title fw-bold"><i class="fa-solid fa-circle-exclamation me-2 text-warning"></i>Yêu cầu đăng nhập</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <div class="text-danger fs-1 mb-3">
                    <i class="fa-solid fa-user-shield"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2">Chức năng dành cho Ứng viên</h5>
                <p class="text-secondary small">Vui lòng đăng nhập bằng tài khoản <strong>Ứng viên</strong> để có thể ứng tuyển trực tiếp vào các bài tuyển dụng này nhé!</p>
            </div>
            <div class="modal-footer justify-content-center border-0 gap-2 pb-4">
                <a href="../auth/login.php" class="btn btn-primary-blue px-4 fw-semibold">Đăng Nhập</a>
                <a href="../auth/register.php" class="btn btn-outline-secondary px-4 fw-semibold">Đăng Ký</a>
            </div>
        </div>
    </div>
</div>


<!-- Script xử lý sự kiện click ứng tuyển -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Tìm tất cả các nút ứng tuyển trên trang
    const applyButtons = document.querySelectorAll('.btn-apply');
    const applyModal = new bootstrap.Modal(document.getElementById('applyNoticeModal'));
    
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
<?php include_once '../../views/layouts/footer.php'; ?>