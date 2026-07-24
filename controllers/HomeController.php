<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/TinTuyenDung.php';

class HomeController
{
    private $userModel;
    private $tinTuyenDungModel;
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;

        $this->userModel = new UserModel($conn);

        // TinTuyenDung hiện tại tự lấy global $conn
        $this->tinTuyenDungModel = new TinTuyenDung();
    }

    /**
     * Lấy thông tin người dùng đang đăng nhập
     */
    public function getProfileData()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /JobCV/index.php?route=auth/login");
            exit();
        }

        $maUser = $_SESSION['user_id'];

        return $this->userModel->getUserById($maUser);
    }

    /**
     * Trang chủ
     */
    public function index()
    {


		$categories = $this->tinTuyenDungModel->getCategories();

		// Lấy danh sách vị trí duy nhất từ database
		$positions = $this->tinTuyenDungModel->getPositions();

		// Lấy danh sách địa điểm duy nhất từ database
		$locations = $this->tinTuyenDungModel->getLocations();
		
		$keyword = $_GET['keyword'] ?? '';
        // Việc làm nổi bật
        $resultJobs = $this->tinTuyenDungModel->getFeaturedJobs(8);

        $jobs = [];

        while ($row = $resultJobs->fetch_assoc()) {
            $jobs[] = $row;
        }

        // Công ty hàng đầu
        $resultCompanies = $this->tinTuyenDungModel->getTopCompanies(4);

        $companies = [];

        while ($row = $resultCompanies->fetch_assoc()) {
            $companies[] = $row;
        }

        // // Ngành nghề phổ biến
        $resultCategories = $this->tinTuyenDungModel->getPopularCategories(4);

        $categories = [];

        while ($row = $resultCategories->fetch_assoc()) {
            $categories[] = $row;
        }

        require_once __DIR__ . '/../views/page/home/index.php';
    }
}