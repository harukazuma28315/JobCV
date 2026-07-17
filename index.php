<?php
/**
 * File: index.php
 * Đường dẫn: Module5_UngTuyenVaQuanLyHoSo/index.php
 * Chức năng: Điểm vào (entry point) duy nhất của module, điều hướng request
 *            đến đúng Controller/action tương ứng dựa trên tham số ?action=.
 *            Áp dụng mô hình Front Controller đơn giản (không dùng framework).
 */

session_start();

// ===== TEST MODULE =====
// TODO: Xóa hoặc comment phần test này khi deploy thật
// === TEST ADMIN (bật cái này để test Dashboard) ===




// $_SESSION['MaUser'] = 'ADMIN001';
// $_SESSION['Role']   = 2;           // 2 = Admin
// $_SESSION['HoTen']  = 'Admin Test';





// $_SESSION['MaUser'] = 'NTD001';   // Phải tồn tại trong bảng user + nhatuyendung
// $_SESSION['Role'] = 1;

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/app/helpers/ResponseHelper.php';
require_once __DIR__ . '/app/helpers/AuthHelper.php';
require_once __DIR__ . '/app/controllers/ApplicationController.php';
require_once __DIR__ . '/app/controllers/RecruiterController.php';
//Admin
require_once __DIR__ . '/app/controllers/AdminController.php';
require_once __DIR__ . '/app/controllers/UserManagementController.php';
require_once __DIR__ . '/app/controllers/JobManagementController.php';


$action = isset($_GET['action']) ? $_GET['action'] : '';

// Bảng định tuyến: action => array(Controller, method)
// Dùng mảng tra cứu thay vì if/else dài dòng, dễ mở rộng sau này.
$routes = array(
	// Nhóm Ứng viên
	'applyForm'        => array('ApplicationController', 'showApplyForm'),
	'submitApplication'=> array('ApplicationController', 'submitApplication'),
	'history'          => array('ApplicationController', 'showHistory'),
	'detail'           => array('ApplicationController', 'showDetail'),

	// Nhóm Nhà tuyển dụng
	'recruiterList'    => array('RecruiterController', 'showList'),
	'recruiterDetail'  => array('RecruiterController', 'showDetail'),
	'updateStatus'     => array('RecruiterController', 'updateStatus'),

	//Module 6- Trang Admin
	// Nhóm Admin
	'adminDashboard' => array('AdminController', 'showDashboard'),
	// Quản lý người dùng 
	'adminUserList' => array('UserManagementController', 'showUserList'),
	'lockUser'      => array('UserManagementController', 'lockUser'),
	'unlockUser'    => array('UserManagementController', 'unlockUser'),
	'approveUser'   => array('UserManagementController', 'approveUser'),
	// Quản lý bài đăng 
    'adminJobList'  => array('JobManagementController', 'showJobList'),
    'approveJob'    => array('JobManagementController', 'approveJob'),
    'rejectJob'     => array('JobManagementController', 'rejectJob'),
    'removeJob'     => array('JobManagementController', 'removeJob'),
);

if ($action === '') {
    header("Location: index.php?action=applyForm");
    exit;
}

if (!isset($routes[$action])) {
    http_response_code(404);
    echo "Action không tồn tại.";
    exit;
}

list($controllerName, $methodName) = $routes[$action];

$controller = new $controllerName();
call_user_func(array($controller, $methodName));