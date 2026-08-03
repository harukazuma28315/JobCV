<!-- Nhúng Header chung -->
<?php 
$baseUrl = $baseUrl ?? '';
include_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="container py-5">
	<div class="row g-4">
		<!-- Cột trái: Tóm tắt thông tin nhanh -->
		<div class="col-12 col-lg-3">
			<div class="card border-0 shadow-sm p-4 text-center bg-white">
				<img src="https://api.dicebear.com/7.x/adventurer/svg?seed=ntl" alt="Avatar" class="rounded-circle border border-3 border-primary mx-auto mb-3" style="width: 90px; height: 90px; object-fit: cover;">
				<h5 class="fw-bold text-dark mb-1"><?= htmlspecialchars($userName) ?></h5> <!-- -->
				<p class="text-muted small mb-3">Ứng viên</p>
				<hr>
				<div class="text-start">
					<p class="small text-muted mb-2">
						<i class="fa-solid fa-briefcase me-2 text-primary-blue"></i>

						Đã nộp:
						<strong><?= $totalApplications ?> hồ sơ</strong>
					</p>

					<p class="small text-muted mb-0">
						<i class="fa-solid fa-circle-check me-2 text-success"></i>

						Được gọi:
						<strong><?= $interviewApplications ?> cuộc hẹn</strong>
					</p>
				</div>
				<a href="<?= $baseUrl ?>/index.php?route=profile" class="btn btn-outline-primary border-primary text-primary-blue btn-sm w-100 mt-3 fw-semibold">
					Quay lại hồ sơ
				</a>
			</div>
		</div>

		<!-- Cột phải: Danh sách tin tuyển dụng đã nộp -->
		<div class="col-12 col-lg-9">
			<div class="card border-0 shadow-sm p-4 bg-white">
				<div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
					<h4 class="fw-bold text-dark mb-0"><i class="fa-solid fa-paper-plane text-success me-2"></i>Lịch Sử Ứng Tuyển</h4>
					<strong><?= $totalApplications ?> hồ sơ</strong>
				</div>

				<div class="table-responsive">
					<table class="table table-hover align-middle">
						<thead class="table-light text-secondary">
							<tr>
								<th scope="col" style="min-width: 160px;">Công việc / Công ty</th>
								<th scope="col">Ngày nộp</th>
								<th scope="col">CV sử dụng</th>
								<th scope="col">Trạng thái</th>
								<th scope="col" class="text-center">Thao tác</th>
							</tr>
						</thead>
						<tbody>
						<?php foreach ($applications as $application): ?>
							<tr>
								<td>
									<h6 class="fw-bold">
										<?= htmlspecialchars($application['TieuDe']) ?>
									</h6>

									<span class="text-muted small">
										<?= htmlspecialchars($application['TenCongTy']) ?>
									</span>
								</td>

								<td class="text-muted small">
									<?= date(
										'd/m/Y',
										strtotime($application['NgayNop'])
									) ?>
								</td>

								<td>
									<a href="<?= $baseUrl ?>/index.php?route=cv/download&maCV=<?= urlencode($application['MaCV']) ?>"
										class="text-decoration-none text-danger small fw-semibold">

										<i class="fa-solid fa-file-arrow-down me-1"></i>

										<?= htmlspecialchars(
											$application['TenFileCV'] ?: 'Chưa có file CV'
										) ?>

									</a>
								</td>

								<td>
									<?php if ($application['TrangThai'] === 'MoiNop'): ?>

										<span class="badge bg-warning text-dark">
											<i class="fa-regular fa-clock me-1"></i>
											Mới nộp
										</span>

									<?php elseif ($application['TrangThai'] === 'DaXem'): ?>

										<span class="badge bg-info text-dark">
											<i class="fa-solid fa-eye me-1"></i>
											Đã xem
										</span>

									<?php elseif ($application['TrangThai'] === 'HenPhongVan'): ?>

										<span class="badge bg-primary">
											<i class="fa-solid fa-calendar-check me-1"></i>
											Hẹn phỏng vấn
										</span>

									<?php elseif ($application['TrangThai'] === 'NhanViec'): ?>

										<span class="badge bg-success">
											<i class="fa-solid fa-circle-check me-1"></i>
											Đã nhận việc
										</span>

									<?php elseif ($application['TrangThai'] === 'TuChoi'): ?>

										<span class="badge bg-danger">
											<i class="fa-solid fa-circle-xmark me-1"></i>
											Chưa phù hợp
										</span>

									<?php endif; ?>
								</td>

								<td class="text-center">
									<a href="<?= $baseUrl ?>/index.php?route=jobs/detail&maTinTuyenDung=<?= urlencode($application['MaTinTuyenDung']) ?>"
										class="btn btn-light btn-sm text-primary-blue">

										<i class="fa-solid fa-eye"></i>

									</a>
								</td>
							</tr>

						<?php endforeach; ?>
					</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Nhúng Footer chung -->
<?php include_once __DIR__ . '/../layouts/footer.php'; ?>