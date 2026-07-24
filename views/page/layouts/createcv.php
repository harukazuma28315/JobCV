<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$baseUrl = '/JobCV';

require_once __DIR__ . '/header.php';

?>

<section class="py-5 bg-light">

    <div class="container">

        <?php if (!$cv): ?>

            <!-- ================================================= -->
            <!-- CHƯA CÓ CV -->
            <!-- ================================================= -->

            <div class="text-center mb-5">

                <h1 class="fw-bold text-dark">
                    <i class="fa-solid fa-file-lines text-primary me-2"></i>
                    Tạo CV của bạn
                </h1>

                <p class="text-muted">
                    Chọn phương thức bạn muốn sử dụng để tạo CV
                </p>

            </div>


            <!-- =========================== -->
            <!-- LỰA CHỌN PHƯƠNG THỨC -->
            <!-- =========================== -->

            <div class="row justify-content-center g-4">

                <!-- NHẬP THỦ CÔNG -->

                <div class="col-md-5">

                    <div
                        class="card border-0 shadow-sm h-100 text-center p-4 cv-option"
                        onclick="showManualForm()"
                        style="cursor: pointer;"
                    >

                        <div class="mb-4">

                            <i
                                class="fa-solid fa-pen-to-square text-primary"
                                style="font-size: 60px;"
                            ></i>

                        </div>

                        <h4 class="fw-bold">
                            Nhập thông tin thủ công
                        </h4>

                        <p class="text-muted">

                            Điền trực tiếp các thông tin cá nhân,
                            kỹ năng, mục tiêu nghề nghiệp.

                        </p>

                        <button
                            type="button"
                            class="btn btn-primary mt-auto"
                        >

                            <i class="fa-solid fa-pen me-2"></i>

                            Tạo CV thủ công

                        </button>

                    </div>

                </div>


                <!-- UPLOAD FILE -->

                <div class="col-md-5">

                    <div
                        class="card border-0 shadow-sm h-100 text-center p-4 cv-option"
                        onclick="showUploadForm()"
                        style="cursor: pointer;"
                    >

                        <div class="mb-4">

                            <i
                                class="fa-solid fa-file-arrow-up text-success"
                                style="font-size: 60px;"
                            ></i>

                        </div>

                        <h4 class="fw-bold">
                            Tải CV cá nhân lên
                        </h4>

                        <p class="text-muted">

                            Tải lên CV đã có sẵn của bạn.

                            Hỗ trợ định dạng PDF, DOC và DOCX.

                        </p>

                        <button
                            type="button"
                            class="btn btn-success mt-auto"
                        >

                            <i class="fa-solid fa-upload me-2"></i>

                            Tải CV lên

                        </button>

                    </div>

                </div>

            </div>


            <!-- =========================== -->
            <!-- FORM NHẬP THỦ CÔNG -->
            <!-- =========================== -->

            <div
                id="manualForm"
                class="card border-0 shadow-sm mt-5"
                style="display: none;"
            >

                <div class="card-header bg-primary text-white">

                    <h4 class="mb-0 fw-bold">

                        <i class="fa-solid fa-pen-to-square me-2"></i>

                        Nhập thông tin CV

                    </h4>

                </div>


                <div class="card-body p-4">

                    <form
                        action="<?= $baseUrl ?>/index.php?route=cv/create-submit"
                        method="POST"
                    >

                        <div class="row g-4">

                            <!-- TIÊU ĐỀ -->

                            <div class="col-12">

                                <label class="form-label fw-bold">

                                    Tiêu đề CV
                                    <span class="text-danger">*</span>

                                </label>

                                <input
                                    type="text"
                                    name="tieuDe"
                                    class="form-control"
                                    placeholder="Ví dụ: Backend Developer - Nguyễn Văn A"
                                    required
                                >

                            </div>


                            <!-- VỊ TRÍ -->

                            <div class="col-md-6">

                                <label class="form-label fw-bold">

                                    Vị trí mong muốn

                                </label>

                                <input
                                    type="text"
                                    name="viTriMongMuon"
                                    class="form-control"
                                    placeholder="Backend Developer"
                                >

                            </div>


                            <!-- EMAIL -->

                            <div class="col-md-6">

                                <label class="form-label fw-bold">

                                    Email
                                    <span class="text-danger">*</span>

                                </label>

                                <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    value="<?= htmlspecialchars($_SESSION['user_email'] ?? '') ?>"
                                    required
                                >

                            </div>


                            <!-- SĐT -->

                            <div class="col-md-6">

                                <label class="form-label fw-bold">

                                    Số điện thoại
                                    <span class="text-danger">*</span>

                                </label>

                                <input
                                    type="text"
                                    name="sdt"
                                    class="form-control"
                                    placeholder="0912345678"
                                    required
                                >

                            </div>


                            <!-- KỸ NĂNG -->

                            <div class="col-12">

                                <label class="form-label fw-bold">

                                    Kỹ năng

                                </label>

                                <textarea
                                    name="kyNang"
                                    class="form-control"
                                    rows="4"
                                    placeholder="PHP, MySQL, Laravel, HTML, CSS..."
                                ></textarea>

                            </div>


                            <!-- SỞ THÍCH -->

                            <div class="col-md-6">

                                <label class="form-label fw-bold">

                                    Sở thích

                                </label>

                                <textarea
                                    name="soThich"
                                    class="form-control"
                                    rows="4"
                                    placeholder="Đọc sách, chơi thể thao..."
                                ></textarea>

                            </div>


                            <!-- MỤC TIÊU -->

                            <div class="col-md-6">

                                <label class="form-label fw-bold">

                                    Mục tiêu nghề nghiệp

                                </label>

                                <textarea
                                    name="mucTieu"
                                    class="form-control"
                                    rows="4"
                                    placeholder="Mục tiêu nghề nghiệp của bạn..."
                                ></textarea>

                            </div>

                        </div>


                        <div class="text-end mt-4">

                            <button
                                type="submit"
                                class="btn btn-primary px-5 fw-bold"
                            >

                                <i class="fa-solid fa-floppy-disk me-2"></i>

                                Lưu CV

                            </button>

                        </div>

                    </form>

                </div>

            </div>


            <!-- =========================== -->
            <!-- FORM UPLOAD -->
            <!-- =========================== -->

            <div
                id="uploadForm"
                class="card border-0 shadow-sm mt-5"
                style="display: none;"
            >

                <div class="card-header bg-success text-white">

                    <h4 class="mb-0 fw-bold">

                        <i class="fa-solid fa-file-arrow-up me-2"></i>

                        Tải CV cá nhân lên

                    </h4>

                </div>


                <div class="card-body p-4">

                    <form
                        action="<?= $baseUrl ?>/index.php?route=cv/upload-submit"
                        method="POST"
                        enctype="multipart/form-data"
                    >

                        <div class="mb-4">

                            <label class="form-label fw-bold">

                                Chọn file CV

                                <span class="text-danger">*</span>

                            </label>

                            <input
                                type="file"
                                name="file"
                                class="form-control"
                                accept=".pdf,.doc,.docx"
                                required
                            >

                            <small class="text-muted">

                                Chỉ hỗ trợ file PDF, DOC hoặc DOCX.

                            </small>

                        </div>


                        <div class="alert alert-info">

                            <i class="fa-solid fa-circle-info me-2"></i>

                            File CV của bạn sẽ được lưu trữ trên hệ thống
                            và có thể được sử dụng khi ứng tuyển.

                        </div>


                        <div class="text-end">

                            <button
                                type="submit"
                                class="btn btn-success px-5 fw-bold"
                            >

                                <i class="fa-solid fa-upload me-2"></i>

                                Tải CV lên

                            </button>

                        </div>

                    </form>

                </div>

            </div>


        <?php else: ?>

            <!-- ================================================= -->
            <!-- ĐÃ CÓ CV -->
            <!-- ================================================= -->

            <div class="text-center mb-5">

                <h1 class="fw-bold text-dark">

                    <i class="fa-solid fa-file-circle-check text-success me-2"></i>

                    Hồ sơ CV của bạn

                </h1>

                <p class="text-muted">

                    Bạn đã có một CV trên hệ thống.

                </p>

            </div>


            <!-- THÔNG TIN CV -->

            <div class="card border-0 shadow-sm mx-auto" style="max-width: 900px;">

                <div class="card-header bg-primary text-white">

                    <div class="d-flex justify-content-between align-items-center">

                        <h4 class="mb-0 fw-bold">

                            <i class="fa-solid fa-file-lines me-2"></i>

                            <?= htmlspecialchars($cv['TieuDe']) ?>

                        </h4>

                        <span class="badge bg-success">

                            CV đang hoạt động

                        </span>

                    </div>

                </div>


                <div class="card-body p-4">

                    <div class="row g-4">


                        <!-- THÔNG TIN CƠ BẢN -->

                        <div class="col-md-6">

                            <h5 class="fw-bold text-primary mb-3">

                                <i class="fa-solid fa-user me-2"></i>

                                Thông tin cơ bản

                            </h5>

                            <p>

                                <strong>Vị trí mong muốn:</strong><br>

                                <?= htmlspecialchars($cv['ViTriMongMuon'] ?: 'Chưa cập nhật') ?>

                            </p>

                            <p>

                                <strong>Email:</strong><br>

                                <?= htmlspecialchars($cv['Email']) ?>

                            </p>

                            <p>

                                <strong>Số điện thoại:</strong><br>

                                <?= htmlspecialchars($cv['SDT']) ?>

                            </p>

                        </div>


                        <!-- KỸ NĂNG -->

                        <div class="col-md-6">

                            <h5 class="fw-bold text-primary mb-3">

                                <i class="fa-solid fa-star me-2"></i>

                                Kỹ năng

                            </h5>

                            <p>

                                <?= nl2br(
                                    htmlspecialchars(
                                        $cv['KyNang'] ?: 'Chưa cập nhật'
                                    )
                                ) ?>

                            </p>

                        </div>


                        <!-- MỤC TIÊU -->

                        <div class="col-md-6">

                            <h5 class="fw-bold text-primary mb-3">

                                <i class="fa-solid fa-bullseye me-2"></i>

                                Mục tiêu nghề nghiệp

                            </h5>

                            <p>

                                <?= nl2br(
                                    htmlspecialchars(
                                        $cv['MucTieu'] ?: 'Chưa cập nhật'
                                    )
                                ) ?>

                            </p>

                        </div>


                        <!-- SỞ THÍCH -->

                        <div class="col-md-6">

                            <h5 class="fw-bold text-primary mb-3">

                                <i class="fa-solid fa-heart me-2"></i>

                                Sở thích

                            </h5>

                            <p>

                                <?= nl2br(
                                    htmlspecialchars(
                                        $cv['SoThich'] ?: 'Chưa cập nhật'
                                    )
                                ) ?>

                            </p>

                        </div>

                    </div>


                    <!-- FILE CV -->

                    <?php if (!empty($cv['DuongDanFileCV'])): ?>

                        <hr>

                        <div class="alert alert-success">

                            <i class="fa-solid fa-file-pdf me-2"></i>

                            <strong>File CV:</strong>

                            <?= htmlspecialchars($cv['TenFileCV']) ?>

                        </div>

                    <?php endif; ?>


                    <!-- BUTTON -->

                    <div class="text-end mt-4">

                        <button
                            type="button"
                            class="btn btn-primary px-4"
                            onclick="showEditForm()"
                        >
                            <i class="fa-solid fa-pen-to-square me-2"></i>
                            Thay đổi thông tin CV
                        </button>

                    </div>

                </div>

            </div>
            <div
                id="editForm"
                class="card border-0 shadow-sm mt-4"
                style="display: none;"
            >

                <div class="card-header bg-warning">

                    <h4 class="mb-0 fw-bold">

                        <i class="fa-solid fa-pen-to-square me-2"></i>

                        Chỉnh sửa thông tin CV

                    </h4>

                </div>

                <div class="card-body p-4">

                    <form
                        action="<?= $baseUrl ?>/index.php?route=cv/update-submit"
                        method="POST"
                    >

                        <input
                            type="hidden"
                            name="maCV"
                            value="<?= htmlspecialchars($cv['MaCV']) ?>"
                        >

                        <div class="row g-4">

                            <div class="col-12">

                                <label class="form-label fw-bold">
                                    Tiêu đề CV
                                </label>

                                <input
                                    type="text"
                                    name="tieuDe"
                                    class="form-control"
                                    value="<?= htmlspecialchars($cv['TieuDe']) ?>"
                                    required
                                >

                            </div>

                            <div class="col-md-6">

                                <label class="form-label fw-bold">
                                    Vị trí mong muốn
                                </label>

                                <input
                                    type="text"
                                    name="viTriMongMuon"
                                    class="form-control"
                                    value="<?= htmlspecialchars($cv['ViTriMongMuon']) ?>"
                                >

                            </div>

                            <div class="col-md-6">

                                <label class="form-label fw-bold">
                                    Email
                                </label>

                                <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    value="<?= htmlspecialchars($cv['Email']) ?>"
                                    required
                                >

                            </div>

                            <div class="col-md-6">

                                <label class="form-label fw-bold">
                                    Số điện thoại
                                </label>

                                <input
                                    type="text"
                                    name="sdt"
                                    class="form-control"
                                    value="<?= htmlspecialchars($cv['SDT']) ?>"
                                    required
                                >

                            </div>

                            <div class="col-12">

                                <label class="form-label fw-bold">
                                    Kỹ năng
                                </label>

                                <textarea
                                    name="kyNang"
                                    class="form-control"
                                    rows="4"
                                ><?= htmlspecialchars($cv['KyNang']) ?></textarea>

                            </div>

                            <div class="col-md-6">

                                <label class="form-label fw-bold">
                                    Sở thích
                                </label>

                                <textarea
                                    name="soThich"
                                    class="form-control"
                                    rows="4"
                                ><?= htmlspecialchars($cv['SoThich']) ?></textarea>

                            </div>

                            <div class="col-md-6">

                                <label class="form-label fw-bold">
                                    Mục tiêu nghề nghiệp
                                </label>

                                <textarea
                                    name="mucTieu"
                                    class="form-control"
                                    rows="4"
                                ><?= htmlspecialchars($cv['MucTieu']) ?></textarea>

                            </div>

                        </div>

                        <div class="text-end mt-4">

                            <button
                                type="button"
                                class="btn btn-secondary me-2"
                                onclick="hideEditForm()"
                            >
                                Hủy
                            </button>

                            <button
                                type="submit"
                                class="btn btn-success px-4"
                            >
                                <i class="fa-solid fa-save me-2"></i>
                                Lưu thay đổi
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        <?php endif; ?>

    </div>

</section>


<script>

function showManualForm() {

    document.getElementById('manualForm').style.display = 'block';

    document.getElementById('uploadForm').style.display = 'none';

    document
        .getElementById('manualForm')
        .scrollIntoView({
            behavior: 'smooth'
        });

}


function showUploadForm() {

    document.getElementById('uploadForm').style.display = 'block';

    document.getElementById('manualForm').style.display = 'none';

    document
        .getElementById('uploadForm')
        .scrollIntoView({
            behavior: 'smooth'
        });

}

</script>


<?php

require_once __DIR__ . '/footer.php';

?>