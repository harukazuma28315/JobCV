<section class="min-vh-100 d-flex align-items-center py-5" style="background: url('<?= $baseUrl ?>/public/images/city-bg.png') no-repeat bottom center; background-size: cover;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12" style="max-width: 850px;">
                <div class="card border-0 shadow-lg overflow-hidden">
                    <div class="row g-0" style="min-height: 480px;">
                        
                        <!-- trái -->
                        <div class="col-md-5 bg-primary-blue d-flex flex-column justify-content-center align-items-center text-white p-5 text-center">
                            <h3 class="fw-bold mb-3">Chào Mừng Trở Lại!</h3>
                            <a href="../home/index.php">
                                <img src="<?= $baseUrl ?>/public/images/logo1.png" alt="JobHub Logo" class="mb-4 rounded-circle" width="80">
                            </a>
                            <p class="small text-white-50 px-2 mb-0">Đăng nhập để tiếp tục kết nối với mọi người!</p>
                        </div>

                        <!-- phải -->
                        <div class="col-md-7 bg-white p-5 d-flex flex-column justify-content-center">
                            <h4 class="fw-bold text-primary-blue mb-4 text-center text-md-start">Đăng Nhập Tài Khoản</h4>
                            
                            <form action="<?= $baseUrl ?>/controllers/LoginController.php" method="POST">
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Địa chỉ Email / Tài khoản</label>
                                    <input type="text" name="Email" class="form-control py-2" placeholder="name@example.com" required>
                                </div>
                                
                                <!-- Ô mật khẩu (đã bỏ link quên mật khẩu) -->
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Mật khẩu</label>
                                    <input type="password" name="MatKhau" class="form-control py-2" placeholder="Nhập mật khẩu của bạn" required>
                                </div>
                                
                                <!-- Ghi nhớ đăng nhập & Quên mật khẩu nằm chung một hàng -->
                                <div class="mb-4 d-flex justify-content-between align-items-center">
                                    <div class="form-check mb-0">
                                        <input type="checkbox" class="form-check-input" id="rememberMe">
                                        <label class="form-check-label text-secondary small" for="rememberMe">Ghi nhớ đăng nhập</label>
                                    </div>
                                    <a href="#" class="text-primary-blue text-decoration-none small">Quên mật khẩu?</a>
                                </div>
                                
                                <button type="submit" class="btn btn-primary-blue w-100 py-2 fw-semibold mb-3">
                                    Đăng Nhập
                                </button>
                                <div class="text-center position-relative my-4">
                                    <hr>
                                    <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 small text-muted">Hoặc tiếp tục với</span>
                                </div>
                                <div class="d-grid gap-2">
                                    <button type="button" class="btn btn-outline-danger w-100 btn-sm py-2">
                                        <i class="fa-brands fa-google me-2"></i> Tiếp tục với Google
                                    </button>
                                    <button type="button" class="btn btn-outline-primary w-100 btn-sm py-2">
                                        <i class="fa-brands fa-facebook me-2"></i> Tiếp tục với Facebook
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>