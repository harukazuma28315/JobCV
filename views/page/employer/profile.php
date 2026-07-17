<!-- Nhúng Header chung -->
<?php include_once '../../views/layouts/header.php'; ?>

<div class="container py-5">
    <div class="row g-4">
<!-- Cột trái: Xem nhanh Logo, Ảnh bìa & Trạng thái -->
<div class="col-12 col-lg-4">
    <div class="card border-0 shadow-sm overflow-hidden bg-white rounded-3">
        <!-- Ảnh bìa doanh nghiệp -->
        <div class="position-relative" style="height: 120px; background-color: #e9ecef;">
            <img src="https://images.unsplash.com/photo-1557804506-669a67965ba0?auto=format&fit=crop&w=800&q=80" alt="Cover" class="w-100 h-100 style-cover" style="object-fit: cover;">
        </div>

        <!-- Logo đè lên ảnh bìa -->
        <div class="text-center position-relative" style="margin-top: -50px; z-index: 2;">
            <div class="position-relative d-inline-block" style="width: 100px; height: 100px;">
                <img src="https://api.dicebear.com/7.x/identicon/svg?seed=vng" alt="Logo" class="rounded-circle border border-3 border-white p-1 bg-white shadow-sm" style="width: 100%; height: 100%; object-fit: contain;">
                
                <!-- Đã sửa: Nút camera sửa Logo tròn xoe 28px x 28px, căn giữa hồng tâm -->
                <span class="position-absolute bottom-0 end-0 bg-primary-blue text-white shadow-sm d-flex align-items-center justify-content-center" 
                      style="cursor: pointer; width: 28px; height: 28px; border-radius: 50%; padding: 0; margin: 0; border: 2px solid #fff;" 
                      title="Đổi Logo">
                    <i class="fa-solid fa-camera" style="font-size: 10px; line-height: 1;"></i>
                </span>
            </div>
        </div>
                <!-- Thông tin tóm tắt -->
                <div class="card-body text-center pt-2">
                    <h5 class="fw-bold text-dark mb-1">VNG Corporation</h5>
                    <p class="text-muted small mb-3">Kiến tạo công nghệ và Phát triển con người</p>
                    <span class="badge bg-light text-primary-blue border fw-semibold px-3 py-2 mb-3">Nhà tuyển dụng chuyên nghiệp</span>
                    <hr class="my-3">
                    <div class="text-start">
                        <p class="small text-muted mb-2"><i class="fa-solid fa-globe me-2 text-primary-blue"></i> Website: <a href="https://vng.com.vn" target="_blank" class="text-decoration-none">vng.com.vn</a></p>
                        <p class="small text-muted mb-2"><i class="fa-solid fa-users me-2 text-primary-blue"></i> Quy mô: <strong>1000+ nhân sự</strong></p>
                        <p class="small text-muted mb-0"><i class="fa-solid fa-location-dot me-2 text-primary-blue"></i> Trụ sở: Ninh Kiều, Cần Thơ</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cột phải: Form cập nhật thông tin chi tiết -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm p-4 bg-white rounded-3">
                <h4 class="fw-bold mb-4 border-start border-4 border-primary-blue ps-3 text-dark">Thông Tin Doanh Nghiệp</h4>
                
                <form action="" method="POST">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold text-dark">Tên công ty / Doanh nghiệp <span class="text-danger">*</span></label>
                            <input type="text" class="form-control py-2" value="VNG Corporation" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold text-dark">Mã số thuế / Giấy phép KD <span class="text-danger">*</span></label>
                            <input type="text" class="form-control py-2" value="0303867111" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold text-dark">Email liên hệ tuyển dụng <span class="text-danger">*</span></label>
                            <input type="email" class="form-control py-2" value="hr@vng.com.vn" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold text-dark">Số điện thoại liên hệ <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control py-2" value="02839623888" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold text-dark">Website công ty</label>
                            <input type="url" class="form-control py-2" value="https://vng.com.vn">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold text-dark">Quy mô nhân sự <span class="text-danger">*</span></label>
                            <select class="form-select py-2" required>
                                <option value="10-50">10 - 50 nhân sự</option>
                                <option value="50-150">50 - 150 nhân sự</option>
                                <option value="150-500">150 - 500 nhân sự</option>
                                <option value="500-1000">500 - 1000 nhân sự</option>
                                <option value="1000+" selected>Trên 1000 nhân sự</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold text-dark">Địa chỉ trụ sở chính <span class="text-danger">*</span></label>
                            <input type="text" class="form-control py-2" value="Đường 3/2, Xuân Khánh, Ninh Kiều, Cần Thơ" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold text-dark">Môi trường làm việc & Giới thiệu chung</label>
                            <textarea class="form-control" rows="5">VNG là doanh nghiệp công nghệ Việt Nam, thành lập năm 2004. Với sứ mệnh "Kiến tạo công nghệ và Phát triển con người", VNG liên tục mang lại các sản phẩm công nghệ chất lượng cho người dùng toàn cầu và xây dựng môi trường làm việc sáng tạo, năng động hàng đầu Việt Nam.</textarea>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-3 border-top text-end">
                        <button type="submit" class="btn btn-primary-blue fw-bold px-4 py-2">Lưu Thay Đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Nhúng Footer chung -->
<?php include_once '../../views/layouts/footer.php'; ?>