<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JobHub Vietnam - Nền Tảng Tuyển Dụng Hàng Đầu</title>
    <link href="<?= $baseUrl ?>/public/css/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= $baseUrl ?>/public/css/css/style.css" rel="stylesheet">
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
<a class="navbar-brand d-flex align-items-center position-relative" href="../home/index.php" style="width: 150px; height: 40px;">
    <img src="<?= $baseUrl ?>/public/images/logo1.png" alt="JobHub Logo" 
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
                    <a class="nav-link" href="../jobs/search.php">Tìm việc làm</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../employer/post-job.php">Danh sách công ty</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../candidate/cv-builder.php">Dịch vụ CV</a>
                </li>
            </ul>
            
            <div class="d-flex gap-2">
                <a href="../auth/login.php" class="btn btn-outline-primary border-primary text-primary-blue px-4">Đăng Nhập</a>
                <a href="../auth/register.php" class="btn btn-primary-blue px-4">Đăng Ký</a>
            </div>
        </div>
    </div>
</nav>