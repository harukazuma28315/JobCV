<?php
session_start();

// Bảo vệ trang chủ: Nếu chưa đăng nhập thì bắt quay về trang đăng nhập ngay[cite: 3]
if (!isset($_SESSION['user_id'])) {
	header("Location: dangnhap.html");
	exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Trang Chủ - Điều Hướng Hệ Thống</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<style>
		body { background-color: #f8f9fa; padding-top: 80px; }
		.menu-card { background: #fff; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); padding: 40px; }
	</style>
</head>
<body>

<div class="container text-center">
	<div class="row justify-content-center">
		<div class="col-md-8 menu-card">
			<h1 class="mb-3 text-primary fw-bold">HỆ THỐNG QUẢN LÝ</h1>
			<p class="text-muted mb-5">Xin chào, <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong>! Vui lòng chọn chức năng bạn muốn làm việc.</p>
			
			<div class="d-flex justify-content-center gap-3">
				<!-- CHỨC NĂNG ĐIỀU HƯỚNG: Bấm vào sẽ chuyển hẳn hướng sang trang profile -->
				<a href="profile.php" class="btn btn-primary btn-lg fw-bold px-4 py-3 shadow-sm">
					👤 Quản lý tài khoản
				</a>
				
				<!-- TODO: Sau này bạn làm thêm tính năng Quản lý CV, Đăng tin... chỉ cần thêm nút ở đây -->
				<!-- <a href="quanlycv.php" class="btn btn-outline-secondary btn-lg fw-bold px-4 py-3">💼 Quản lý CV</a> -->
			</div>
		</div>
	</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>