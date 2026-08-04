<?php

require_once __DIR__ . '/../models/JobApply.php';
require_once __DIR__ . '/../models/CV.php';
require_once __DIR__ . '/../models/JobPosting.php';

class JobApplyController {
	private $jobApplyModel;
	private $cvModel;
	private $tinModel;

	public function __construct() {
		$this->jobApplyModel = new JobApply();
		$this->cvModel = new CV();
		$this->tinModel = new JobPosting();
	}

	/**
	 * Hiển thị trang ứng tuyển
	 */
	public function apply($maTinTuyenDung) {
		if (!isset($_SESSION['user_id'])) {
			header('Location: /JobCV/index.php?route=auth/login');
			exit;
		}

		$maUser = $_SESSION['user_id'];

		$job = $this->tinModel->getById($maTinTuyenDung);

		if (!$job) {
			http_response_code(404);
			echo "Không tìm thấy tin tuyển dụng.";
			exit;
		}

		if ($job['TrangThai'] !== 'DangMo') {
			echo "
				<script>
					alert('Tin tuyển dụng này đã đóng.');
					window.history.back();
				</script>
			";
			exit;
		}

		$cv = $this->cvModel->getByCandidate($maUser);

		if (!$cv) {
			echo "
				<script>
					alert('Bạn chưa có CV. Vui lòng tạo CV trước khi ứng tuyển.');
					window.location.href =
						'/JobCV/index.php?route=cv/create';
				</script>
			";
			exit;
		}

		$hasApplied = $this->jobApplyModel->hasApplied(
			$cv['MaCV'],
			$maTinTuyenDung
		);

		if ($hasApplied) {
			echo "
				<script>
					alert('Bạn đã ứng tuyển vào công việc này rồi.');
					window.history.back();
				</script>
			";
			exit;
		}

		require_once __DIR__ . '/../views/applicant/applyJob.php';
	}

	/**
	 * Xử lý gửi hồ sơ
	 */
	public function submit() {
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			header('Location: /JobCV/index.php');
			exit;
		}

		if (!isset($_SESSION['user_id'])) {
			header('Location: /JobCV/index.php?route=auth/login');
			exit;
		}

		$maUser = $_SESSION['user_id'];

		$maTinTuyenDung = $_POST['maTinTuyenDung'] ?? '';
		$coverLetter = trim($_POST['coverLetter'] ?? '');

		if (empty($maTinTuyenDung)) {
			die('Thiếu mã tin tuyển dụng.');
		}

		$cv = $this->cvModel->getByCandidate($maUser);

		if (!$cv) {
			die('Bạn chưa có CV.');
		}

		$maCV = $cv['MaCV'];

		if (
			$this->jobApplyModel->hasApplied(
				$maCV,
				$maTinTuyenDung
			)
		) {
			die('Bạn đã ứng tuyển công việc này rồi.');
		}

		$maHS = $this->jobApplyModel->generateId();

		$success = $this->jobApplyModel->create(
			$maHS,
			$maCV,
			$maTinTuyenDung,
			$coverLetter
		);

		if ($success) {
			echo "
				<script>
					alert('Ứng tuyển thành công!');
					window.location.href =
						'/JobCV/index.php?route=jobs/detail&maTinTuyenDung={$maTinTuyenDung}';
				</script>
			";
		} else {
			echo "
				<script>
					alert('Có lỗi xảy ra khi gửi hồ sơ.');
					window.history.back();
				</script>
			";
		}
	}

	/**
	 * Hiển thị danh sách các tin tuyển dụng mà ứng viên đang đăng nhập đã ứng tuyển.
	 *
	 * @return void
	 */
	public function appliedJobs() {
		if (!isset($_SESSION['user_id'])) {
			header("Location: /JobCV/index.php?route=auth/login");
			exit;
		}

		$maUngVien = $_SESSION['user_id'];

		$applications =
			$this->jobApplyModel
			->getApplicationsByCandidate($maUngVien);

		$totalApplications = count($applications);

		$interviewApplications = 0;

		foreach ($applications as $application) {
			if ($application['TrangThai'] === 'HenPhongVan') {
				$interviewApplications++;
			}
		}

		$userName = $_SESSION['user_name'] ?? 'Ứng viên';

		$baseUrl = '/JobCV';

		require_once __DIR__ .
			'/../views/page/candidate/applied-jobs.php';
	}
}