<?php
/**
 * File: app/views/applicant/applicationHistory.php
 * Đường dẫn: Module5_UngTuyenVaQuanLyHoSo/app/views/applicant/applicationHistory.php
 * Chức năng: Hiển thị danh sách lịch sử ứng tuyển của ứng viên đang đăng nhập.
 *            Dữ liệu ($danhSachHoSoUngTuyen, $thongBao) được truyền từ ApplicationController.
 */
?>
<!DOCTYPE html>
<html lang="vi">
<head>
	<meta charset="UTF-8">
	<title>Lịch sử ứng tuyển</title>
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>
	<div class="container">
		<h1>Lịch sử ứng tuyển</h1>

		<?php if ($thongBao): ?>
			<div class="flash flash-<?php echo htmlspecialchars($thongBao['type']); ?>">
				<?php echo htmlspecialchars($thongBao['message']); ?>
			</div>
		<?php endif; ?>

		<?php if (empty($danhSachHoSoUngTuyen)): ?>
			<p>Bạn chưa nộp hồ sơ ứng tuyển nào.</p>
		<?php else: ?>
			<table>
				<thead>
					<tr>
						<th>Ngày nộp</th>
						<th>Vị trí</th>
						<th>Công ty</th>
						<th>CV đã nộp</th>
						<th>Trạng thái</th>
						<th>Hành động</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($danhSachHoSoUngTuyen as $hoSo): ?>
						<tr>
							<td><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($hoSo['NgayNop']))); ?></td>
							<td><?php echo htmlspecialchars($hoSo['TenTin']); ?></td>
							<td><?php echo htmlspecialchars($hoSo['TenCongTy']); ?></td>
							<td><?php echo htmlspecialchars($hoSo['CvTieuDe']); ?></td>
							<td>
								<span class="badge badge-<?php echo htmlspecialchars($hoSo['TrangThai']); ?>">
									<?php echo htmlspecialchars($hoSo['TrangThai']); ?>
								</span>
							</td>
							<td>
								<a href="<?php echo BASE_URL; ?>/index.php?action=detail&maHS=<?php echo urlencode($hoSo['MaHS']); ?>">
									Xem chi tiết
								</a>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>
	</div>
</body>
</html>