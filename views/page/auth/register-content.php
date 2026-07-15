<section class="min-vh-100 d-flex align-items-center py-4" style="background: url('../../public/images/city-bg.png') no-repeat bottom center; background-size: cover;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12" style="max-width: 850px;">
                <div class="card border-0 shadow-lg overflow-hidden">
                    <div class="row g-0" style="min-height: 480px;">
                        
                        <!-- trái: Điều hướng chọn Luồng đăng ký -->
                        <div class="col-md-5 bg-primary-blue text-white p-4 d-flex flex-column justify-content-center align-items-center text-center">
                            <h3 class="fw-bold mb-3">Chào Mừng Đến Với JobHub</h3>
                                                    <a href="../home/index.php">
                                                        <img src="../../public/images/logo.png" alt="JobHub Logo" class="mb-4 rounded-circle" width="80">
                                                    </a>
                            <p class="mb-4 text-white-50 fs-6">Chọn loại tài khoản phù hợp với bạn!</p>
                            
                            <!-- Tab điều hướng giữa ỨV và DN -->
                            <div class="nav flex-column nav-pills w-100 gap-2" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                <button class="nav-link text-white active border border-white-50" id="candidate-tab" data-bs-toggle="pill" data-bs-target="#candidate-form" type="button" role="tab">
                                    <i class="fa-solid fa-user-graduate me-2"></i> Dành cho Ứng Viên
                                </button>
                                <button class="nav-link text-white border border-white-50" id="employer-tab" data-bs-toggle="pill" data-bs-target="#employer-form" type="button" role="tab">
                                    <i class="fa-solid fa-building me-2"></i> Dành cho Nhà Tuyển Dụng
                                </button>
                            </div>
                        </div>

                        <!-- phải: Form nhập dữ liệu tương ứng -->
                        <div class="col-md-7 bg-white p-4">
                            <div class="tab-content" id="v-pills-tabContent">
                                
                                <!-- FORM 1: ĐĂNG KÝ ỨNG VIÊN -->
                                <div class="tab-pane fade show active" id="candidate-form" role="tabpanel">
                                    <h4 class="fw-bold text-primary-blue mb-4">Đăng Ký Tài Khoản Ứng Viên</h4>
                                    <form action="doRegisterCandidate.php" method="POST">
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">Họ và Tên</label>
                                            <input type="text" class="form-control py-2" placeholder="Nhập họ và tên của bạn" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">Địa chỉ Email</label>
                                            <input type="email" class="form-control py-2" placeholder="name@example.com" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">Mật khẩu</label>
                                            <input type="password" class="form-control py-2" placeholder="Tối thiểu 6 ký tự" required>
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label small fw-semibold">Xác nhận Mật khẩu</label>
                                            <input type="password" class="form-control py-2" placeholder="Nhập lại mật khẩu" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary-blue w-100 py-2 mb-3">Đăng Ký</button>
                                    </form>
                                    <div class="text-center position-relative my-4">
                                        <hr><span class="position-absolute top-50 start-50 translate-middle bg-white px-3 small text-muted">Hoặc tiếp tục với</span>
                                    </div>
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-outline-danger w-100 btn-sm py-2"><i class="fa-brands fa-google me-2"></i> Tiếp tục với Google</button>
                                        <button class="btn btn-outline-primary w-100 btn-sm py-2"><i class="fa-brands fa-facebook me-2"></i> Tiếp tục với Facebook</button>
                                    </div>
                                </div>

                                <!-- FORM 2: ĐĂNG KÝ NHÀ TUYỂN DỤNG -->
                                <div class="tab-pane fade" id="employer-form" role="tabpanel">
                                    <h4 class="fw-bold text-success mb-4">Đăng Ký Tài Khoản Doanh Nghiệp</h4>
                                    <form action="doRegisterEmployer.php" method="POST">
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">Tên Doanh Nghiệp / Công Ty</label>
                                            <input type="text" class="form-control py-2 border-success-subtle" placeholder="Nhập tên công ty chính thức" required>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label small fw-semibold">Mã Số Thuế / GPKD</label>
                                                <input type="text" class="form-control py-2 border-success-subtle" placeholder="Mã số thuế" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label small fw-semibold">Quy Mô Công Ty</label>
                                                <select class="form-select py-2 border-success-subtle">
                                                    <option>10-50 nhân viên</option>
                                                    <option>50-200 nhân viên</option>
                                                    <option>200+ nhân viên</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">Địa Chỉ Email Doanh Nghiệp</label>
                                            <input type="email" class="form-control py-2 border-success-subtle" placeholder="hr@company.com" required>
                                        </div>
                                        <div class>
                                            <div class="col mb-3">
                                                <label class="form-label small fw-semibold">Mật khẩu</label>
                                                <input type="password" class="form-control py-2 border-success-subtle" placeholder="Tối thiểu 6 ký tự" required>
                                            </div>
                                            <div class="col mb-4">
                                                <label class="form-label small fw-semibold">Xác nhận Mật khẩu</label>
                                                <input type="password" class="form-control py-2 border-success-subtle" placeholder="Nhập lại mật khẩu" required>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-success w-100 py-2 mb-3">Đăng Ký</button>
                                        <div class="text-center position-relative my-4">
                                        <hr><span class="position-absolute top-50 start-50 translate-middle bg-white px-3 small text-muted">Hoặc tiếp tục với</span>
                                    </div>
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-outline-danger w-100 btn-sm py-2"><i class="fa-brands fa-google me-2"></i> Tiếp tục với Google</button>
                                        <button class="btn btn-outline-primary w-100 btn-sm py-2"><i class="fa-brands fa-facebook me-2"></i> Tiếp tục với Facebook</button>
                                    </div>
                                    </form>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
