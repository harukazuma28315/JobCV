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
                                <!-- TAB FORM ỨNG VIÊN -->
                                <div class="tab-pane fade show active" id="candidate-form" role="tabpanel">
                                    <h4 class="fw-bold text-primary-blue mb-4">Đăng Ký Tài Khoản Ứng Viên</h4>
                                    <form action="<?= $baseUrl ?>/index.php?route=auth/register-submit" method="POST" class="needs-validation" novalidate id="registerForm">
                                        <input type="hidden" id="Role" name="Role" value="0">

                                        <!-- Email -->
                                        <div class="mb-3">
                                            <label for="Email" class="form-label small fw-semibold" id="labelEmail">Email <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="email" class="form-control py-2" id="Email" name="Email" required placeholder="name@example.com" title="Vui lòng nhập định dạng email hợp lệ (ví dụ: name@example.com)">
                                                <button class="btn btn-outline-secondary fw-bold" type="button" id="btnGetOtp" onclick="requestOtp('candidate')">Lấy mã xác thực</button>
                                            </div>
                                        </div>

                                        <!-- OTP -->
                                        <div class="mb-3">
                                            <label for="Otp" class="form-label small fw-semibold">Mã xác thực OTP <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control py-2" id="Otp" name="Otp" required placeholder="Nhập 6 chữ số lấy từ Email" inputmode="numeric" maxlength="6" pattern="[0-9]{6}" oninput="this.value = this.value.replace(/\D/g, '')" title="Mã OTP phải bao gồm đúng 6 chữ số">
                                        </div>

                                        <!-- Mật khẩu -->
                                        <div class="mb-3">
                                            <label for="MatKhau" class="form-label small fw-semibold">Mật khẩu <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control py-2" id="MatKhau" name="MatKhau" required placeholder="Nhập mật khẩu" minlength="6" title="Mật khẩu tối thiểu 6 ký tự">
                                        </div>

                                        <!-- Xác nhận mật khẩu -->
                                        <div class="mb-3">
                                            <label for="MatKhauConfirm" class="form-label small fw-semibold">Nhập lại mật khẩu <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control py-2" id="MatKhauConfirm" name="MatKhauConfirm" required placeholder="Xác nhận lại mật khẩu">
                                            <div class="invalid-feedback" id="passwordErrorMsg">Vui lòng nhập lại mật khẩu trùng khớp.</div>
                                        </div>

                                        <!-- Họ và tên -->
                                        <div class="mb-3">
                                            <label for="HoTen" class="form-label small fw-semibold" id="labelHoTen">Họ và Tên <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control py-2" id="HoTen" name="HoTen" required placeholder="Nhập họ và tên đầy đủ" maxlength="100">
                                        </div>

                                        <!-- Ngày sinh & Giới tính -->
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

                                        <!-- Số điện thoại -->
                                        <div class="mb-3">
                                            <label for="SDT" class="form-label small fw-semibold" id="labelSDT">Số điện thoại</label>
                                            <input type="tel" class="form-control py-2" id="SDT" name="SDT" placeholder="Nhập số điện thoại (10 số)" inputmode="numeric" maxlength="10" pattern="(03|05|07|08|09)[0-9]{8}" oninput="this.value = this.value.replace(/\D/g, '')" title="Số điện thoại phải gồm 10 chữ số đúng đầu số Việt Nam (ví dụ: 0912345678)">
                                        </div>

                                        <!-- Địa chỉ (Ứng viên) -->
                                        <div class="mb-3">
                                            <label for="DiaChi" class="form-label small fw-semibold" id="labelDiaChi">Địa chỉ</label>
                                            <input type="text" class="form-control py-2" id="DiaChi" name="DiaChi" placeholder="Số nhà, tên đường, tỉnh/thành phố" maxlength="255" pattern="^[a-zA-Z0-9àáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđĐ\s,\/]+$" oninput="this.value = this.value.replace(/[^a-zA-Z0-9àáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđĐ\s,\/]/g, '')" title="Địa chỉ chỉ được chứa chữ cái, số, khoảng trắng, dấu phẩy (,) và dấu xuyệt (/). Không chứa các ký tự đặc biệt khác">
                                        </div>

                                        <!-- Đơn vị tuyển dụng -->
                                        <div id="employerFields" style="display: none;" class="border p-3 rounded bg-light mb-3">
                                            <h6 class="text-secondary mb-3 border-bottom pb-2">Thông tin doanh nghiệp bổ sung</h6>
                                            <div class="mb-3">
                                                <label for="MaSoThue" class="form-label small fw-semibold">Mã số thuế <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control py-2" id="MaSoThue" name="MaSoThue" placeholder="Nhập mã số thuế công ty (10-13 số)" inputmode="numeric" maxlength="13" pattern="[0-9]{10,13}" oninput="this.value = this.value.replace(/\D/g, '')" title="Mã số thuế bao gồm từ 10 đến 13 chữ số">
                                            </div>
                                            <div class="mb-3">
                                                <label for="Website" class="form-label small fw-semibold">Website công ty</label>
                                                <input type="url" class="form-control py-2" id="Website" name="Website" placeholder="https://example.com" maxlength="255" pattern="^(https?:\/\/)?([\w\d-]+\.)+[\w\d-]+(\/.*)?$" oninput="this.value = this.value.replace(/\s/g, '').replace(/[^a-zA-Z0-9\.\:\/\_\-\?\=\&\#]/g, '')" title="Nhập định dạng website hợp lệ (ví dụ: https://example.com hoặc example.com)">
                                            </div>
                                            <div class="mb-3">
                                                <label for="LinhVuc" class="form-label small fw-semibold">Lĩnh vực hoạt động</label>
                                                <input type="text" class="form-control py-2" id="LinhVuc" name="LinhVuc" placeholder="Ví dụ: Công nghệ thông tin, Logistics..." maxlength="150">
                                            </div>
                                        </div>

                                        <div class="d-grid gap-2 mt-4">
                                            <button type="submit" class="btn btn-primary-blue btn-lg" id="btnSubmit">Đăng Ký Ứng Viên</button>
                                        </div>
                                    </form>
                                </div>

                                <!-- TAB FORM DOANH NGHIỆP -->
                                <div class="tab-pane fade" id="employer-form" role="tabpanel">
                                    <h4 class="fw-bold text-success mb-4">Đăng Ký Tài Khoản Doanh Nghiệp</h4>
                                    <form action="<?= $baseUrl ?>/index.php?route=auth/register-submit" method="POST" class="needs-validation" novalidate id="registerFormEmployer">
                                        <input type="hidden" id="RoleEmployer" name="Role" value="1">

                                        <!-- Email Công ty -->
                                        <div class="mb-3">
                                            <label for="EmailEmployer" class="form-label small fw-semibold" id="labelEmailEmployer">Email công ty <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="email" class="form-control py-2" id="EmailEmployer" name="Email" required placeholder="hr@company.com" title="Vui lòng nhập định dạng email hợp lệ">
                                                <button class="btn btn-outline-secondary fw-bold" type="button" id="btnGetOtpEmployer" onclick="requestOtp('employer')">Lấy mã xác thực</button>
                                            </div>
                                        </div>

                                        <!-- OTP -->
                                        <div class="mb-3">
                                            <label for="OtpEmployer" class="form-label small fw-semibold">Mã xác thực OTP <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control py-2" id="OtpEmployer" name="Otp" required placeholder="Nhập 6 chữ số lấy từ Email" inputmode="numeric" maxlength="6" pattern="[0-9]{6}" oninput="this.value = this.value.replace(/\D/g, '')" title="Mã OTP phải bao gồm đúng 6 chữ số">
                                        </div>

                                        <!-- Mật khẩu -->
                                        <div class="mb-3">
                                            <label for="MatKhauEmployer" class="form-label small fw-semibold">Mật khẩu <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control py-2" id="MatKhauEmployer" name="MatKhau" required placeholder="Nhập mật khẩu" minlength="6" title="Mật khẩu tối thiểu 6 ký tự">
                                        </div>

                                        <!-- Xác nhận mật khẩu -->
                                        <div class="mb-3">
                                            <label for="MatKhauConfirmEmployer" class="form-label small fw-semibold">Nhập lại mật khẩu <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control py-2" id="MatKhauConfirmEmployer" name="MatKhauConfirm" required placeholder="Xác nhận lại mật khẩu">
                                        </div>

                                        <!-- Tên công ty -->
                                        <div class="mb-3">
                                            <label for="HoTenEmployer" class="form-label small fw-semibold">Tên công ty / Doanh nghiệp <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control py-2" id="HoTenEmployer" name="HoTen" required placeholder="Nhập tên công ty chính thức" maxlength="150">
                                        </div>

                                        <!-- SĐT công ty -->
                                        <div class="mb-3">
                                            <label for="SDTEmployer" class="form-label small fw-semibold">Số điện thoại công ty</label>
                                            <input type="tel" class="form-control py-2" id="SDTEmployer" name="SDT" placeholder="Nhập số điện thoại công ty" inputmode="numeric" maxlength="10" pattern="(03|05|07|08|09)[0-9]{8}" oninput="this.value = this.value.replace(/\D/g, '')" title="Số điện thoại phải gồm 10 chữ số đúng đầu số Việt Nam">
                                        </div>

                                        <!-- Địa chỉ trụ sở (Doanh nghiệp) -->
                                        <div class="mb-3">
                                            <label for="DiaChiEmployer" class="form-label small fw-semibold">Địa chỉ trụ sở chính</label>
                                            <input type="text" class="form-control py-2" id="DiaChiEmployer" name="DiaChi" placeholder="Số nhà, tên đường, tỉnh/thành phố" maxlength="255" pattern="^[a-zA-Z0-9àáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđĐ\s,\/]+$" oninput="this.value = this.value.replace(/[^a-zA-Z0-9àáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđĐ\s,\/]/g, '')" title="Địa chỉ chỉ được chứa chữ cái, số, khoảng trắng, dấu phẩy (,) và dấu xuyệt (/). Không chứa các ký tự đặc biệt khác">
                                        </div>

                                        <!-- Chi tiết Doanh Nghiệp -->
                                        <div class="border p-3 rounded bg-light mb-3">
                                            <div class="mb-3">
                                                <label for="MaSoThueEmployer" class="form-label small fw-semibold">Mã số thuế <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control py-2" id="MaSoThueEmployer" name="MaSoThue" required placeholder="Nhập mã số thuế công ty (10-13 số)" inputmode="numeric" maxlength="13" pattern="[0-9]{10,13}" oninput="this.value = this.value.replace(/\D/g, '')" title="Mã số thuế bao gồm từ 10 đến 13 chữ số">
                                            </div>
                                            <div class="mb-3">
                                                <label for="WebsiteEmployer" class="form-label small fw-semibold">Website công ty</label>
                                                <input type="url" class="form-control py-2" id="WebsiteEmployer" name="Website" placeholder="https://example.com" maxlength="255" pattern="^(https?:\/\/)?([\w\d-]+\.)+[\w\d-]+(\/.*)?$" oninput="this.value = this.value.replace(/\s/g, '').replace(/[^a-zA-Z0-9\.\:\/\_\-\?\=\&\#]/g, '')" title="Nhập định dạng website hợp lệ (ví dụ: https://example.com hoặc example.com)">
                                            </div>
                                            <div class="mb-3">
                                                <label for="LinhVucEmployer" class="form-label small fw-semibold">Lĩnh vực hoạt động</label>
                                                <input type="text" class="form-control py-2" id="LinhVucEmployer" name="LinhVuc" placeholder="Ví dụ: Công nghệ thông tin, Logistics..." maxlength="150">
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

<script>
    window.appConfig = {
        baseUrl: '<?= $baseUrl ?>'
    };
</script>
<script src="<?= $baseUrl ?>/assets/js/register.js"></script>