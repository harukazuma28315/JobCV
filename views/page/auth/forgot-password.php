<?php include_once '../../views/layouts/header.php'; ?>
<!-- Phần thân trang Quên mật khẩu -->
<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center position-relative py-5" 
     style="background-color: #f0f4f8; background-image: url('../public/images/bg-city.png'); background-size: cover; background-position: center;">
    
    <div class="card p-4 p-md-5 shadow-sm border-0" style="max-width: 760px; width: 100%; border-radius: 12px;">
        <div class="card-body p-0">
            <!-- Tiêu đề -->
            <h2 class="card-title fw-bold mb-3" style="color: #2b5a8f;">Quên mật khẩu</h2>
            <p class="text-secondary mb-4">Vui lòng nhập địa chỉ email bạn đã đăng ký để nhận mã xác thực.</p>
            
            <form action="" method="POST">
                <!-- Ô nhập Email + Nút lấy mã -->
                <div class="mb-4">
                    <label for="email" class="form-label fw-semibold text-dark">Email</label>
                    <div class="row g-2">
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-secondary">
                                    <i class="bi bi-person"></i> <!-- Sử dụng Bootstrap Icon hoặc FontAwesome nếu có -->
                                </span>
                                <input type="email" id="email" name="email" class="form-control border-start-0 ps-0" placeholder="Nhập địa chỉ email..." required style="box-shadow: none;">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn text-white w-100 py-2 fw-semibold" style="background-color: #2b5a8f; border-radius: 4px;">Lấy Mã Xác Thực</button>
                        </div>
                    </div>
                </div>

                <!-- Ô nhập mã xác thực -->
                <div class="mb-4">
                    <label for="verification_code" class="form-label fw-semibold text-dark">Nhập mã xác thực</label>
                    <input type="text" id="verification_code" name="verification_code" class="form-control" placeholder="Nhập mã gồm 6 chữ số..." required style="box-shadow: none; padding: 10px 12px;">
                </div>

                <!-- Nút Xác Nhận -->
                <button type="submit" class="btn text-white w-100 py-2.5 fw-semibold mb-4" style="background-color: #628cb6; border-radius: 4px; font-size: 1.05rem;">Xác Nhận</button>

                <!-- Quay lại đăng nhập -->
                <div class="text-start">
                    <a href="login.php" class="text-decoration-none text-dark fw-medium">Quay lại Đăng Nhập</a>
                </div>
            </form>
        </div>
    </div>
</div>
