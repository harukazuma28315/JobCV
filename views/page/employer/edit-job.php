<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container py-5">
	<div class="row">
		<div class="col-12">
			<div class="card border-0 shadow-sm p-4 bg-white rounded-3">
				<div class="border-bottom pb-3 mb-4 d-flex justify-content-between align-items-center">
					<div>
						<h5 class="fw-bold text-dark mb-1"><i class="fa-solid fa-file-pen text-primary-blue me-2"></i>Chỉnh sửa tin tuyển dụng</h5>
						<p class="text-muted small mb-0">Cập nhật thông tin cho tin: <strong><?= htmlspecialchars($job['TieuDe']) ?></strong></p>
					</div>
					<a href="/JobCV/index.php?route=jobs/manage" class="btn btn-light">
						<i class="fa-solid fa-arrow-left me-2"></i>Quay lại
					</a>
				</div>
				<form action="/JobCV/index.php?route=jobs/update" method="POST">
					<input type="hidden" name="maTinTuyenDung" value="<?= htmlspecialchars($job['MaTinTuyenDung']) ?>">
					<?php
						$selectedCategoryId = $selectedCategoryId ?? null;
						$selectedLocationId = $selectedLocationId ?? null;
						$hinhThuc = $job['HinhThucLamViec'] ?? '';
						$capBac = $job['CapBac'] ?? '';
					?>
					<div class="row g-3 mb-4">
						<div class="col-12">
							<label class="form-label fw-semibold text-dark">Tiêu đề tin tuyển dụng <span class="text-danger">*</span></label>
							<input type="text" name="tieuDe" class="form-control py-2"
								value="<?= htmlspecialchars($job['TieuDe'] ?? '') ?>" required>
						</div>
						<div class="col-12 col-md-6">
							<label class="form-label fw-semibold text-dark">Vị trí tuyển dụng <span class="text-danger">*</span></label>
							<input type="text"
									name="viTriTuyenDung"
									class="form-control py-2"
									value="<?= htmlspecialchars($job['ViTriTuyenDung'] ?? '') ?>"
									required>
						</div>
						<div class="col-12 col-md-6">
							<label class="form-label fw-semibold text-dark">Lĩnh vực / Ngành nghề <span class="text-danger">*</span></label>
							<select name="category" class="form-select py-2" required>
								<option value="">-- Chọn ngành nghề --</option>
								<?php if (!empty($categories)): ?>
									<?php foreach ($categories as $category): ?>
										<option value="<?= htmlspecialchars($category['MaDanhMuc']) ?>"
											<?= ($selectedCategoryId !== null && $selectedCategoryId == $category['MaDanhMuc']) ? 'selected' : '' ?>>
											<?= htmlspecialchars($category['TenDanhMuc']) ?>
										</option>
									<?php endforeach; ?>
								<?php endif; ?>
							</select>
						</div>
						<div class="col-12 col-md-6">
							<label class="form-label fw-semibold text-dark">Cấp bậc <span class="text-danger">*</span></label>
							<select name="capBac" class="form-select py-2" required>
								<option value="" <?= $capBac === '' ? 'selected disabled' : '' ?>>-- Chọn cấp bậc --</option>
								<option value="Intern" <?= $capBac === 'Intern' ? 'selected' : '' ?>>Thực tập sinh</option>
								<option value="Fresher" <?= $capBac === 'Fresher' ? 'selected' : '' ?>>Fresher</option>
								<option value="Junior" <?= $capBac === 'Junior' ? 'selected' : '' ?>>Junior</option>
								<option value="Middle" <?= $capBac === 'Middle' ? 'selected' : '' ?>>Middle</option>
								<option value="Senior" <?= $capBac === 'Senior' ? 'selected' : '' ?>>Senior</option>
								<option value="Manager" <?= $capBac === 'Manager' ? 'selected' : '' ?>>Quản lý</option>
							</select>
						</div>
						<div class="col-12 col-md-6">
							<label class="form-label fw-semibold text-dark">Hình thức làm việc <span class="text-danger">*</span></label>
							<select name="hinhThucLamViec" class="form-select py-2" required>
								<option value="" <?= $hinhThuc === '' ? 'selected disabled' : '' ?>>-- Chọn hình thức --</option>
								<option value="Full-time" <?= $hinhThuc === 'Full-time' ? 'selected' : '' ?>>Toàn thời gian (Full-time)</option>
								<option value="Part-time" <?= $hinhThuc === 'Part-time' ? 'selected' : '' ?>>Bán thời gian (Part-time)</option>
								<option value="Internship" <?= $hinhThuc === 'Internship' ? 'selected' : '' ?>>Thực tập sinh (Internship)</option>
							</select>
						</div>
						<div class="col-12 col-md-6">
							<label class="form-label fw-semibold text-dark">Số năm kinh nghiệm <span class="text-danger">*</span></label>
							<input type="number"
									name="soNamKinhNghiem"
									class="form-control py-2"
									value="<?= htmlspecialchars($job['SoNamKinhNghiem'] ?? '0') ?>"
									min="0"
									required>
						</div>
						<div class="col-12 col-md-6">
							<label class="form-label fw-semibold text-dark">Độ tuổi yêu cầu <span class="text-danger">*</span></label>
							<input type="text"
									name="doTuoiYeuCau"
									class="form-control py-2"
									value="<?= htmlspecialchars($job['DoTuoiYeuCau'] ?? '') ?>"
									required>
						</div>
						<div class="col-12 col-md-6">
							<label class="form-label fw-semibold text-dark">Số lượng tuyển <span class="text-danger">*</span></label>
							<input type="number"
									name="soLuongTuyen"
									class="form-control py-2"
									value="<?= htmlspecialchars($job['SoLuongTuyen'] ?? '1') ?>"
									min="1"
									required>
						</div>
						<div class="col-12 col-md-6">
							<label class="form-label fw-semibold text-dark">Thời gian thử việc (tháng) <span class="text-danger">*</span></label>
							<input type="number"
									name="thoiGianThuViec"
									class="form-control py-2"
									value="<?= htmlspecialchars($job['ThoiGianThuViec'] ?? '0') ?>"
									min="0"
									required>
						</div>
						<div class="col-12 col-md-6">
							<label class="form-label fw-semibold text-dark">Mức lương <span class="text-danger">*</span></label>
							<input type="number"
									name="mucLuong"
									class="form-control py-2"
									value="<?= htmlspecialchars($job['MucLuong'] ?? '') ?>"
									min="0"
									required>
						</div>
						<div class="col-12 col-md-6">
							<label class="form-label fw-semibold text-dark">Hạn chót nhận hồ sơ <span class="text-danger">*</span></label>
							<input type="date"
									name="ngayHetHan"
									class="form-control py-2"
									value="<?= !empty($job['NgayHetHan']) ? date('Y-m-d', strtotime($job['NgayHetHan'])) : '' ?>"
									required>
						</div>
						<div class="col-12 col-md-6">
							<label class="form-label fw-semibold text-dark">Tỉnh / Thành phố <span class="text-danger">*</span></label>
							<select name="location" class="form-select py-2" required>
								<option value="">-- Chọn tỉnh / thành phố --</option>
								<?php if (!empty($locations)): ?>
									<?php foreach ($locations as $location): ?>
										<option value="<?= htmlspecialchars($location['MaDanhMuc']) ?>"
											<?= ($selectedLocationId !== null && $selectedLocationId == $location['MaDanhMuc']) ? 'selected' : '' ?>>
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
									value="<?= htmlspecialchars($job['DiaChiLamViec'] ?? '') ?>"
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
									required><?= htmlspecialchars($job['MoTaCongViec'] ?? '') ?></textarea>
						</div>
						<div class="col-12">
							<label class="form-label fw-semibold text-dark">Yêu cầu ứng viên (Tiêu chí sơ tuyển) <span class="text-danger">*</span></label>
							<textarea name="yeuCauCongViec"
									class="form-control"
									rows="4"
									required><?= htmlspecialchars($job['YeuCauCongViec'] ?? '') ?></textarea>
						</div>
					</div>
					<div class="text-end pt-4 border-top mt-4">
						<a href="/JobCV/index.php?route=jobs/manage" class="btn btn-light px-4 py-2 me-2">Hủy</a>
						<button type="submit" class="btn btn-primary-blue fw-bold px-4 py-2">Lưu thay đổi</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>