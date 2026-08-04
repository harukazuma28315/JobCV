<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/JobPosting.php';
require_once __DIR__ . '/../models/Employer.php';

class HomeController {
	private $userModel;
	private $tinTuyenDungModel;
	private $conn;
	private $nhaTuyenDungModel;

	public function __construct($conn) {
		$this->conn = $conn;

		$this->userModel = new UserModel($conn);

		$this->tinTuyenDungModel = new JobPosting();
		$this->nhaTuyenDungModel = new Employer();
	}

	/**
	 * Lấy thông tin người dùng đang đăng nhập
	 */
	public function getProfileData() {
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
	public function index() {


		$categories = $this->tinTuyenDungModel->getCategories();

		$positions = $this->tinTuyenDungModel->getPositions();

		$locations = $this->tinTuyenDungModel->getLocations();
		
		$keyword = $_GET['keyword'] ?? '';
		$resultJobs = $this->tinTuyenDungModel->getFeaturedJobs(8);

		$jobs = [];

		while ($row = $resultJobs->fetch_assoc()) {
			$jobs[] = $row;
		}

		$resultCompanies = $this->tinTuyenDungModel->getTopCompanies(4);

		$companies = [];

		while ($row = $resultCompanies->fetch_assoc()) {
			$companies[] = $row;
		}

		$resultCategories = $this->tinTuyenDungModel->getPopularCategories(4);

		$categories = [];

		while ($row = $resultCategories->fetch_assoc()) {
			$categories[] = $row;
		}

		$totalJobs = 0;
		$totalApplications = 0;

		if (
			isset($_SESSION['user_id']) &&
			isset($_SESSION['user_role']) &&
			$_SESSION['user_role'] == 1
		) {
			$maNhaTuyenDung = $_SESSION['user_id'];

			$totalJobs = $this->nhaTuyenDungModel
				->countJobs($maNhaTuyenDung);

			$totalApplications = $this->nhaTuyenDungModel
				->countApplications($maNhaTuyenDung);
		}

		require_once __DIR__ . '/../views/page/home/index.php';
	}
}