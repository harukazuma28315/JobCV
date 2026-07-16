<?php

require_once "controllers/CVController.php";

$controller = new CVController();

$data = [
	"maCV" => "CV200",
	"maUngVien" => "UV001",
	"tieuDe" => "",
	"kyNang" => "PHP",
	"soThich" => "Code",
	"mucTieu" => "Backend",
	"trangThai" => 1,
	"viTriMongMuon" => "Backend",
	"email" => "abc",
	"sdt" => "123"
];

$result = $controller->create($data);

echo $result ? "Thành công" : "Dữ liệu không hợp lệ";