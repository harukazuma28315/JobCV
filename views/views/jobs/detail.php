<?php include_once '../../views/layouts/header.php'; ?>

<section class="py-5 bg-dark text-white position-relative" style="background: linear-gradient(135deg, #0b2239 0%, #1d446c 100%);">
    <div class="container">
        <div class="row align-items-center">
            <!-- trái: Thông tin job -->
            <div class="col-12 col-md-8 mb-4 mb-md-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/JobCV/index.php" class="text-white-50 text-decoration-none">Trang chủ</a></li>
                        <li class="breadcrumb-item"><a href="#" class="text-white-50 text-decoration-none">Việc làm</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Chi tiết</li>
                    </ol>
                </nav>
                
                <div class="d-flex align-items-start align-items-md-center gap-3 flex-column flex-md-row">
                    
                    <div class="bg-white rounded p-2 d-flex align-items-center justify-content-center shadow-sm" style="width: 75px; height: 75px; min-width: 75px;">
                        <i class="fa-solid fa-briefcase text-secondary fs-1"></i>
                    </div>
                    <div>
                        <h1 class="fw-bold h2 mb-1">Senior Software Engineer</h1>
                        <p class="fs-5 mb-2 text-warning fw-semibold">VNG Corporation</p>
                        
                        <div class="d-flex flex-wrap gap-3 small text-white-50">
                            <span><i class="fa-solid fa-money-bill-wave text-success me-1"></i> 1,500 - 2,500 USD</span>
                            <span><i class="fa-solid fa-location-dot text-danger me-1"></i> Quận 7, TP. Hồ Chí Minh</span>
                            <span><i class="fa-solid fa-calendar-days text-primary me-1"></i> Hạn nộp: 30/08/2026</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- phải: tương tác nhanh -->
            <div class="col-12 col-md-4 text-md-end">
                <button class="btn btn-warning fw-bold text-dark px-4 py-2 me-2" data-bs-toggle="modal" data-bs-target="#applyJobModal">
                    <i class="fa-solid fa-paper-plane me-2"></i>Ứng Tuyển Ngay
                </button>
                <button class="btn btn-outline-light px-3 py-2" title="Lưu việc làm">
                    <i class="fa-regular fa-bookmark"></i>
                </button>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            
            <!-- CỘT TRÁI: CHI TIẾT NỘI DUNG -->
            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-sm p-4 mb-4">
                    <h4 class="fw-bold mb-4 border-start border-4 border-primary ps-3 text-dark">Chi Tiết Tuyển Dụng</h4>
                    
                    <!-- Mô tả công việc -->
                    <div class="mb-4">
                        <h5 class="fw-bold text-dark"><i class="fa-solid fa-circle-info text-primary-blue me-2"></i>Mô tả công việc</h5>
                        <ul class="text-secondary lh-lg ps-3">
                            <li>Tham gia nghiên cứu, phát triển các dự án sản phẩm chất lượng cao (Web/Mobile Apps) thuộc hệ sinh thái của VNG.</li>
                            <li>Thiết kế hệ thống database, viết APIs hiệu năng cao (Node.js/Go/Java) phục vụ hàng triệu người dùng hoạt động đồng thời.</li>
                            <li>Cải tiến, tối ưu hóa các dòng code hiện có để đảm bảo tốc độ phản hồi cực nhanh, độ trễ cực thấp.</li>
                            <li>Hợp tác chặt chẽ với team UI/UX Designer, Product Owner và các bên liên quan để hoàn thiện các tính năng đúng tiến độ.</li>
                        </ul>
                    </div>

                    <!-- Yêu cầu ứng viên -->
                    <div class="mb-4">
                        <h5 class="fw-bold text-dark"><i class="fa-solid fa-user-gear text-primary-blue me-2"></i>Yêu cầu công việc</h5>
                        <ul class="text-secondary lh-lg ps-3">
                            <li>Tốt nghiệp Đại học chuyên ngành CNTT, An toàn thông tin hoặc các ngành tương đương.</li>
                            <li>Tối thiểu 3 năm kinh nghiệm làm việc ở vị trí tương đương.</li>
                            <li>Thành thạo tối thiểu một ngôn ngữ lập trình Back-end (Node.js, Python, Java) và cơ sở dữ liệu (MySQL, PostgreSQL, MongoDB).</li>
                            <li>Có kinh nghiệm làm việc với Redis, Docker, CI/CD và kiến trúc Microservices là lợi thế lớn.</li>
                            <li>Khả năng tư duy logic tốt, chủ động tìm kiếm giải pháp và kỹ năng làm việc nhóm hiệu quả.</li>
                        </ul>
                    </div>

                    <!-- Quyền lợi được hưởng -->
                    <div class="mb-4">
                        <h5 class="fw-bold text-dark"><i class="fa-solid fa-gift text-primary-blue me-2"></i>Quyền lợi được hưởng</h5>
                        <ul class="text-secondary lh-lg ps-3">
                            <li>Lương cứng thỏa thuận cực cạnh tranh tùy theo năng lực thực tế + Thưởng tháng 13, 14 theo KPI hiệu quả công việc.</li>
                            <li>Hưởng đầy đủ bảo hiểm xã hội, bảo hiểm y tế cao cấp (PVI) sau thời gian thử việc.</li>
                            <li>Cung cấp trang thiết bị hiện đại phục vụ công việc (Macbook/Laptop cấu hình cao, màn hình rời).</li>
                            <li>Môi trường làm việc trẻ trung, sáng tạo, cơ hội thăng tiến lên Tech Lead/Manager nhanh chóng.</li>
                            <li>Tham gia teambuilding, du lịch hàng năm của công ty và miễn phí cơm trưa, teabreak mỗi ngày.</li>
                        </ul>
                    </div>

                    <!-- Nút Ứng tuyển chân trang chi tiết -->
                    <div class="text-center mt-4 pt-3 border-top">
                        <button class="btn btn-primary-blue fw-bold px-5 py-3" data-bs-toggle="modal" data-bs-target="#applyJobModal">
                            ỨNG TUYỂN NGAY BÂY GIỜ
                        </button>
                        <p class="text-muted small mt-2">Hạn nộp hồ sơ sắp hết, hãy nắm bắt cơ hội ngay!</p>
                    </div>
                </div>
            </div>

            <!-- CỘT PHẢI -->
            <div class="col-12 col-lg-4">
                <!-- Card Tóm tắt công việc -->
                <div class="card border-0 shadow-sm p-4 mb-4">
                    <h5 class="fw-bold mb-3 pb-2 border-bottom text-dark">Thông Tin Chung</h5>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex align-items-center">
                            <span class="bg-light text-primary-blue p-2 rounded me-3"><i class="fa-solid fa-briefcase"></i></span>
                            <div>
                                <small class="text-muted d-block">Cấp bậc</small>
                                <strong class="text-dark">Nhân viên / Senior</strong>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="bg-light text-primary-blue p-2 rounded me-3"><i class="fa-solid fa-hourglass-half"></i></span>
                            <div>
                                <small class="text-muted d-block">Kinh nghiệm</small>
                                <strong class="text-dark">3 - 5 năm</strong>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="bg-light text-primary-blue p-2 rounded me-3"><i class="fa-solid fa-user-clock"></i></span>
                            <div>
                                <small class="text-muted d-block">Hình thức làm việc</small>
                                <strong class="text-dark">Toàn thời gian (Onsite)</strong>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="bg-light text-primary-blue p-2 rounded me-3"><i class="fa-solid fa-users"></i></span>
                            <div>
                                <small class="text-muted d-block">Số lượng tuyển</small>
                                <strong class="text-dark">02 người</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Thông tin công ty -->
                <div class="card border-0 shadow-sm p-4 text-center">
                    <div class="bg-light rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="fa-solid fa-building text-primary-blue fs-1"></i>
                    </div>
                    <h5 class="fw-bold mb-1 text-dark">VNG Corporation</h5>
                    <span class="badge bg-light text-primary-blue mb-3">Công nghệ hàng đầu</span>
                    
                    <p class="small text-muted mb-4 text-start">VNG Corporation là một trong những doanh nghiệp công nghệ hàng đầu tại Việt Nam, sở hữu hệ sinh thái phong phú bao gồm Zalo, Zing, ZaloPay, các sản phẩm game và giải pháp chuyển đổi số cho doanh nghiệp.</p>
                    
                    <a href="#" class="btn btn-outline-primary btn-sm w-100 py-2">Xem trang công ty</a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ==================== MODAL ĐĂNG KÝ ỨNG TUYỂN (APPLY MODAL) ==================== -->
<div class="modal fade" id="applyJobModal" tabindex="-1" aria-labelledby="applyJobModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary-blue text-white">
                <h5 class="modal-title fw-bold" id="applyJobModalLabel">Nộp Hồ Sơ Ứng Tuyển</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="/JobCV/index.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="controller" value="job">
                <input type="hidden" name="action" value="submit_apply">
                <input type="hidden" name="job_id" value="1"> <!-- Sẽ đổi thành dynamic php id -->

                <div class="modal-body p-4">
                    <div class="mb-3 text-center">
                        <span class="text-muted">Bạn đang ứng tuyển vị trí:</span>
                        <h6 class="fw-bold text-primary-blue mt-1 fs-5">Senior Software Engineer</h6>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Họ và tên <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="applicant_name" placeholder="Nguyễn Văn A" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Email liên hệ <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="applicant_email" placeholder="email@gmail.com" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Số điện thoại <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control" name="applicant_phone" placeholder="09xxxxxxxx" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Tải CV từ máy tính <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="cv_file" accept=".pdf,.doc,.docx" required>
                        <div class="form-text small">Hỗ trợ định dạng .pdf, .doc, .docx (tối đa 5MB)</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Thư giới thiệu (Không bắt buộc)</label>
                        <textarea class="form-control" name="cover_letter" rows="3" placeholder="Giới thiệu ngắn gọn kinh nghiệm và lý do bạn phù hợp với công việc này..."></textarea>
                    </div>
                </div>

                <div class="modal-footer border-top-0 justify-content-end gap-2 pb-4">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-warning fw-bold text-dark px-4">Gửi Hồ Sơ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once '../../views/layouts/footer.php'; ?>