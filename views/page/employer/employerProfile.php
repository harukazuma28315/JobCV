<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$baseUrl = '/JobCV';
// require_once __DIR__ . '/../../../config/db.php';
// require_once __DIR__ . '/../../../controllers/ProfileController.php';
// require_once __DIR__ . '/../../../controllers/LogoutController.php';
include_once __DIR__ . '/../layouts/header.php'; 

// $logoutCtrl = new LogoutController($conn);
// $logoutCtrl->handleLogout();

// $profileCtrl = new ProfileController($conn);
// $profileCtrl->handleUpdateProfile();
// $profile = $profileCtrl->handleGetProfile();

$resetEmail = $_SESSION['user_email'] ?? $profile['email'] ?? '';
?>

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
                        
                        <span class="position-absolute bottom-0 end-0 bg-primary-blue text-white shadow-sm d-flex align-items-center justify-content-center" 
                              style="cursor: pointer; width: 28px; height: 28px; border-radius: 50%; padding: 0; margin: 0; border: 2px solid #fff;" 
                              title="Đổi Logo">
                            <i class="fa-solid fa-camera" style="font-size: 10px; line-height: 1;"></i>
                        </span>
                    </div>
                </div>
                <!-- Thông tin tóm tắt -->
                <div class="card-body text-center pt-2">
                    <h5 class="fw-bold text-dark mb-1"><?php echo htmlspecialchars($profile['companyName'] ?? 'Chưa cập nhật'); ?></h5>
                    <p class="text-muted small mb-3">Thông tin tài khoản nhà tuyển dụng</p>
                    <span class="badge bg-light text-primary-blue border fw-semibold px-3 py-2 mb-3">Nhà tuyển dụng</span>
                    <hr class="my-3">
                    <div class="text-start">
                        <p class="small text-muted mb-2"><i class="fa-solid fa-globe me-2 text-primary-blue"></i> Website: <a href="<?php echo htmlspecialchars($profile['website'] ?? '#'); ?>" target="_blank" class="text-decoration-none"><?php echo htmlspecialchars($profile['website'] ?? 'Chưa cập nhật'); ?></a></p>
                        <p class="small text-muted mb-2"><i class="fa-solid fa-industry me-2 text-primary-blue"></i> Lĩnh vực: <strong><?php echo htmlspecialchars($profile['industry'] ?? 'Chưa cập nhật'); ?></strong></p>
                        <p class="small text-muted mb-2"><i class="fa-solid fa-location-dot me-2 text-primary-blue"></i> Địa chỉ: <?php echo htmlspecialchars($profile['address'] ?? 'Chưa cập nhật'); ?></p>
                        <p class="small text-muted mb-0"><i class="fa-solid fa-id-card me-2 text-primary-blue"></i> Mã số thuế: <?php echo htmlspecialchars($profile['taxCode'] ?? 'Chưa cập nhật'); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cột phải: Form cập nhật thông tin chi tiết -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm p-4 bg-white rounded-3">
                <h4 class="fw-bold mb-4 border-start border-4 border-primary-blue ps-3 text-dark">Thông Tin Doanh Nghiệp</h4>
                
                <form action="<?= $baseUrl ?>/index.php?route=profile/update" method="POST" class="needs-validation" novalidate id="profileForm">
                    <input type="hidden" name="action" value="update">
                    <div class="row g-3">
                        <!-- Tên công ty (Chỉ đọc) -->
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold text-dark">Tên công ty / Doanh nghiệp</label>
                            <input type="text" class="form-control py-2" value="<?php echo htmlspecialchars($profile['companyName'] ?? ''); ?>" readonly>
                        </div>

                        <!-- Mã số thuế (Chỉ đọc) -->
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold text-dark">Mã số thuế / Giấy phép KD</label>
                            <input type="text" class="form-control py-2" value="<?php echo htmlspecialchars($profile['taxCode'] ?? ''); ?>" readonly>
                        </div>

                        <!-- Email (Chỉ đọc) -->
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold text-dark">Email liên hệ tuyển dụng</label>
                            <input type="email" class="form-control py-2" value="<?php echo htmlspecialchars($profile['email'] ?? ''); ?>" readonly>
                        </div>

                        <!-- Số điện thoại -->
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold text-dark">Số điện thoại liên hệ <span class="text-danger">*</span></label>
                            <input type="tel" name="sdt" class="form-control py-2" value="<?php echo htmlspecialchars($profile['phone'] ?? ''); ?>" required inputmode="numeric" maxlength="10" pattern="(03|05|07|08|09)[0-9]{8}" oninput="this.value = this.value.replace(/\D/g, '')" title="Số điện thoại phải bao gồm 10 chữ số chuẩn nhà mạng Việt Nam">
                            <div class="invalid-feedback">Vui lòng nhập đúng 10 số điện thoại đầu số Việt Nam.</div>
                        </div>

                        <!-- Website công ty -->
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold text-dark">Website công ty</label>
                            <input type="url" name="website" class="form-control py-2" value="<?php echo htmlspecialchars($profile['website'] ?? ''); ?>" placeholder="https://example.com" maxlength="255" pattern="https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&//=]*)" oninput="this.value = this.value.replace(/\s/g, '').replace(/[^a-zA-Z0-9\.\:\/\_\-\?\=\&\#]/g, '')" title="Địa chỉ website phải bắt đầu bằng http:// hoặc https:// (ví dụ: https://example.com)">
                            <div class="invalid-feedback">Website phải bắt đầu bằng http:// hoặc https:// (ví dụ: https://example.com)</div>
                        </div>

                        <!-- Lĩnh vực hoạt động -->
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold text-dark">Lĩnh vực hoạt động</label>
                            <input type="text" name="linhVuc" class="form-control py-2" value="<?php echo htmlspecialchars($profile['industry'] ?? ''); ?>" maxlength="150">
                        </div>

                        <!-- Địa chỉ trụ sở -->
                        <div class="col-12">
                            <label class="form-label fw-semibold text-dark">Địa chỉ trụ sở chính <span class="text-danger">*</span></label>
                            <input type="text" name="diaChi" class="form-control py-2" value="<?php echo htmlspecialchars($profile['address'] ?? ''); ?>" required maxlength="255" pattern="^[a-zA-Z0-9àáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđĐ\s,\/]+$" oninput="this.value = this.value.replace(/[^a-zA-Z0-9àáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđĐ\s,\/]/g, '')" title="Địa chỉ chỉ được chứa chữ cái, số, khoảng trắng, dấu phẩy (,) và dấu xuyệt (/). Không chứa các ký tự đặc biệt khác">
                            <div class="invalid-feedback">Địa chỉ chứa ký tự không hợp lệ.</div>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-3 border-top text-end">
                        <a href="<?= $baseUrl ?>/index.php?route=auth/logout" class="btn btn-outline-danger fw-bold px-4 py-2 me-auto" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')">
                            <i class="fa-solid fa-right-from-bracket me-2"></i>Đăng Xuất
                        </a>
                        <a href="<?= $baseUrl ?>/index.php?route=auth/forgot-password&email=<?= urlencode($resetEmail) ?>" class="btn btn-outline-warning fw-bold px-4 py-2" onclick="return true;">
                            <i class="fa-solid fa-key me-2"></i>Đổi Mật Khẩu
                        </a>
                        <button type="submit" class="btn btn-primary-blue fw-bold px-4 py-2">Lưu Thay Đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('profileForm');
    if (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    }
});
</script>

<!-- Nhúng Footer chung -->
<?php include_once __DIR__ . '/../layouts/footer.php'; ?>