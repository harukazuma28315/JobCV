<?php
/**
 * File: app/views/admin/jobs.php
 * Chức năng: Quản lý bài đăng - Admin
 */
if (session_status() === PHP_SESSION_NONE) { 
	session_start(); 
}

/**
 * Gộp trạng thái duyệt + trạng thái mở/đóng + hết hạn thành 1 badge duy nhất.
 * Dùng chung cho cả bảng danh sách và modal xem chi tiết.
 */
if (!function_exists('getJobStatusBadge')) {
function getJobStatusBadge($tin) {
	$duyet = $tin['TrangThaiDuyet'] ?? '';
	$moDong = $tin['TrangThai'] ?? '';
	$daHetHan = !empty($tin['NgayHetHan'])
		&& strtotime($tin['NgayHetHan']) < strtotime('today');

	if ($duyet === 'ChoDuyet') {
		return ['bg-warning text-dark', 'fa-solid fa-hourglass-half', 'Chờ duyệt'];
	} elseif ($duyet === 'TuChoi') {
		return ['bg-danger', 'fa-solid fa-circle-xmark', 'Bị từ chối'];
	} elseif ($duyet === 'DaGo') {
		return ['bg-dark', 'fa-solid fa-eye-slash', 'Đã gỡ'];
	} elseif ($daHetHan) {
		return ['bg-danger', 'fa-solid fa-clock-rotate-left', 'Đã hết hạn'];
	} elseif ($moDong !== 'DangMo') {
		return ['bg-secondary', 'fa-solid fa-ban', 'Đã đóng'];
	}
	return ['bg-success', 'fa-solid fa-circle-check', 'Đang hoạt động'];
}
}

/**
 * Nhãn hiển thị tiếng Việt cho cấp bậc / hình thức làm việc (dữ liệu DB lưu dạng mã).
 */
if (!function_exists('getCapBacLabel')) {
function getCapBacLabel($ma) {
	$map = [
		'Intern' => 'Thực tập sinh', 'Fresher' => 'Fresher', 'Junior' => 'Junior',
		'Middle' => 'Middle', 'Senior' => 'Senior', 'Manager' => 'Quản lý',
	];
	return $map[$ma] ?? ($ma ?: '—');
}
}
if (!function_exists('getHinhThucLabel')) {
function getHinhThucLabel($ma) {
	$map = [
		'Full-time' => 'Toàn thời gian (Full-time)', 'Part-time' => 'Bán thời gian (Part-time)',
		'Internship' => 'Thực tập sinh (Internship)',
	];
	return $map[$ma] ?? ($ma ?: '—');
}
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Quản lý bài đăng - Admin</title>
	<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/bootstrap.min.css">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
	<style>
		:root { --sidebar-width: 260px; --primary-blue: #1e5ba6; }
		body { background-color: #f4f7f6; font-family: 'Segoe UI', sans-serif; }
		.sidebar { width: var(--sidebar-width); height: 100vh; position: fixed; top: 0; left: 0; background-color: #fff; border-right: 1px solid #e5e5e5; }
		.main-content { margin-left: var(--sidebar-width); padding: 20px; }
		.nav-link-admin { display: flex; align-items: center; padding: 12px 20px; color: #555; text-decoration: none; font-weight: 500; border-radius: 8px; margin: 4px 15px; }
		.nav-link-admin.active { background-color: #f0f4f9; color: var(--primary-blue); }
		.action-grid{
    display:grid;
    grid-template-columns:80px 95px 95px;
    gap:8px;
    justify-content:start;
}

.action-grid form{
    margin:0;
    display:flex;
}

.action-grid .btn{
    width:100%;
    height:31px;
    padding:4px 8px;
    font-size:.85rem;
    display:flex;
    align-items:center;
    justify-content:center;
    white-space:nowrap;
    border-radius:6px;
}
		.btn-span-2 { grid-column: span 2; width: 100%; }
	</style>
</head>
<body>

<div class="sidebar d-flex flex-column justify-content-between py-3">
	<div>
		<div class="px-4 py-3 border-bottom mb-3">
			<h4 class="text-primary-blue fw-bold mb-0">JobHub Admin</h4>
		</div>
		<div class="nav flex-column">
			<a href="<?= BASE_URL ?>/index.php?route=admin/dashboard" class="nav-link-admin">
				<i class="fa-solid fa-house me-3"></i>Trang chủ
			</a>
			<a href="<?= BASE_URL ?>/index.php?route=admin/users" class="nav-link-admin">
				<i class="fa-solid fa-users me-3"></i>Quản lý người dùng
			</a>
			<a href="<?= BASE_URL ?>/index.php?route=admin/jobs" class="nav-link-admin active">
				<i class="fa-solid fa-file-signature me-3"></i>Quản lý bài đăng
			</a>
			<a href="<?= BASE_URL ?>/index.php?route=admin/categories" class="nav-link-admin">
				<i class="fa-solid fa-folder-tree me-3"></i>Quản lý danh mục
			</a>
		</div>
	</div>
	<div class="px-4">
		<a href="<?= BASE_URL ?>/index.php?route=auth/logout" class="btn btn-outline-danger w-100">
			Thoát giao diện Admin
		</a>
	</div>
</div>

<div class="main-content">
	<h3 class="fw-bold mb-4">Quản lý bài đăng</h3>

	<?php if (isset($thongBao) && !empty($thongBao)): 
		$alertClass = (isset($thongBao['type']) && $thongBao['type'] === 'success') ? 'success' : 'danger';
	?>
		<div class="alert alert-<?= $alertClass ?> alert-dismissible fade show" role="alert">
			<?= htmlspecialchars($thongBao['message'] ?? '') ?>
			<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
		</div>
	<?php endif; ?>
	<form method="GET" action="<?= BASE_URL ?>/index.php">
		<input type="hidden" name="route" value="admin/jobs">
		<div class="card border-0 shadow-sm p-3 mb-4 rounded-3">
			<div class="row g-3">
				<div class="col-md-7">
					<div class="input-group">
						<span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
						<input type="text" name="keyword" class="form-control border-start-0" 
							   placeholder="Tìm kiếm theo tên công ty hoặc vị trí tuyển dụng..." 
							   value="<?= htmlspecialchars($keyword ?? '') ?>">
					</div>
				</div>
				<div class="col-md-3">
					<select name="status" class="form-select">
						<option value="">-- Trạng thái --</option>
						<option value="ChoDuyet" <?= (isset($status) && $status === 'ChoDuyet') ? 'selected' : '' ?>>Chờ duyệt</option>
						<option value="DaDuyet"  <?= (isset($status) && $status === 'DaDuyet')  ? 'selected' : '' ?>>Đã duyệt</option>
						<option value="TuChoi"   <?= (isset($status) && $status === 'TuChoi')   ? 'selected' : '' ?>>Từ chối duyệt</option>
						<option value="DaGo"     <?= (isset($status) && $status === 'DaGo')     ? 'selected' : '' ?>>Đã gỡ</option>
					</select>
				</div>
				<div class="col-md-2">
					<button type="submit" class="btn btn-secondary w-100"><i class="fa-solid fa-filter me-2"></i>Lọc tin</button>
				</div>
			</div>
		</div>
	</form>

	<div class="card border-0 shadow-sm rounded-3">
		<div class="table-responsive p-3">
			<table class="table align-middle table-hover mb-0">
				<thead class="table-light">
					<tr>
						<th>Công ty</th>
						<th>Vị trí</th>
						<th>Ngày đăng</th>
						<th>Trạng thái</th>
						<th class="text-center" style="width: 250px;">Hành động</th>
					</tr>
				</thead>
				<tbody>
					<?php if (empty($danhSachTin)): ?>
						<tr><td colspan="5" class="text-center py-4">Không tìm thấy tin tuyển dụng nào.</td></tr>
					<?php else: ?>
						<?php foreach ($danhSachTin as $tin): ?>
							<tr>
								<td><strong><?= htmlspecialchars($tin['TenCongTy'] ?? '') ?></strong></td>
								<td><?= htmlspecialchars($tin['TieuDe'] ?? '') ?></td>
								<td><?= !empty($tin['NgayDang']) ? date('d/m/Y', strtotime($tin['NgayDang'])) : '' ?></td>
								<td>
									<?php $statusBadge = getJobStatusBadge($tin); ?>
									<span class="badge <?= $statusBadge[0] ?> py-1.5 px-3 rounded-pill">
										<i class="<?= $statusBadge[1] ?> me-1"></i><?= $statusBadge[2] ?>
									</span>
								</td>
								<td>
									<div class="action-grid">
										<button class="btn btn-outline-primary" title="Xem chi tiết"
												type="button"
												data-bs-toggle="modal"
												data-bs-target="#viewJobModal-<?= htmlspecialchars($tin['MaTinTuyenDung']) ?>">
											<i class="fa-solid fa-eye me-1"></i>Xem
										</button>

										<?php if ($tin['TrangThaiDuyet'] === 'ChoDuyet'): ?>
											<form method="POST" action="<?= BASE_URL ?>/index.php?route=admin/jobs/approve" style="display:inline;">
												<input type="hidden" name="maTin" value="<?= htmlspecialchars($tin['MaTinTuyenDung']) ?>">
												<button type="submit" class="btn btn-success" onclick="return confirm('Duyệt tin này?')">
													<i class="fa-solid fa-check me-1"></i>Duyệt
												</button>
											</form>
											<form method="POST" action="<?= BASE_URL ?>/index.php?route=admin/jobs/reject" style="display:inline;">
												<input type="hidden" name="maTin" value="<?= htmlspecialchars($tin['MaTinTuyenDung']) ?>">
												<button type="submit" class="btn btn-danger" onclick="return confirm('Từ chối tin này?')">
													<i class="fa-solid fa-xmark me-1"></i>Từ chối
												</button>
											</form>
										<?php elseif ($tin['TrangThaiDuyet'] === 'DaDuyet'): ?>
											<form method="POST" action="<?= BASE_URL ?>/index.php?route=admin/jobs/remove" style="display:inline;">
												<input type="hidden" name="maTin" value="<?= htmlspecialchars($tin['MaTinTuyenDung']) ?>">
												<button type="submit" class="btn btn-outline-danger btn-span-2" onclick="return confirm('Gỡ tin này?')">
													<i class="fa-solid fa-ban me-1"></i>Gỡ tin
												</button>
											</form>
										<?php elseif ($tin['TrangThaiDuyet'] === 'TuChoi'): ?>
											<form method="POST" action="<?= BASE_URL ?>/index.php?route=admin/jobs/approve" style="display:inline;">
												<input type="hidden" name="maTin" value="<?= htmlspecialchars($tin['MaTinTuyenDung']) ?>">
												<button type="submit" class="btn btn-outline-success btn-span-2" onclick="return confirm('Xem xét lại và duyệt?')">
													<i class="fa-solid fa-rotate-left me-1"></i>Xem xét lại
												</button>
											</form>
										<?php endif; ?>
									</div>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				</tbody>
			</table>
		</div>

		<?php foreach ($danhSachTin as $tin): $vStatus = getJobStatusBadge($tin); ?>
			<div class="modal fade" id="viewJobModal-<?= htmlspecialchars($tin['MaTinTuyenDung']) ?>" tabindex="-1" aria-hidden="true">
				<div class="modal-dialog modal-dialog-scrollable modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title fw-bold">
								<i class="fa-solid fa-file-lines text-primary-blue me-2"></i>Chi tiết tin tuyển dụng
							</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<div class="d-flex justify-content-between align-items-start mb-3 pb-3 border-bottom">
								<div>
									<h5 class="fw-bold text-dark mb-1"><?= htmlspecialchars($tin['TieuDe'] ?? '') ?></h5>
									<div class="text-muted">
										<i class="fa-solid fa-building me-1"></i><?= htmlspecialchars($tin['TenCongTy'] ?? '') ?>
									</div>
								</div>
								<span class="badge <?= $vStatus[0] ?> py-1.5 px-3 rounded-pill">
									<i class="<?= $vStatus[1] ?> me-1"></i><?= $vStatus[2] ?>
								</span>
							</div>

							<div class="row g-3 mb-3">
								<div class="col-12 col-md-6">
									<div class="text-muted small fw-semibold">Vị trí tuyển dụng</div>
									<div><?= htmlspecialchars($tin['ViTriTuyenDung'] ?? '') ?: '—' ?></div>
								</div>
								<div class="col-12 col-md-6">
									<div class="text-muted small fw-semibold">Lĩnh vực / Ngành nghề</div>
									<div><?= htmlspecialchars($tin['LinhVucNganhNghe'] ?? $tin['TenDanhMuc'] ?? '') ?: '—' ?></div>
								</div>
								<div class="col-12 col-md-6">
									<div class="text-muted small fw-semibold">Cấp bậc</div>
									<div><?= htmlspecialchars(getCapBacLabel($tin['CapBac'] ?? '')) ?></div>
								</div>
								<div class="col-12 col-md-6">
									<div class="text-muted small fw-semibold">Hình thức làm việc</div>
									<div><?= htmlspecialchars(getHinhThucLabel($tin['HinhThucLamViec'] ?? '')) ?></div>
								</div>
								<div class="col-12 col-md-6">
									<div class="text-muted small fw-semibold">Số năm kinh nghiệm</div>
									<div><?= isset($tin['SoNamKinhNghiem']) && $tin['SoNamKinhNghiem'] !== null && $tin['SoNamKinhNghiem'] !== '' ? htmlspecialchars($tin['SoNamKinhNghiem']) . ' năm' : '—' ?></div>
								</div>
								<div class="col-12 col-md-6">
									<div class="text-muted small fw-semibold">Độ tuổi yêu cầu</div>
									<div><?= htmlspecialchars($tin['DoTuoiYeuCau'] ?? '') ?: '—' ?></div>
								</div>
								<div class="col-12 col-md-6">
									<div class="text-muted small fw-semibold">Số lượng tuyển</div>
									<div><?= isset($tin['SoLuongTuyen']) && $tin['SoLuongTuyen'] !== null && $tin['SoLuongTuyen'] !== '' ? htmlspecialchars($tin['SoLuongTuyen']) : '—' ?></div>
								</div>
								<div class="col-12 col-md-6">
									<div class="text-muted small fw-semibold">Thời gian thử việc</div>
									<div><?= isset($tin['ThoiGianThuViec']) && $tin['ThoiGianThuViec'] !== null && $tin['ThoiGianThuViec'] !== '' ? htmlspecialchars($tin['ThoiGianThuViec']) . ' tháng' : '—' ?></div>
								</div>
								<div class="col-12 col-md-6">
									<div class="text-muted small fw-semibold">Mức lương</div>
									<div class="text-success fw-semibold">
										<?= !empty($tin['MucLuong']) ? number_format((float) $tin['MucLuong'], 0, ',', '.') . ' đ' : '—' ?>
									</div>
								</div>
								<div class="col-12 col-md-6">
									<div class="text-muted small fw-semibold">Hạn chót nhận hồ sơ</div>
									<div><?= !empty($tin['NgayHetHan']) ? date('d/m/Y', strtotime($tin['NgayHetHan'])) : '—' ?></div>
								</div>
								<div class="col-12 col-md-6">
									<div class="text-muted small fw-semibold">Ngày đăng</div>
									<div><?= !empty($tin['NgayDang']) ? date('d/m/Y', strtotime($tin['NgayDang'])) : '—' ?></div>
								</div>
								<div class="col-12 col-md-6">
								<div class="text-muted small fw-semibold">Địa điểm</div>
								<div>
									<i class="fa-solid fa-map-marker-alt me-1 text-primary"></i>
									<?= htmlspecialchars($tin['DiaDiemLamViec'] ?? '') ?: '—' ?>
								</div>
							</div>

							<div class="col-12 col-md-6">
								<div class="text-muted small fw-semibold">Địa chỉ chi tiết</div>
								<div>
									<i class="fa-solid fa-location-dot me-1 text-danger"></i>
									<?= htmlspecialchars($tin['DiaChiLamViec'] ?? '') ?: '—' ?>
								</div>
							</div>
							</div>

							<h6 class="fw-bold text-primary-blue text-uppercase border-top pt-3 mb-2">Mô tả công việc (JD)</h6>
							<p class="mb-3" style="white-space: pre-line;"><?= htmlspecialchars($tin['MoTaCongViec'] ?? '') ?: '—' ?></p>

							<h6 class="fw-bold text-primary-blue text-uppercase border-top pt-3 mb-2">Yêu cầu ứng viên</h6>
							<p class="mb-0" style="white-space: pre-line;"><?= htmlspecialchars($tin['YeuCauCongViec'] ?? '') ?: '—' ?></p>
						</div>
						<div class="modal-footer">
							<?php if ($tin['TrangThaiDuyet'] === 'ChoDuyet'): ?>
								<form method="POST" action="<?= BASE_URL ?>/index.php?route=admin/jobs/reject" class="d-inline">
									<input type="hidden" name="maTin" value="<?= htmlspecialchars($tin['MaTinTuyenDung']) ?>">
									<button type="submit" class="btn btn-danger" onclick="return confirm('Từ chối tin này?')">
										<i class="fa-solid fa-xmark me-1"></i>Từ chối
									</button>
								</form>
								<form method="POST" action="<?= BASE_URL ?>/index.php?route=admin/jobs/approve" class="d-inline">
									<input type="hidden" name="maTin" value="<?= htmlspecialchars($tin['MaTinTuyenDung']) ?>">
									<button type="submit" class="btn btn-success" onclick="return confirm('Duyệt tin này?')">
										<i class="fa-solid fa-check me-1"></i>Duyệt
									</button>
								</form>
							<?php elseif ($tin['TrangThaiDuyet'] === 'DaDuyet'): ?>
								<form method="POST" action="<?= BASE_URL ?>/index.php?route=admin/jobs/remove" class="d-inline">
									<input type="hidden" name="maTin" value="<?= htmlspecialchars($tin['MaTinTuyenDung']) ?>">
									<button type="submit" class="btn btn-outline-danger" onclick="return confirm('Gỡ tin này?')">
										<i class="fa-solid fa-ban me-1"></i>Gỡ tin
									</button>
								</form>
							<?php elseif ($tin['TrangThaiDuyet'] === 'TuChoi'): ?>
								<form method="POST" action="<?= BASE_URL ?>/index.php?route=admin/jobs/approve" class="d-inline">
									<input type="hidden" name="maTin" value="<?= htmlspecialchars($tin['MaTinTuyenDung']) ?>">
									<button type="submit" class="btn btn-outline-success" onclick="return confirm('Xem xét lại và duyệt?')">
										<i class="fa-solid fa-rotate-left me-1"></i>Xem xét lại
									</button>
								</form>
							<?php endif; ?>
							<button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
						</div>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>

<script src="<?= BASE_URL ?>/assets/js/jsbs/bootstrap.bundle.min.js"></script>
</body>
</html>