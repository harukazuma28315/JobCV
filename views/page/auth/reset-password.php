<?php include_once '../../views/layouts/header.php'; ?>

<!-- Phần thân trang Đặt lại mật khẩu -->
<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center position-relative py-5" 
     style="background-color: #f0f4f8; background-image: url('../public/images/bg-city.png'); background-size: cover; background-position: center;">
    
    <div class="card p-4 p-md-5 shadow-sm border-0" style="max-width: 760px; width: 100%; border-radius: 12px;">
        <div class="card-body p-0">
            <!-- Tiêu đề -->
            <h2 class="card-title fw-bold mb-3" style="color: #2b5a8f;">Đặt lại mật khẩu</h2>
            <p class="text-secondary mb-4">Tạo mật khẩu mới cho tài khoản của bạn.</p>
            
            <form action="" method="POST">
                <!-- Mật khẩu mới -->
                <div class="mb-4">
                    <label for="new_password" class="form-label fw-semibold text-dark">Mật khẩu mới</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-secondary">
                            <i class="bi bi-person"></i>
                        </span>
                        <input type="password" id="new_password" name="new_password" class="form-control border-start-0 border-end-0 ps-0" placeholder="Nhập mật khẩu mới..." required style="box-shadow: none;">
                        <span class="input-group-text bg-white border-start-0 text-secondary" style="cursor: pointer;">
                            <i class="bi bi-eye-slash"></i> <!-- Icon ẩn/hiện mật khẩu -->
                        </span>
                    </div>
                </div>

                <!-- Nhập lại mật khẩu mới -->
                <div class="mb-4">
                    <label for="confirm_password" class="form-label fw-semibold text-dark">Nhập lại mật khẩu mới</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-secondary">
                            <i class="bi bi-person"></i>
                        </span>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control border-start-0 border-end-0 ps-0" placeholder="Nhập lại mật khẩu mới..." required style="box-shadow: none;">
                        <span class="input-group-text bg-white border-start-0 text-secondary" style="cursor: pointer;">
                            <i class="bi bi-eye-slash"></i>
                        </span>
                    </div>
                </div>

                <!-- Nút Lưu Mật Khẩu Mới -->
                <button type="submit" class="btn text-white w-100 py-2.5 fw-semibold mb-4" style="background-color: #628cb6; border-radius: 4px; font-size: 1.05rem;">Lưu Mật Khẩu Mới</button>

                <!-- Quay lại đăng nhập -->
                <div class="text-start">
                    <a href="login.php" class="text-decoration-none text-dark fw-medium">Quay lại Đăng Nhập</a>
                </div>
            </form>
        </div>
    </div>
</div>

