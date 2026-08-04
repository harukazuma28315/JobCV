<?php

require_once __DIR__ . "/../models/JobPosting.php";
require_once __DIR__ . '/../models/JobApply.php';
require_once __DIR__ . '/../models/CV.php';

/**
 * Controller quản lý tin tuyển dụng.
 */
class JobPostingController {
	private $tinTuyenDungModel;
	private $jobApplyModel;
	private $cvModel;

	public function __construct() {
		$this->tinTuyenDungModel = new JobPosting();
		$this->jobApplyModel = new JobApply();
		$this->cvModel = new CV();
	}
	

	/**
	 * Lấy danh sách tin tuyển dụng.
	 *
	 * @return mysqli_result
	 */
	public function index() {
		$salary = $_GET['salary'] ?? '';
		$sort = $_GET['sort'] ?? 'newest';

		$filters = [
			'keyword' => $_GET['keyword'] ?? '',
			'location' => $_GET['location'] ?? '',
			'position' => $_GET['position'] ?? '',
			'capBac' => $_GET['level'] ?? '',
			'category' => $_GET['category'] ?? '',
			'hinhThucLamViec' => $_GET['job_type'] ?? '',
			'soNamKinhNghiem' => $_GET['experience'] ?? '',
			'postedDate' => $_GET['posted_date'] ?? '',
			'minSalary' => '',
			'maxSalary' => ''
		];
		$categories = $this->tinTuyenDungModel->getCategories();

		$positions = $this->tinTuyenDungModel->getPositions();

		$locations = $this->tinTuyenDungModel->getLocations();

		switch ($salary) {
			case 'under-10':
				$filters['maxSalary'] = 10000000;
				break;

			case '10-15':
				$filters['minSalary'] = 10000000;
				$filters['maxSalary'] = 15000000;
				break;

			case '15-20':
				$filters['minSalary'] = 15000000;
				$filters['maxSalary'] = 20000000;
				break;

			case 'over-20':
				$filters['minSalary'] = 20000000;
				break;
		}

		$hasFilter = false;

		foreach ($filters as $value) {
			if ($value !== '') {
				$hasFilter = true;
				break;
			}
		}

		if ($hasFilter) {
			$result = $this->tinTuyenDungModel->filter($filters, $sort);
		} else {
			$result = $this->tinTuyenDungModel->getAll($sort);
		}

		$jobs = [];

		while ($row = $result->fetch_assoc()) {
			$jobs[] = [
				'id' => $row['MaTinTuyenDung'],
				'title' => $row['TieuDe'],
				'company_name' => $row['TenCongTy'],
				'company_logo' => '',
				'salary_text' => $row['MucLuong'],
				'location_text' => $row['DiaChiLamViec'],
				'experience_text' => $row['SoNamKinhNghiem'] . ' năm kinh nghiệm'
			];
		}

		require_once __DIR__ . '/../views/page/jobs/search.php';
	}

	/**
	 * Lấy thông tin chi tiết tin tuyển dụng.
	 *
	 * @param string $maTinTuyenDung
	 * @return array|null
	 */
	public function detail($maTinTuyenDung) {
		$job = $this->tinTuyenDungModel->getById($maTinTuyenDung);

		if (!$job) {
			http_response_code(404);
			echo "404 - Không tìm thấy tin tuyển dụng.";
			exit;
		}
		$hasApplied = false;

		if (isset($_SESSION['user_id'])) {

			$cv = $this->cvModel->getByCandidate($_SESSION['user_id']);

			if ($cv) {
				$hasApplied = $this->jobApplyModel->hasApplied(
					$cv['MaCV'],
					$maTinTuyenDung
				);
			}
		}

		require_once __DIR__ . '/../views/page/jobs/detail.php';
	}

	/**
	 * Đăng tin tuyển dụng mới.
	 *
	 * @param array $tinTuyenDungData
	 * @return bool
	 */
	public function create(array $tinTuyenDungData = []) {
		if (
			!isset($_SESSION['user_id']) ||
			($_SESSION['user_role'] ?? 0) != 1
		) {
			exit('Bạn không có quyền truy cập.');
		}

		$maNhaTuyenDung = $_SESSION['user_id'];

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {

			$tinTuyenDungData['maNhaTuyenDung'] = $maNhaTuyenDung;

			if (!$this->validateJobData($tinTuyenDungData)) {
				exit('Dữ liệu đăng tin không hợp lệ.');
			}

			$result = $this->tinTuyenDungModel->create($tinTuyenDungData);

			if ($result) {
				header(
					'Location: /JobCV/index.php?route=jobs/manage'
				);
				exit;
			}

			exit('Đăng tin tuyển dụng thất bại.');
		}

		$categories = $this->tinTuyenDungModel->getCategories();
		$locations = $this->tinTuyenDungModel->getLocations();
		$jobs = $this->tinTuyenDungModel
		->getByEmployer($maNhaTuyenDung);

		$activeTab = 'post';

		require_once __DIR__ . '/../views/page/employer/post-job.php';
	}

	/**
	 * Hiển thị danh sách tin tuyển dụng do nhà tuyển dụng đang đăng nhập đã đăng,
	 * để họ quản lý (sửa/xóa/gia hạn/đóng tin).
	 *
	 * @return void
	 */
	public function manage() {
		if (
			!isset($_SESSION['user_id']) ||
			($_SESSION['user_role'] ?? 0) != 1
		) {
			exit("Bạn không có quyền.");
		}

		$maNhaTuyenDung = $_SESSION['user_id'];

		$jobs = $this->tinTuyenDungModel->getByEmployer($maNhaTuyenDung);
		$categories = $this->tinTuyenDungModel->getCategories();
		$locations = $this->tinTuyenDungModel->getLocations();

		$activeTab = 'manage';

		require_once __DIR__ . '/../views/page/employer/post-job.php';
	}

	/**
	 * Hiển thị trang chỉnh sửa tin tuyển dụng.
	 *
	 * @param string $maTinTuyenDung
	 */
	public function editPage($maTinTuyenDung) {
		$job = $this->authorizeJobOwner($maTinTuyenDung);

		$categories = $this->tinTuyenDungModel->getCategories();
		$locations = $this->tinTuyenDungModel->getLocations();
		$selectedCategoryId = $this->tinTuyenDungModel->getCategoryIdByJob($maTinTuyenDung);
		$selectedLocationId = $this->tinTuyenDungModel->getLocationIdByJob($maTinTuyenDung);

		require_once __DIR__ . '/../views/page/employer/edit-job.php';
	}

	/**
	 * Cập nhật nội dung tin tuyển dụng.
	 *
	 * @param array $tinTuyenDungData
	 * @return bool
	 */
	public function update(array $tinTuyenDungData) {
		$maTinTuyenDung = $tinTuyenDungData['maTinTuyenDung'] ?? '';

		$this->authorizeJobOwner($maTinTuyenDung);

		if (!$this->validateJobData($tinTuyenDungData)) {
			echo "<script>alert('Dữ liệu chỉnh sửa không hợp lệ.'); window.history.back();</script>";
			exit;
		}

		$result = $this->tinTuyenDungModel->update($tinTuyenDungData);

		$this->tinTuyenDungModel->syncJobCategory(
			$maTinTuyenDung,
			$tinTuyenDungData['category'] ?? '',
			'NganhNghe'
		);
		$this->tinTuyenDungModel->syncJobCategory(
			$maTinTuyenDung,
			$tinTuyenDungData['location'] ?? '',
			'diadiem'
		);

		if ($result) {
			echo "
				<script>
					alert('Cập nhật tin tuyển dụng thành công!');
					window.location.href = '/JobCV/index.php?route=jobs/manage';
				</script>
			";
		} else {
			echo "
				<script>
					alert('Cập nhật tin tuyển dụng thất bại.');
					window.history.back();
				</script>
			";
		}
	}

	/**
	 * Xóa tin tuyển dụng.
	 *
	 * @param string $maTinTuyenDung
	 * @return bool
	 */
	public function delete(array $data = []) {
		$maTinTuyenDung = $data['maTinTuyenDung'] ?? '';

		$this->authorizeJobOwner($maTinTuyenDung);

		$result = $this->tinTuyenDungModel->delete($maTinTuyenDung);

		if ($result) {
			echo "
				<script>
					alert('Đã xóa tin tuyển dụng.');
					window.location.href = '/JobCV/index.php?route=jobs/manage';
				</script>
			";
		} else {
			echo "
				<script>
					alert('Xóa tin tuyển dụng thất bại.');
					window.history.back();
				</script>
			";
		}
		exit;
	}

	/**
	 * Gia hạn thời gian tuyển dụng.
	 *
	 * @param string $maTinTuyenDung
	 * @param string $ngayHetHan
	 * @return bool
	 */
	public function extendDeadline(array $data = []) {
		$maTinTuyenDung = $data['maTinTuyenDung'] ?? '';
		$ngayHetHan = $data['ngayHetHan'] ?? '';

		if (empty($ngayHetHan)) {
			echo "<script>alert('Vui lòng chọn hạn nộp mới.'); window.history.back();</script>";
			exit;
		}

		$this->authorizeJobOwner($maTinTuyenDung);

		$result = $this->tinTuyenDungModel->extendDeadline(
			$maTinTuyenDung,
			$ngayHetHan
		);

		if ($result) {
			echo "
				<script>
					alert('Gia hạn tin tuyển dụng thành công!');
					window.location.href = '/JobCV/index.php?route=jobs/manage';
				</script>
			";
		} else {
			echo "
				<script>
					alert('Gia hạn tin tuyển dụng thất bại.');
					window.history.back();
				</script>
			";
		}
		exit;
	}

	/**
	 * Đóng tin tuyển dụng.
	 *
	 * @param string $maTinTuyenDung
	 * @return bool
	 */
	public function toggleStatus(array $data = []) {
		$maTinTuyenDung = $data['maTinTuyenDung'] ?? '';

		$job = $this->authorizeJobOwner($maTinTuyenDung);

		if (($job['TrangThai'] ?? '') === 'DangMo') {
			$result = $this->tinTuyenDungModel->closeJob($maTinTuyenDung);
			$successMsg = 'Đã tạm dừng/đóng tin tuyển dụng.';
			$failMsg = 'Đóng tin tuyển dụng thất bại.';
		} else {
			$result = $this->tinTuyenDungModel->openJob($maTinTuyenDung);
			$successMsg = 'Đã mở lại tin tuyển dụng.';
			$failMsg = 'Mở lại tin tuyển dụng thất bại.';
		}

		if ($result) {
			echo "
				<script>
					alert('" . $successMsg . "');
					window.location.href = '/JobCV/index.php?route=jobs/manage';
				</script>
			";
		} else {
			echo "
				<script>
					alert('" . $failMsg . "');
					window.history.back();
				</script>
			";
		}
		exit;
	}

	/**
	 * Kiểm tra người dùng đã đăng nhập, đúng vai trò nhà tuyển dụng,
	 * và là chủ sở hữu của tin tuyển dụng đang thao tác.
	 * Nếu không hợp lệ thì dừng thực thi ngay.
	 *
	 * @param string $maTinTuyenDung
	 * @return array Dữ liệu tin tuyển dụng
	 */
	private function authorizeJobOwner($maTinTuyenDung) {
		if (
			!isset($_SESSION['user_id']) ||
			($_SESSION['user_role'] ?? 0) != 1
		) {
			exit('Bạn không có quyền truy cập.');
		}

		if (empty($maTinTuyenDung)) {
			exit('Mã tin tuyển dụng không hợp lệ.');
		}

		$job = $this->tinTuyenDungModel->getById($maTinTuyenDung);

		if (!$job) {
			exit('Không tìm thấy tin tuyển dụng.');
		}

		// Chặn IDOR: nhà tuyển dụng chỉ được thao tác trên tin do chính mình đăng,
		// không được sửa/xóa tin của nhà tuyển dụng khác dù biết mã tin.
		if ($job['MaNhaTuyenDung'] != $_SESSION['user_id']) {
			exit('Bạn không có quyền thao tác với tin tuyển dụng này.');
		}

		return $job;
	}

	/**
	 * Kiểm tra dữ liệu tin tuyển dụng.
	 *
	 * @param array $tinTuyenDungData
	 * @return bool
	 */
	private function validateJobData(array $data) {
		if (empty(trim($data['tieuDe'] ?? ''))) {
			return false;
		}

		if (empty(trim($data['category'] ?? ''))) {
			return false;
		}

		if (empty(trim($data['moTaCongViec'] ?? ''))) {
			return false;
		}

		if (empty(trim($data['yeuCauCongViec'] ?? ''))) {
			return false;
		}

		if (empty($data['ngayHetHan'] ?? '')) {
			return false;
		}

		if (empty($data['mucLuong'] ?? '')) {
			return false;
		}

		if (empty(trim($data['diaChiLamViec'] ?? ''))) {
			return false;
		}

		if (empty(trim($data['hinhThucLamViec'] ?? ''))) {
			return false;
		}

		if (empty(trim($data['viTriTuyenDung'] ?? ''))) {
			return false;
		}

		if (empty(trim($data['capBac'] ?? ''))) {
			return false;
		}

		if (!isset($data['soNamKinhNghiem']) || $data['soNamKinhNghiem'] === '') {
			return false;
		}

		if (empty($data['soLuongTuyen'] ?? '')) {
			return false;
		}

		if (empty(trim($data['location'] ?? ''))) {
			return false;
		}

		return true;
	}
}