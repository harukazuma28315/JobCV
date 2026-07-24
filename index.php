<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/db.php';

require_once __DIR__ . '/controllers/AdminController.php';
require_once __DIR__ . '/controllers/CategoryController.php';
require_once __DIR__ . '/controllers/ApplicationController.php';
require_once __DIR__ . '/controllers/CVController.php';
require_once __DIR__ . '/controllers/GoogleAuthController.php';
require_once __DIR__ . '/controllers/HomeController.php';
require_once __DIR__ . '/controllers/JobManagementController.php';
require_once __DIR__ . '/controllers/LoginController.php';
require_once __DIR__ . '/controllers/LogoutController.php';
require_once __DIR__ . '/controllers/NhaTuyenDungController.php';
require_once __DIR__ . '/controllers/ProfileController.php';
require_once __DIR__ . '/controllers/RegisterController.php';
require_once __DIR__ . '/controllers/RecruiterController.php';
require_once __DIR__ . '/controllers/TinTuyenDungController.php';
require_once __DIR__ . '/controllers/UserManagementController.php';
require_once __DIR__ . '/controllers/ForgotPasswordController.php';
require_once __DIR__ . '/controllers/OtpController.php';
require_once __DIR__ . '/controllers/JobApplyController.php';

// ==========================================================
// ROUTE
// ==========================================================

$route = isset($_GET['route']) ? trim($_GET['route']) : 'home';


// ==========================================================
// BẢNG ĐỊNH TUYẾN
// ==========================================================

$routes = [

	// =========================
	// TRANG CHỦ
	// =========================

	'home' => [
		'controller' => 'HomeController',
		'method' => 'index',
		'constructor' => 'database'
	],


	// =========================
	// XÁC THỰC
	// =========================

	'auth/login' => [
		'controller' => 'LoginController',
		'method' => 'showLogin',
		'constructor' => 'database'
	],

	'auth/login-submit' => [
		'controller' => 'LoginController',
		'method' => 'handleLogin',
		'constructor' => 'database'
	],

	'auth/forgot-password' => [
		'controller' => 'LoginController',
		'method' => 'showForgotPassword',
		'constructor' => 'database'
	],

	'auth/reset-password' => [
		'controller' => 'LoginController',
		'method' => 'showResetPassword',
		'constructor' => 'database'
	],

	'auth/register' => [
    'controller' => 'RegisterController',
    'method' => 'showRegister',
    'constructor' => 'database'
	],

	'auth/register-submit' => [
		'controller' => 'RegisterController',
		'method' => 'handleRegister',
		'constructor' => 'database'
	],
	
	'auth/google' => [
		'controller' => 'GoogleAuthController',
		'method' => 'handleGoogleAuth',
		'constructor' => 'database'
	],

	'auth/logout' => [
		'controller' => 'LogoutController',
		'method' => 'handleLogout',
		'constructor' => 'database'
	],


	// =========================
	// PROFILE
	// =========================

	'profile' => [
		'controller' => 'ProfileController',
		'method' => 'showProfile',
		'constructor' => 'database'
	],

	'profile/update' => [
		'controller' => 'ProfileController',
		'method' => 'handleUpdateProfile',
		'constructor' => 'database'
	],

	// =========================
	// TIN TUYỂN DỤNG
	// =========================

	'jobs/list' => [
		'controller' => 'TinTuyenDungController',
		'method' => 'index',
		'constructor' => 'default'
	],

	'jobs/detail' => [
		'controller' => 'TinTuyenDungController',
		'method' => 'detail',
		'constructor' => 'default',
		'parameters' => [
			'maTinTuyenDung'
		]
	],


	// =========================
	// NHÀ TUYỂN DỤNG
	// =========================



	// =========================
	// ỨNG TUYỂN
	// =========================

	'jobs/apply' => [
		'controller' => 'JobApplyController',
		'method' => 'apply',
		'constructor' => 'default',
		'parameters' => [
			'maTinTuyenDung'
		]
	],

	'jobs/apply-submit' => [
		'controller' => 'JobApplyController',
		'method' => 'submit',
		'constructor' => 'default'
	],

	// =========================
	// NHÀ TUYỂN DỤNG - QUẢN LÝ ỨNG VIÊN
	// =========================



	// =========================
	// ADMIN
	// =========================

<<<<<<< HEAD
	'admin/dashboard' => [
		'controller' => 'AdminController',
		'method' => 'showDashboard',
		'constructor' => 'default'
	],

	'admin/users' => [
		'controller' => 'UserManagementController',
		'method' => 'showUserList',
		'constructor' => 'default'
	],

	'admin/users/lock' => [
		'controller' => 'UserManagementController',
		'method' => 'lockUser',
		'constructor' => 'default'
	],

	'admin/users/unlock' => [
		'controller' => 'UserManagementController',
		'method' => 'unlockUser',
		'constructor' => 'default'
	],

	'admin/users/approve' => [
		'controller' => 'UserManagementController',
		'method' => 'approveUser',
		'constructor' => 'default'
	],

	'admin/jobs' => [
		'controller' => 'JobManagementController',
		'method' => 'showJobList',
		'constructor' => 'default'
	],

	'admin/jobs/approve' => [
		'controller' => 'JobManagementController',
		'method' => 'approveJob',
		'constructor' => 'default'
	],

	'admin/jobs/reject' => [
		'controller' => 'JobManagementController',
		'method' => 'rejectJob',
		'constructor' => 'default'
	],

	'admin/jobs/remove' => [
		'controller' => 'JobManagementController',
		'method' => 'removeJob',
		'constructor' => 'default'
	],
	// Route quản lý danh mục (ngành nghề, địa điểm)
	'admin/categories' => [
    'controller' => 'CategoryController',
    'method' => 'showCategories',
    'constructor' => 'default'
	],
	// Thêm route delete danh mục
	'admin/categories/delete' => [
		'controller' => 'CategoryController',
		'method' => 'deleteCategory',
		'constructor' => 'default'
	],

	// Thêm route add danh mục
	'admin/categories/add' => [
		'controller' => 'CategoryController',
		'method' => 'addCategory',
		'constructor' => 'default'
	],
	//Thêm route update danh mục
	'admin/categories/update' => [         
        'controller' => 'CategoryController',
        'method' => 'updateCategory',
        'constructor' => 'default'
    ],
	//forgot password
	'auth/forgot-password-submit' => [
        'controller' => 'ForgotPasswordController',
        'method' => 'handleRequest',
        'constructor' => 'database'
    ],

	// =========================
    // XÁC THỰC OTP ĐĂNG KÝ
    // =========================
	'auth/send-otp' => [
        'controller' => 'OtpController',
        'method' => 'handleRequest',
        'constructor' => 'database'
    ],
=======
>>>>>>> 6314ee3af0627145a0073db051b45661dea19941
];

// ==========================================================
// KIỂM TRA ROUTE
// ==========================================================

if (!isset($routes[$route])) {
	http_response_code(404);
	echo '404 - Không tìm thấy route.';
	exit;
}


$routeConfig = $routes[$route];

$controllerName = $routeConfig['controller'];
$methodName = $routeConfig['method'];


// ==========================================================
// KHỞI TẠO CONTROLLER
// ==========================================================

if ($routeConfig['constructor'] === 'database') {
	$controller = new $controllerName($conn);
} else {
	$controller = new $controllerName();
}


// ==========================================================
// CHUẨN BỊ THAM SỐ
// ==========================================================

$parameters = [];

if (isset($routeConfig['parameters'])) {
	foreach ($routeConfig['parameters'] as $parameter) {

		if ($parameter === 'post') {
			$parameters[] = $_POST;
			continue;
		}

		if ($parameter === 'file') {
			$parameters[] = $_FILES['file'] ?? null;
			continue;
		}

		$parameters[] = $_REQUEST[$parameter] ?? null;
	}
}

// ==========================================================
// KIỂM TRA METHOD
// ==========================================================

if (!method_exists($controller, $methodName)) {
	http_response_code(500);
	echo '500 - Method không tồn tại trong Controller.';
	exit;
}


// ==========================================================
// GỌI CONTROLLER
// ==========================================================

$result = call_user_func_array(
	[
		$controller,
		$methodName
	],
	$parameters
);


// ==========================================================
// TRẢ KẾT QUẢ
// ==========================================================

if ($result !== null) {
	return $result;
}
