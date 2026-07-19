<?php
$baseUrl = '/JobCV';
$email = trim($_GET['email'] ?? '');
include_once '../../page/layouts/header.php';
?>

<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center position-relative py-5"
     style="background-color: #f0f4f8; background-image: url('../assets/images/bg-city.png'); background-size: cover; background-position: center;">

    <div class="card p-4 p-md-5 shadow-sm border-0" style="max-width: 760px; width: 100%; border-radius: 12px;">
        <div class="card-body p-0">
            <h2 class="card-title fw-bold mb-3" style="color: #2b5a8f;">Đặt lại mật khẩu</h2>
            <p class="text-secondary mb-4">Tạo mật khẩu mới cho tài khoản của bạn.</p>

            <form id="resetForm" novalidate>
                <input type="hidden" name="action" value="reset_password">

                <div class="mb-4">
                    <label for="email" class="form-label fw-semibold text-dark">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>" readonly style="box-shadow: none; padding: 10px 12px;">
                </div>

                <div class="mb-4">
                    <label for="matKhau" class="form-label fw-semibold text-dark">Mật khẩu mới</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-secondary">
                            <i class="bi bi-person"></i>
                        </span>
                        <input type="password" id="matKhau" name="matKhau" class="form-control border-start-0 border-end-0 ps-0" placeholder="Nhập mật khẩu mới..." required style="box-shadow: none;">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="matKhauConfirm" class="form-label fw-semibold text-dark">Nhập lại mật khẩu mới</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-secondary">
                            <i class="bi bi-person"></i>
                        </span>
                        <input type="password" id="matKhauConfirm" name="matKhauConfirm" class="form-control border-start-0 border-end-0 ps-0" placeholder="Nhập lại mật khẩu mới..." required style="box-shadow: none;">
                    </div>
                </div>

                <button type="button" id="btnResetPassword" class="btn text-white w-100 py-2.5 fw-semibold mb-4" style="background-color: #628cb6; border-radius: 4px; font-size: 1.05rem;">Lưu Mật Khẩu Mới</button>
                <div id="resetMessage" class="small"></div>

                <div class="text-start mt-3">
                    <a href="login.php" class="text-decoration-none text-dark fw-medium">Quay lại Đăng Nhập</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- <script>
const baseUrl = '<?= $baseUrl ?>';
const resetForm = document.getElementById('resetForm');
const resetMessage = document.getElementById('resetMessage');
const btnResetPassword = document.getElementById('btnResetPassword');

function showResetMessage(message, type = 'info') {
    resetMessage.className = `small mt-2 text-${type === 'success' ? 'success' : type === 'danger' ? 'danger' : 'secondary'}`;
    resetMessage.innerText = message;
}

btnResetPassword.addEventListener('click', () => {
    const formData = new FormData(resetForm);
    btnResetPassword.disabled = true;
    btnResetPassword.innerText = 'Đang xử lý...';

    fetch(`${baseUrl}/controllers/ForgotPasswordController.php`, { method: 'POST', body: formData })
        .then(response => response.json())
        .then(data => {
            showResetMessage(data.message, data.status === 'success' ? 'success' : 'danger');
            if (data.status === 'success') {
                setTimeout(() => {
                    window.location.href = `${baseUrl}/views/page/auth/login.php`;
                }, 1200);
            }
        })
        .catch(() => {
            showResetMessage('Không thể kết nối tới máy chủ.', 'danger');
        })
        .finally(() => {
            btnResetPassword.disabled = false;
            btnResetPassword.innerText = 'Lưu Mật Khẩu Mới';
        });
});
</script> -->

<script>
    window.appConfig = {
        baseUrl: '<?= $baseUrl ?>'
    };
</script>
<script src="<?= $baseUrl ?>/assets/js/reset-password.js"></script>