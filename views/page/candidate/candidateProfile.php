<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$baseUrl = '/JobCV';
require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../../controllers/ProfileController.php';
require_once __DIR__ . '/../../../controllers/LogoutController.php';
include_once '../../page/layouts/header.php';

$logoutCtrl = new LogoutController($conn);
$logoutCtrl->handleLogout();

$profileCtrl = new ProfileController($conn);
$profileCtrl->handleUpdateProfile();
$profileData = $profileCtrl->handleGetProfile();

?>

<div class="container py-5">
    <div class="row g-4">
        
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm p-4 text-center bg-white mb-4">
                <div class="position-relative d-inline-block mx-auto mb-3" style="width: 110px; height: 110px;">
                    <img src="https://api.dicebear.com/7.x/adventurer/svg?seed=<?php echo htmlspecialchars($user['username'] ?? 'default'); ?>" alt="Avatar" class="rounded-circle border border-3 border-primary" style="width: 100%; height: 100%; object-fit: cover;">
                    
                    <span class="position-absolute bottom-0 end-0 bg-primary text-white shadow-sm d-flex align-items-center justify-content-center" 
                          style="cursor: pointer; width: 32px; height: 32px; border-radius: 50%; padding: 0; margin: 0; border: 2px solid #fff;" 
                          title="Đổi ảnh đại diện">
                        <i class="fa-solid fa-camera fa-sm" style="line-height: 1;"></i>
                    </span>
                </div>
                
                <h5 class="fw-bold text-dark mb-1"><?php echo htmlspecialchars($profileData['fullname']); ?></h5> 
                <!-- <p class="text-muted small mb-3">Mã UV: <?php echo htmlspecialchars($profileData['candidate_code']); ?></p>  -->
                <span class="badge bg-light text-primary-blue border fw-semibold px-3 py-2 mb-4">Ứng viên</span>

                <div class="nav flex-column nav-pills text-start gap-2" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <!-- <button class="nav-link active py-3 px-3 d-flex align-items-center gap-3 border-0" id="v-pills-info-tab" data-bs-toggle="pill" data-bs-target="#v-pills-info" type="button" role="tab" aria-selected="true">
                        <i class="fa-regular fa-user text-primary fs-5"></i>
                        <span>Thông tin cá nhân</span>
                    </button> -->
                    <a href="list_cv.php" class="nav-link py-3 px-3 d-flex align-items-center gap-3 border-0 text-decoration-none">
                        <i class="fa-solid fa-file-pdf text-danger fs-5"></i>
                        <span>Quản lý hồ sơ & CV</span>
                    </a>
                    <a href="applied-jobs.php" class="nav-link py-3 px-3 d-flex align-items-center gap-3 border-0 text-decoration-none">
                        <i class="fa-solid fa-paper-plane text-success fs-5"></i>
                        <span>Việc làm đã ứng tuyển</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-8">
            <div class="tab-content" id="v-pills-tabContent">
                
                <div class="tab-pane fade show active" id="v-pills-info" role="tabpanel" aria-labelledby="v-pills-info-tab">
                    <div class="card border-0 shadow-sm p-4 bg-white">
                        <h4 class="fw-bold mb-4 border-start border-4 border-primary ps-3 text-dark">Thông Tin Cá Nhân</h4>
                        
                        <form action="candidateProfile.php" method="POST">
                            <input type="hidden" name="action" value="update">
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold text-dark">Họ và tên</label>
                                    <input type="text" name="hoTen" class="form-control py-2" value="<?php echo htmlspecialchars($profileData['fullname']); ?>" required>
                                </div>
                                <!-- <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold text-dark">Mã số ứng viên</label>
                                    <input type="text" name="candidate_code" class="form-control py-2" value="<?php echo htmlspecialchars($profileData['candidate_code']); ?>" readonly>
                                </div> -->
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold text-dark">Email liên hệ</label>
                                    <input type="email" name="email" class="form-control py-2" value="<?php echo htmlspecialchars($profileData['email']); ?>" readonly>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold text-dark">Số điện thoại</label>
                                    <input type="tel" name="sdt" class="form-control py-2" value="<?php echo htmlspecialchars($profileData['phone']); ?>" required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold text-dark">Ngày sinh</label>
                                    <input type="date" name="ngaySinh" class="form-control py-2" value="<?php echo htmlspecialchars($profileData['birthDate']); ?>">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold text-dark">Giới tính</label>
                                    <select name="gioiTinh" class="form-select py-2">
                                        <option value="1" <?php echo ($profileData['gender'] !== null && (int)$profileData['gender'] === 1) ? 'selected' : ''; ?>>Nam</option>
                                        <option value="0" <?php echo ($profileData['gender'] !== null && (int)$profileData['gender'] === 0) ? 'selected' : ''; ?>>Nữ</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-dark">Địa chỉ</label>
                                    <input type="text" name="diaChi" class="form-control py-2" value="<?php echo htmlspecialchars($profileData['address']); ?>">
                                </div>
                                <!-- <div class="col-12">
                                    <label class="form-label fw-semibold text-dark">Giới thiệu ngắn về bản thân</label>
                                    <textarea name="bio" class="form-control" rows="4"><?php echo htmlspecialchars($profileData['bio']); ?></textarea>
                                </div> -->
                            </div>
                            
                            <div class="mt-4 pt-3 border-top text-end">
                                <a href="?action=logout" class="btn btn-outline-danger fw-bold px-4 py-2 me-auto" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')">
                                    <i class="fa-solid fa-right-from-bracket me-2"></i>Đăng Xuất
                                </a>
                                <button type="submit" name="btn_submit" class="btn btn-primary-blue fw-bold px-4 py-2">Lưu Thay Đổi</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- <div class="tab-pane fade" id="v-pills-cv" role="tabpanel" aria-labelledby="v-pills-cv-tab">
                    <div class="card border-0 shadow-sm p-4 bg-white">
                        <h4 class="fw-bold mb-4 border-start border-4 border-primary ps-3 text-dark">Quản Lý Hồ Sơ & CV</h4>
                        
                        <form action="upload-cv.php" method="POST" enctype="multipart/form-data">
                            <div class="border border-2 border-dashed rounded-3 p-4 text-center bg-light mb-4 position-relative">
                                <input type="file" name="cv_file" class="position-absolute top-0 start-0 w-100 h-100 opacity-0" style="cursor: pointer;" accept=".pdf,.doc,.docx" onchange="this.form.submit()">
                                <div class="py-3">
                                    <i class="fa-solid fa-cloud-arrow-up text-primary-blue fs-1 mb-3"></i>
                                    <h5 class="fw-bold text-dark">Kéo thả hoặc tải lên CV của bạn</h5>
                                    <p class="text-muted small mb-0">Hỗ trợ định dạng .PDF, .DOCX, .DOC (Tối đa 5MB)</p>
                                </div>
                            </div>
                        </form>

                        <h6 class="fw-bold text-dark mb-3">Các file CV đã tải lên:</h6>
                        <div class="d-flex flex-column gap-3">
                            <?php if (!empty($user['cv_filename'])): ?>
                                <div class="p-3 border rounded-3 bg-white d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="fs-2 text-danger"><i class="fa-solid fa-file-pdf"></i></span>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-1"><?php echo htmlspecialchars($user['cv_filename']); ?></h6>
                                            <span class="text-muted small">Cập nhật: <?php echo htmlspecialchars($user['cv_updated_at'] ?? ''); ?></span>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="<?php echo htmlspecialchars($user['cv_filepath'] ?? '#'); ?>" target="_blank" class="btn btn-light btn-sm text-primary-blue" title="Xem trước"><i class="fa-solid fa-eye"></i></a>
                                        <a href="delete-cv.php" class="btn btn-light btn-sm text-danger" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa CV này?')"><i class="fa-solid fa-trash-can"></i></a>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="text-center p-3 text-muted border rounded-3 bg-light-subtle">
                                    Bạn chưa tải lên CV nào.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div> -->

            </div>
        </div>

    </div>
</div>

<style>
    .border-dashed {
        border-style: dashed !important;
        border-color: #1e5ba6 !important;
        transition: background-color 0.2s ease;
    }
    .border-dashed:hover {
        background-color: #eef4fc !important;
    }
</style>

<?php include_once '../../page/layouts/footer.php'; ?>