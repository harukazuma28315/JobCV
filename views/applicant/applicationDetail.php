<?php
/**
 * File: app/views/applicant/applicationDetail.php
 * Đường dẫn: Module5_UngTuyenVaQuanLyHoSo/app/views/applicant/applicationDetail.php
 * Chức năng: Hiển thị chi tiết một hồ sơ ứng tuyển mà ứng viên đã nộp.
 *            Dữ liệu ($hoSoUngTuyen) được truyền từ ApplicationController.
 */
?>
<!DOCTYPE html>
<html lang="vi">
<head>
	<meta charset="UTF-8">
	<title>Chi tiết hồ sơ ứng tuyển</title>
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>
	<div class="container">
		<h1>Chi tiết hồ sơ ứng tuyển</h1>

		<div class="detail-row">
			<span class="label">Vị trí ứng tuyển:</span> 
			<?php echo htmlspecialchars($hoSoUngTuyen['TenTin']); ?>
		</div>
		<div class="detail-row">
			<span class="label">Công ty:</span> 
			<?php echo htmlspecialchars($hoSoUngTuyen['TenCongTy']); ?>
		</div>
		<div class="detail-row">
			<span class="label">Địa chỉ làm việc:</span> 
			<?php echo htmlspecialchars($hoSoUngTuyen['DiaChiLamViec']); ?>
		</div>
		<div class="detail-row">
			<span class="label">CV đã nộp:</span> 
			<?php echo htmlspecialchars($hoSoUngTuyen['CvTieuDe']); ?>
		</div>
		<div class="detail-row">
			<span class="label">Ngày nộp:</span> 
			<?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($hoSoUngTuyen['NgayNop']))); ?>
		</div>
		<div class="detail-row">
			<span class="label">Trạng thái:</span>
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

		<a href="<?php echo BASE_URL; ?>/index.php?action=history" 
		   class="btn" 
		   style="background-color:#6b7280;">
			Quay lại lịch sử
		</a>
	</div>
</body>
</html>