<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/db.php';

require_once __DIR__ . '/controllers/AdminController.php';
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
