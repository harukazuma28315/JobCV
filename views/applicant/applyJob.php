<?php
/**
 * File: app/views/applicant/applyJob.php
 * Đường dẫn: Module5_UngTuyenVaQuanLyHoSo/app/views/applicant/applyJob.php
 * Chức năng: Giao diện nộp hồ sơ ứng tuyển cho ứng viên.
 *            Dữ liệu ($tinTuyenDung, $danhSachCv, $thongBao) được truyền từ ApplicationController.
 */
?>
<!DOCTYPE html>
<html lang="vi">
<head>
	<meta charset="UTF-8">
	<title>Nộp hồ sơ ứng tuyển</title>
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>
	<div class="container">
		<h1>Nộp hồ sơ ứng tuyển</h1>
		<h2><?php echo htmlspecialchars($tinTuyenDung['TieuDe']); ?></h2>

		<?php if ($thongBao): ?>
			<div class="flash flash-<?php echo htmlspecialchars($thongBao['type']); ?>">
				<?php echo htmlspecialchars($thongBao['message']); ?>
			</div>
		<?php endif; ?>

		<?php if (empty($danhSachCv)): ?>
			<p>Bạn chưa có CV nào đang hoạt động. Vui lòng tạo CV trước khi ứng tuyển.</p>
		<?php else: ?>
			<form action="<?php echo BASE_URL; ?>/index.php?action=submitApplication"
				method="POST"
				enctype="multipart/form-data">

				<input type="hidden" name="maTinTuyenDung" value="<?php echo htmlspecialchars($tinTuyenDung['MaTinTuyenDung']); ?>">

				<div class="form-group">
					<label for="maCV">Chọn CV</label>
					<select name="maCV" id="maCV" required>
						<option value="">-- Chọn CV --</option>
						<?php foreach ($danhSachCv as $cv): ?>
							<option value="<?php echo htmlspecialchars($cv['MaCV']); ?>">
								<?php echo htmlspecialchars($cv['TieuDe']); ?>
								<?php if (!empty($cv['ViTriMongMuon'])): ?>
									(<?php echo htmlspecialchars($cv['ViTriMongMuon']); ?>)
								<?php endif; ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>

				<div class="form-group">
					<label for="coverLetter">Cover Letter (văn bản, tối đa <?php echo MAX_COVER_LETTER_TEXT_LENGTH; ?> ký tự)</label>
					<textarea
						name="coverLetter"
						id="coverLetter"
						maxlength="<?php echo MAX_COVER_LETTER_TEXT_LENGTH; ?>">
					</textarea>
					<div id="coverLetterCounter" style="font-size:12px;color:#6b7280;margin-top:4px;">0 / <?php echo MAX_COVER_LETTER_TEXT_LENGTH; ?> ký tự</div>
				</div>

				<div class="form-group">
					<label for="coverLetterFile">File Cover Letter (PDF/DOC/DOCX, tối đa 5MB - không bắt buộc)</label>
					<input type="file" name="coverLetterFile" id="coverLetterFile" accept=".pdf,.doc,.docx">
				</div>

				<button type="submit" class="btn">Nộp hồ sơ</button>
				<a href="<?php echo BASE_URL; ?>/index.php?action=history" 
				   class="btn" 
				   style="background-color:#6b7280;">
					Hủy
				</a>
			</form>
		<?php endif; ?>
	</div>

<!-- validate.js tạm thời bỏ để test backend -->
</body>
</html>