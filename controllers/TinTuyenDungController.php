<?php

require_once __DIR__ . "/../models/TinTuyenDung.php";
require_once __DIR__ . '/../models/JobApply.php';
require_once __DIR__ . '/../models/CV.php';

/**
 * Controller quản lý tin tuyển dụng.
 */
class TinTuyenDungController
{
	private $tinTuyenDungModel;
	private $jobApplyModel;
	private $cvModel;

	public function __construct()
	{
		$this->tinTuyenDungModel = new TinTuyenDung();
		$this->jobApplyModel = new JobApply();
		$this->cvModel = new CV();
	}
	

	/**
	 * Lấy danh sách tin tuyển dụng.
	 *
	 * @return mysqli_result
	 */
	public function index()
	{
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

		// Lấy danh sách vị trí duy nhất từ database
		$positions = $this->tinTuyenDungModel->getPositions();

		// Lấy danh sách địa điểm duy nhất từ database
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
	public function detail($maTinTuyenDung)
	{
		// Lấy thông tin tin tuyển dụng từ database
		$job = $this->tinTuyenDungModel->getById($maTinTuyenDung);

		// Nếu không tìm thấy tin tuyển dụng
		if (!$job) {
			http_response_code(404);
			echo "404 - Không tìm thấy tin tuyển dụng.";
			exit;
		}
		$hasApplied = false;

		if (isset($_SESSION['user_id'])) {

			$cv = $this->cvModel->getByUngVien($_SESSION['user_id']);

			if ($cv) {
				$hasApplied = $this->jobApplyModel->hasApplied(
					$cv['MaCV'],
					$maTinTuyenDung
				);
			}
		}

		// Nạp giao diện chi tiết
		require_once __DIR__ . '/../views/page/jobs/detail.php';
	}

	/**
	 * Đăng tin tuyển dụng mới.
	 *
	 * @param array $tinTuyenDungData
	 * @return bool
	 */
	public function create(array $tinTuyenDungData = [])
	{
		// Chỉ cho nhà tuyển dụng truy cập
		if (
			!isset($_SESSION['user_id']) ||
			($_SESSION['user_role'] ?? 0) != 1
		) {
			exit('Bạn không có quyền truy cập.');
		}

		// Lấy mã nhà tuyển dụng đang đăng nhập
		$maNhaTuyenDung = $_SESSION['user_id'];

		// Nếu submit form
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

		// Lấy danh sách ngành nghề & địa điểm từ database
		$categories = $this->tinTuyenDungModel->getCategories();
		$locations = $this->tinTuyenDungModel->getLocations();
		$jobs = $this->tinTuyenDungModel
        ->getByNhaTuyenDung($maNhaTuyenDung);

		// Vào từ route đăng tin -> mở sẵn tab "Đăng tin mới"
		$activeTab = 'post';

		// Hiển thị trang đăng tin
		require_once __DIR__ . '/../views/page/employer/post-job.php';
	}

	public function manage()
	{
		if (
			!isset($_SESSION['user_id']) ||
			($_SESSION['user_role'] ?? 0) != 1
		) {
			exit("Bạn không có quyền.");
		}

		$maNhaTuyenDung = $_SESSION['user_id'];

		$jobs = $this->tinTuyenDungModel->getByNhaTuyenDung($maNhaTuyenDung);
		$categories = $this->tinTuyenDungModel->getCategories();
		$locations = $this->tinTuyenDungModel->getLocations();

		// Vào từ route quản lý -> mở sẵn tab "Quản lý tin tuyển dụng"
		$activeTab = 'manage';

		// Dùng chung view post-job.php (đã có tab Quản lý)
		require_once __DIR__ . '/../views/page/employer/post-job.php';
	}

	/**
	 * Hiển thị trang chỉnh sửa tin tuyển dụng.
	 *
	 * @param string $maTinTuyenDung
	 */
	public function editPage($maTinTuyenDung)
	{
		// authorizeJobOwner đã tự kiểm tra đăng nhập, vai trò và quyền sở hữu
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
	public function update(array $tinTuyenDungData)
	{
		$maTinTuyenDung = $tinTuyenDungData['maTinTuyenDung'] ?? '';

		$this->authorizeJobOwner($maTinTuyenDung);

		if (!$this->validateJobData($tinTuyenDungData)) {
			echo "<script>alert('Dữ liệu chỉnh sửa không hợp lệ.'); window.history.back();</script>";
			exit;
		}

		$result = $this->tinTuyenDungModel->update($tinTuyenDungData);

		// Đồng bộ lại ngành nghề & địa điểm dù nội dung chính có đổi hay không
		$this->tinTuyenDungModel->syncJobDanhMuc(
			$maTinTuyenDung,
			$tinTuyenDungData['category'] ?? '',
			'NganhNghe'
		);
		$this->tinTuyenDungModel->syncJobDanhMuc(
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
	public function delete($maTinTuyenDung)
	{
		$this->authorizeJobOwner($maTinTuyenDung);

		return $this->tinTuyenDungModel->delete($maTinTuyenDung);
	}

	/**
	 * Gia hạn thời gian tuyển dụng.
	 *
	 * @param string $maTinTuyenDung
	 * @param string $ngayHetHan
	 * @return bool
	 */
	public function extendDeadline($maTinTuyenDung, $ngayHetHan)
	{
		if (empty($ngayHetHan)) {
			return false;
		}

		$this->authorizeJobOwner($maTinTuyenDung);

		return $this->tinTuyenDungModel->extendDeadline(
			$maTinTuyenDung,
			$ngayHetHan
		);
	}

	/**
	 * Đóng tin tuyển dụng.
	 *
	 * @param string $maTinTuyenDung
	 * @return bool
	 */
	public function closeJob($maTinTuyenDung)
	{
		$this->authorizeJobOwner($maTinTuyenDung);

		return $this->tinTuyenDungModel->closeJob($maTinTuyenDung);
	}

	/**
	 * Kiểm tra người dùng đã đăng nhập, đúng vai trò nhà tuyển dụng,
	 * và là chủ sở hữu của tin tuyển dụng đang thao tác.
	 * Nếu không hợp lệ thì dừng thực thi ngay.
	 *
	 * @param string $maTinTuyenDung
	 * @return array Dữ liệu tin tuyển dụng
	 */
	private function authorizeJobOwner($maTinTuyenDung)
	{
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
	private function validateJobData(array $data)
	{
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