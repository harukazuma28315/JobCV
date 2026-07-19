<?php
/**
 * File: app/views/recruiter/applicationList.php
 * Đường dẫn: Module5_UngTuyenVaQuanLyHoSo/app/views/recruiter/applicationList.php
 * Chức năng: Hiển thị danh sách hồ sơ ứng tuyển vào các tin của Nhà tuyển dụng,
 *            kèm bộ lọc theo tin và theo trạng thái.
 *            Dữ liệu ($danhSachHoSoUngTuyen, $danhSachTinTuyenDung, $thongBao) được truyền từ RecruiterController.
 */
?>
<!DOCTYPE html>
<html lang="vi">
<head>
	<meta charset="UTF-8">
	<title>Danh sách hồ sơ ứng tuyển</title>
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>
	<div class="container">
		<h1>Danh sách hồ sơ ứng tuyển</h1>

		<?php if ($thongBao): ?>
			<div class="flash flash-<?php echo htmlspecialchars($thongBao['type']); ?>">
				<?php echo htmlspecialchars($thongBao['message']); ?>
			</div>
		<?php endif; ?>

		<form class="filter-bar" method="GET" action="<?php echo BASE_URL; ?>/index.php">
			<input type="hidden" name="action" value="recruiterList">

			<select name="maTin">
				<option value="">-- Tất cả tin tuyển dụng --</option>
				<?php foreach ($danhSachTinTuyenDung as $tin): ?>
					<option value="<?php echo htmlspecialchars($tin['MaTinTuyenDung']); ?>"
						<?php echo (isset($_GET['maTin']) && $_GET['maTin'] === $tin['MaTinTuyenDung']) ? 'selected' : ''; ?>>
						<?php echo htmlspecialchars($tin['TieuDe']); ?>
					</option>
				<?php endforeach; ?>
			</select>

			<select name="trangThai">
				<option value="">-- Tất cả trạng thái --</option>
				<?php
				$danhSachTrangThai = array('MoiNop', 'DaXem', 'HenPhongVan', 'NhanViec', 'TuChoi');
				foreach ($danhSachTrangThai as $trangThai):
				?>
					<option value="<?php echo $trangThai; ?>"
						<?php echo (isset($_GET['trangThai']) && $_GET['trangThai'] === $trangThai) ? 'selected' : ''; ?>>
						<?php echo $trangThai; ?>
					</option>
				<?php endforeach; ?>
			</select>

			<button type="submit" class="btn">Lọc</button>
		</form>

		<?php if (empty($danhSachHoSoUngTuyen)): ?>
			<p>Chưa có hồ sơ ứng tuyển nào phù hợp.</p>
		<?php else: ?>
			<table>
				<thead>
					<tr>
						<th>Ngày nộp</th>
						<th>Ứng viên</th>
						<th>Tin tuyển dụng</th>
						<th>CV</th>
						<th>Trạng thái</th>
						<th>Hành động</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($danhSachHoSoUngTuyen as $hoSo): ?>
						<tr>
							<td><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($hoSo['NgayNop']))); ?></td>
							<td><?php echo htmlspecialchars($hoSo['TenUngVien']); ?></td>
							<td><?php echo htmlspecialchars($hoSo['TenTin']); ?></td>
							<td><?php echo htmlspecialchars($hoSo['CvTieuDe']); ?></td>
							<td>
								<span class="badge badge-<?php echo htmlspecialchars($hoSo['TrangThai']); ?>">
									<?php echo htmlspecialchars($hoSo['TrangThai']); ?>
								</span>
							</td>
							<td>
								<a href="<?php echo BASE_URL; ?>/index.php?action=recruiterDetail&maHS=<?php echo urlencode($hoSo['MaHS']); ?>">
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