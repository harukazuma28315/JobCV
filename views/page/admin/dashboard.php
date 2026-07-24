<?php
// views/admin/dashboard.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
// Mock data để render biểu đồ & bảng
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang tổng quan hệ thống - Admin JobHub</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>
    <style>
        :root { --sidebar-width: 260px; --primary-blue: #1e5ba6; }
        body { background-color: #f4f7f6; font-family: 'Segoe UI', system-ui, sans-serif; }
        .sidebar { width: var(--sidebar-width); height: 100vh; position: fixed; top: 0; left: 0; background-color: #fff; border-right: 1px solid #e5e5e5; z-index: 100; }
        .main-content { margin-left: var(--sidebar-width); min-height: 100vh; padding: 20px; }
        .nav-link-admin { display: flex; align-items: center; padding: 12px 20px; color: #555; text-decoration: none; font-weight: 500; border-radius: 8px; margin: 4px 15px; transition: all 0.2s; }
        .nav-link-admin:hover, .nav-link-admin.active { background-color: #f0f4f9; color: var(--primary-blue); }
        .stat-card { border: none; border-radius: 12px; transition: transform 0.2s; }
        .stat-card:hover { transform: translateY(-3px); }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar d-flex flex-column justify-content-between py-3">
    <div>
        <div class="px-4 py-3 border-bottom mb-3">
            <h4 class="text-primary-blue fw-bold mb-0">JobHub Admin</h4>
        </div>
        <div class="nav flex-column">
           <a href="<?= BASE_URL ?>/index.php?route=admin/dashboard" class="nav-link-admin active">
                <i class="fa-solid fa-house me-3"></i>Trang chủ
            </a>
            <a href="<?= BASE_URL ?>/index.php?route=admin/users" class="nav-link-admin ">
                <i class="fa-solid fa-users me-3"></i>Quản lý người dùng
            </a>
            <a href="<?= BASE_URL ?>/index.php?route=admin/jobs" class="nav-link-admin">
                <i class="fa-solid fa-file-signature me-3"></i>Quản lý bài đăng
            </a>
            <a href="<?= BASE_URL ?>/index.php?route=admin/categories" class="nav-link-admin">
                <i class="fa-solid fa-folder-tree me-3"></i>Quản lý danh mục
            </a>
        </div>
    </div>
    <div class="px-4">
        <a href="<?= BASE_URL ?>/index.php?route=home" class="btn btn-outline-danger w-100"><i class="fa-solid fa-arrow-right-from-bracket me-2"></i>Thoát giao diện Admin</a>
    </div>
</div>

<!-- MAIN CONTENT -->
<div class="main-content">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">Trang tổng quan hệ thống</h3>
        <div class="d-flex align-items-center gap-3">
            <span class="text-muted"><i class="fa-solid fa-calendar me-2"></i>Hôm nay, <?= date('d/m/Y') ?></span>
            <img src="https://api.dicebear.com/7.x/bottts/svg?seed=admin" class="rounded-circle border" style="width: 40px; height: 40px;">
        </div>
    </div>

    <!-- 3 Cột Thống Kê (Stats Grid) -->
    <div class="row g-3 mb-4">
        <!-- Tổng số người dùng -->
        <div class="col-12 col-sm-4 col-lg-4">
            <div class="card stat-card shadow-sm p-3 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-semibold">Tổng số người dùng</span>
                        <h3 class="fw-bold mt-1 mb-0"><?= number_format($thongKe['tongNguoiDung'] ?? 0) ?></h3>
                    </div>
                    <span class="badge bg-success-subtle text-success p-2 fs-6 rounded-circle"><i class="fa-solid fa-users"></i></span>
                </div>
            </div>
        </div>

        <!-- Tổng số bài đã đăng -->
        <div class="col-12 col-sm-4 col-lg-4">
            <div class="card stat-card shadow-sm p-3 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-semibold">Tổng số bài đã đăng</span>
                        <h3 class="fw-bold mt-1 mb-0 text-primary"><?= number_format($thongKe['tongBaiDang'] ?? 0) ?></h3>
                    </div>
                    <span class="badge bg-success-subtle text-success p-2 fs-6 rounded-circle"><i class="fa-solid fa-building"></i></span>
                </div>
            </div>
        </div>

        <!-- Tổng số doanh nghiệp -->
        <div class="col-12 col-sm-4 col-lg-4">
            <div class="card stat-card shadow-sm p-3 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-semibold">Tổng số doanh nghiệp</span>
                        <h3 class="fw-bold mt-1 mb-0 text-primary"><?= number_format($thongKe['tongDoanhNghiep'] ?? 0) ?></h3>
                    </div>
                    <span class="badge bg-success-subtle text-success p-2 fs-6 rounded-circle"><i class="fa-solid fa-building"></i></span>
                </div>
            </div>
        </div>
    <!-- KHU VỰC BIỂU ĐỒ SONG SONG ĐỘNG -->
    <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
            <div>
                <h5 class="fw-bold mb-1">Báo cáo số liệu thống kê chi tiết</h5>
                <span class="text-muted small">Chọn loại báo cáo và khoảng thời gian bên dưới để cập nhật số liệu</span>
            </div>
            <!-- Bộ đôi menu lọc song song -->
            <div class="d-flex gap-2">
                <!-- 1. Menu loại biểu đồ (Được đẩy sang trái) -->
                <select class="form-select border-primary text-primary-blue fw-semibold" 
                        id="chartSelector" 
                        onchange="updateCharts()" 
                        style="width: auto; min-width: 250px;">
                    <option value="users" <?= (($_GET['chartType'] ?? 'users') === 'users') ? 'selected' : '' ?>>Thống kê tài khoản người dùng</option>
                    <option value="jobs" <?= (($_GET['chartType'] ?? 'users') === 'jobs') ? 'selected' : '' ?>>Thống kê bài viết</option>
                    <option value="efficiency" <?= (($_GET['chartType'] ?? 'users') === 'efficiency') ? 'selected' : '' ?>>Thống kê hiệu quả tuyển dụng</option>
                </select>

                <!-- 2. Menu chọn khoảng thời gian (Nằm bên phải ngoài cùng) -->
               <select class="form-select border-primary text-primary-blue fw-semibold" 
                        id="timeSelector" 
                        onchange="updateCharts()" 
                        style="width: auto; min-width: 130px;">
                    <option value="7days" <?= (($_GET['period'] ?? '30days') === '7days') ? 'selected' : '' ?>>7 ngày qua</option>
                    <option value="30days" <?= (($_GET['period'] ?? '30days') === '30days') ? 'selected' : '' ?>>1 tháng qua</option>
                    <option value="1year" <?= (($_GET['period'] ?? '30days') === '1year') ? 'selected' : '' ?>>1 năm qua</option>
                </select>
            </div>
        </div>

        <div class="row g-4">
            <!-- BIỂU ĐỒ TRÒN (BÊN TRÁI) -->
            <div class="col-12 col-lg-5">
                <div class="border rounded-3 p-3 bg-light d-flex flex-column align-items-center justify-content-center" style="min-height: 350px;">
                    <h6 class="fw-bold text-center mb-3 text-muted" id="pieChartTitle">Tỷ lệ trạng thái tài khoản (%)</h6>
                    <div style="width: 100%; max-width: 260px; margin: 0 auto;">
                        <canvas id="pieChartCanvas"></canvas>
                    </div>
                </div>
            </div>

            <!-- BIỂU ĐỒ CỘT (BÊN PHẢI) -->
            <div class="col-12 col-lg-7">
                <div class="border rounded-3 p-3 bg-light d-flex flex-column align-items-center justify-content-center" style="min-height: 350px;">
                    <h6 class="fw-bold text-center mb-3 text-muted" id="barChartTitle">Số lượng tài khoản chi tiết</h6>
                    <div style="width: 100%; height: 260px;">
                        <canvas id="barChartCanvas"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPT ĐIỀU KHIỂN BIỂU ĐỒ DỰA TRÊN LỰA CHỌN -->
    <script>
        Chart.register(ChartDataLabels);

        let pieChart, barChart;
        let currentData = <?= json_encode($duLieuBieuDo ?? ['labels' => [], 'data' => [], 'pieTitle' => 'Không có dữ liệu', 'barTitle' => 'Không có dữ liệu']) ?>;

        window.addEventListener('DOMContentLoaded', () => {
            initCharts(currentData);
        });

        function initCharts(data) {
            // Biểu đồ tròn
            const ctxPie = document.getElementById('pieChartCanvas').getContext('2d');
            if (pieChart) pieChart.destroy();
            
            pieChart = new Chart(ctxPie, {
                type: 'pie',
                data: {
                    labels: data.labels || [],
                    datasets: [{
                        data: data.data || [],
                        backgroundColor: ['#1e5ba6', '#dc3545', '#198754', '#ffc107']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom' },
                        datalabels: {
                            color: '#fff',
                            font: { weight: 'bold' },
                            formatter: (value, ctx) => {
                                let sum = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                return sum ? ((value * 100 / sum).toFixed(1)) + "%" : "0%";
                            }
                        }
                    }
                }
            });

            // Biểu đồ cột
            const ctxBar = document.getElementById('barChartCanvas').getContext('2d');
            if (barChart) barChart.destroy();

            barChart = new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: data.labels || [],
                    datasets: [{
                        label: 'Số lượng',
                        data: data.data || [],
                        backgroundColor: ['#1e5ba6', '#dc3545', '#198754', '#ffc107'],
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });
        }

        function updateCharts() {
            const type = document.getElementById('chartSelector').value;
            const period = document.getElementById('timeSelector').value;
            window.location.href = `?route=admin/dashboard&chartType=${type}&period=${period}`;
        }

        // Gắn sự kiện
        document.getElementById('chartSelector').addEventListener('change', updateCharts);
        document.getElementById('timeSelector').addEventListener('change', updateCharts);
    </script>
</body>
</html>