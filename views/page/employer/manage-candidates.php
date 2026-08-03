<?php
$baseUrl = '/JobCV';
require_once __DIR__ . '/../layouts/header.php';
?>
<?php
// Lấy filter từ Controller hoặc GET
$currentMaTin = $currentFilters['maTin'] ?? ($_GET['maTin'] ?? '');
$currentTrangThai = $currentFilters['trangThai'] ?? ($_GET['trangThai'] ?? '');
?>

<div class="container py-5">
	<!-- Header chức năng -->
	<div class="d-flex justify-content-between align-items-center mb-4">
		<div>
			<h4 class="fw-bold text-dark mb-1">
				<i class="fa-solid fa-users-gear text-primary-blue me-2"></i>Hệ thống Quản lý Hồ sơ Ứng viên (ATS)
			</h4>
			<p class="text-muted small mb-0">Theo dõi, sơ loại và tương tác tự động với ứng viên nhanh chóng.</p>
		</div>
	</div>

	<!-- Bộ lọc -->
	<div class="card border-0 shadow-sm p-3 mb-4 bg-white rounded-3">
		<form class="row g-3 align-items-end" method="GET" action="">
			<input type="hidden" name="route" value="recruiter/list">

			<div class="col-12 col-md-5">
				<label class="form-label small fw-semibold text-muted mb-1">Lọc theo tin tuyển dụng</label>
				<select name="maTin" class="form-select form-select-sm">
					<option value="" <?= ($currentMaTin === '') ? 'selected' : '' ?>>-- Tất cả tin tuyển dụng --</option>
					<?php if (!empty($danhSachTinTuyenDung)): ?>
						<?php foreach ($danhSachTinTuyenDung as $tin): ?>
							<option value="<?= htmlspecialchars($tin['MaTinTuyenDung']) ?>" 
								<?= ($currentMaTin == $tin['MaTinTuyenDung']) ? 'selected' : '' ?>>
								<?= htmlspecialchars($tin['TieuDe']) ?>
							</option>
						<?php endforeach; ?>
					<?php endif; ?>
				</select>
			</div>

			<div class="col-12 col-md-4">
				<label class="form-label small fw-semibold text-muted mb-1">Bộ lọc nhanh trạng thái</label>
				<select name="trangThai" class="form-select form-select-sm">
					<option value="" <?= ($currentTrangThai === '') ? 'selected' : '' ?>>Tất cả trạng thái</option>
					<option value="<?= STATUS_MOI_NOP ?>" <?= ($currentTrangThai == STATUS_MOI_NOP) ? 'selected' : '' ?>>Vừa ứng tuyển</option>
					<option value="<?= STATUS_DA_XEM ?>" <?= ($currentTrangThai == STATUS_DA_XEM) ? 'selected' : '' ?>>Đã xem</option>
					<option value="<?= STATUS_HEN_PHONG_VAN ?>" <?= ($currentTrangThai == STATUS_HEN_PHONG_VAN) ? 'selected' : '' ?>>Hẹn phỏng vấn</option>
					<option value="<?= STATUS_NHAN_VIEC ?>" <?= ($currentTrangThai == STATUS_NHAN_VIEC) ? 'selected' : '' ?>>Nhận việc</option>
					<option value="<?= STATUS_TU_CHOI ?>" <?= ($currentTrangThai == STATUS_TU_CHOI) ? 'selected' : '' ?>>Từ chối</option>
				</select>
			</div>

			<div class="col-12 col-md-3">
				<button type="submit" class="btn btn-primary-blue btn-sm w-100 fw-bold py-2">
					<i class="fa-solid fa-filter me-1"></i> Áp dụng
				</button>
			</div>
		</form>
	</div>

	<!-- Danh sách Hồ sơ -->
	<div class="card border-0 shadow-sm p-4 bg-white rounded-3">
		<div class="table-responsive">
			<table class="table table-hover align-middle">
				<thead class="table-light text-secondary">
					<tr>
						<th>Ứng viên</th>
						<th>CV</th>
						<!-- <th>Điểm đánh giá</th> -->
						<th>Trạng thái</th>
						<th class="text-center">Hành động</th>
					</tr>
				</thead>
				<tbody>
					<?php if (empty($danhSachHoSoUngTuyen)): ?>
						<tr><td colspan="5" class="text-center py-5 text-muted">Chưa có hồ sơ nào.</td></tr>
					<?php else: ?>
						<?php foreach ($danhSachHoSoUngTuyen as $hs): ?>
						<tr>
							<td>
								<div class="d-flex align-items-center gap-3">
									<img src="https://api.dicebear.com/7.x/adventurer/svg?seed=<?= htmlspecialchars($hs['MaUngVien'] ?? 'user') ?>" 
										class="rounded-circle border" style="width: 45px; height: 45px;">
									<div>
										<h6 class="fw-bold mb-0"><?= htmlspecialchars($hs['HoTen'] ?? 'Không tên') ?></h6>
										<small class="text-muted"><?= htmlspecialchars($hs['Email'] ?? '') ?></small>
									</div>
								</div>
							</td>
							<td>
								<a href="<?= $baseUrl ?>/index.php?route=recruiter/detail&maHS=<?= urlencode($hs['MaHS'] ?? '') ?>"
								class="btn btn-sm btn-outline-primary">
									<i class="fa-solid fa-file-lines me-1"></i>
									Xem CV
								</a>
							</td>
							<!-- <td>
								<?php if (isset($hs['DiemDanhGia']) && $hs['DiemDanhGia'] > 0): ?>
									<span class="badge bg-success text-white">
										<?= number_format($hs['DiemDanhGia'], 1) ?>/10
									</span>
								<?php else: ?>
									<span class="badge bg-secondary text-white">
										Chưa đánh giá
									</span>
								<?php endif; ?>
							</td> -->
							<td>
								<?php 
									$statusClass = match($hs['TrangThai'] ?? '') {
										STATUS_MOI_NOP => 'bg-warning text-dark',
										STATUS_DA_XEM => 'bg-info text-white',
										STATUS_HEN_PHONG_VAN => 'bg-success text-white',
										STATUS_NHAN_VIEC => 'bg-success text-white',
										STATUS_TU_CHOI => 'bg-danger text-white',
										default => 'bg-secondary'
									};
								?>
								<span class="badge <?= $statusClass ?>"><?= htmlspecialchars($hs['TrangThaiText'] ?? $hs['TrangThai'] ?? 'Không xác định') ?></span>
							</td>
							<td class="text-center">
								<?php
									$trangThai = $hs['TrangThai'] ?? '';
									?>

									<!-- MỚI NỘP -->
									<?php if ($trangThai === STATUS_MOI_NOP): ?>

										<!-- ĐÃ XEM -->
										<form
											method="POST"
											action="<?= BASE_URL ?>/index.php?route=recruiter/update-status"
											class="d-inline"
										>

											<input
												type="hidden"
												name="maHS"
												value="<?= htmlspecialchars($hs['MaHS']) ?>"
											>

											<input
												type="hidden"
												name="trangThai"
												value="DaXem"
											>

											<button
												type="submit"
												class="btn btn-outline-info btn-sm mb-1"
												onclick="return confirm('Xác nhận đã xem hồ sơ?')"
											>

												<i class="fa-solid fa-eye"></i>
												Đã xem

											</button>

										</form>


										<!-- HẸN PHỎNG VẤN -->
										<form
											method="POST"
											action="<?= BASE_URL ?>/index.php?route=recruiter/update-status"
											class="d-inline"
										>

											<input
												type="hidden"
												name="maHS"
												value="<?= htmlspecialchars($hs['MaHS']) ?>"
											>

											<input
												type="hidden"
												name="trangThai"
												value="HenPhongVan"
											>

											<button
												type="submit"
												class="btn btn-outline-success btn-sm mb-1"
												onclick="return confirm('Xác nhận hẹn phỏng vấn?')"
											>

												<i class="fa-solid fa-calendar-check"></i>
												Hẹn PV

											</button>

										</form>

									<?php endif; ?>


									<!-- ĐÃ XEM -->
									<?php if ($trangThai === STATUS_DA_XEM): ?>

										<form
											method="POST"
											action="<?= BASE_URL ?>/index.php?route=recruiter/update-status"
											class="d-inline"
										>

											<input
												type="hidden"
												name="maHS"
												value="<?= htmlspecialchars($hs['MaHS']) ?>"
											>

											<input
												type="hidden"
												name="trangThai"
												value="HenPhongVan"
											>

											<button
												type="submit"
												class="btn btn-outline-success btn-sm mb-1"
												onclick="return confirm('Xác nhận hẹn phỏng vấn?')"
											>

												<i class="fa-solid fa-calendar-check"></i>
												Hẹn PV

											</button>

										</form>

									<?php endif; ?>


									<!-- HẸN PHỎNG VẤN -->
									<?php if ($trangThai === STATUS_HEN_PHONG_VAN): ?>

										<form
											method="POST"
											action="<?= BASE_URL ?>/index.php?route=recruiter/update-status"
											class="d-inline"
										>

											<input
												type="hidden"
												name="maHS"
												value="<?= htmlspecialchars($hs['MaHS']) ?>"
											>

											<input
												type="hidden"
												name="trangThai"
												value="NhanViec"
											>

											<button
												type="submit"
												class="btn btn-outline-success btn-sm mb-1"
												onclick="return confirm('Xác nhận nhận việc?')"
											>

												<i class="fa-solid fa-circle-check"></i>
												Nhận việc

											</button>

										</form>

									<?php endif; ?>


									<!-- TỪ CHỐI -->
									<?php if (
										$trangThai !== STATUS_NHAN_VIEC &&
										$trangThai !== STATUS_TU_CHOI
									): ?>

										<form
											method="POST"
											action="<?= BASE_URL ?>/index.php?route=recruiter/update-status"
											class="d-inline"
										>

											<input
												type="hidden"
												name="maHS"
												value="<?= htmlspecialchars($hs['MaHS']) ?>"
											>

											<input
												type="hidden"
												name="trangThai"
												value="TuChoi"
											>

											<button
												type="submit"
												class="btn btn-outline-danger btn-sm mb-1"
												onclick="return confirm('Xác nhận từ chối?')"
											>

												<i class="fa-solid fa-user-xmark"></i>
												Từ chối

											</button>

										</form>

									<?php endif; ?>
							</td>
						</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>