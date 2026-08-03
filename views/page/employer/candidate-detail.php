<?php
$baseUrl = '/JobCV';
require_once __DIR__ . '/../layouts/header.php';

// Dữ liệu được truyền từ RecruiterController::showDetail():
// $hoSoUngTuyen (hồ sơ ứng tuyển + thông tin ứng viên/tin tuyển dụng),
// $cv, $hocVanList, $kinhNghiemList, $duAnList, $chungChiList (chi tiết CV),
// $thongBao (flash message).

$trangThai = $hoSoUngTuyen['TrangThai'] ?? '';

$statusClass = match ($trangThai) {
	STATUS_MOI_NOP => 'bg-warning text-dark',
	STATUS_DA_XEM => 'bg-info text-white',
	STATUS_HEN_PHONG_VAN => 'bg-success text-white',
	STATUS_NHAN_VIEC => 'bg-success text-white',
	STATUS_TU_CHOI => 'bg-danger text-white',
	default => 'bg-secondary'
};

$statusLabel = match ($trangThai) {
	STATUS_MOI_NOP => 'Vừa ứng tuyển',
	STATUS_DA_XEM => 'Đã xem',
	STATUS_HEN_PHONG_VAN => 'Hẹn phỏng vấn',
	STATUS_NHAN_VIEC => 'Nhận việc',
	STATUS_TU_CHOI => 'Từ chối',
	default => 'Không xác định'
};
?>

<section class="py-5 bg-light">
	<div class="container">

		<!-- Quay lại danh sách -->
		<div class="mb-4">
			<a href="<?= $baseUrl ?>/index.php?route=recruiter/list" class="btn btn-outline-secondary btn-sm">
				<i class="fa-solid fa-arrow-left me-2"></i>Quay lại danh sách ứng viên
			</a>
		</div>

		<?php if (!empty($thongBao)): ?>
			<div class="alert alert-<?= $thongBao['type'] === 'error' ? 'danger' : 'success' ?>">
				<?= htmlspecialchars($thongBao['message']) ?>
			</div>
		<?php endif; ?>

		<!-- HEADER: Ứng viên + Tin ứng tuyển + Trạng thái -->
		<div class="card border-0 shadow-sm mx-auto mb-4" style="max-width: 900px;">
			<div class="card-body p-4">
				<div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
					<div class="d-flex align-items-center gap-3">
						<img
							src="https://api.dicebear.com/7.x/adventurer/svg?seed=<?= htmlspecialchars($hoSoUngTuyen['MaCV'] ?? 'user') ?>"
							class="rounded-circle border"
							style="width: 60px; height: 60px;"
						>
						<div>
							<h4 class="fw-bold mb-1"><?= htmlspecialchars($hoSoUngTuyen['TenUngVien'] ?? 'Không tên') ?></h4>
							<div class="text-muted small">
								<i class="fa-solid fa-envelope me-1"></i><?= htmlspecialchars($hoSoUngTuyen['EmailUngVien'] ?? '') ?>
								<?php if (!empty($hoSoUngTuyen['SdtUngVien'])): ?>
									&nbsp;&middot;&nbsp;<i class="fa-solid fa-phone me-1"></i><?= htmlspecialchars($hoSoUngTuyen['SdtUngVien']) ?>
								<?php endif; ?>
							</div>
						</div>
					</div>
					<span class="badge <?= $statusClass ?> px-3 py-2"><?= htmlspecialchars($statusLabel) ?></span>
				</div>

				<hr>

				<p class="mb-1"><strong>Ứng tuyển vào tin:</strong> <?= htmlspecialchars($hoSoUngTuyen['TenTin'] ?? '') ?></p>
				<?php if (!empty($hoSoUngTuyen['NgayNop'])): ?>
					<p class="mb-0 text-muted small">Ngày nộp: <?= date('d/m/Y H:i', strtotime($hoSoUngTuyen['NgayNop'])) ?></p>
				<?php endif; ?>

				<?php if (!empty($hoSoUngTuyen['CoverLetter'])): ?>
					<hr>
					<h6 class="fw-bold">Thư giới thiệu</h6>
					<p class="mb-0"><?= nl2br(htmlspecialchars($hoSoUngTuyen['CoverLetter'])) ?></p>
				<?php endif; ?>
			</div>
		</div>

		<?php if (!$cv): ?>

			<div class="card border-0 shadow-sm mx-auto p-4 text-center" style="max-width: 900px;">
				<p class="text-muted mb-0">Ứng viên chưa có CV trên hệ thống.</p>
			</div>

		<?php else: ?>

			<!-- CHI TIẾT CV (CHỈ XEM) -->
			<div class="card border-0 shadow-sm mx-auto" style="max-width: 900px;">

				<div class="card-header bg-primary text-white">
					<div class="d-flex justify-content-between align-items-center">
						<h4 class="mb-0 fw-bold">
							<i class="fa-solid fa-file-lines me-2"></i>
							<?= htmlspecialchars($cv['TieuDe']) ?>
						</h4>
					</div>
				</div>

				<div class="card-body p-4">
					<div class="row g-4">

						<!-- THÔNG TIN CƠ BẢN -->
						<div class="col-md-6">
							<h5 class="fw-bold text-primary mb-3">
								<i class="fa-solid fa-user me-2"></i>Thông tin cơ bản
							</h5>
							<p>
								<strong>Vị trí mong muốn:</strong><br>
								<?= htmlspecialchars($cv['ViTriMongMuon'] ?: 'Chưa cập nhật') ?>
							</p>
							<p>
								<strong>Email:</strong><br>
								<?= htmlspecialchars($cv['Email']) ?>
							</p>
							<p class="mb-0">
								<strong>Số điện thoại:</strong><br>
								<?= htmlspecialchars($cv['SDT']) ?>
							</p>
						</div>

						<!-- KỸ NĂNG -->
						<div class="col-md-6">
							<h5 class="fw-bold text-primary mb-3">
								<i class="fa-solid fa-star me-2"></i>Kỹ năng
							</h5>
							<p class="mb-0"><?= nl2br(htmlspecialchars($cv['KyNang'] ?: 'Chưa cập nhật')) ?></p>
						</div>

						<!-- MỤC TIÊU -->
						<div class="col-md-6">
							<h5 class="fw-bold text-primary mb-3">
								<i class="fa-solid fa-bullseye me-2"></i>Mục tiêu nghề nghiệp
							</h5>
							<p class="mb-0"><?= nl2br(htmlspecialchars($cv['MucTieu'] ?: 'Chưa cập nhật')) ?></p>
						</div>

						<!-- SỞ THÍCH -->
						<div class="col-md-6">
							<h5 class="fw-bold text-primary mb-3">
								<i class="fa-solid fa-heart me-2"></i>Sở thích
							</h5>
							<p class="mb-0"><?= nl2br(htmlspecialchars($cv['SoThich'] ?: 'Chưa cập nhật')) ?></p>
						</div>

					</div>

					<!-- HỌC VẤN -->
					<hr>
					<h5 class="fw-bold text-primary mb-3">
						<i class="fa-solid fa-graduation-cap me-2"></i>Học vấn
					</h5>

					<?php if (empty($hocVanList)): ?>
						<p class="text-muted">Chưa cập nhật học vấn.</p>
					<?php else: ?>
						<?php foreach ($hocVanList as $hocVan): ?>
							<div class="card border-0 shadow-sm mb-3 p-3">
								<h6 class="fw-bold mb-1"><?= htmlspecialchars($hocVan['TenTruong']) ?></h6>
								<p class="text-muted small mb-1">
									<?= htmlspecialchars($hocVan['ChuyenNganh'] ?: 'Chưa cập nhật chuyên ngành') ?>
									<?php if (!empty($hocVan['NamBatDau']) || !empty($hocVan['NamKetThuc'])): ?>
										&middot; <?= htmlspecialchars($hocVan['NamBatDau'] ?? '?') ?> - <?= htmlspecialchars($hocVan['NamKetThuc'] ?? '?') ?>
									<?php endif; ?>
								</p>
								<?php if (!empty($hocVan['HocLuc']) || !empty($hocVan['GPA'])): ?>
									<p class="small mb-0">
										<?= htmlspecialchars($hocVan['HocLuc'] ?? '') ?>
										<?= !empty($hocVan['GPA']) ? ' &middot; GPA: ' . htmlspecialchars($hocVan['GPA']) : '' ?>
									</p>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>

					<!-- KINH NGHIỆM -->
					<hr>
					<h5 class="fw-bold text-primary mb-3">
						<i class="fa-solid fa-briefcase me-2"></i>Kinh nghiệm làm việc
					</h5>

					<?php if (empty($kinhNghiemList)): ?>
						<p class="text-muted">Chưa cập nhật kinh nghiệm làm việc.</p>
					<?php else: ?>
						<?php foreach ($kinhNghiemList as $kinhNghiem): ?>
							<div class="card border-0 shadow-sm mb-3 p-3">
								<h6 class="fw-bold mb-1">
									<?= htmlspecialchars($kinhNghiem['ViTri'] ?: 'Chưa cập nhật vị trí') ?>
									&mdash; <?= htmlspecialchars($kinhNghiem['TenCongTy']) ?>
								</h6>
								<p class="text-muted small mb-1"><?= htmlspecialchars($kinhNghiem['ThoiGianLamViec'] ?: '') ?></p>
								<?php if (!empty($kinhNghiem['MoTa'])): ?>
									<p class="small mb-0"><?= nl2br(htmlspecialchars($kinhNghiem['MoTa'])) ?></p>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>

					<!-- DỰ ÁN -->
					<hr>
					<h5 class="fw-bold text-primary mb-3">
						<i class="fa-solid fa-diagram-project me-2"></i>Dự án
					</h5>

					<?php if (empty($duAnList)): ?>
						<p class="text-muted">Chưa cập nhật dự án.</p>
					<?php else: ?>
						<?php foreach ($duAnList as $duAn): ?>
							<div class="card border-0 shadow-sm mb-3 p-3">
								<h6 class="fw-bold mb-1"><?= htmlspecialchars($duAn['TenDuAn']) ?></h6>
								<p class="text-muted small mb-1">
									<?= htmlspecialchars($duAn['ViTri'] ?: 'Chưa cập nhật vị trí') ?>
									<?php if (!empty($duAn['SoLuongThanhVien'])): ?>
										&middot; <?= (int) $duAn['SoLuongThanhVien'] ?> thành viên
									<?php endif; ?>
								</p>
								<?php if (!empty($duAn['CongNgheSuDung'])): ?>
									<p class="small mb-1"><strong>Công nghệ:</strong> <?= htmlspecialchars($duAn['CongNgheSuDung']) ?></p>
								<?php endif; ?>
								<?php if (!empty($duAn['MoTa'])): ?>
									<p class="small mb-0"><?= nl2br(htmlspecialchars($duAn['MoTa'])) ?></p>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>

					<!-- CHỨNG CHỈ -->
					<hr>
					<h5 class="fw-bold text-primary mb-3">
						<i class="fa-solid fa-certificate me-2"></i>Chứng chỉ
					</h5>

					<?php if (empty($chungChiList)): ?>
						<p class="text-muted">Chưa cập nhật chứng chỉ.</p>
					<?php else: ?>
						<?php foreach ($chungChiList as $chungChi): ?>
							<div class="card border-0 shadow-sm mb-3 p-3">
								<h6 class="fw-bold mb-1"><?= htmlspecialchars($chungChi['TenChungChi']) ?></h6>
								<p class="text-muted small mb-1">
									<?= htmlspecialchars($chungChi['ToChucCap'] ?: 'Chưa cập nhật nơi cấp') ?>
									<?php if (!empty($chungChi['NgayCap'])): ?>
										&middot; Cấp ngày <?= date('d/m/Y', strtotime($chungChi['NgayCap'])) ?>
									<?php endif; ?>
									<?php if (!empty($chungChi['NgayHetHan'])): ?>
										&middot; Hết hạn <?= date('d/m/Y', strtotime($chungChi['NgayHetHan'])) ?>
									<?php endif; ?>
								</p>
								<?php if (!empty($chungChi['DuongLinkChungChi'])): ?>
									<p class="small mb-0">
										<a href="<?= htmlspecialchars($chungChi['DuongLinkChungChi']) ?>" target="_blank" class="text-decoration-none">
											<i class="fa-solid fa-link me-1"></i>Xem chứng chỉ
										</a>
									</p>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>

					<!-- FILE CV ĐÍNH KÈM (nếu ứng viên upload sẵn file, chỉ được tải, không sửa) -->
					<?php if (!empty($cv['DuongDanFileCV'])): ?>
						<hr>
						<div class="card border-success">
							<div class="card-body">
								<h5 class="fw-bold text-success mb-3">
									<i class="fa-solid fa-file-circle-check me-2"></i>File CV đính kèm
								</h5>
								<p class="mb-3">
									<i class="fa-solid fa-file me-2"></i><?= htmlspecialchars($cv['TenFileCV']) ?>
								</p>
								<a
									href="<?= $baseUrl ?>/index.php?route=cv/download&maCV=<?= urlencode($cv['MaCV']) ?>"
									class="btn btn-primary"
									target="_blank"
								>
									<i class="fa-solid fa-download me-2"></i>Tải file CV
								</a>
							</div>
						</div>
					<?php endif; ?>

				</div>
			</div>

			<!-- HÀNH ĐỘNG CẬP NHẬT TRẠNG THÁI -->
			<div class="card border-0 shadow-sm mx-auto mt-4 p-3" style="max-width: 900px;">
				<div class="d-flex flex-wrap gap-2 justify-content-center">

					<?php if ($trangThai === STATUS_MOI_NOP): ?>
						<form method="POST" action="<?= BASE_URL ?>/index.php?route=recruiter/update-status" class="d-inline">
							<input type="hidden" name="maHS" value="<?= htmlspecialchars($hoSoUngTuyen['MaHS']) ?>">
							<input type="hidden" name="trangThai" value="DaXem">
							<button type="submit" class="btn btn-outline-info" onclick="return confirm('Xác nhận đã xem hồ sơ?')">
								<i class="fa-solid fa-eye"></i> Đã xem
							</button>
						</form>
					<?php endif; ?>

					<?php if ($trangThai === STATUS_MOI_NOP || $trangThai === STATUS_DA_XEM): ?>
						<form method="POST" action="<?= BASE_URL ?>/index.php?route=recruiter/update-status" class="d-inline">
							<input type="hidden" name="maHS" value="<?= htmlspecialchars($hoSoUngTuyen['MaHS']) ?>">
							<input type="hidden" name="trangThai" value="HenPhongVan">
							<button type="submit" class="btn btn-outline-success" onclick="return confirm('Xác nhận hẹn phỏng vấn?')">
								<i class="fa-solid fa-calendar-check"></i> Hẹn PV
							</button>
						</form>
					<?php endif; ?>

					<?php if ($trangThai === STATUS_HEN_PHONG_VAN): ?>
						<form method="POST" action="<?= BASE_URL ?>/index.php?route=recruiter/update-status" class="d-inline">
							<input type="hidden" name="maHS" value="<?= htmlspecialchars($hoSoUngTuyen['MaHS']) ?>">
							<input type="hidden" name="trangThai" value="NhanViec">
							<button type="submit" class="btn btn-outline-success" onclick="return confirm('Xác nhận nhận việc?')">
								<i class="fa-solid fa-circle-check"></i> Nhận việc
							</button>
						</form>
					<?php endif; ?>

					<?php if ($trangThai !== STATUS_NHAN_VIEC && $trangThai !== STATUS_TU_CHOI): ?>
						<form method="POST" action="<?= BASE_URL ?>/index.php?route=recruiter/update-status" class="d-inline">
							<input type="hidden" name="maHS" value="<?= htmlspecialchars($hoSoUngTuyen['MaHS']) ?>">
							<input type="hidden" name="trangThai" value="TuChoi">
							<button type="submit" class="btn btn-outline-danger" onclick="return confirm('Xác nhận từ chối?')">
								<i class="fa-solid fa-user-xmark"></i> Từ chối
							</button>
						</form>
					<?php endif; ?>

				</div>
			</div>

		<?php endif; ?>

	</div>
</section>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>
