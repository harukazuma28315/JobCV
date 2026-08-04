<?php

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

$baseUrl = '/JobCV';

require_once __DIR__ . '/../layouts/header.php';
?>

<section class="py-5 bg-light">
	<div class="container">
		<?php if (!$cv): ?>
			<div class="text-center mb-5">
				<h1 class="fw-bold text-dark">
					<i class="fa-solid fa-file-lines text-primary me-2"></i>
					Tạo CV của bạn
				</h1>
				<p class="text-muted">
					Chọn phương thức bạn muốn sử dụng để tạo CV
				</p>
			</div>

			<div class="row justify-content-center g-4">
				<div class="col-md-5">
					<div class="card border-0 shadow-sm h-100 text-center p-4 cv-option" onclick="showManualForm()"
						style="cursor: pointer;">
						<div class="mb-4">
							<i class="fa-solid fa-pen-to-square text-primary" style="font-size: 60px;"></i>
						</div>
						<h4 class="fw-bold">
							Nhập thông tin thủ công
						</h4>
						<p class="text-muted">
							Điền trực tiếp các thông tin cá nhân,
							kỹ năng, mục tiêu nghề nghiệp.
						</p>
						<button type="button" class="btn btn-primary mt-auto">
							<i class="fa-solid fa-pen me-2"></i>
							Tạo CV thủ công
						</button>
					</div>
				</div>
				<div class="col-md-5">
					<div class="card border-0 shadow-sm h-100 text-center p-4 cv-option" onclick="showUploadForm()"
						style="cursor: pointer;">
						<div class="mb-4">
							<i class="fa-solid fa-file-arrow-up text-success" style="font-size: 60px;"></i>
						</div>
						<h4 class="fw-bold">
							Tải CV cá nhân lên
						</h4>
						<p class="text-muted">
							Tải lên CV đã có sẵn của bạn.
							Hỗ trợ định dạng PDF, DOC và DOCX.
						</p>
						<button type="button" class="btn btn-success mt-auto">
							<i class="fa-solid fa-upload me-2"></i>
							Tải CV lên
						</button>
					</div>
				</div>
			</div>
			<div id="manualForm" class="card border-0 shadow-sm mt-5" style="display: none;">
				<div class="card-header bg-primary text-white">
					<h4 class="mb-0 fw-bold">
						<i class="fa-solid fa-pen-to-square me-2"></i>
						Nhập thông tin CV
					</h4>
				</div>
				<div class="card-body p-4">
					<form action="<?= $baseUrl ?>/index.php?route=cv/create-submit" method="POST">
						<div class="row g-4">
							<div class="col-12">
								<label class="form-label fw-bold">
									Tiêu đề CV
									<span class="text-danger">*</span>
								</label>
								<input type="text" name="tieuDe" class="form-control"
									placeholder="Ví dụ: Backend Developer - Nguyễn Văn A" required>
							</div>

							<div class="col-md-6">
								<label class="form-label fw-bold">
									Vị trí mong muốn
								</label>
								<input type="text" name="viTriMongMuon" class="form-control"
									placeholder="Backend Developer">
							</div>

							<div class="col-md-6">
								<label class="form-label fw-bold">
									Email
									<span class="text-danger">*</span>
								</label>
								<input type="email" name="email" class="form-control"
									value="<?= htmlspecialchars($_SESSION['user_email'] ?? '') ?>" required>
							</div>

							<div class="col-md-6">
								<label class="form-label fw-bold">
									Số điện thoại
									<span class="text-danger">*</span>
								</label>
								<input type="text" name="sdt" class="form-control" placeholder="0912345678" required>
							</div>

							<div class="col-12">
								<label class="form-label fw-bold">
									Kỹ năng
								</label>
								<textarea name="kyNang" class="form-control" rows="4"
									placeholder="PHP, MySQL, Laravel, HTML, CSS..."></textarea>
							</div>

							<div class="col-md-6">
								<label class="form-label fw-bold">
									Sở thích
								</label>
								<textarea name="soThich" class="form-control" rows="4"
									placeholder="Đọc sách, du lịch, thể thao..."></textarea>
							</div>

							<div class="col-md-6">
								<label class="form-label fw-bold">
									Mục tiêu nghề nghiệp
								</label>
								<textarea name="mucTieu" class="form-control" rows="4"
									placeholder="Mục tiêu nghề nghiệp của bạn..."></textarea>
							</div>
						</div>

						<hr class="my-4">
						<h5 class="fw-bold text-primary mb-3">
							<i class="fa-solid fa-graduation-cap me-2"></i>
							Học vấn
						</h5>
						<div id="hocVanContainer" data-next-index="0"></div>
						<button type="button" class="btn btn-outline-primary btn-sm mb-4"
							onclick="addHocVanRow('hocVanContainer')">
							<i class="fa-solid fa-plus me-1"></i>
							Thêm học vấn
						</button>
						<hr class="my-4">
						<h5 class="fw-bold text-primary mb-3">
							<i class="fa-solid fa-briefcase me-2"></i>
							Kinh nghiệm làm việc
						</h5>
						<div id="kinhNghiemContainer" data-next-index="0"></div>
						<button type="button" class="btn btn-outline-primary btn-sm mb-4"
							onclick="addKinhNghiemRow('kinhNghiemContainer')">
							<i class="fa-solid fa-plus me-1"></i>
							Thêm kinh nghiệm
						</button>
						<hr class="my-4">
						<h5 class="fw-bold text-primary mb-3">
							<i class="fa-solid fa-diagram-project me-2"></i>
							Dự án
						</h5>
						<div id="duAnContainer" data-next-index="0"></div>
						<button type="button" class="btn btn-outline-primary btn-sm mb-4"
							onclick="addDuAnRow('duAnContainer')">
							<i class="fa-solid fa-plus me-1"></i>
							Thêm dự án
						</button>
						<hr class="my-4">
						<h5 class="fw-bold text-primary mb-3">
							<i class="fa-solid fa-certificate me-2"></i>
							Chứng chỉ
						</h5>
						<div id="chungChiContainer" data-next-index="0"></div>
						<button type="button" class="btn btn-outline-primary btn-sm mb-4"
							onclick="addChungChiRow('chungChiContainer')">
							<i class="fa-solid fa-plus me-1"></i>
							Thêm chứng chỉ
						</button>
						<div class="text-end mt-4">
							<button type="submit" class="btn btn-primary px-5 fw-bold">
								<i class="fa-solid fa-floppy-disk me-2"></i>
								Lưu CV
							</button>
						</div>
					</form>
				</div>
			</div>

			<div id="uploadForm" class="card border-0 shadow-sm mt-5" style="display: none;">
				<div class="card-header bg-success text-white">
					<h4 class="mb-0 fw-bold">
						<i class="fa-solid fa-file-arrow-up me-2"></i>
						Tải CV cá nhân lên
					</h4>
				</div>
				<div class="card-body p-4">
					<form action="<?= $baseUrl ?>/index.php?route=cv/upload-submit" method="POST"
						enctype="multipart/form-data">
						<div class="mb-4">
							<label class="form-label fw-bold">
								Chọn file CV
								<span class="text-danger">*</span>
							</label>
							<input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx" required>
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
							<button type="submit" class="btn btn-success px-5 fw-bold">
								<i class="fa-solid fa-upload me-2"></i>
								Tải CV lên
							</button>
						</div>
					</form>
				</div>
			</div>

		<?php else: ?>

			<div class="text-center mb-5">
				<h1 class="fw-bold text-dark">
					<i class="fa-solid fa-file-circle-check text-success me-2"></i>
					Hồ sơ CV của bạn
				</h1>
				<p class="text-muted">
					Bạn đã có một CV trên hệ thống.
				</p>
			</div>

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
					<hr>
					<h5 class="fw-bold text-primary mb-3">
						<i class="fa-solid fa-graduation-cap me-2"></i>
						Học vấn
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
										&middot; <?= htmlspecialchars($hocVan['NamBatDau'] ?? '?') ?> -
										<?= htmlspecialchars($hocVan['NamKetThuc'] ?? '?') ?>
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
					<hr>
					<h5 class="fw-bold text-primary mb-3">
						<i class="fa-solid fa-briefcase me-2"></i>
						Kinh nghiệm làm việc
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
					<hr>
					<h5 class="fw-bold text-primary mb-3">
						<i class="fa-solid fa-diagram-project me-2"></i>
						Dự án
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
									<p class="small mb-1"><strong>Công nghệ:</strong> <?= htmlspecialchars($duAn['CongNgheSuDung']) ?>
									</p>
								<?php endif; ?>
								<?php if (!empty($duAn['MoTa'])): ?>
									<p class="small mb-0"><?= nl2br(htmlspecialchars($duAn['MoTa'])) ?></p>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
					<hr>
					<h5 class="fw-bold text-primary mb-3">
						<i class="fa-solid fa-certificate me-2"></i>
						Chứng chỉ
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
										<a href="<?= htmlspecialchars($chungChi['DuongLinkChungChi']) ?>" target="_blank"
											class="text-decoration-none">
											<i class="fa-solid fa-link me-1"></i>Xem chứng chỉ
										</a>
									</p>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>

					<hr>
					<div class="card border-success">
						<div class="card-body">
							<?php if (!empty($cv['DuongDanFileCV'])): ?>
								<h5 class="fw-bold text-success mb-3">
									<i class="fa-solid fa-file-circle-check me-2"></i>
									File CV đã tải lên
								</h5>
								<p class="mb-3">
									<i class="fa-solid fa-file me-2"></i>
									<?= htmlspecialchars($cv['TenFileCV']) ?>
								</p>
								<div class="d-flex gap-2 flex-wrap">

									<a href="<?= $baseUrl ?>/index.php?route=cv/download&maCV=<?= urlencode($cv['MaCV']) ?>"
										class="btn btn-primary">
										<i class="fa-solid fa-download me-2"></i>
										Tải CV xuống
									</a>

									<button type="button" class="btn btn-warning"
										onclick="document.getElementById('changeFileForm').style.display='block'">
										<i class="fa-solid fa-rotate me-2"></i>
										Thay đổi file
									</button>
								</div>

								<div id="changeFileForm" class="mt-4" style="display: none;">
									<form action="<?= $baseUrl ?>/index.php?route=cv/change-file" method="POST"
										enctype="multipart/form-data">
										<input type="hidden" name="maCV" value="<?= htmlspecialchars($cv['MaCV']) ?>">
										<label class="form-label fw-bold">
											Chọn file CV mới
										</label>
										<input type="file" name="file" class="form-control mb-3" accept=".pdf,.doc,.docx"
											required>
										<div class="d-flex gap-2">
											<button type="submit" class="btn btn-success">
												<i class="fa-solid fa-upload me-2"></i>
												Upload file mới
											</button>
											<button type="button" class="btn btn-secondary"
												onclick="document.getElementById('changeFileForm').style.display='none'">
												Hủy
											</button>
										</div>
									</form>
								</div>
							<?php else: ?>
								<h5 class="fw-bold text-warning mb-3">
									<i class="fa-solid fa-file-arrow-up me-2"></i>
									Bạn chưa tải file CV lên
								</h5>
								<p class="text-muted">
									Bạn có thể tải lên file CV cá nhân để sử dụng khi ứng tuyển.
								</p>

								<form action="<?= $baseUrl ?>/index.php?route=cv/upload-submit" method="POST"
									enctype="multipart/form-data">
									<input type="hidden" name="maCV" value="<?= htmlspecialchars($cv['MaCV']) ?>">
									<div class="mb-3">
										<label class="form-label fw-bold">
											Chọn file CV
										</label>
										<input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx" required>
									</div>
									<button type="submit" class="btn btn-success">
										<i class="fa-solid fa-upload me-2"></i>
										Tải CV lên
									</button>
								</form>
							<?php endif; ?>
						</div>
					</div>

					<div class="text-end mt-4">
						<button type="button" class="btn btn-primary px-4" onclick="showEditForm()">
							<i class="fa-solid fa-pen-to-square me-2"></i>
							Thay đổi thông tin CV
						</button>
					</div>
				</div>
			</div>
			<div id="editForm" class="card border-0 shadow-sm mt-4" style="display: none;">
				<div class="card-header bg-warning">
					<h4 class="mb-0 fw-bold">
						<i class="fa-solid fa-pen-to-square me-2"></i>
						Chỉnh sửa thông tin CV
					</h4>
				</div>

				<div class="card-body p-4">
					<form action="<?= $baseUrl ?>/index.php?route=cv/update-submit" method="POST">
						<input type="hidden" name="maCV" value="<?= htmlspecialchars($cv['MaCV'] ?? '') ?>">

						<div class="row g-4">

							<div class="col-12">
								<label class="form-label fw-bold">
									Tiêu đề CV
									<span class="text-danger">*</span>
								</label>
								<input type="text" name="tieuDe" class="form-control"
									value="<?= htmlspecialchars($cv['TieuDe'] ?? '') ?>" required>
							</div>

							<div class="col-md-6">
								<label class="form-label fw-bold">
									Vị trí mong muốn
								</label>
								<input type="text" name="viTriMongMuon" class="form-control"
									value="<?= htmlspecialchars($cv['ViTriMongMuon'] ?? '') ?>">
							</div>

							<div class="col-md-6">
								<label class="form-label fw-bold">
									Email
									<span class="text-danger">*</span>
								</label>
								<input type="email" name="email" class="form-control"
									value="<?= htmlspecialchars($cv['Email'] ?? '') ?>" required>
							</div>

							<div class="col-md-6">
								<label class="form-label fw-bold">
									Số điện thoại
									<span class="text-danger">*</span>
								</label>
								<input type="text" name="sdt" class="form-control"
									value="<?= htmlspecialchars($cv['SDT'] ?? '') ?>" required>
							</div>

							<div class="col-12">
								<label class="form-label fw-bold">
									Kỹ năng
								</label>
								<textarea name="kyNang" class="form-control"
									rows="4"><?= htmlspecialchars($cv['KyNang'] ?? '') ?></textarea>
							</div>

							<div class="col-md-6">
								<label class="form-label fw-bold">
									Sở thích
								</label>
								<textarea name="soThich" class="form-control"
									rows="4"><?= htmlspecialchars($cv['SoThich'] ?? '') ?></textarea>
							</div>

							<div class="col-md-6">
								<label class="form-label fw-bold">
									Mục tiêu nghề nghiệp
								</label>
								<textarea name="mucTieu" class="form-control"
									rows="4"><?= htmlspecialchars($cv['MucTieu'] ?? '') ?></textarea>
							</div>
						</div>

						<hr class="my-4">

						<h5 class="fw-bold text-primary mb-3">
							<i class="fa-solid fa-graduation-cap me-2"></i>
							Học vấn
						</h5>

						<div id="hocVanContainerEdit" data-next-index="<?= count($hocVanList) ?>">
							<?php foreach ($hocVanList as $i => $hocVan): ?>
								<div class="card border-0 shadow-sm mb-3 p-3 repeat-block position-relative">
									<button type="button" class="btn-close position-absolute top-0 end-0 m-3" aria-label="Xóa"
										onclick="this.closest('.repeat-block').remove()"></button>
									<input type="hidden" name="hocVan[<?= $i ?>][maHocVan]"
										value="<?= htmlspecialchars($hocVan['MaHocVan']) ?>">
									<div class="row g-3">
										<div class="col-md-6">
											<label class="form-label fw-bold">Tên trường <span
													class="text-danger">*</span></label>
											<input type="text" name="hocVan[<?= $i ?>][tenTruong]" class="form-control"
												value="<?= htmlspecialchars($hocVan['TenTruong']) ?>" required>
										</div>
										<div class="col-md-6">
											<label class="form-label fw-bold">Chuyên ngành</label>
											<input type="text" name="hocVan[<?= $i ?>][chuyenNganh]" class="form-control"
												value="<?= htmlspecialchars($hocVan['ChuyenNganh'] ?? '') ?>">
										</div>
										<div class="col-md-4">
											<label class="form-label fw-bold">Học lực</label>
											<input type="text" name="hocVan[<?= $i ?>][hocLuc]" class="form-control"
												value="<?= htmlspecialchars($hocVan['HocLuc'] ?? '') ?>"
												placeholder="Giỏi/Khá/Trung bình...">
										</div>
										<div class="col-md-2">
											<label class="form-label fw-bold">GPA</label>
											<input type="text" name="hocVan[<?= $i ?>][gpa]" class="form-control"
												value="<?= htmlspecialchars($hocVan['GPA'] ?? '') ?>">
										</div>
										<div class="col-md-3">
											<label class="form-label fw-bold">Năm bắt đầu</label>
											<input type="number" name="hocVan[<?= $i ?>][namBatDau]" class="form-control"
												value="<?= htmlspecialchars($hocVan['NamBatDau'] ?? '') ?>" placeholder="2020">
										</div>
										<div class="col-md-3">
											<label class="form-label fw-bold">Năm kết thúc</label>
											<input type="number" name="hocVan[<?= $i ?>][namKetThuc]" class="form-control"
												value="<?= htmlspecialchars($hocVan['NamKetThuc'] ?? '') ?>" placeholder="2024">
										</div>
									</div>
								</div>
							<?php endforeach; ?>
						</div>

						<button type="button" class="btn btn-outline-primary btn-sm mb-4"
							onclick="addHocVanRow('hocVanContainerEdit')">
							<i class="fa-solid fa-plus me-1"></i> Thêm học vấn
						</button>

						<hr class="my-4">

						<h5 class="fw-bold text-primary mb-3">
							<i class="fa-solid fa-briefcase me-2"></i>
							Kinh nghiệm làm việc
						</h5>

						<div id="kinhNghiemContainerEdit" data-next-index="<?= count($kinhNghiemList) ?>">
							<?php foreach ($kinhNghiemList as $i => $kinhNghiem): ?>
								<div class="card border-0 shadow-sm mb-3 p-3 repeat-block position-relative">
									<button type="button" class="btn-close position-absolute top-0 end-0 m-3" aria-label="Xóa"
										onclick="this.closest('.repeat-block').remove()"></button>
									<input type="hidden" name="kinhNghiem[<?= $i ?>][maCongViec]"
										value="<?= htmlspecialchars($kinhNghiem['MaCongViec']) ?>">
									<div class="row g-3">
										<div class="col-md-6">
											<label class="form-label fw-bold">Tên công ty <span
													class="text-danger">*</span></label>
											<input type="text" name="kinhNghiem[<?= $i ?>][tenCongTy]" class="form-control"
												value="<?= htmlspecialchars($kinhNghiem['TenCongTy']) ?>" required>
										</div>
										<div class="col-md-6">
											<label class="form-label fw-bold">Vị trí</label>
											<input type="text" name="kinhNghiem[<?= $i ?>][viTri]" class="form-control"
												value="<?= htmlspecialchars($kinhNghiem['ViTri'] ?? '') ?>">
										</div>
										<div class="col-md-6">
											<label class="form-label fw-bold">Thời gian làm việc</label>
											<input type="text" name="kinhNghiem[<?= $i ?>][thoiGianLamViec]"
												class="form-control"
												value="<?= htmlspecialchars($kinhNghiem['ThoiGianLamViec'] ?? '') ?>"
												placeholder="01/2022 - 03/2023">
										</div>
										<div class="col-12">
											<label class="form-label fw-bold">Mô tả công việc</label>
											<textarea name="kinhNghiem[<?= $i ?>][moTa]" class="form-control"
												rows="3"><?= htmlspecialchars($kinhNghiem['MoTa'] ?? '') ?></textarea>
										</div>
									</div>
								</div>
							<?php endforeach; ?>
						</div>

						<button type="button" class="btn btn-outline-primary btn-sm mb-4"
							onclick="addKinhNghiemRow('kinhNghiemContainerEdit')">
							<i class="fa-solid fa-plus me-1"></i> Thêm kinh nghiệm
						</button>

						<hr class="my-4">

						<h5 class="fw-bold text-primary mb-3">
							<i class="fa-solid fa-diagram-project me-2"></i>
							Dự án
						</h5>

						<div id="duAnContainerEdit" data-next-index="<?= count($duAnList) ?>">
							<?php foreach ($duAnList as $i => $duAn): ?>
								<div class="card border-0 shadow-sm mb-3 p-3 repeat-block position-relative">
									<button type="button" class="btn-close position-absolute top-0 end-0 m-3" aria-label="Xóa"
										onclick="this.closest('.repeat-block').remove()"></button>
									<input type="hidden" name="duAn[<?= $i ?>][maDuAn]"
										value="<?= htmlspecialchars($duAn['MaDuAn']) ?>">
									<div class="row g-3">
										<div class="col-md-6">
											<label class="form-label fw-bold">Tên dự án <span
													class="text-danger">*</span></label>
											<input type="text" name="duAn[<?= $i ?>][tenDuAn]" class="form-control"
												value="<?= htmlspecialchars($duAn['TenDuAn']) ?>" required>
										</div>
										<div class="col-md-4">
											<label class="form-label fw-bold">Vị trí / Vai trò</label>
											<input type="text" name="duAn[<?= $i ?>][viTri]" class="form-control"
												value="<?= htmlspecialchars($duAn['ViTri'] ?? '') ?>">
										</div>
										<div class="col-md-2">
											<label class="form-label fw-bold">Số thành viên</label>
											<input type="number" name="duAn[<?= $i ?>][soLuongThanhVien]" class="form-control"
												value="<?= htmlspecialchars($duAn['SoLuongThanhVien'] ?? '') ?>" min="1">
										</div>
										<div class="col-12">
											<label class="form-label fw-bold">Công nghệ sử dụng</label>
											<input type="text" name="duAn[<?= $i ?>][congNgheSuDung]" class="form-control"
												value="<?= htmlspecialchars($duAn['CongNgheSuDung'] ?? '') ?>"
												placeholder="React, Node.js, MongoDB...">
										</div>
										<div class="col-12">
											<label class="form-label fw-bold">Mô tả dự án</label>
											<textarea name="duAn[<?= $i ?>][moTa]" class="form-control"
												rows="3"><?= htmlspecialchars($duAn['MoTa'] ?? '') ?></textarea>
										</div>
									</div>
								</div>
							<?php endforeach; ?>
						</div>

						<button type="button" class="btn btn-outline-primary btn-sm mb-4"
							onclick="addDuAnRow('duAnContainerEdit')">
							<i class="fa-solid fa-plus me-1"></i> Thêm dự án
						</button>

						<hr class="my-4">

						<h5 class="fw-bold text-primary mb-3">
							<i class="fa-solid fa-certificate me-2"></i>
							Chứng chỉ
						</h5>

						<div id="chungChiContainerEdit" data-next-index="<?= count($chungChiList) ?>">
							<?php foreach ($chungChiList as $i => $chungChi): ?>
								<div class="card border-0 shadow-sm mb-3 p-3 repeat-block position-relative">
									<button type="button" class="btn-close position-absolute top-0 end-0 m-3" aria-label="Xóa"
										onclick="this.closest('.repeat-block').remove()"></button>
									<input type="hidden" name="chungChi[<?= $i ?>][maChungChi]"
										value="<?= htmlspecialchars($chungChi['MaChungChi']) ?>">
									<div class="row g-3">
										<div class="col-md-6">
											<label class="form-label fw-bold">Tên chứng chỉ <span
													class="text-danger">*</span></label>
											<input type="text" name="chungChi[<?= $i ?>][tenChungChi]" class="form-control"
												value="<?= htmlspecialchars($chungChi['TenChungChi']) ?>" required>
										</div>
										<div class="col-md-6">
											<label class="form-label fw-bold">Tổ chức cấp</label>
											<input type="text" name="chungChi[<?= $i ?>][toChucCap]" class="form-control"
												value="<?= htmlspecialchars($chungChi['ToChucCap'] ?? '') ?>">
										</div>
										<div class="col-md-3">
											<label class="form-label fw-bold">Ngày cấp</label>
											<input type="date" name="chungChi[<?= $i ?>][ngayCap]" class="form-control"
												value="<?= htmlspecialchars($chungChi['NgayCap'] ?? '') ?>">
										</div>
										<div class="col-md-3">
											<label class="form-label fw-bold">Ngày hết hạn</label>
											<input type="date" name="chungChi[<?= $i ?>][ngayHetHan]" class="form-control"
												value="<?= htmlspecialchars($chungChi['NgayHetHan'] ?? '') ?>">
										</div>
										<div class="col-md-6">
											<label class="form-label fw-bold">Mã số chứng chỉ</label>
											<input type="text" name="chungChi[<?= $i ?>][maSoChungChi]" class="form-control"
												value="<?= htmlspecialchars($chungChi['MaSoChungChi'] ?? '') ?>">
										</div>
										<div class="col-12">
											<label class="form-label fw-bold">Link chứng chỉ</label>
											<input type="url" name="chungChi[<?= $i ?>][duongLinkChungChi]" class="form-control"
												value="<?= htmlspecialchars($chungChi['DuongLinkChungChi'] ?? '') ?>"
												placeholder="https://...">
										</div>
									</div>
								</div>
							<?php endforeach; ?>
						</div>

						<button type="button" class="btn btn-outline-primary btn-sm mb-4"
							onclick="addChungChiRow('chungChiContainerEdit')">
							<i class="fa-solid fa-plus me-1"></i> Thêm chứng chỉ
						</button>


						<div class="text-end mt-4">
							<button type="button" class="btn btn-secondary me-2" onclick="hideEditForm()">
								Hủy
							</button>

							<button type="submit" class="btn btn-success px-4">
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
	function showEditForm() {

		const form = document.getElementById('editForm');

		form.style.display = 'block';

		form.scrollIntoView({
			behavior: 'smooth'
		});

	}

	function hideEditForm() {

		document.getElementById('editForm').style.display = 'none';

	}

	/**
	 * Tạo và thêm 1 khối "học vấn" trống vào container chỉ định.
	 * Dùng data-next-index trên container để đảm bảo mỗi khối thêm mới có
	 * chỉ số riêng, không trùng với các khối đã render sẵn từ server (edit form).
	 */
	function addHocVanRow(containerId) {
		const container = document.getElementById(containerId);
		const idx = parseInt(container.dataset.nextIndex, 10);
		container.dataset.nextIndex = idx + 1;

		const div = document.createElement('div');
		div.className = 'card border-0 shadow-sm mb-3 p-3 repeat-block position-relative';
		div.innerHTML = `
		<button type="button" class="btn-close position-absolute top-0 end-0 m-3" aria-label="Xóa" onclick="this.closest('.repeat-block').remove()"></button>
		<input type="hidden" name="hocVan[${idx}][maHocVan]" value="">
		<div class="row g-3">
			<div class="col-md-6">
				<label class="form-label fw-bold">Tên trường <span class="text-danger">*</span></label>
				<input type="text" name="hocVan[${idx}][tenTruong]" class="form-control" required>
			</div>
			<div class="col-md-6">
				<label class="form-label fw-bold">Chuyên ngành</label>
				<input type="text" name="hocVan[${idx}][chuyenNganh]" class="form-control">
			</div>
			<div class="col-md-4">
				<label class="form-label fw-bold">Học lực</label>
				<input type="text" name="hocVan[${idx}][hocLuc]" class="form-control" placeholder="Giỏi/Khá/Trung bình...">
			</div>
			<div class="col-md-2">
				<label class="form-label fw-bold">GPA</label>
				<input type="text" name="hocVan[${idx}][gpa]" class="form-control">
			</div>
			<div class="col-md-3">
				<label class="form-label fw-bold">Năm bắt đầu</label>
				<input type="number" name="hocVan[${idx}][namBatDau]" class="form-control" placeholder="2020">
			</div>
			<div class="col-md-3">
				<label class="form-label fw-bold">Năm kết thúc</label>
				<input type="number" name="hocVan[${idx}][namKetThuc]" class="form-control" placeholder="2024">
			</div>
		</div>
	`;
		container.appendChild(div);
	}

	/**
	 * Tạo và thêm 1 khối "kinh nghiệm làm việc" trống (cùng cơ chế addHocVanRow).
	 */
	function addKinhNghiemRow(containerId) {
		const container = document.getElementById(containerId);
		const idx = parseInt(container.dataset.nextIndex, 10);
		container.dataset.nextIndex = idx + 1;

		const div = document.createElement('div');
		div.className = 'card border-0 shadow-sm mb-3 p-3 repeat-block position-relative';
		div.innerHTML = `
		<button type="button" class="btn-close position-absolute top-0 end-0 m-3" aria-label="Xóa" onclick="this.closest('.repeat-block').remove()"></button>
		<input type="hidden" name="kinhNghiem[${idx}][maCongViec]" value="">
		<div class="row g-3">
			<div class="col-md-6">
				<label class="form-label fw-bold">Tên công ty <span class="text-danger">*</span></label>
				<input type="text" name="kinhNghiem[${idx}][tenCongTy]" class="form-control" required>
			</div>
			<div class="col-md-6">
				<label class="form-label fw-bold">Vị trí</label>
				<input type="text" name="kinhNghiem[${idx}][viTri]" class="form-control">
			</div>
			<div class="col-md-6">
				<label class="form-label fw-bold">Thời gian làm việc</label>
				<input type="text" name="kinhNghiem[${idx}][thoiGianLamViec]" class="form-control" placeholder="01/2022 - 03/2023">
			</div>
			<div class="col-12">
				<label class="form-label fw-bold">Mô tả công việc</label>
				<textarea name="kinhNghiem[${idx}][moTa]" class="form-control" rows="3"></textarea>
			</div>
		</div>
	`;
		container.appendChild(div);
	}

	/**
	 * Tạo và thêm 1 khối "dự án" trống (cùng cơ chế addHocVanRow).
	 */
	function addDuAnRow(containerId) {
		const container = document.getElementById(containerId);
		const idx = parseInt(container.dataset.nextIndex, 10);
		container.dataset.nextIndex = idx + 1;

		const div = document.createElement('div');
		div.className = 'card border-0 shadow-sm mb-3 p-3 repeat-block position-relative';
		div.innerHTML = `
		<button type="button" class="btn-close position-absolute top-0 end-0 m-3" aria-label="Xóa" onclick="this.closest('.repeat-block').remove()"></button>
		<input type="hidden" name="duAn[${idx}][maDuAn]" value="">
		<div class="row g-3">
			<div class="col-md-6">
				<label class="form-label fw-bold">Tên dự án <span class="text-danger">*</span></label>
				<input type="text" name="duAn[${idx}][tenDuAn]" class="form-control" required>
			</div>
			<div class="col-md-4">
				<label class="form-label fw-bold">Vị trí / Vai trò</label>
				<input type="text" name="duAn[${idx}][viTri]" class="form-control">
			</div>
			<div class="col-md-2">
				<label class="form-label fw-bold">Số thành viên</label>
				<input type="number" name="duAn[${idx}][soLuongThanhVien]" class="form-control" min="1">
			</div>
			<div class="col-12">
				<label class="form-label fw-bold">Công nghệ sử dụng</label>
				<input type="text" name="duAn[${idx}][congNgheSuDung]" class="form-control" placeholder="React, Node.js, MongoDB...">
			</div>
			<div class="col-12">
				<label class="form-label fw-bold">Mô tả dự án</label>
				<textarea name="duAn[${idx}][moTa]" class="form-control" rows="3"></textarea>
			</div>
		</div>
	`;
		container.appendChild(div);
	}

	/**
	 * Tạo và thêm 1 khối "chứng chỉ" trống (cùng cơ chế addHocVanRow).
	 */
	function addChungChiRow(containerId) {
		const container = document.getElementById(containerId);
		const idx = parseInt(container.dataset.nextIndex, 10);
		container.dataset.nextIndex = idx + 1;

		const div = document.createElement('div');
		div.className = 'card border-0 shadow-sm mb-3 p-3 repeat-block position-relative';
		div.innerHTML = `
		<button type="button" class="btn-close position-absolute top-0 end-0 m-3" aria-label="Xóa" onclick="this.closest('.repeat-block').remove()"></button>
		<input type="hidden" name="chungChi[${idx}][maChungChi]" value="">
		<div class="row g-3">
			<div class="col-md-6">
				<label class="form-label fw-bold">Tên chứng chỉ <span class="text-danger">*</span></label>
				<input type="text" name="chungChi[${idx}][tenChungChi]" class="form-control" required>
			</div>
			<div class="col-md-6">
				<label class="form-label fw-bold">Tổ chức cấp</label>
				<input type="text" name="chungChi[${idx}][toChucCap]" class="form-control">
			</div>
			<div class="col-md-3">
				<label class="form-label fw-bold">Ngày cấp</label>
				<input type="date" name="chungChi[${idx}][ngayCap]" class="form-control">
			</div>
			<div class="col-md-3">
				<label class="form-label fw-bold">Ngày hết hạn</label>
				<input type="date" name="chungChi[${idx}][ngayHetHan]" class="form-control">
			</div>
			<div class="col-md-6">
				<label class="form-label fw-bold">Mã số chứng chỉ</label>
				<input type="text" name="chungChi[${idx}][maSoChungChi]" class="form-control">
			</div>
			<div class="col-12">
				<label class="form-label fw-bold">Link chứng chỉ</label>
				<input type="url" name="chungChi[${idx}][duongLinkChungChi]" class="form-control" placeholder="https://...">
			</div>
		</div>
	`;
		container.appendChild(div);
	}
</script>

<?php
require_once __DIR__ . '/../layouts/footer.php';
?>