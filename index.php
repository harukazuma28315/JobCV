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
$_SESSION['MaUser'] = 'NTD001';   // Phải tồn tại trong bảng user + nhatuyendung
$_SESSION['Role'] = 1;

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/helpers/ResponseHelper.php';
require_once __DIR__ . '/helpers/AuthHelper.php';
require_once __DIR__ . '/controllers/ApplicationController.php';
require_once __DIR__ . '/controllers/RecruiterController.php';

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