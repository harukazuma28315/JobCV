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

            <form id="forgotForm" class="needs-validation" novalidate>
                <div class="mb-4">
                    <label for="email" class="form-label fw-semibold text-dark">Email</label>
                    <div class="row g-2">
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-secondary">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input type="email" id="email" name="email" class="form-control border-start-0 ps-0" placeholder="Nhập địa chỉ email..." required style="box-shadow: none;">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button type="button" id="btnGetOtp" class="btn text-white w-100 py-2 fw-semibold" style="background-color: #2b5a8f; border-radius: 4px;">Lấy Mã Xác Thực</button>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="otp" class="form-label fw-semibold text-dark">Nhập mã xác thực</label>
                    <input type="text" id="otp" name="otp" class="form-control" placeholder="Nhập mã gồm 6 chữ số..." required maxlength="6" style="box-shadow: none; padding: 10px 12px;">
                </div>

                <button type="button" id="btnVerifyOtp" class="btn text-white w-100 py-2.5 fw-semibold mb-4" style="background-color: #628cb6; border-radius: 4px; font-size: 1.05rem;">Xác Nhận</button>
                <div id="formMessage" class="small"></div>

                <div class="text-start mt-3">
                    <a href="<?= $baseUrl ?>/index.php?route=auth/login" class="text-decoration-none text-dark fw-medium">Quay lại Đăng Nhập</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- <script>
const baseUrl = '<?= $baseUrl ?>';
const emailInput = document.getElementById('email');
const otpInput = document.getElementById('otp');
const btnGetOtp = document.getElementById('btnGetOtp');
const btnVerifyOtp = document.getElementById('btnVerifyOtp');
const formMessage = document.getElementById('formMessage');

function setButtonState(button, disabled, text) {
    button.disabled = disabled;
    button.innerText = text;
}

function showMessage(message, type = 'info') {
    formMessage.className = `small mt-2 text-${type === 'success' ? 'success' : type === 'danger' ? 'danger' : 'secondary'}`;
    formMessage.innerText = message;
}

function startCooldown(seconds) {
    let remaining = seconds;
    setButtonState(btnGetOtp, true, `Đợi (${remaining}s)`);
    const timer = setInterval(() => {
        remaining--;
        if (remaining <= 0) {
            clearInterval(timer);
            setButtonState(btnGetOtp, false, 'Lấy Mã Xác Thực');
            return;
        }
        setButtonState(btnGetOtp, true, `Đợi (${remaining}s)`);
    }, 1000);
}

function sendForgotOtp() {
    const email = emailInput.value.trim();
    if (!email) {
        showMessage('Vui lòng nhập email trước khi lấy mã.', 'danger');
        return;
    }

    const formData = new FormData();
    formData.append('action', 'send_forgot_otp');
    formData.append('email', email);

    setButtonState(btnGetOtp, true, 'Đang gửi...');
    fetch(`${baseUrl}/controllers/ForgotPasswordController.php`, { method: 'POST', body: formData })
        .then(response => response.json())
        .then(data => {
            showMessage(data.message, data.status === 'success' ? 'success' : 'danger');
            if (data.status === 'success' || data.status === 'cooldown') {
                startCooldown(data.remaining || 60);
            } else {
                setButtonState(btnGetOtp, false, 'Lấy Mã Xác Thực');
            }
        })
        .catch(() => {
            showMessage('Không thể kết nối tới máy chủ.', 'danger');
            setButtonState(btnGetOtp, false, 'Lấy Mã Xác Thực');
        });
}

function verifyForgotOtp() {
    const email = emailInput.value.trim();
    const otp = otpInput.value.trim();

    if (!email || !otp) {
        showMessage('Vui lòng nhập đầy đủ email và mã OTP.', 'danger');
        return;
    }

    const formData = new FormData();
    formData.append('action', 'verify_otp');
    formData.append('email', email);
    formData.append('otp', otp);

    setButtonState(btnVerifyOtp, true, 'Đang xác nhận...');
    fetch(`${baseUrl}/controllers/ForgotPasswordController.php`, { method: 'POST', body: formData })
        .then(response => response.json())
        .then(data => {
            showMessage(data.message, data.status === 'success' ? 'success' : 'danger');
            if (data.status === 'success') {
                window.location.href = `${baseUrl}/index.php?route=auth/reset-password&email=${encodeURIComponent(email)}`;
            } else {
                setButtonState(btnVerifyOtp, false, 'Xác Nhận');
            }
        })
        .catch(() => {
            showMessage('Không thể kết nối tới máy chủ.', 'danger');
            setButtonState(btnVerifyOtp, false, 'Xác Nhận');
        });
}

btnGetOtp.addEventListener('click', sendForgotOtp);
btnVerifyOtp.addEventListener('click', verifyForgotOtp);
</script> -->

<script>
    window.appConfig = {
        baseUrl: '<?= $baseUrl ?>'
    };
</script>
<script src="<?= $baseUrl ?>/assets/js/forgot-password.js"></script>