<?php
/**
 * File: app/views/recruiter/applicationDetail.php
 * Đường dẫn: Module5_UngTuyenVaQuanLyHoSo/app/views/recruiter/applicationDetail.php
 * Chức năng: Hiển thị chi tiết hồ sơ ứng tuyển và form cập nhật trạng thái
 *            (trạng thái mới sẽ tự động gửi email cho ứng viên).
 *            Dữ liệu ($hoSoUngTuyen, $thongBao) được truyền từ RecruiterController.
 */
?>
<!DOCTYPE html>
<html lang="vi">
<head>
	<meta charset="UTF-8">
	<title>Chi tiết hồ sơ ứng tuyển - Nhà tuyển dụng</title>
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>
	<div class="container">
		<h1>Chi tiết hồ sơ ứng tuyển</h1>

		<?php if ($thongBao): ?>
			<div class="flash flash-<?php echo htmlspecialchars($thongBao['type']); ?>">
				<?php echo htmlspecialchars($thongBao['message']); ?>
			</div>
		<?php endif; ?>

		<div class="detail-row">
			<span class="label">Ứng viên:</span> 
			<?php echo htmlspecialchars($hoSoUngTuyen['TenUngVien']); ?>
		</div>
		<div class="detail-row">
			<span class="label">Email liên hệ:</span> 
			<?php echo htmlspecialchars($hoSoUngTuyen['EmailUngVien']); ?>
		</div>
		<div class="detail-row">
			<span class="label">SĐT:</span> 
			<?php echo htmlspecialchars($hoSoUngTuyen['SdtUngVien']); ?>
		</div>
		<div class="detail-row">
			<span class="label">Vị trí ứng tuyển:</span> 
			<?php echo htmlspecialchars($hoSoUngTuyen['TenTin']); ?>
		</div>
		<div class="detail-row">
			<span class="label">CV:</span> 
			<?php echo htmlspecialchars($hoSoUngTuyen['CvTieuDe']); ?>
			(<?php echo htmlspecialchars($hoSoUngTuyen['ViTriMongMuon']); ?>)
		</div>
		<div class="detail-row">
			<span class="label">Kỹ năng:</span> 
			<?php echo nl2br(htmlspecialchars($hoSoUngTuyen['KyNang'])); ?>
		</div>
		<div class="detail-row">
			<span class="label">Mục tiêu nghề nghiệp:</span> 
			<?php echo nl2br(htmlspecialchars($hoSoUngTuyen['MucTieu'])); ?>
		</div>
		<div class="detail-row">
			<span class="label">Ngày nộp:</span> 
			<?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($hoSoUngTuyen['NgayNop']))); ?>
		</div>
		<div class="detail-row">
			<span class="label">Trạng thái hiện tại:</span>
			<span class="badge badge-<?php echo htmlspecialchars($hoSoUngTuyen['TrangThai']); ?>">
				<?php echo htmlspecialchars($hoSoUngTuyen['TrangThai']); ?>
			</span>
		</div>

		<?php if (!empty($hoSoUngTuyen['CoverLetter'])): ?>
			<div class="detail-row">
				<span class="label">Cover Letter:</span>
				<p><?php echo nl2br(htmlspecialchars($hoSoUngTuyen['CoverLetter'])); ?></p>
			</div>
		<?php endif; ?>

		<?php if (!empty($hoSoUngTuyen['CoverLetterFile'])): ?>
			<div class="detail-row">
				<span class="label">File Cover Letter:</span>
				<a href="<?php echo UPLOAD_COVER_LETTER_URL . htmlspecialchars($hoSoUngTuyen['CoverLetterFile']); ?>" 
				   target="_blank">Xem file đính kèm</a>
			</div>
		<?php endif; ?>

		<hr>

		<h2>Cập nhật trạng thái</h2>
		<form action="<?php echo BASE_URL; ?>/index.php?action=updateStatus" 
			  method="POST"
			  onsubmit="return confirmUpdateStatus(event);">
			<input type="hidden" name="maHS" value="<?php echo htmlspecialchars($hoSoUngTuyen['MaHS']); ?>">

			<div class="form-group">
				<label for="trangThai">Trạng thái mới</label>
				<select name="trangThai" id="trangThai" required>
					<option value="MoiNop" <?php echo $hoSoUngTuyen['TrangThai'] === 'MoiNop' ? 'selected' : ''; ?>>Mới nộp</option>
					<option value="DaXem" <?php echo $hoSoUngTuyen['TrangThai'] === 'DaXem' ? 'selected' : ''; ?>>Đã xem</option>
					<option value="HenPhongVan" <?php echo $hoSoUngTuyen['TrangThai'] === 'HenPhongVan' ? 'selected' : ''; ?>>Hẹn phỏng vấn</option>
					<option value="NhanViec" <?php echo $hoSoUngTuyen['TrangThai'] === 'NhanViec' ? 'selected' : ''; ?>>Nhận việc</option>
					<option value="TuChoi" <?php echo $hoSoUngTuyen['TrangThai'] === 'TuChoi' ? 'selected' : ''; ?>>Từ chối</option>
				</select>
			</div>

			<button type="submit" class="btn">Cập nhật trạng thái</button>
			<a href="<?php echo BASE_URL; ?>/index.php?action=recruiterList" 
			   class="btn" 
			   style="background-color:#6b7280;">
				Quay lại danh sách
			</a>
		</form>
	</div>

	<script src="<?php echo BASE_URL; ?>/assets/js/validate.js"></script>
</body>
</html>