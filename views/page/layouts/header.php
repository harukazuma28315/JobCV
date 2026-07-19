<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$baseUrl = isset($baseUrl) ? $baseUrl : '/JobCV';
$isLoggedIn = !empty($_SESSION['user_id']);
$userName = trim($_SESSION['user_name'] ?? '');
$avatarText = !empty($userName) ? strtoupper(substr($userName, 0, 1)) : 'U';
$userRole = isset($_SESSION['user_role']) ? (int)$_SESSION['user_role'] : 0;
$profilePage = ($userRole === 1)
    ? $baseUrl . '/index.php?route=employer/detail'
    : $baseUrl . '/index.php?route=profile';
?>

<!DOCTYPE html>

<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JobHub Vietnam - Nền Tảng Tuyển Dụng Hàng Đầu</title>
    <link href="<?= $baseUrl ?>/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= $baseUrl ?>/assets/css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #1e5ba6; 
            --secondary-blue: #0f3d73;
            --light-bg: #f4f7f6;
        }
        .bg-primary-blue { background-color: var(--primary-blue) !important; }
        .text-primary-blue { color: var(--primary-blue) !important; }
        .btn-primary-blue {
            background-color: var(--primary-blue);
            color: #fff;
            border: none;
        }
        .btn-primary-blue:hover {
            background-color: var(--secondary-blue);
            color: #fff;
        }
        .nav-link {
            font-weight: 500;
            color: #333 !important;
        }
        .nav-link:hover {
            color: var(--primary-blue) !important;
        }
    </style>
</head>
<body class="bg-light">

<!-- Thanh Header -->

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top py-2">
    <div class="container">
        <!-- Logo -->
<a class="navbar-brand d-flex align-items-center position-relative" href="<?= $baseUrl ?>/index.php?route=home" style="width: 150px; height: 40px;">
    <img src="<?= $baseUrl ?>/assets/images/logo1.png" alt="JobHub Logo" 
         style="height: 50px; width: auto; object-fit: contain; position: absolute; top: 50%; transform: translateY(-50%); max-width: none;">
</a>


    <!-- Toggle di động -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
    </button>
    
    <!-- Liên kết chức năng -->
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mx-auto">
            <li class="nav-item">
                <a class="nav-link" href="<?= $baseUrl ?>/index.php?route=jobs/list">Tìm việc làm</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= $baseUrl ?>/index.php?route=employer/detail">Danh sách công ty</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= $baseUrl ?>/index.php?route=cv/list">Dịch vụ CV</a>
            </li>
        </ul>
        
        <div class="d-flex gap-2 align-items-center">
            <?php if ($isLoggedIn): ?>
                <a href="<?= $profilePage ?>" class="btn btn-outline-primary border-primary text-primary-blue px-3 d-flex align-items-center gap-2 rounded-pill">
                    <span class="rounded-circle bg-primary-blue text-white d-inline-flex align-items-center justify-content-center" style="width: 34px; height: 34px; font-weight: 700;">
                        <?= htmlspecialchars($avatarText) ?>
                    </span>
                    <span class="fw-semibold">Tài khoản</span>
                </a>
            <?php else: ?>
                <a href="<?= $baseUrl ?>/index.php?route=auth/login" class="btn btn-outline-primary border-primary text-primary-blue px-4">Đăng Nhập</a>
                <a href="<?= $baseUrl ?>/index.php?route=auth/register" class="btn btn-primary-blue px-4">Đăng Ký</a>
            <?php endif; ?>
        </div>
    </div>
</div>

</nav>
