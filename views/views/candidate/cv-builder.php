<!-- Nhúng Header chung -->
<?php include_once '../../views/layouts/header.php'; ?>

<div class="bg-white border-bottom py-5">
    <div class="container text-center">
        <span class="badge bg-primary-blue px-3 py-2 mb-2 text-uppercase fw-semibold">Công cụ miễn phí</span>
        <h1 class="fw-bold text-dark mb-3">Tạo CV Chuyên Nghiệp Trong 5 Phút</h1>
        <p class="text-muted mx-auto" style="max-width: 600px;">Sử dụng các mẫu CV thiết kế chuẩn ATS, được khuyên dùng bởi các nhà tuyển dụng hàng đầu để gia tăng 80% cơ hội được gọi phỏng vấn.</p>
    </div>
</div>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-dark mb-0">Chọn mẫu CV để bắt đầu</h4>
        <div class="d-flex gap-2">
            <button class="btn btn-light border btn-sm active text-primary-blue fw-semibold">Tất cả mẫu</button>
            <button class="btn btn-light border btn-sm">Sinh viên mới ra trường</button>
            <button class="btn btn-light border btn-sm">IT / Phần mềm</button>
        </div>
    </div>

    <!-- Grid danh sách các mẫu CV -->
    <div class="row g-4">
        <!-- Mẫu CV 1 -->
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100 overflow-hidden cv-card">
                <div class="position-relative bg-light border-bottom text-center p-4" style="height: 280px; overflow: hidden;">
                    <img src="https://api.dicebear.com/7.x/initials/svg?seed=CV-IT-Professional&backgroundColor=1e5ba6" alt="CV Template" class="img-fluid rounded border shadow-sm" style="max-height: 100%;">
                    <div class="cv-overlay position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-70 d-flex align-items-center justify-content-center opacity-0 transition-all">
                        <button class="btn btn-primary-blue fw-bold px-4 py-2"><i class="fa-solid fa-wand-magic-sparkles me-2"></i>Dùng mẫu này</button>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="fw-bold text-dark mb-1">Mẫu CV IT Professional</h5>
                    <p class="text-muted small mb-0">Thiết kế tối giản, làm nổi bật kỹ năng lập trình, dự án cá nhân và công nghệ sử dụng.</p>
                </div>
            </div>
        </div>

        <!-- Mẫu CV 2 -->
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100 overflow-hidden cv-card">
                <div class="position-relative bg-light border-bottom text-center p-4" style="height: 280px; overflow: hidden;">
                    <img src="https://api.dicebear.com/7.x/initials/svg?seed=CV-Creative-Pink&backgroundColor=ff4081" alt="CV Template" class="img-fluid rounded border shadow-sm" style="max-height: 100%;">
                    <div class="cv-overlay position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-70 d-flex align-items-center justify-content-center opacity-0 transition-all">
                        <button class="btn btn-primary-blue fw-bold px-4 py-2"><i class="fa-solid fa-wand-magic-sparkles me-2"></i>Dùng mẫu này</button>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="fw-bold text-dark mb-1">Mẫu CV Sáng Tạo (Creative)</h5>
                    <p class="text-muted small mb-0">Màu sắc trẻ trung, phù hợp cho các ngành Marketing, Thiết kế hoặc Truyền thông.</p>
                </div>
            </div>
        </div>

        <!-- Mẫu CV 3 -->
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100 overflow-hidden cv-card">
                <div class="position-relative bg-light border-bottom text-center p-4" style="height: 280px; overflow: hidden;">
                    <img src="https://api.dicebear.com/7.x/initials/svg?seed=CV-Executive-Dark&backgroundColor=37474f" alt="CV Template" class="img-fluid rounded border shadow-sm" style="max-height: 100%;">
                    <div class="cv-overlay position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-70 d-flex align-items-center justify-content-center opacity-0 transition-all">
                        <button class="btn btn-primary-blue fw-bold px-4 py-2"><i class="fa-solid fa-wand-magic-sparkles me-2"></i>Dùng mẫu này</button>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="fw-bold text-dark mb-1">Mẫu CV Cổ Điển Standard</h5>
                    <p class="text-muted small mb-0">Bố cục 1 cột truyền thống, tối ưu hóa từ khóa quét tự động bằng hệ thống ATS tuyển dụng.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .cv-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .cv-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .cv-card:hover .cv-overlay {
        opacity: 1 !important;
    }
    .transition-all {
        transition: all 0.3s ease;
    }
</style>

<!-- Nhúng Footer chung -->
<?php include_once '../../views/layouts/footer.php'; ?>