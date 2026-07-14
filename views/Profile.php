<?php
require_once __DIR__ . '/../controllers/ProfileController.php';

$profileCtrl = new ProfileController($conn);

// Kiểm tra và thực hiện đăng xuất nếu người dùng bấm nút
$profileCtrl->handleLogout();

$profileCtrl->handleUpdateProfile();
$user = $profileCtrl->handleGetProfile();
$conn->close();

$isCompany = ($user['Role'] == 1);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Thông Tin Tài Khoản</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<style>
		body { background-color: #f4f6f9; padding-top: 50px; }
		.card-profile { background: #fff; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
		.edit-mode { display: none; }
	</style>
</head>
<body>

<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-7">
			<!-- Thanh điều hướng nhỏ phía trên bảng thông tin -->
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <div>
                    <a href="trangchu.php" class="text-decoration-none fw-bold">← Quay lại Trang Chủ</a>
                    <?php if(isset($_GET['status']) && $_GET['status'] === 'success'): ?>
                        <span class="badge bg-success ms-3 p-2">Cập nhật thông tin thành công!</span>
                    <?php endif; ?>
                </div>
                
                <div>
                    <!-- NÚT ĐỔI MẬT KHẨU MỚI THÊM VÀO -->
                    <a href="doimatkhau.html" class="btn btn-outline-primary btn-sm fw-bold shadow-sm me-2">
                        🔑 Đổi mật khẩu
                    </a>

                    <!-- NÚT ĐĂNG XUẤT -->
                    <a href="profile.php?action=logout" class="btn btn-outline-danger btn-sm fw-bold shadow-sm" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')">
                        🚪 Đăng xuất
                    </a>
                </div>
            </div>
			
			<div class="card card-profile p-4">
				<h4 class="text-primary border-bottom pb-2 mb-4 fw-bold text-center">
					<?php echo $isCompany ? '🏢 THÔNG TIN CÔNG TY' : '👤 THÔNG TIN CÁ NHÂN'; ?>
				</h4>
				
				<form action="profile.php" method="POST">
					<input type="hidden" name="action" value="update">
					
					<table class="table table-striped table-hover align-middle">
						<tbody>
							<?php if ($isCompany): ?>
								<!-- ================= GIAO DIỆN DÀNH CHO CÔNG TY ================= -->
								<tr>
									<th scope="row" style="width: 30%;">Tên công ty:</th>
									<td><strong><?php echo htmlspecialchars($user['TenCongTy']); ?></strong></td>
								</tr>
								<tr>
									<th scope="row">Mã số thuế:</th>
									<td><?php echo htmlspecialchars($user['MaSoThue'] ?? 'Chưa cập nhật'); ?></td>
								</tr>
								<tr>
									<th scope="row">Website công ty:</th>
									<td>
										<span class="view-mode"><?php echo htmlspecialchars($user['Website'] ?? 'Chưa cập nhật'); ?></span>
										<input type="url" name="website" class="form-control form-control-sm edit-mode" value="<?php echo htmlspecialchars($user['Website'] ?? ''); ?>">
									</td>
								</tr>
								<tr>
									<th scope="row">Lĩnh vực hoạt động:</th>
									<td>
										<span class="view-mode"><?php echo htmlspecialchars($user['LinhVuc'] ?? 'Chưa cập nhật'); ?></span>
										<input type="text" name="linhVuc" class="form-control form-control-sm edit-mode" value="<?php echo htmlspecialchars($user['LinhVuc'] ?? ''); ?>">
									</td>
								</tr>

							<?php else: ?>
								<!-- ================= GIAO DIỆN DÀNH CHO ỨNG VIÊN ================= -->
								<tr>
									<th scope="row" style="width: 30%;">Họ và tên:</th>
									<td>
										<span class="view-mode"><?php echo htmlspecialchars($user['HoTen']); ?></span>
										<input type="text" name="hoTen" class="form-control form-control-sm edit-mode" value="<?php echo htmlspecialchars($user['HoTen']); ?>" required>
									</td>
								</tr>
								<tr>
									<th scope="row">Ngày sinh:</th>
									<td>
										<span class="view-mode"><?php echo !empty($user['NgaySinh']) ? date('d/m/Y', strtotime($user['NgaySinh'])) : 'Chưa cập nhật'; ?></span>
										<input type="date" name="ngaySinh" class="form-control form-control-sm edit-mode" value="<?php echo $user['NgaySinh'] ?? ''; ?>">
									</td>
								</tr>
								<tr>
									<th scope="row">Giới tính:</th>
									<td>
										<span class="view-mode">
											<?php 
												if ($user['GioiTinh'] === null) echo 'Chưa cập nhật';
												else echo $user['GioiTinh'] == 1 ? 'Nam' : 'Nữ';
											?>
										</span>
										<select name="gioiTinh" class="form-select form-select-sm edit-mode">
											<option value="" <?php echo $user['GioiTinh'] === null ? 'selected' : ''; ?>>Chưa chọn</option>
											<option value="1" <?php echo $user['GioiTinh'] == 1 ? 'selected' : ''; ?>>Nam</option>
											<option value="0" <?php echo $user['GioiTinh'] == 0 && $user['GioiTinh'] !== null ? 'selected' : ''; ?>>Nữ</option>
										</select>
									</td>
								</tr>
							<?php endif; ?>

							<!-- ================= THÀNH PHẦN CHUNG ================= -->
							<tr>
								<th scope="row">Email tài khoản:</th>
								<td><span class="text-muted"><?php echo htmlspecialchars($user['Email']); ?></span></td>
							</tr>
							<tr>
								<th scope="row">Số điện thoại:</th>
								<td>
									<span class="view-mode"><?php echo htmlspecialchars($user['SDT'] ?? 'Chưa cập nhật'); ?></span>
									<input type="text" name="sdt" class="form-control form-control-sm edit-mode" value="<?php echo htmlspecialchars($user['SDT'] ?? ''); ?>">
								</td>
							</tr>
							<tr>
								<th scope="row">Địa chỉ:</th>
								<td>
									<span class="view-mode"><?php echo htmlspecialchars($user['DiaChi'] ?? 'Chưa cập nhật'); ?></span>
									<input type="text" name="diaChi" class="form-control form-control-sm edit-mode" value="<?php echo htmlspecialchars($user['DiaChi'] ?? ''); ?>">
								</td>
							</tr>
						</tbody>
					</table>

					<!-- HỆ THỐNG NÚT BẤM -->
					<div class="text-end mt-4">
						<button type="button" id="btnEdit" class="btn btn-warning fw-bold view-mode" onclick="switchMode(true)">✏️ Chỉnh sửa</button>
						<button type="button" id="btnCancel" class="btn btn-secondary fw-bold edit-mode me-2" onclick="switchMode(false)">❌ Hủy bỏ</button>
						<button type="submit" id="btnSave" class="btn btn-success fw-bold edit-mode">💾 Cập nhật</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
function switchMode(isEditMode) {
	const viewElements = document.querySelectorAll('.view-mode');
	const editElements = document.querySelectorAll('.edit-mode');
	
	if (isEditMode) {
		viewElements.forEach(el => el.style.display = 'none');
		editElements.forEach(el => el.style.display = 'block');
	} else {
		viewElements.forEach(el => el.style.display = '');
		editElements.forEach(el => el.style.display = 'none');
	}
}
</script>
</body>
</html>