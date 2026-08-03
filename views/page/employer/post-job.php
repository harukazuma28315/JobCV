<?php 
$jobsList = [];
while ($row = $jobs->fetch_assoc()) {
	$jobsList[] = $row;
}
$total = count($jobsList);
$activeTab = $activeTab ?? 'manage';


include_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="container py-5">
	<div class="row">
		<div class="col-12">
			
			<!-- Điều hướng Tab giữa Quản lý và Đăng tin -->
			<ul class="nav nav-pills mb-4 gap-2 bg-white p-2 rounded-3 shadow-sm border" id="pills-tab" role="tablist">
				<li class="nav-item" role="presentation">
					<button class="nav-link <?= $activeTab === 'manage' ? 'active' : '' ?> fw-bold px-4 py-2.5" id="pills-manage-tab" data-bs-toggle="pill" data-bs-target="#pills-manage" type="button" role="tab">
						<i class="fa-solid fa-list-check me-2"></i>Quản lý tin tuyển dụng
					</button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="nav-link <?= $activeTab === 'post' ? 'active' : '' ?> fw-bold px-4 py-2.5 text-primary-blue" id="pills-post-tab" data-bs-toggle="pill" data-bs-target="#pills-post" type="button" role="tab">
						<i class="fa-solid fa-file-signature me-2"></i>Đăng tin mới
					</button>
				</li>
			</ul>

			<div class="tab-content" id="pills-tabContent">
				
				<!-- TAB 1: DANH SÁCH QUẢN LÝ TIN ĐÃ ĐĂNG -->
				<div class="tab-pane fade <?= $activeTab === 'manage' ? 'show active' : '' ?>" id="pills-manage" role="tabpanel">
					<div class="card border-0 shadow-sm p-4 bg-white rounded-3">
						<div class="d-flex justify-content-between align-items-center mb-4">
							<h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-layer-group me-2 text-primary-blue"></i>Danh sách tin tuyển dụng</h5>
							<span class="badge bg-light text-dark border">Tổng số tin: <?= $total ?></span>
						</div>

						<div class="table-responsive">
							<table class="table align-middle table-hover">
								<thead class="table-light text-secondary">
									<tr>
										<th scope="col" style="min-width: 250px;">Tin tuyển dụng</th>
										<th scope="col">Ngày đăng</th>
										<th scope="col">Hạn nộp</th>
										<th scope="col">Lượt ứng tuyển</th>
										<th scope="col">Trạng thái</th>
										<th scope="col" class="text-center">Thao tác</th>
									</tr>
								</thead>
								<tbody>
									<?php if ($total === 0): ?>
										<tr>
											<td colspan="6" class="text-center py-5 text-muted">
												Bạn chưa đăng tin tuyển dụng nào.
											</td>
										</tr>
									<?php else: ?>
										<?php foreach ($jobsList as $job): ?>
											<?php
												// ==================================================
												// Gộp trạng thái duyệt + trạng thái mở/đóng + hết hạn
												// thành 1 badge duy nhất, ưu tiên hiển thị lý do
												// quan trọng nhất (giống layout post-job1, nhưng
												// vẫn giữ đủ thông tin nghiệp vụ thật).
												// ==================================================
												$duyet = $job['TrangThaiDuyet'] ?? '';
												$moDong = $job['TrangThai'] ?? '';
												$daHetHan = !empty($job['NgayHetHan'])
													&& strtotime($job['NgayHetHan']) < strtotime('today');

												if ($duyet === 'ChoDuyet') {
													$statusBadge = ['bg-warning text-dark', 'fa-solid fa-hourglass-half', 'Chờ duyệt'];
												} elseif ($duyet === 'TuChoi') {
													$statusBadge = ['bg-danger', 'fa-solid fa-circle-xmark', 'Bị từ chối'];
												} elseif ($duyet === 'dago') {
													$statusBadge = ['bg-dark', 'fa-solid fa-eye-slash', 'Đã gỡ'];
												} elseif ($daHetHan) {
													$statusBadge = ['bg-danger', 'fa-solid fa-clock-rotate-left', 'Đã hết hạn'];
												} elseif ($moDong !== 'DangMo') {
													$statusBadge = ['bg-secondary', 'fa-solid fa-ban', 'Đã đóng'];
												} else {
													$statusBadge = ['bg-success', 'fa-solid fa-circle-check', 'Đang hoạt động'];
												}

												$soUngVien = (int) ($job['SoUngVien'] ?? 0);
											?>
											<tr>
												<td>
													<h6 class="fw-bold text-dark mb-1"><?= htmlspecialchars($job['TieuDe']) ?></h6>
													<span class="text-muted small">
														<?php if (!empty($job['MucLuong'])): ?>
															<i class="fa-solid fa-money-bill-wave me-1 text-success"></i><?= htmlspecialchars($job['MucLuong']) ?>
														<?php endif; ?>
														<?php if (!empty($job['DiaChiLamViec'])): ?>
															<?= !empty($job['MucLuong']) ? ' | ' : '' ?><i class="fa-solid fa-location-dot me-1"></i><?= htmlspecialchars($job['DiaChiLamViec']) ?>
														<?php endif; ?>
													</span>
												</td>
												<td class="small"><?= date('d/m/Y', strtotime($job['NgayDang'])) ?></td>
												<td class="small <?= $daHetHan ? 'text-danger fw-semibold' : 'text-muted' ?>">
													<?= date('d/m/Y', strtotime($job['NgayHetHan'])) ?>
												</td>
												<td>
													<a href="/JobCV/index.php?route=recruiter/list&maTin=<?= urlencode($job['MaTinTuyenDung']) ?>"
														class="badge <?= $soUngVien > 0 ? 'bg-primary' : 'bg-secondary' ?> text-white text-decoration-none px-2 py-1"
														title="Xem danh sách ứng viên">
														<i class="fa-solid fa-users me-1"></i> <?= $soUngVien ?> Ứng tuyển
													</a>
												</td>
												<td>
													<span class="badge <?= $statusBadge[0] ?>">
														<i class="<?= $statusBadge[1] ?> me-1"></i><?= $statusBadge[2] ?>
													</span>
												</td>
												<td>
													<div class="d-flex gap-1 justify-content-center">
														<a href="/JobCV/index.php?route=jobs/edit&maTinTuyenDung=<?= urlencode($job['MaTinTuyenDung']) ?>"
															class="btn btn-light btn-sm text-primary" title="Chỉnh sửa nội dung">
															<i class="fa-solid fa-pen-to-square"></i>
														</a>

														<!-- Gia hạn: mở modal chọn hạn nộp mới, submit qua route=jobs/extend -->
														<button type="button"
																class="btn btn-light btn-sm text-warning"
																title="Gia hạn tin"
																data-bs-toggle="modal"
																data-bs-target="#extendModal-<?= htmlspecialchars($job['MaTinTuyenDung']) ?>">
															<i class="fa-regular fa-clock"></i>
														</button>

														<!-- Đóng / Mở lại tin: submit qua route=jobs/toggle -->
														<form action="/JobCV/index.php?route=jobs/toggle" method="POST" class="d-inline"
																onsubmit="return confirm('<?= $moDong === 'DangMo' ? 'Xác nhận tạm dừng/đóng tin tuyển dụng này?' : 'Xác nhận mở lại tin tuyển dụng này?' ?>');">
															<input type="hidden" name="maTinTuyenDung" value="<?= htmlspecialchars($job['MaTinTuyenDung']) ?>">
															<button type="submit"
																	class="btn btn-light btn-sm <?= $moDong === 'DangMo' ? 'text-danger' : 'text-success' ?>"
																	title="<?= $moDong === 'DangMo' ? 'Tạm dừng/Đóng tuyển dụng' : 'Mở lại tin tuyển dụng' ?>">
																<i class="fa-solid <?= $moDong === 'DangMo' ? 'fa-ban' : 'fa-rotate-left' ?>"></i>
															</button>
														</form>

														<!-- Xóa tin -->
														<form action="/JobCV/index.php?route=jobs/delete" method="POST" class="d-inline"
																onsubmit="return confirm('Xác nhận xóa vĩnh viễn tin tuyển dụng này? Hành động này không thể hoàn tác.');">
															<input type="hidden" name="maTinTuyenDung" value="<?= htmlspecialchars($job['MaTinTuyenDung']) ?>">
															<button type="submit" class="btn btn-light btn-sm text-secondary" title="Xóa tin">
																<i class="fa-solid fa-trash"></i>
															</button>
														</form>
													</div>
												</td>
											</tr>
										<?php endforeach; ?>
									<?php endif; ?>
								</tbody>
							</table>
						</div>

						<!-- Modal Gia hạn tin: đặt ngoài <table> vì <div> không được phép
							 là con trực tiếp của <tbody> (browser sẽ tự đẩy ra ngoài,
							 gây vỡ layout như hình chụp lỗi trước đó) -->
						<?php foreach ($jobsList as $job): ?>
							<div class="modal fade" id="extendModal-<?= htmlspecialchars($job['MaTinTuyenDung']) ?>" tabindex="-1" aria-hidden="true">
								<div class="modal-dialog modal-dialog-centered">
									<form action="/JobCV/index.php?route=jobs/extend" method="POST" class="modal-content">
										<div class="modal-header">
											<h6 class="modal-title fw-bold">Gia hạn tin tuyển dụng</h6>
											<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
										</div>
										<div class="modal-body">
											<p class="text-muted small mb-2"><?= htmlspecialchars($job['TieuDe']) ?></p>
											<label class="form-label fw-semibold">Hạn nộp mới <span class="text-danger">*</span></label>
											<input type="hidden" name="maTinTuyenDung" value="<?= htmlspecialchars($job['MaTinTuyenDung']) ?>">
											<input type="date" name="ngayHetHan" class="form-control"
													min="<?= date('Y-m-d') ?>" required>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
											<button type="submit" class="btn btn-primary-blue">Xác nhận gia hạn</button>
										</div>
									</form>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>

				<!-- TAB 2: FORM ĐĂNG TIN TUYỂN DỤNG MỚI -->
				<div class="tab-pane fade <?= $activeTab === 'post' ? 'show active' : '' ?>" id="pills-post" role="tabpanel">
					<div class="card border-0 shadow-sm p-4 bg-white rounded-3">
						<div class="border-bottom pb-3 mb-4">
							<h5 class="fw-bold text-dark mb-1"><i class="fa-solid fa-file-pen text-primary-blue me-2"></i>Đăng tin tuyển dụng mới</h5>
							<p class="text-muted small mb-0">Vui lòng điền đầy đủ các tiêu chí lọc hồ sơ để hệ thống phân loại ứng viên tốt nhất.</p>
						</div>

						<form action="/JobCV/index.php?route=jobs/create" method="POST">
							<div class="row g-3 mb-4">
								<div class="col-12">
									<label class="form-label fw-semibold text-dark">Tiêu đề tin tuyển dụng <span class="text-danger">*</span></label>
									<input type="text" name="tieuDe" class="form-control py-2" placeholder="Ví dụ: Thực Tập Sinh Web Developer (PHP/Laravel)" required>
								</div>
								<div class="col-12 col-md-6">
									<label class="form-label fw-semibold text-dark">Vị trí tuyển dụng <span class="text-danger">*</span></label>
									<input type="text"
											name="viTriTuyenDung"
											class="form-control py-2"
											placeholder="Ví dụ: Web Developer"
											required>
								</div>
								<div class="col-12 col-md-6">
									<label class="form-label fw-semibold text-dark">Lĩnh vực / Ngành nghề <span class="text-danger">*</span></label>
									<select name="category" class="form-select py-2" required>
										<option value="">-- Chọn ngành nghề --</option>
										<?php if (!empty($categories)): ?>
											<?php foreach ($categories as $category): ?>
												<option value="<?= htmlspecialchars($category['MaDanhMuc']) ?>">
													<?= htmlspecialchars($category['TenDanhMuc']) ?>
												</option>
											<?php endforeach; ?>
										<?php endif; ?>
									</select>
								</div>
								<div class="col-12 col-md-6">
									<label class="form-label fw-semibold text-dark">Cấp bậc <span class="text-danger">*</span></label>
									<select name="capBac" class="form-select py-2" required>
										<option value="" selected disabled>-- Chọn cấp bậc --</option>
										<option value="Intern">Thực tập sinh</option>
										<option value="Fresher">Fresher</option>
										<option value="Junior">Junior</option>
										<option value="Middle">Middle</option>
										<option value="Senior">Senior</option>
										<option value="Manager">Quản lý</option>
									</select>
								</div>
								<div class="col-12 col-md-6">
									<label class="form-label fw-semibold text-dark">Hình thức làm việc <span class="text-danger">*</span></label>
									<select name="hinhThucLamViec" class="form-select py-2" required>
										<option value="" selected disabled>-- Chọn hình thức --</option>
										<option value="Full-time">Toàn thời gian (Full-time)</option>
										<option value="Part-time">Bán thời gian (Part-time)</option>
										<option value="Internship">Thực tập sinh (Internship)</option>
									</select>
								</div>
								<div class="col-12 col-md-6">
									<label class="form-label fw-semibold text-dark">Số năm kinh nghiệm <span class="text-danger">*</span></label>
									<input type="number"
											name="soNamKinhNghiem"
											class="form-control py-2"
											placeholder="Ví dụ: 1"
											min="0"
											value="0"
											required>
								</div>
								<div class="col-12 col-md-6">
									<label class="form-label fw-semibold text-dark">Độ tuổi yêu cầu</label>
									<input type="text"
											name="doTuoiYeuCau"
											class="form-control py-2"
											placeholder="Ví dụ: 22-30">
								</div>
								<div class="col-12 col-md-6">
									<label class="form-label fw-semibold text-dark">Số lượng tuyển <span class="text-danger">*</span></label>
									<input type="number"
											name="soLuongTuyen"
											class="form-control py-2"
											placeholder="Ví dụ: 2"
											min="1"
											value="1"
											required>
								</div>
								<div class="col-12 col-md-6">
									<label class="form-label fw-semibold text-dark">Thời gian thử việc (tháng)</label>
									<input type="number"
											name="thoiGianThuViec"
											class="form-control py-2"
											placeholder="Ví dụ: 2"
											min="0"
											value="0">
								</div>
								<div class="col-12 col-md-6">
									<label class="form-label fw-semibold text-dark">Mức lương mong muốn <span class="text-danger">*</span></label>
									<input type="number"
											name="mucLuong"
											class="form-control py-2"
											placeholder="Ví dụ: 15000000"
											min="0"
											required>
								</div>
								<div class="col-12 col-md-6">
									<label class="form-label fw-semibold text-dark">Hạn chót nhận hồ sơ <span class="text-danger">*</span></label>
										<input type="date"
												name="ngayHetHan"
												class="form-control py-2"
												required>
								</div>
								<div class="col-12 col-md-6">
									<label class="form-label fw-semibold text-dark">Tỉnh / Thành phố <span class="text-danger">*</span></label>
									<select name="location" class="form-select py-2" required>
										<option value="">-- Chọn tỉnh / thành phố --</option>
										<?php if (!empty($locations)): ?>
											<?php foreach ($locations as $location): ?>
												<option value="<?= htmlspecialchars($location['MaDanhMuc']) ?>">
													<?= htmlspecialchars($location['TenDanhMuc']) ?>
												</option>
											<?php endforeach; ?>
										<?php endif; ?>
									</select>
								</div>
								<div class="col-12 col-md-6">
									<label class="form-label fw-semibold text-dark">Địa chỉ chi tiết <span class="text-danger">*</span></label>
									<input type="text"
											name="diaChiLamViec"
											class="form-control py-2"
											placeholder="Ví dụ: Quận Ninh Kiều"
											required>
								</div>
							</div>

							<h6 class="fw-bold text-primary-blue mb-3 text-uppercase border-top pt-4">Nội dung chi tiết & Tiêu chí lọc hồ sơ</h6>
							<div class="row g-3">
								<div class="col-12">
									<label class="form-label fw-semibold text-dark">Mô tả công việc (JD) <span class="text-danger">*</span></label>
									<textarea name="moTaCongViec"
											class="form-control"
											rows="4"
											required></textarea>
								</div>
								<div class="col-12">
									<label class="form-label fw-semibold text-dark">Yêu cầu ứng viên (Tiêu chí sơ tuyển) <span class="text-danger">*</span></label>
									<textarea name="yeuCauCongViec"
											class="form-control"
											rows="4"
											required></textarea>
								</div>
							</div>

							<div class="text-end pt-4 border-top mt-4">
								<button type="reset" class="btn btn-light px-4 py-2 me-2">Nhập lại</button>
								<button type="submit" class="btn btn-primary-blue fw-bold px-4 py-2">Đăng tuyển dụng ngay</button>
							</div>
						</form>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>