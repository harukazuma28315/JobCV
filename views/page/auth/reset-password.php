<?php
$baseUrl = '/JobCV';
$email = trim($_GET['email'] ?? '');
include_once __DIR__ . '/../layouts/header.php';
?>

<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center position-relative py-5"
	 style="background-color: #f0f4f8; background-image: url('../assets/images/bg-city.png'); background-size: cover; background-position: center;">

	<div class="card p-4 p-md-5 shadow-sm border-0" style="max-width: 760px; width: 100%; border-radius: 12px;">
		<div class="card-body p-0">
			<h2 class="card-title fw-bold mb-3" style="color: #2b5a8f;">Đặt lại mật khẩu</h2>
			<p class="text-secondary mb-4">Tạo mật khẩu mới cho tài khoản của bạn (từ 6 đến 32 ký tự, không chứa khoảng trắng).</p>

			<!-- Bỏ novalidate nếu muốn dùng bong bóng thông báo mặc định của trình duyệt, 
				 hoặc giữ needs-validation để dùng Bootstrap Validation UI -->
			<form id="resetForm" class="needs-validation" novalidate autocomplete="off">
				<input type="hidden" name="action" value="reset_password">

				<div class="mb-4">
					<label for="email" class="form-label fw-semibold text-dark">Email tài khoản</label>
					<div class="input-group">
						<span class="input-group-text bg-white border-end-0 text-secondary">
							<i class="bi bi-envelope"></i>
						</span>
						<input type="email" id="email" name="email" class="form-control border-start-0 ps-0" value="<?= htmlspecialchars($email) ?>" readonly style="box-shadow: none; padding: 10px 12px; background-color: #f8f9fa;">
					</div>
				</div>

				<div class="mb-4">
					<label for="matKhau" class="form-label fw-semibold text-dark">Mật khẩu mới <span class="text-danger">*</span></label>
					<div class="input-group has-validation">
						<span class="input-group-text bg-white border-end-0 text-secondary">
							<i class="bi bi-lock"></i>
						</span>
						<input type="password" id="matKhau" name="matKhau" class="form-control border-start-0 ps-0" placeholder="Nhập mật khẩu mới (6-32 ký tự)" required minlength="6" maxlength="32" pattern="^\S{6,32}$" oninput="this.value = this.value.replace(/\s/g, '')" title="Mật khẩu phải từ 6 đến 32 ký tự và không được chứa khoảng trắng" autocomplete="new-password" style="box-shadow: none;">
						<div class="invalid-feedback">
							Mật khẩu phải từ 6 đến 32 ký tự và không chứa khoảng trắng!
						</div>
					</div>
				</div>

				<div class="mb-4">
					<label for="matKhauConfirm" class="form-label fw-semibold text-dark">Nhập lại mật khẩu mới <span class="text-danger">*</span></label>
					<div class="input-group has-validation">
						<span class="input-group-text bg-white border-end-0 text-secondary">
							<i class="bi bi-shield-check"></i>
						</span>
						<input type="password" id="matKhauConfirm" name="matKhauConfirm" class="form-control border-start-0 ps-0" placeholder="Nhập lại mật khẩu mới" required minlength="6" maxlength="32" pattern="^\S{6,32}$" oninput="this.value = this.value.replace(/\s/g, '')" title="Mật khẩu phải từ 6 đến 32 ký tự và không được chứa khoảng trắng" autocomplete="new-password" style="box-shadow: none;">
						<div class="invalid-feedback">
							Vui lòng nhập lại mật khẩu từ 6 đến 32 ký tự!
						</div>
					</div>
				</div>

				<button type="submit" id="btnResetPassword" class="btn text-white w-100 py-2.5 fw-semibold mb-3" style="background-color: #628cb6; border-radius: 4px; font-size: 1.05rem;">Lưu Mật Khẩu Mới</button>
				<div id="resetMessage" class="small"></div>

				<div class="text-start mt-3">
					<a href="<?= $baseUrl ?>/index.php?route=auth/forgot-password" class="text-decoration-none text-dark fw-medium">
						<i class="bi bi-arrow-left me-1"></i>Quay lại
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
<script src="<?= $baseUrl ?>/assets/js/reset-password.js"></script>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>