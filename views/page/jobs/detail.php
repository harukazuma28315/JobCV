<?php
$baseUrl = '/JobCV';

require_once __DIR__ . '/../layouts/header.php';
?>

<section class="py-5 bg-dark text-white position-relative" style="background: linear-gradient(135deg, #0b2239 0%, #1d446c 100%);">
    <div class="container">
        <div class="row align-items-center">
            <!-- trái: Thông tin job -->
            <div class="col-12 col-md-8 mb-4 mb-md-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/JobCV/index.php" class="text-white-50 text-decoration-none">Trang chủ</a></li>
                        <li class="breadcrumb-item text-white" aria-current="page">Chi tiết</li>
                    </ol>
                </nav>
                
                <div class="d-flex align-items-start align-items-md-center gap-3 flex-column flex-md-row">
                    
                    <div class="bg-white rounded p-2 d-flex align-items-center justify-content-center shadow-sm" style="width: 75px; height: 75px; min-width: 75px;">
                        <i class="fa-solid fa-briefcase text-secondary fs-1"></i>
                    </div>
                    <div>
                        <h1 class="fw-bold h2 mb-1">
                            <?= htmlspecialchars($job['TieuDe']) ?>
                        </h1>

                        <p class="fs-5 mb-2 text-warning fw-semibold">
                            <?= htmlspecialchars($job['MaNhaTuyenDung']) ?>
                        </p>
                        
                        <div class="d-flex flex-wrap gap-3 small text-white-50">
                            <span>
                                <i class="fa-solid fa-money-bill-wave text-success me-1"></i>
                                <?= number_format($job['MucLuong'], 0, ',', '.') ?> VNĐ
                            </span>
                            <span>
                                <i class="fa-solid fa-location-dot text-danger me-1"></i>
                                <?= htmlspecialchars($job['DiaChiLamViec']) ?>
                            </span>
                            <span>
                                <i class="fa-solid fa-calendar-days text-primary me-1"></i>
                                Hạn nộp:
                                <?= date('d/m/Y', strtotime($job['NgayHetHan'])) ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- phải: tương tác nhanh -->
            <div class="col-12 col-md-4 text-md-end">
                <a 
                    href="/JobCV/index.php?route=jobs/apply&maTinTuyenDung=<?= urlencode($job['MaTinTuyenDung']) ?>"
                    class="btn btn-warning fw-bold text-dark px-4 py-2"
                >
                    <i class="fa-solid fa-paper-plane me-2"></i>
                    Ứng Tuyển Ngay
                </a>
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
                    <div class="text-secondary lh-lg">
                        <?= nl2br(htmlspecialchars($job['MoTaCongViec'])) ?>
                    </div>

                    <!-- Yêu cầu ứng viên -->
                    <div class="mb-4">
                        <h5 class="fw-bold text-dark"><i class="fa-solid fa-user-gear text-primary-blue me-2"></i>Yêu cầu công việc</h5>
                        <div class="text-secondary lh-lg">
                            <?= nl2br(htmlspecialchars($job['YeuCauCongViec'])) ?>
                        </div>
                    </div>

                    <!-- Quyền lợi được hưởng -->
                <!--     <div class="mb-4">
                        <h5 class="fw-bold text-dark"><i class="fa-solid fa-gift text-primary-blue me-2"></i>Quyền lợi được hưởng</h5>
                        <ul class="text-secondary lh-lg ps-3">
                            <li>Lương cứng thỏa thuận cực cạnh tranh tùy theo năng lực thực tế + Thưởng tháng 13, 14 theo KPI hiệu quả công việc.</li>
                            <li>Hưởng đầy đủ bảo hiểm xã hội, bảo hiểm y tế cao cấp (PVI) sau thời gian thử việc.</li>
                            <li>Cung cấp trang thiết bị hiện đại phục vụ công việc (Macbook/Laptop cấu hình cao, màn hình rời).</li>
                            <li>Môi trường làm việc trẻ trung, sáng tạo, cơ hội thăng tiến lên Tech Lead/Manager nhanh chóng.</li>
                            <li>Tham gia teambuilding, du lịch hàng năm của công ty và miễn phí cơm trưa, teabreak mỗi ngày.</li>
                        </ul>
                    </div> -->

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
                                <strong class="text-dark">
                                    <?= htmlspecialchars($job['CapBac']) ?>
                                </strong>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="bg-light text-primary-blue p-2 rounded me-3"><i class="fa-solid fa-hourglass-half"></i></span>
                            <div>
                                <small class="text-muted d-block">Kinh nghiệm</small>
                                <strong class="text-dark">
                                    <?= htmlspecialchars($job['SoNamKinhNghiem']) ?> năm
                                </strong>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="bg-light text-primary-blue p-2 rounded me-3"><i class="fa-solid fa-user-clock"></i></span>
                            <div>
                                <small class="text-muted d-block">Hình thức làm việc</small>
                                <strong class="text-dark">
                                    <?= htmlspecialchars($job['HinhThucLamViec']) ?>
                                </strong>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="bg-light text-primary-blue p-2 rounded me-3"><i class="fa-solid fa-users"></i></span>
                            <div>
                                <small class="text-muted d-block">Số lượng tuyển</small>
                                <strong class="text-dark">
                                    <?= htmlspecialchars($job['SoLuongTuyen']) ?> người
                                </strong>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</section>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>