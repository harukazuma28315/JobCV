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
		'method' => 'handleGetProfile',
		'constructor' => 'database'
	],

	'profile/update' => [
		'controller' => 'ProfileController',
		'method' => 'handleUpdateProfile',
		'constructor' => 'database'
	],


	// =========================
	// CV
	// =========================

	'cv/list' => [
		'controller' => 'CVController',
		'method' => 'index',
		'constructor' => 'default'
	],

	'cv/detail' => [
		'controller' => 'CVController',
		'method' => 'detail',
		'constructor' => 'default',
		'parameters' => [
			'maCV'
		]
	],

	'cv/create' => [
		'controller' => 'CVController',
		'method' => 'create',
		'constructor' => 'default',
		'parameters' => [
			'post'
		]
	],

	'cv/update' => [
		'controller' => 'CVController',
		'method' => 'update',
		'constructor' => 'default',
		'parameters' => [
			'post'
		]
	],

	'cv/delete' => [
		'controller' => 'CVController',
		'method' => 'delete',
		'constructor' => 'default',
		'parameters' => [
			'maCV'
		]
	],

	'cv/upload' => [
		'controller' => 'CVController',
		'method' => 'uploadCV',
		'constructor' => 'default',
		'parameters' => [
			'maCV',
			'file'
		]
	],

	'cv/download' => [
		'controller' => 'CVController',
		'method' => 'downloadCV',
		'constructor' => 'default',
		'parameters' => [
			'maCV'
		]
	],

	'cv/delete-file' => [
		'controller' => 'CVController',
		'method' => 'deleteUploadedCV',
		'constructor' => 'default',
		'parameters' => [
			'maCV'
		]
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

	'jobs/create' => [
		'controller' => 'TinTuyenDungController',
		'method' => 'create',
		'constructor' => 'default',
		'parameters' => [
			'post'
		]
	],

	'jobs/update' => [
		'controller' => 'TinTuyenDungController',
		'method' => 'update',
		'constructor' => 'default',
		'parameters' => [
			'post'
		]
	],

	'jobs/delete' => [
		'controller' => 'TinTuyenDungController',
		'method' => 'delete',
		'constructor' => 'default',
		'parameters' => [
			'maTinTuyenDung'
		]
	],

	'jobs/extend-deadline' => [
		'controller' => 'TinTuyenDungController',
		'method' => 'extendDeadline',
		'constructor' => 'default',
		'parameters' => [
			'maTinTuyenDung',
			'ngayHetHan'
		]
	],

	'jobs/close' => [
		'controller' => 'TinTuyenDungController',
		'method' => 'closeJob',
		'constructor' => 'default',
		'parameters' => [
			'maTinTuyenDung'
		]
	],


	// =========================
	// NHÀ TUYỂN DỤNG
	// =========================

	'employer/detail' => [
		'controller' => 'NhaTuyenDungController',
		'method' => 'detail',
		'constructor' => 'default',
		'parameters' => [
			'maNhaTuyenDung'
		]
	],

	'employer/update' => [
		'controller' => 'NhaTuyenDungController',
		'method' => 'update',
		'constructor' => 'default',
		'parameters' => [
			'post'
		]
	],

	'employer/upload-logo' => [
		'controller' => 'NhaTuyenDungController',
		'method' => 'uploadLogo',
		'constructor' => 'default',
		'parameters' => [
			'maNhaTuyenDung',
			'file'
		]
	],

	'employer/upload-cover' => [
		'controller' => 'NhaTuyenDungController',
		'method' => 'uploadCover',
		'constructor' => 'default',
		'parameters' => [
			'maNhaTuyenDung',
			'file'
		]
	],


	// =========================
	// ỨNG TUYỂN
	// =========================

	'application/apply' => [
		'controller' => 'ApplicationController',
		'method' => 'showApplyForm',
		'constructor' => 'default'
	],

	'application/submit' => [
		'controller' => 'ApplicationController',
		'method' => 'submitApplication',
		'constructor' => 'default'
	],

	'application/history' => [
		'controller' => 'ApplicationController',
		'method' => 'showHistory',
		'constructor' => 'default'
	],

	'application/detail' => [
		'controller' => 'ApplicationController',
		'method' => 'showDetail',
		'constructor' => 'default'
	],


	// =========================
	// NHÀ TUYỂN DỤNG - QUẢN LÝ ỨNG VIÊN
	// =========================

	'recruiter/list' => [
		'controller' => 'RecruiterController',
		'method' => 'showList',
		'constructor' => 'default'
	],

	'recruiter/detail' => [
		'controller' => 'RecruiterController',
		'method' => 'showDetail',
		'constructor' => 'default'
	],

	'recruiter/update-status' => [
		'controller' => 'RecruiterController',
		'method' => 'updateStatus',
		'constructor' => 'default'
	],


	// =========================
	// ADMIN
	// =========================

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
	]
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
