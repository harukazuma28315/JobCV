<?php
$baseUrl = '/JobCV';
include_once __DIR__ . '/../layouts/header.php';
?>
<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center position-relative py-5"
     style="background-color: #f0f4f8; background-image: url('../assets/images/bg-city.png'); background-size: cover; background-position: center;">

    <div class="card p-4 p-md-5 shadow-sm border-0" style="max-width: 760px; width: 100%; border-radius: 12px;">
        <div class="card-body p-0">
            <h2 class="card-title fw-bold mb-3" style="color: #2b5a8f;">Quên mật khẩu</h2>
            <p class="text-secondary mb-4">Nhập email đã đăng ký để nhận mã xác thực. Mỗi lần lấy mã lại sẽ cần chờ 60 giây.</p>

            <form id="forgotForm" class="needs-validation" novalidate autocomplete="on">
                <div class="mb-4">
                    <label for="email" class="form-label fw-semibold text-dark">Email <span class="text-danger">*</span></label>
                    <div class="row g-2">
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-secondary">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="email" id="email" name="email" class="form-control border-start-0 ps-0" placeholder="Nhập địa chỉ email..." required maxlength="100" autocomplete="email" style="box-shadow: none;">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button type="button" id="btnGetOtp" class="btn text-white w-100 py-2 fw-semibold" style="background-color: #2b5a8f; border-radius: 4px;">Lấy Mã Xác Thực</button>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="otp" class="form-label fw-semibold text-dark">Nhập mã xác thực <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-secondary">
                            <i class="bi bi-shield-lock"></i>
                        </span>
                        <input type="text" id="otp" name="otp" class="form-control border-start-0 ps-0" placeholder="Nhập mã gồm 6 chữ số..." required minlength="6" maxlength="6" inputmode="numeric" pattern="[0-9]{6}" oninput="this.value = this.value.replace(/\D/g, '')" autocomplete="one-time-code" style="box-shadow: none; padding: 10px 12px;">
                    </div>
                </div>

                <button type="button" id="btnVerifyOtp" class="btn text-white w-100 py-2.5 fw-semibold mb-3" style="background-color: #628cb6; border-radius: 4px; font-size: 1.05rem;">Xác Nhận</button>
                <div id="formMessage" class="small"></div>

                <div class="text-start mt-3">
                    <a href="<?= $baseUrl ?>/index.php?route=auth/login" class="text-decoration-none text-dark fw-medium">
                        <i class="bi bi-arrow-left me-1"></i>Quay lại Đăng Nhập
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    window.appConfig = {
        baseUrl: '<?= $baseUrl ?>'
    };
</script>
<script src="<?= $baseUrl ?>/assets/js/forgot-password.js"></script>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>