<!-- Nhúng Header chung của bạn -->
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
 include_once '../../views/layouts/header.php'; ?>

<div class="container py-5">
    <div class="row g-4">
        
        <!-- CỘT TRÁI: THÔNG TIN TÓM TẮT & DIỀU HƯỚNG NHANH (4/12) -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm p-4 text-center bg-white mb-4">
                <!-- Avatar hoạt hình mặc định hoặc tải lên -->
<div class="position-relative d-inline-block mx-auto mb-3" style="width: 110px; height: 110px;">
    <img src="https://api.dicebear.com/7.x/adventurer/svg?seed=ntl" alt="Avatar" class="rounded-circle border border-3 border-primary" style="width: 100%; height: 100%; object-fit: cover;">
    
    <!-- Đã sửa: Ép kích thước 1:1 (32px x 32px), triệt tiêu padding lệch và căn giữa icon hoàn hảo -->
    <span class="position-absolute bottom-0 end-0 bg-primary text-white shadow-sm d-flex align-items-center justify-content-center" 
          style="cursor: pointer; width: 32px; height: 32px; border-radius: 50%; padding: 0; margin: 0; border: 2px solid #fff;" 
          title="Đổi ảnh đại diện">
        <i class="fa-solid fa-camera fa-sm" style="line-height: 1;"></i>
    </span>
</div>
                
                <h5 class="fw-bold text-dark mb-1">Nguyễn Thị Liễu</h5> <!-- -->
                <p class="text-muted small mb-3">Mã UV: UV23111109</p> <!-- -->
                <span class="badge bg-light text-primary-blue border fw-semibold px-3 py-2 mb-4">Ứng viên / Lập trình viên</span>

                <!-- Menu Tab chuyển đổi nhanh bằng Bootstrap Nav-Pills -->
                <div class="nav flex-column nav-pills text-start gap-2" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <button class="nav-link active py-3 px-3 d-flex align-items-center gap-3 border-0" id="v-pills-info-tab" data-bs-toggle="pill" data-bs-target="#v-pills-info" type="button" role="tab" aria-selected="true">
                        <i class="fa-regular fa-user text-primary fs-5"></i>
                        <span>Thông tin cá nhân</span>
                    </button>
                    <button class="nav-link py-3 px-3 d-flex align-items-center gap-3 border-0" id="v-pills-cv-tab" data-bs-toggle="pill" data-bs-target="#v-pills-cv" type="button" role="tab" aria-selected="false">
                        <i class="fa-solid fa-file-pdf text-danger fs-5"></i>
                        <span>Quản lý hồ sơ & CV</span>
                    </button>
                    <a href="applied-jobs.php" class="nav-link py-3 px-3 d-flex align-items-center gap-3 border-0 text-decoration-none">
                        <i class="fa-solid fa-paper-plane text-success fs-5"></i>
                        <span>Việc làm đã ứng tuyển</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- CỘT PHẢI: NỘI DUNG CHI TIẾT THEO TAB (8/12) -->
        <div class="col-12 col-lg-8">
            <div class="tab-content" id="v-pills-tabContent">
                
                <!-- TAB 1: THÔNG TIN CÁ NHÂN -->
                <div class="tab-pane fade show active" id="v-pills-info" role="tabpanel" aria-labelledby="v-pills-info-tab">
                    <div class="card border-0 shadow-sm p-4 bg-white">
                        <h4 class="fw-bold mb-4 border-start border-4 border-primary ps-3 text-dark">Thông Tin Cá Nhân</h4>
                        
                        <form action="" method="POST">
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold text-dark">Họ và tên <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control py-2" value="Nguyễn Thị Liễu" required> <!-- -->
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold text-dark">Mã số ứng viên</label>
                                    <input type="text" class="form-control py-2" value="UV2311109" readonly> <!-- -->
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold text-dark">Email liên hệ <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control py-2" value="ntl@gmail.com" required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold text-dark">Số điện thoại <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control py-2" value="0987654321" required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold text-dark">Lĩnh vực hoạt động</label>
                                    <input type="text" class="form-control py-2" value="Công nghệ thông tin / Kỹ thuật phần mềm">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold text-dark">Vị trí mong muốn</label>
                                    <input type="text" class="form-control py-2" value="Junior Web Developer">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-dark">Địa chỉ</label>
                                    <input type="text" class="form-control py-2" value="Ninh Kiều, Cần Thơ"> <!-- -->
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-dark">Giới thiệu ngắn về bản thân</label>
                                    <textarea class="form-control" rows="4">Em là sinh viên IT mới ra trường, có khả năng tư duy thuật toán tốt, đang học hỏi phát triển các ứng dụng Web vững chắc bằng PHP/MySQL và cải thiện kỹ năng front-end.</textarea>
                                </div>
                            </div>
                            
                            <div class="mt-4 pt-3 border-top text-end">
                                <button type="submit" class="btn btn-primary-blue fw-bold px-4 py-2">Lưu Thay Đổi</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- TAB 2: QUẢN LÝ HỒ SƠ & CV -->
                <div class="tab-pane fade" id="v-pills-cv" role="tabpanel" aria-labelledby="v-pills-cv-tab">
                    <div class="card border-0 shadow-sm p-4 bg-white">
                        <h4 class="fw-bold mb-4 border-start border-4 border-primary ps-3 text-dark">Quản Lý Hồ Sơ & CV</h4>
                        
                        <!-- Khu vực Upload File CV mới -->
                        <div class="border border-2 border-dashed rounded-3 p-4 text-center bg-light mb-4 position-relative">
                            <input type="file" class="position-absolute top-0 start-0 w-100 h-100 opacity-0" style="cursor: pointer;" accept=".pdf,.doc,.docx">
                            <div class="py-3">
                                <i class="fa-solid fa-cloud-arrow-up text-primary-blue fs-1 mb-3"></i>
                                <h5 class="fw-bold text-dark">Kéo thả hoặc tải lên CV của bạn</h5>
                                <p class="text-muted small mb-0">Hỗ trợ định dạng .PDF, .DOCX, .DOC (Tối đa 5MB)</p>
                            </div>
                        </div>

                        <!-- Danh sách CV đã tải lên -->
                        <h6 class="fw-bold text-dark mb-3">Các file CV đã tải lên:</h6>
                        <div class="d-flex flex-column gap-3">
                            <!-- Card CV 1 -->
                            <div class="p-3 border rounded-3 bg-white d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="fs-2 text-danger"><i class="fa-solid fa-file-pdf"></i></span>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-1">Nguyen_Thi_Lieu_CV_2026.pdf</h6>
                                        <span class="text-muted small">Cập nhật: 12:30 - 15/07/2026</span>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="#" class="btn btn-light btn-sm text-primary-blue" title="Xem trước"><i class="fa-solid fa-eye"></i></a>
                                    <button class="btn btn-light btn-sm text-danger" title="Xóa"><i class="fa-solid fa-trash-can"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<!-- Thêm định dạng cho border nét đứt (Dashed Border) của khung upload -->
<style>
    .border-dashed {
        border-style: dashed !important;
        border-color: #1e5ba6 !important;
        transition: background-color 0.2s ease;
    }
    .border-dashed:hover {
        background-color: #eef4fc !important;
    }
</style>

<!-- Nhúng Footer chung -->
<?php include_once '../../views/layouts/footer.php'; ?>