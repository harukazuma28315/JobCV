<section class="min-vh-100 d-flex align-items-center py-4" style="background: url('<?= $baseUrl ?>/assets/images/city-bg.png') no-repeat bottom center; background-size: cover;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12" style="max-width: 950px;">
                <div class="card border-0 shadow-lg overflow-hidden">
                    <div class="row g-0" style="min-height: 620px;">

                        <div class="col-md-5 bg-primary-blue text-white p-4 d-flex flex-column justify-content-center align-items-center text-center">
                            <h3 class="fw-bold mb-3">Chào Mừng Đến Với JobHub</h3>
                            <a href="<?= $baseUrl ?>/views/page/home/index.php">
                                <img src="<?= $baseUrl ?>/assets/images/logo.png" alt="JobHub Logo" class="mb-4 rounded-circle" width="80">
                            </a>
                            <p class="mb-4 text-white-50 fs-6">Chọn loại tài khoản phù hợp với bạn!</p>

                            <div class="nav flex-column nav-pills w-100 gap-2" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                <button class="nav-link text-white active border border-white-50" id="candidate-tab" data-bs-toggle="pill" data-bs-target="#candidate-form" type="button" role="tab" onclick="switchRole(0)">
                                    <i class="fa-solid fa-user-graduate me-2"></i> Dành cho Ứng Viên
                                </button>
                                <button class="nav-link text-white border border-white-50" id="employer-tab" data-bs-toggle="pill" data-bs-target="#employer-form" type="button" role="tab" onclick="switchRole(1)">
                                    <i class="fa-solid fa-building me-2"></i> Dành cho Nhà Tuyển Dụng
                                </button>
                            </div>
                        </div>

                        <div class="col-md-7 bg-white p-4">
                            <div class="tab-content" id="v-pills-tabContent">
                                <div class="tab-pane fade show active" id="candidate-form" role="tabpanel">
                                    <h4 class="fw-bold text-primary-blue mb-4">Đăng Ký Tài Khoản Ứng Viên</h4>
                                    <form action="<?= $baseUrl ?>/controllers/RegisterController.php" method="POST" class="needs-validation" novalidate id="registerForm">
                                        <input type="hidden" id="Role" name="Role" value="0">

                                        <div class="mb-3">
                                            <label for="Email" class="form-label small fw-semibold" id="labelEmail">Email <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="email" class="form-control py-2" id="Email" name="Email" required placeholder="name@example.com">
                                                <button class="btn btn-outline-secondary fw-bold" type="button" id="btnGetOtp" onclick="requestOtp('candidate')">Lấy mã xác thực</button>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="Otp" class="form-label small fw-semibold">Mã xác thực OTP <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control py-2" id="Otp" name="Otp" required placeholder="Nhập 6 chữ số lấy từ Email" maxlength="6">
                                        </div>

                                        <div class="mb-3">
                                            <label for="MatKhau" class="form-label small fw-semibold">Mật khẩu <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control py-2" id="MatKhau" name="MatKhau" required placeholder="Nhập mật khẩu">
                                        </div>

                                        <div class="mb-3">
                                            <label for="MatKhauConfirm" class="form-label small fw-semibold">Nhập lại mật khẩu <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control py-2" id="MatKhauConfirm" name="MatKhauConfirm" required placeholder="Xác nhận lại mật khẩu">
                                            <div class="invalid-feedback" id="passwordErrorMsg">Vui lòng nhập lại mật khẩu trùng khớp.</div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="HoTen" class="form-label small fw-semibold" id="labelHoTen">Họ và Tên <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control py-2" id="HoTen" name="HoTen" required placeholder="Nhập họ và tên đầy đủ">
                                        </div>

                                        <div class="row" id="personalFields">
                                            <div class="col-md-6 mb-3">
                                                <label for="NgaySinh" class="form-label small fw-semibold">Ngày sinh</label>
                                                <input type="date" class="form-control py-2" id="NgaySinh" name="NgaySinh">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label small fw-semibold d-block">Giới tính</label>
                                                <div class="form-check form-check-inline mt-2">
                                                    <input class="form-check-input" type="radio" name="GioiTinh" id="Nam" value="0" checked>
                                                    <label class="form-check-label" for="Nam">Nam</label>
                                                </div>
                                                <div class="form-check form-check-inline mt-2">
                                                    <input class="form-check-input" type="radio" name="GioiTinh" id="Nu" value="1">
                                                    <label class="form-check-label" for="Nu">Nữ</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="SDT" class="form-label small fw-semibold" id="labelSDT">Số điện thoại</label>
                                            <input type="tel" class="form-control py-2" id="SDT" name="SDT" placeholder="Nhập số điện thoại">
                                        </div>

                                        <div class="mb-3">
                                            <label for="DiaChi" class="form-label small fw-semibold" id="labelDiaChi">Địa chỉ</label>
                                            <input type="text" class="form-control py-2" id="DiaChi" name="DiaChi" placeholder="Số nhà, tên đường, tỉnh/thành phố">
                                        </div>

                                        <div id="employerFields" style="display: none;" class="border p-3 rounded bg-light mb-3">
                                            <h6 class="text-secondary mb-3 border-bottom pb-2">Thông tin doanh nghiệp bổ sung</h6>
                                            <div class="mb-3">
                                                <label for="MaSoThue" class="form-label small fw-semibold">Mã số thuế <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control py-2" id="MaSoThue" name="MaSoThue" placeholder="Nhập mã số thuế công ty">
                                            </div>
                                            <div class="mb-3">
                                                <label for="Website" class="form-label small fw-semibold">Website công ty</label>
                                                <input type="url" class="form-control py-2" id="Website" name="Website" placeholder="https://example.com">
                                            </div>
                                            <div class="mb-3">
                                                <label for="LinhVuc" class="form-label small fw-semibold">Lĩnh vực hoạt động</label>
                                                <input type="text" class="form-control py-2" id="LinhVuc" name="LinhVuc" placeholder="Ví dụ: Công nghệ thông tin, Logistics...">
                                            </div>
                                        </div>

                                        <div class="d-grid gap-2 mt-4">
                                            <button type="submit" class="btn btn-primary-blue btn-lg" id="btnSubmit">Đăng Ký Ứng Viên</button>
                                        </div>
                                    </form>
                                </div>

                                <div class="tab-pane fade" id="employer-form" role="tabpanel">
                                    <h4 class="fw-bold text-success mb-4">Đăng Ký Tài Khoản Doanh Nghiệp</h4>
                                    <form action="<?= $baseUrl ?>/controllers/RegisterController.php" method="POST" class="needs-validation" novalidate id="registerFormEmployer">
                                        <input type="hidden" id="RoleEmployer" name="Role" value="1">

                                        <div class="mb-3">
                                            <label for="EmailEmployer" class="form-label small fw-semibold" id="labelEmailEmployer">Email công ty <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="email" class="form-control py-2" id="EmailEmployer" name="Email" required placeholder="hr@company.com">
                                                <button class="btn btn-outline-secondary fw-bold" type="button" id="btnGetOtpEmployer" onclick="requestOtp('employer')">Lấy mã xác thực</button>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="OtpEmployer" class="form-label small fw-semibold">Mã xác thực OTP <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control py-2" id="OtpEmployer" name="Otp" required placeholder="Nhập 6 chữ số lấy từ Email" maxlength="6">
                                        </div>

                                        <div class="mb-3">
                                            <label for="MatKhauEmployer" class="form-label small fw-semibold">Mật khẩu <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control py-2" id="MatKhauEmployer" name="MatKhau" required placeholder="Nhập mật khẩu">
                                        </div>

                                        <div class="mb-3">
                                            <label for="MatKhauConfirmEmployer" class="form-label small fw-semibold">Nhập lại mật khẩu <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control py-2" id="MatKhauConfirmEmployer" name="MatKhauConfirm" required placeholder="Xác nhận lại mật khẩu">
                                        </div>

                                        <div class="mb-3">
                                            <label for="HoTenEmployer" class="form-label small fw-semibold">Tên công ty / Doanh nghiệp <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control py-2" id="HoTenEmployer" name="HoTen" required placeholder="Nhập tên công ty chính thức">
                                        </div>

                                        <div class="mb-3">
                                            <label for="SDTEmployer" class="form-label small fw-semibold">Số điện thoại công ty</label>
                                            <input type="tel" class="form-control py-2" id="SDTEmployer" name="SDT" placeholder="Nhập số điện thoại công ty">
                                        </div>

                                        <div class="mb-3">
                                            <label for="DiaChiEmployer" class="form-label small fw-semibold">Địa chỉ trụ sở chính</label>
                                            <input type="text" class="form-control py-2" id="DiaChiEmployer" name="DiaChi" placeholder="Số nhà, tên đường, tỉnh/thành phố">
                                        </div>

                                        <div class="border p-3 rounded bg-light mb-3">
                                            <div class="mb-3">
                                                <label for="MaSoThueEmployer" class="form-label small fw-semibold">Mã số thuế <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control py-2" id="MaSoThueEmployer" name="MaSoThue" required placeholder="Nhập mã số thuế công ty">
                                            </div>
                                            <div class="mb-3">
                                                <label for="WebsiteEmployer" class="form-label small fw-semibold">Website công ty</label>
                                                <input type="url" class="form-control py-2" id="WebsiteEmployer" name="Website" placeholder="https://example.com">
                                            </div>
                                            <div class="mb-3">
                                                <label for="LinhVucEmployer" class="form-label small fw-semibold">Lĩnh vực hoạt động</label>
                                                <input type="text" class="form-control py-2" id="LinhVucEmployer" name="LinhVuc" placeholder="Ví dụ: Công nghệ thông tin, Logistics...">
                                            </div>
                                        </div>

                                        <div class="d-grid gap-2 mt-4">
                                            <button type="submit" class="btn btn-success btn-lg">Đăng Ký Nhà Tuyển Dụng</button>
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

<!-- <script>
function requestOtp() {
    const emailInput = document.getElementById('Email')?.value || document.getElementById('EmailEmployer')?.value || '';
    const btnGetOtp = document.getElementById('btnGetOtp') || document.getElementById('btnGetOtpEmployer');

    if (!emailInput) {
        alert('Vui lòng nhập địa chỉ Email trước khi yêu cầu mã xác thực!');
        return;
    }

    if (btnGetOtp) {
        btnGetOtp.disabled = true;
        btnGetOtp.innerText = 'Đang gửi...';
    }

    const formData = new FormData();
    formData.append('action', 'send_otp');
    formData.append('email', emailInput);

    fetch('<?= $baseUrl ?>/controllers/OtpController.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        if (data.status === 'success' || data.status === 'cooldown') {
            let countdown = data.remaining || 60;
            const timer = setInterval(() => {
                countdown--;
                if (btnGetOtp) {
                    btnGetOtp.innerText = `Gửi lại sau (${countdown}s)`;
                }
                if (countdown <= 0) {
                    clearInterval(timer);
                    if (btnGetOtp) {
                        btnGetOtp.disabled = false;
                        btnGetOtp.innerText = 'Lấy mã xác thực';
                    }
                }
            }, 1000);
        } else {
            if (btnGetOtp) {
                btnGetOtp.disabled = false;
                btnGetOtp.innerText = 'Lấy mã xác thực';
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Đã có lỗi kết nối xảy ra!');
        if (btnGetOtp) {
            btnGetOtp.disabled = false;
            btnGetOtp.innerText = 'Lấy mã xác thực';
        }
    });
}

function switchRole(roleVal) {
    document.getElementById('Role').value = roleVal;
    document.getElementById('RoleEmployer').value = roleVal;

    const personalFields = document.getElementById('personalFields');
    const employerFields = document.getElementById('employerFields');
    const btnSubmit = document.getElementById('btnSubmit');
    const labelHoTen = document.getElementById('labelHoTen');
    const inputHoTen = document.getElementById('HoTen');
    const labelEmail = document.getElementById('labelEmail');
    const labelSDT = document.getElementById('labelSDT');
    const labelDiaChi = document.getElementById('labelDiaChi');
    const maSoThue = document.getElementById('MaSoThue');

    if (roleVal === 1) {
        if (personalFields) personalFields.style.display = 'none';
        if (employerFields) employerFields.style.display = 'block';
        if (labelHoTen) labelHoTen.innerHTML = 'Tên công ty / Doanh nghiệp <span class="text-danger">*</span>';
        if (inputHoTen) inputHoTen.placeholder = 'Nhập tên công ty chính thức';
        if (labelEmail) labelEmail.innerHTML = 'Email công ty <span class="text-danger">*</span>';
        if (labelSDT) labelSDT.innerText = 'Số điện thoại công ty';
        if (labelDiaChi) labelDiaChi.innerText = 'Địa chỉ trụ sở chính';
        if (btnSubmit) {
            btnSubmit.innerText = 'Đăng Ký Nhà Tuyển Dụng';
            btnSubmit.className = 'btn btn-success btn-lg';
        }
        if (maSoThue) maSoThue.setAttribute('required', 'required');
    } else {
        if (personalFields) personalFields.style.display = 'flex';
        if (employerFields) employerFields.style.display = 'none';
        if (labelHoTen) labelHoTen.innerHTML = 'Họ và Tên <span class="text-danger">*</span>';
        if (inputHoTen) inputHoTen.placeholder = 'Nhập họ và tên đầy đủ';
        if (labelEmail) labelEmail.innerHTML = 'Email <span class="text-danger">*</span>';
        if (labelSDT) labelSDT.innerText = 'Số điện thoại';
        if (labelDiaChi) labelDiaChi.innerText = 'Địa chỉ';
        if (btnSubmit) {
            btnSubmit.innerText = 'Đăng Ký Ứng Viên';
            btnSubmit.className = 'btn btn-primary-blue btn-lg';
        }
        if (maSoThue) maSoThue.removeAttribute('required');
    }
}

(function () {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            const password = document.getElementById('MatKhau')?.value || document.getElementById('MatKhauEmployer')?.value || '';
            const confirmPassword = document.getElementById('MatKhauConfirm')?.value || document.getElementById('MatKhauConfirmEmployer')?.value || '';
            const confirmInput = document.getElementById('MatKhauConfirm') || document.getElementById('MatKhauConfirmEmployer');

            if (password !== confirmPassword) {
                confirmInput.setCustomValidity('Mật khẩu không trùng khớp!');
            } else {
                confirmInput.setCustomValidity('');
            }

            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();
</script> -->

<script>
    window.appConfig = {
        baseUrl: '<?= $baseUrl ?>'
    };
</script>
<script src="<?= $baseUrl ?>/assets/js/register.js"></script>