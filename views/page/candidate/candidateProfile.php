<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$baseUrl = '/JobCV';
require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../../controllers/ProfileController.php';
require_once __DIR__ . '/../../../controllers/LogoutController.php';
include_once __DIR__ . '/../layouts/header.php';

if (!isset($conn)) {
    $conn = Database::getConnection(); 
}

$logoutCtrl = new LogoutController($conn);
$logoutCtrl->handleLogout();

$profileCtrl = new ProfileController($conn);
$profileCtrl->handleUpdateProfile();
$profileData = $profileCtrl->handleGetProfile();
$resetEmail = $_SESSION['user_email'] ?? $profileData['email'] ?? '';

?>

<div class="container py-5">
    <div class="row g-4">
        
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm p-4 text-center bg-white mb-4">
                <div class="position-relative d-inline-block mx-auto mb-3" style="width: 110px; height: 110px;">
                    <img src="https://api.dicebear.com/7.x/adventurer/svg?seed=ntl" alt="Avatar" class="rounded-circle border border-3 border-primary" style="width: 100%; height: 100%; object-fit: cover;">
                    
                    <span class="position-absolute bottom-0 end-0 bg-primary text-white shadow-sm d-flex align-items-center justify-content-center" 
                          style="cursor: pointer; width: 32px; height: 32px; border-radius: 50%; padding: 0; margin: 0; border: 2px solid #fff;" 
                          title="Đổi ảnh đại diện">
                        <i class="fa-solid fa-camera fa-sm" style="line-height: 1;"></i>
                    </span>
                </div>
                
                <h5 class="fw-bold text-dark mb-1"><?php echo htmlspecialchars($profileData['fullname'] ?? ''); ?></h5> 
                <span class="badge bg-light text-primary-blue border fw-semibold px-3 py-2 mb-4">Ứng viên</span>

                <div class="nav flex-column nav-pills text-start gap-2" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <a href="index.php?route=cv/create" class="nav-link py-3 px-3 d-flex align-items-center gap-3 border-0 text-decoration-none">
                        <i class="fa-solid fa-file-pdf text-danger fs-5"></i>
                        <span>Quản lý hồ sơ & CV</span>
                    </a>
                    <a href="index.php?route=applications/applied" class="nav-link py-3 px-3 d-flex align-items-center gap-3 border-0 text-decoration-none">
                        <i class="fa-solid fa-paper-plane text-success fs-5"></i>
                        <span>Việc làm đã ứng tuyển</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-8">
            <div class="tab-content" id="v-pills-tabContent">
                
                <div class="tab-pane fade show active" id="v-pills-info" role="tabpanel" aria-labelledby="v-pills-info-tab">
                    <div class="card border-0 shadow-sm p-4 bg-white">
                        <h4 class="fw-bold mb-4 border-start border-4 border-primary ps-3 text-dark">Thông Tin Cá Nhân</h4>
                        
                        <form action="<?= $baseUrl ?>/index.php?route=profile/update" method="POST" class="needs-validation" novalidate id="candidateForm">
                            <input type="hidden" name="action" value="update">
                            <div class="row g-3">
                                <!-- Họ và tên (Giống trang Đăng ký) -->
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold text-dark">Họ và tên <span class="text-danger">*</span></label>
                                    <input type="text" name="hoTen" id="HoTen" class="form-control py-2" value="<?php echo htmlspecialchars($profileData['fullname'] ?? ''); ?>" required maxlength="100"
                                           pattern="^[a-zA-ZàáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđĐ\s\.]+$"
                                           oninput="this.value = this.value.replace(/[^a-zA-ZàáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđĐ\s\.]/g, '').replace(/\s+/g, ' ');"
                                           title="Họ và tên chỉ bao gồm chữ cái và khoảng trắng, không chứa ký tự đặc biệt">
                                </div>
                                
                                <!-- Email (Chỉ đọc) -->
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold text-dark">Email liên hệ</label>
                                    <input type="email" name="email" class="form-control py-2" value="<?php echo htmlspecialchars($profileData['email'] ?? ''); ?>" readonly>
                                </div>

                                <!-- Số điện thoại (Giống trang Đăng ký) -->
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold text-dark">Số điện thoại <span class="text-danger">*</span></label>
                                    <input type="text" name="sdt" class="form-control py-2" value="<?php echo htmlspecialchars($profileData['phone'] ?? ''); ?>" required placeholder="Nhập 10 chữ số (vd: 0912345678)" inputmode="numeric" maxlength="10" pattern="0[0-9]{9}" oninput="this.value = this.value.replace(/[^0-9]/g, '')" title="Số điện thoại phải bao gồm đúng 10 chữ số và bắt đầu bằng số 0">
                                </div>

                                <!-- Ngày sinh -->
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold text-dark">Ngày sinh</label>
                                    <input type="date" name="ngaySinh" class="form-control py-2" value="<?php echo htmlspecialchars($profileData['birthDate'] ?? ''); ?>">
                                </div>

                                <!-- Giới tính -->
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold text-dark">Giới tính</label>
                                    <select name="gioiTinh" class="form-select py-2">
                                        <option value="1" <?php echo (isset($profileData['gender']) && (int)$profileData['gender'] === 1) ? 'selected' : ''; ?>>Nam</option>
                                        <option value="0" <?php echo (isset($profileData['gender']) && (int)$profileData['gender'] === 0) ? 'selected' : ''; ?>>Nữ</option>
                                    </select>
                                </div>

                                <!-- Địa chỉ (Giống trang Đăng ký) -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-dark">Địa chỉ</label>
                                    <input type="text" name="diaChi" class="form-control py-2" value="<?php echo htmlspecialchars($profileData['address'] ?? ''); ?>" maxlength="255" pattern="^[a-zA-Z0-9àáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđĐ\s,\/]+$" oninput="this.value = this.value.replace(/[^a-zA-Z0-9àáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđĐ\s,\/]/g, '')" title="Địa chỉ chỉ được chứa chữ cái, số, khoảng trắng, dấu phẩy (,) và dấu xuyệt (/). Không chứa các ký tự đặc biệt khác">
                                </div>
                            </div>
                            
                            <div class="mt-4 pt-3 border-top text-end">
                                <a href="<?= $baseUrl ?>/index.php?route=auth/logout" class="btn btn-outline-danger fw-bold px-4 py-2 me-auto" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')">
                                    <i class="fa-solid fa-right-from-bracket me-2"></i>Đăng Xuất
                                </a>
                                <a href="<?= $baseUrl ?>/index.php?route=auth/forgot-password&email=<?= urlencode($resetEmail) ?>" class="btn btn-outline-warning fw-bold px-4 py-2" onclick="return true;">
                                    <i class="fa-solid fa-key me-2"></i>Đổi Mật Khẩu
                                </a>
                                <button type="submit" name="btn_submit" class="btn btn-primary-blue fw-bold px-4 py-2">Lưu Thay Đổi</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const hoTenInput = document.getElementById('HoTen');
    if (hoTenInput) {
        hoTenInput.addEventListener('blur', function () {
            this.value = this.value.trim().replace(/\s+/g, ' ');
        });
    }

    const form = document.getElementById('candidateForm');
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

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>