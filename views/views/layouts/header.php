<?php
// Khởi tạo session nếu chưa được bật
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Định nghĩa base URL động dựa vào đường dẫn hiện tại của ứng dụng
if (!defined('BASE_URL')) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $rootPath = dirname(dirname(dirname($_SERVER['SCRIPT_NAME'])));
    $rootPath = rtrim(str_replace('\\', '/', $rootPath), '/');
    if ($rootPath === '') {
        $rootPath = '/';
    }
    define('BASE_URL', $protocol . '://' . $host . $rootPath . '/');
}

// ==================== BỘ CHUYỂN ĐỔI TÀI KHOẢN ẢO NHANH QUA URL ====================
function getMockUserByRole($role) {
    $users = [
        'candidate' => [
            'id' => 101,
            'name' => 'Nguyễn Thị Liễu',
            'email' => 'ntl@gmail.com',
            'role' => 'candidate',
            'avatar' => 'https://api.dicebear.com/7.x/adventurer/svg?seed=ntl'
        ],
        'employer' => [
            'id' => 202,
            'name' => 'VNG HR Team',
            'email' => 'hr@vng.com.vn',
            'role' => 'employer',
            'avatar' => 'https://api.dicebear.com/7.x/identicon/svg?seed=vng'
        ],
        'admin' => [
            'id' => 999,
            'name' => 'Hệ Thống Admin',
            'email' => 'admin@jobhub.vn',
            'role' => 'admin',
            'avatar' => 'https://api.dicebear.com/7.x/bottts/svg?seed=admin'
        ]
    ];

    return $users[$role] ?? null;
}

$loginAsRole = $_GET['login_as'] ?? null;
$previewAsRole = $_GET['preview_as'] ?? null;

// Xử lý Đăng xuất / Đổi vai trò tài khoản thật
if ($loginAsRole) {
    if ($loginAsRole === 'logout') {
        unset($_SESSION['user']);
        unset($_SESSION['preview_user']); // Đã sửa: Xóa sạch cả preview để tránh lỗi dính giao diện cũ
    } else {
        $mockUser = getMockUserByRole($loginAsRole);
        if ($mockUser) {
            $_SESSION['user'] = $mockUser;
        }
    }
    $redirectUrl = strtok($_SERVER['REQUEST_URI'], '?');
    header('Location: ' . $redirectUrl);
    exit();
}

// Xử lý Đổi vai trò bản xem trước (Preview)
if ($previewAsRole) {
    if ($previewAsRole === 'logout') {
        unset($_SESSION['preview_user']);
    } else {
        $mockUser = getMockUserByRole($previewAsRole);
        if ($mockUser) {
            $_SESSION['preview_user'] = $mockUser;
        }
    }
    $redirectUrl = strtok($_SERVER['REQUEST_URI'], '?');
    header('Location: ' . $redirectUrl);
    exit();
}

// Cập nhật lại giá trị sau khi xử lý các tham số GET
$sessionUser = $_SESSION['user'] ?? null;
$previewUser = $_SESSION['preview_user'] ?? null;

// Nếu chưa có session user và chưa có preview user, tự động mock dựa vào URL trang hiện tại.
if (!$sessionUser && !$previewUser) {
    $requestUri = $_SERVER['REQUEST_URI'] ?? '';
    if (str_contains($requestUri, '/views/candidate/') || str_contains($requestUri, '/candidate/')) {
        $previewUser = getMockUserByRole('candidate');
    } elseif (str_contains($requestUri, '/views/employer/') || str_contains($requestUri, '/employer/')) {
        $previewUser = getMockUserByRole('employer');
    } elseif (str_contains($requestUri, '/views/admin/') || str_contains($requestUri, '/admin/')) {
        $previewUser = getMockUserByRole('admin');
    }
}

$currentUser = $previewUser ?? $sessionUser;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JobHub Vietnam - Nền Tảng Tuyển Dụng Hàng Đầu</title>
    
    <!-- CẬP NHẬT: Nhúng CDN Bootstrap 5.3 trực tuyến để đảm bảo sửa lỗi vỡ khung Admin -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Giữ nguyên link CSS cục bộ tại thư mục public/css/css/ để dùng các class custom riêng của bạn -->
    <link href="<?= BASE_URL ?>public/css/css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-blue: #1e5ba6; 
            --secondary-blue: #0f3d73;
            --light-bg: #f8f9fa;
        }
        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
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
        /* Hỗ trợ hiển thị UI mượt hơn cho Dashboard Admin */
        .card {
            border: none !important;
            border-radius: 12px !important;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.05) !important;
        }
    </style>
</head>
<body>

<!-- Thanh Header Điều Hướng -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top py-2">
    <div class="container">
        <!-- Logo ứng dụng -->
        <a class="navbar-brand d-flex align-items-center position-relative" href="<?= BASE_URL ?>index.php" style="width: 150px; height: 40px;">
            <img src="<?= BASE_URL ?>public/images/logo1.png" alt="JobHub Logo" 
                 style="height: 50px; width: auto; object-fit: contain; position: absolute; top: 50%; transform: translateY(-50%); max-width: none;">
        </a>
        
        <!-- Toggle button hiển thị trên Mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>index.php">Trang chủ</a>
                </li>

                <!-- Menu ở giữa hiển thị cho Khách vãng lai, Ứng viên hoặc Nhà tuyển dụng -->
                <?php if (!$currentUser || $currentUser['role'] === 'candidate' || $currentUser['role'] === 'employer'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>views/jobs/search.php">Tìm việc làm</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>views/jobs/companies.php">Danh sách công ty</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>views/candidate/cv-builder.php">Dịch vụ CV</a>
                    </li>
                <?php endif; ?>

                <!-- Menu ở giữa hiển thị dành riêng cho tài khoản ADMIN -->
                <?php if ($currentUser && $currentUser['role'] === 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link text-danger fw-bold" href="<?= BASE_URL ?>views/admin/dashboard.php">
                            <i class="fa-solid fa-toolbox me-1"></i> Dashboard Admin
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>views/admin/users.php">Quản lý thành viên</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>views/admin/verify-job.php">Duyệt bài viết</a>
                    </li>
                <?php endif; ?>
            </ul>
            
            <!-- Khu vực Góc phải: Đăng nhập/Đăng ký hoặc Thông tin tài khoản -->
            <div class="d-flex align-items-center gap-2">
                <?php if (!$currentUser): ?>
                    <!-- Giao diện khi CHƯA đăng nhập -->
                    <a href="<?= BASE_URL ?>views/auth/login.php" class="btn btn-outline-primary border-primary text-primary-blue px-4">Đăng Nhập</a>
                    <a href="<?= BASE_URL ?>views/auth/register.php" class="btn btn-primary-blue px-4">Đăng Ký</a>
                <?php else: ?>
                    <!-- Giao diện khi ĐÃ đăng nhập -->
                    <div class="dropdown">
                        <a class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" href="#" role="button" id="userMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="<?= $currentUser['avatar'] ?>" alt="Avatar" class="rounded-circle border border-2 border-primary me-2" style="width: 38px; height: 38px; object-fit: cover;">
                            <span class="fw-semibold d-none d-sm-inline" style="color: #333;"><?= htmlspecialchars($currentUser['name']) ?></span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="userMenuLink">
                            <li class="px-3 py-2 bg-light rounded-top">
                                <span class="d-block small text-muted">Vai trò: <strong class="text-primary-blue text-uppercase"><?= htmlspecialchars($currentUser['role']) ?></strong></span>
                                <strong class="text-dark small" style="word-break: break-all;"><?= htmlspecialchars($currentUser['email']) ?></strong>
                            </li>
                            <li><hr class="dropdown-divider my-1"></li>
                            
                            <!-- Menu hiển thị nếu vai trò là ỨNG VIÊN (Candidate) -->
                            <?php if ($currentUser['role'] === 'candidate'): ?>
                                <li>
                                    <a class="dropdown-item" href="<?= BASE_URL ?>views/candidate/profile.php">
                                        <i class="fa-regular fa-id-card me-2 text-primary"></i>Hồ sơ cá nhân
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?= BASE_URL ?>views/candidate/applied-jobs.php">
                                        <i class="fa-solid fa-briefcase me-2 text-success"></i>Việc đã ứng tuyển
                                    </a>
                                </li>
                            
                            <!-- Menu hiển thị nếu vai trò là NHÀ TUYỂN DỤNG (Employer) -->
                            <?php elseif ($currentUser['role'] === 'employer'): ?>
                                <li>
                                    <a class="dropdown-item" href="<?= BASE_URL ?>views/employer/profile.php">
                                        <i class="fa-regular fa-building me-2 text-primary text-center" style="width: 20px;"></i>Thông tin doanh nghiệp
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider my-1"></li>
                                <li class="dropdown-header text-uppercase fs-7 fw-bold text-muted pb-1">Quản lý tuyển dụng</li>
                                <li>
                                    <a class="dropdown-item text-primary-blue fw-semibold" href="<?= BASE_URL ?>views/employer/post-job.php">
                                        <i class="fa-solid fa-file-pen me-2 text-center" style="width: 20px;"></i>Đăng tin mới
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?= BASE_URL ?>views/employer/post-job.php">
                                        <i class="fa-solid fa-list-check me-2 text-secondary text-center" style="width: 20px;"></i>Quản lý tin đăng
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?= BASE_URL ?>views/employer/manage-candidates.php">
                                        <i class="fa-solid fa-users-rectangle me-2 text-success text-center" style="width: 20px;"></i>Hồ sơ ứng tuyển
                                    </a>
                                </li>
                            
                            <!-- Menu hiển thị nhanh nếu vai trò là ADMIN -->
                            <?php elseif ($currentUser['role'] === 'admin'): ?>
                                <li>
                                    <a class="dropdown-item fw-bold text-danger" href="<?= BASE_URL ?>views/admin/dashboard.php">
                                        <i class="fa-solid fa-gauge me-2"></i>Vào Trang Dashboard
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <li><hr class="dropdown-divider my-1"></li>
                            <li>
                                <!-- Nút Đăng xuất gửi lệnh huỷ toàn bộ session lên URL -->
                                <a class="dropdown-item text-danger fw-semibold" href="?login_as=logout">
                                    <i class="fa-solid fa-power-off me-2"></i>Đăng xuất
                                </a>
                            </li>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<!-- Header chỉ giữ phần đầu trang và thanh điều hướng. Footer sẽ chịu trách nhiệm đưa script và đóng thẻ body/html. -->