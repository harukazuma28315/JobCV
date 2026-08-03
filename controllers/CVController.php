<?php

require_once __DIR__ . "/../services/CvService.php";

/**
 * Controller quản lý CV ứng viên.
 */
class CVController {
	private $cvService;

	public function __construct() {
		$this->cvService = new CvService();
	}

	/**
	 * Lấy toàn bộ danh sách CV.
	 *
	 * @return mysqli_result
	 */
	public function index() {
		return $this->cvService->getAll();
	}

	/**
	 * Lấy toàn bộ thông tin chi tiết CV.
	 *
	 * @param string $maCV
	 * @return array
	 */
	public function detail($maCV) {
		return $this->cvService->getFullDetail($maCV);
	}

	/**
	 * Tạo mới CV.
	 *
	 * @param array $cvData
	 * @return bool
	 */
	public function create(array $cvData) {
		if (!$this->validateCVData($cvData)) {
			return false;
		}

		return $this->cvService->create($cvData);
	}

	/**
	 * Hiển thị trang tạo CV
	 */
	public function createPage() {
		if (!isset($_SESSION['user_id'])) {
			header('Location: /JobCV/index.php?route=auth/login');
			exit;
		}

		$maUngVien = $_SESSION['user_id'];

		$cv = $this->cvService->getByCandidate($maUngVien);

		// Nếu đã có CV thì lấy kèm 4 danh sách con để hiển thị và đổ sẵn vào
		// form chỉnh sửa; chưa có CV thì để mảng rỗng cho view không bị lỗi.
		$hocVanList = [];
		$kinhNghiemList = [];
		$duAnList = [];
		$chungChiList = [];

		if ($cv) {
			$fullDetail = $this->cvService->getFullDetail($cv['MaCV']);
			$hocVanList = $fullDetail['hocVan']->fetch_all(MYSQLI_ASSOC);
			$kinhNghiemList = $fullDetail['kinhNghiem']->fetch_all(MYSQLI_ASSOC);
			$duAnList = $fullDetail['duAn']->fetch_all(MYSQLI_ASSOC);
			$chungChiList = $fullDetail['chungChi']->fetch_all(MYSQLI_ASSOC);
		}

		require_once __DIR__ . '/../views/page/candidate/cv-builder.php';
	}

	/**
	 * Lưu CV nhập thủ công
	 */
	public function createSubmit($post) {
		if (!isset($_SESSION['user_id'])) {
			header('Location: /JobCV/index.php?route=auth/login');
			exit;
		}

		$maUngVien = $_SESSION['user_id'];

		// Chuẩn hóa và validate dữ liệu phía server
		$tieuDe = trim(preg_replace('/\s+/', ' ', $post['tieuDe'] ?? ''));
		$viTriMongMuon = trim(preg_replace('/\s+/', ' ', $post['viTriMongMuon'] ?? ''));
		$sdt = trim($post['sdt'] ?? '');
		$email = $_SESSION['user_email'] ?? ''; // Email cố định từ session, không lấy từ $post

		if (empty($tieuDe)) {
			echo "<script>alert('Tiêu đề CV không được bỏ trống!'); window.history.back();</script>";
			exit;
		}

		if (empty($viTriMongMuon)) {
			echo "<script>alert('Vị trí mong muốn không được bỏ trống!'); window.history.back();</script>";
			exit;
		}

		if (!preg_match('/^0[0-9]{9}$/', $sdt)) {
			echo "<script>alert('Số điện thoại phải gồm đúng 10 chữ số và bắt đầu bằng số 0!'); window.history.back();</script>";
			exit;
		}

		$maCV = 'CV' . time();

		$cvData = [
			'maCV' => $maCV,
			'maUngVien' => $maUngVien,
			'tieuDe' => $tieuDe,
			'kyNang' => trim($post['kyNang'] ?? ''),
			'soThich' => trim($post['soThich'] ?? ''),
			'mucTieu' => trim($post['mucTieu'] ?? ''),
			'trangThai' => 1,
			'viTriMongMuon' => $viTriMongMuon,
			'email' => $email,
			'sdt' => $sdt
		];

		$success = $this->cvService->create($cvData);

		if ($success) {
			// CV là mới tạo nên chưa có dữ liệu con nào trong DB: sync sẽ tự
			// tạo mới toàn bộ các mục gửi lên từ form (không có gì để xóa).
			$this->cvService->syncEducation($maCV, $post['hocVan'] ?? []);
			$this->cvService->syncExperience($maCV, $post['kinhNghiem'] ?? []);
			$this->cvService->syncProject($maCV, $post['duAn'] ?? []);
			$this->cvService->syncCertificate($maCV, $post['chungChi'] ?? []);

			echo "
				<script>
					alert('Tạo CV thành công!');
					window.location.href = '/JobCV/index.php?route=cv/create';
				</script>
			";
		} else {
			echo "
				<script>
					alert('Không thể tạo CV.');
					window.history.back();
				</script>
			";
		}
	}

	/**
	 * Xử lý submit form chỉnh sửa CV: cập nhật CV chính và toàn bộ
	 * danh sách con (học vấn, kinh nghiệm, dự án, chứng chỉ).
	 *
	 * @param array $post Dữ liệu từ $_POST của form chỉnh sửa CV
	 * @return void
	 */
	public function updateSubmit($post) {
		if (!isset($_SESSION['user_id'])) {
			header('Location: /JobCV/index.php?route=auth/login');
			exit;
		}

		// Lấy mã CV từ form
		$maCV = trim($post['maCV'] ?? '');

		if (empty($maCV)) {
			exit('Mã CV không hợp lệ.');
		}

		// Lấy CV trong database
		$cv = $this->cvService->getById($maCV);

		if (!$cv) {
			exit('Không tìm thấy CV.');
		}

		// Kiểm tra CV có đúng của user đang đăng nhập không
		if ($cv['MaUngVien'] != $_SESSION['user_id']) {
			exit('Bạn không có quyền chỉnh sửa CV này.');
		}

		// Chuẩn hóa và validate dữ liệu phía server
		$tieuDe = trim(preg_replace('/\s+/', ' ', $post['tieuDe'] ?? ''));
		$viTriMongMuon = trim(preg_replace('/\s+/', ' ', $post['viTriMongMuon'] ?? ''));
		$sdt = trim($post['sdt'] ?? '');
		$email = $_SESSION['user_email'] ?? $cv['Email']; // Email cố định từ session/DB

		if (empty($tieuDe)) {
			echo "<script>alert('Tiêu đề CV không được bỏ trống!'); window.history.back();</script>";
			exit;
		}

		if (empty($viTriMongMuon)) {
			echo "<script>alert('Vị trí mong muốn không được bỏ trống!'); window.history.back();</script>";
			exit;
		}

		if (!preg_match('/^0[0-9]{9}$/', $sdt)) {
			echo "<script>alert('Số điện thoại phải gồm đúng 10 chữ số và bắt đầu bằng số 0!'); window.history.back();</script>";
			exit;
		}

		$cvData = [
			'maCV' => $maCV,
			'maUngVien' => $_SESSION['user_id'],
			'tieuDe' => $tieuDe,
			'kyNang' => trim($post['kyNang'] ?? ''),
			'soThich' => trim($post['soThich'] ?? ''),
			'mucTieu' => trim($post['mucTieu'] ?? ''),
			'trangThai' => 1,
			'viTriMongMuon' => $viTriMongMuon,
			'email' => $email,
			'sdt' => $sdt
		];

		$success = $this->cvService->update($cvData);

		if ($success) {
			// Đồng bộ 4 danh sách con: mục có mã cũ -> cập nhật, mục mới thêm
			// trên form -> tạo mới, mục cũ bị xóa trên form -> xóa khỏi DB.
			$this->cvService->syncEducation($maCV, $post['hocVan'] ?? []);
			$this->cvService->syncExperience($maCV, $post['kinhNghiem'] ?? []);
			$this->cvService->syncProject($maCV, $post['duAn'] ?? []);
			$this->cvService->syncCertificate($maCV, $post['chungChi'] ?? []);

			echo "
				<script>
					alert('Cập nhật CV thành công!');
					window.location.href = '/JobCV/index.php?route=cv/create';
				</script>
			";
		} else {
			echo "
				<script>
					alert('Không thể cập nhật CV.');
					window.history.back();
				</script>
			";
		}
	}

	/**
	 * Thay thế file CV đính kèm (upload mới) cho một CV đã tồn tại.
	 *
	 * @param string $maCV Mã CV cần đổi file
	 * @param array  $file Dữ liệu file từ $_FILES
	 * @return void
	 */
	public function changeFile($maCV, $file) {
		if (!isset($_SESSION['user_id'])) {
			header('Location: /JobCV/index.php?route=auth/login');
			exit;
		}

		if (!$file || $file['error'] !== 0) {
			exit('File không hợp lệ.');
		}

		$cv = $this->cvService->getById($maCV);

		if (!$cv) {
			exit('Không tìm thấy CV.');
		}

		// Kiểm tra CV có đúng user đang đăng nhập không
		if ($cv['MaUngVien'] != $_SESSION['user_id']) {
			exit('Bạn không có quyền chỉnh sửa CV này.');
		}

		$extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

		$allowedExtensions = ['pdf', 'doc', 'docx'];

		if (!in_array($extension, $allowedExtensions)) {
			exit('Chỉ hỗ trợ file PDF, DOC hoặc DOCX.');
		}

		$oldPath = null;
		if (!empty($cv['DuongDanFileCV'])) {
			$oldPath = __DIR__ . '/../' . $cv['DuongDanFileCV'];
		}

		$fileName = $maCV . '_' . time() . '.' . $extension;
		$uploadPath = __DIR__ . '/../uploads/' . $fileName;

		if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
			exit('Không thể upload file mới.');
		}

		$fileData = [
			'maCV' => $maCV,
			'tenFileCV' => $fileName,
			'duongDanFileCV' => 'uploads/' . $fileName,
			'loaiFile' => $extension
		];

		$success = $this->cvService->updateFile($fileData);

		if ($success) {
			if ($oldPath && file_exists($oldPath)) {
				unlink($oldPath);
			}

			echo "
				<script>
					alert('Thay đổi file CV thành công!');
					window.location.href = '/JobCV/index.php?route=cv/create';
				</script>
			";
		} else {
			if (file_exists($uploadPath)) {
				unlink($uploadPath);
			}

			echo "
				<script>
					alert('Không thể cập nhật file CV.');
					window.history.back();
				</script>
			";
		}
	}

	/**
	 * Upload CV cá nhân
	 */
	public function uploadSubmit($file) {
		if (!isset($_SESSION['user_id'])) {
			header('Location: /JobCV/index.php?route=auth/login');
			exit;
		}

		if (!$file || $file['error'] !== 0) {
			echo "
				<script>
					alert('File upload không hợp lệ.');
					window.history.back();
				</script>
			";
			exit;
		}

		$extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
		// Giới hạn định dạng cho phép để tránh upload file thực thi (php, exe...) lên server
		$allowed = ['pdf', 'doc', 'docx'];

		if (!in_array($extension, $allowed)) {
			echo "
				<script>
					alert('Chỉ hỗ trợ file PDF, DOC hoặc DOCX.');
					window.history.back();
				</script>
			";
			exit;
		}

		$maCV = 'CV' . time();

		$cvData = [
			'maCV' => $maCV,
			'maUngVien' => $_SESSION['user_id'],
			'tieuDe' => 'CV cá nhân - ' . ($_SESSION['user_name'] ?? 'Ứng viên'),
			'kyNang' => '',
			'soThich' => '',
			'mucTieu' => '',
			'trangThai' => 1,
			'viTriMongMuon' => '',
			'email' => $_SESSION['user_email'] ?? '',
			'sdt' => ''
		];

		$created = $this->cvService->create($cvData);

		if (!$created) {
			die('Không thể tạo CV.');
		}

		$success = $this->uploadCV($maCV, $file);

		if ($success) {
			echo "
				<script>
					alert('Tải CV lên thành công!');
					window.location.href = '/JobCV/index.php?route=cv/create';
				</script>
			";
		} else {
			// Upload file thất bại: xóa luôn bản ghi CV vừa tạo để tránh để lại
			// CV rỗng (không có file đính kèm) trong hệ thống.
			$this->cvService->delete($maCV);

			echo "
				<script>
					alert('Không thể tải file lên.');
					window.history.back();
				</script>
			";
		}
	}

	/**
	 * Cập nhật CV.
	 *
	 * @param array $cvData
	 * @return bool
	 */
	public function update(array $cvData) {
		if (!$this->validateCVData($cvData)) {
			return false;
		}

		return $this->cvService->update($cvData);
	}

	/**
	 * Xóa CV.
	 *
	 * @param string $maCV
	 * @return bool
	 */
	public function delete($maCV) {
		return $this->cvService->delete($maCV);
	}

	/**
	 * Tạo đầy đủ hồ sơ CV.
	 *
	 * @param array $cvData
	 * @param array $hocVanList
	 * @param array $kinhNghiemList
	 * @param array $duAnList
	 * @param array $chungChiList
	 * @return bool
	 */
	public function createFullCV(
		array $cvData,
		array $hocVanList,
		array $kinhNghiemList,
		array $duAnList,
		array $chungChiList
	) {
		return $this->cvService->createFullCv($cvData, $hocVanList, $kinhNghiemList, $duAnList, $chungChiList);
	}

	/**
	 * Cập nhật toàn bộ hồ sơ CV.
	 *
	 * @param array $cvData
	 * @param array $hocVanList
	 * @param array $kinhNghiemList
	 * @param array $duAnList
	 * @param array $chungChiList
	 * @return bool
	 */
	public function updateFullCV(
		array $cvData,
		array $hocVanList,
		array $kinhNghiemList,
		array $duAnList,
		array $chungChiList
	) {
		return $this->cvService->updateFullCv($cvData, $hocVanList, $kinhNghiemList, $duAnList, $chungChiList);
	}

	/**
	 * Upload file CV.
	 *
	 * @param string $maCV
	 * @param array $file
	 * @return bool
	 */
	public function uploadCV($maCV, $file) {
		if ($file["error"] != 0) {
			return false;
		}

		$extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));

		$allowExtensions = ["pdf", "doc", "docx"];

		if (!in_array($extension, $allowExtensions)) {
			return false;
		}

		$fileName = $maCV . "." . $extension;

		$uploadPath = __DIR__ . "/../uploads/" . $fileName;

		if (!move_uploaded_file($file["tmp_name"], $uploadPath)) {
			return false;
		}

		$fileData = [
			"maCV" => $maCV,
			"tenFileCV" => $file["name"],
			"duongDanFileCV" => "uploads/" . $fileName,
			"loaiFile" => $extension
		];

		return $this->cvService->updateFile($fileData);
	}

	/**
	 * Lấy thông tin file CV đã upload.
	 *
	 * @param string $maCV
	 * @return array|null
	 */
	public function getUploadedFile($maCV) {
		$cv = $this->cvService->getById($maCV);

		if (!$cv) {
			return null;
		}

		return [
			"tenFile" => $cv["TenFileCV"],
			"duongDan" => $cv["DuongDanFileCV"],
			"loaiFile" => $cv["LoaiFile"]
		];
	}

	/**
	 * Tải file CV theo mã CV.
	 *
	 * @param string $maCV
	 * @return void
	 */
	public function downloadCV($maCV) {
		$cv = $this->cvService->getById($maCV);

		if (!$cv) {
			exit("Không tìm thấy CV.");
		}

		if (empty($cv["DuongDanFileCV"])) {
			exit("CV chưa có file.");
		}

		$filePath = __DIR__ . "/../" . $cv["DuongDanFileCV"];

		if (!file_exists($filePath)) {
			exit("File không tồn tại.");
		}

		// Ép trình duyệt tải file về thay vì mở trực tiếp trong tab
		// (vì file có thể là PDF/DOC mà trình duyệt thường tự render).
		header("Content-Description: File Transfer");
		header("Content-Type: application/octet-stream");
		header("Content-Disposition: attachment; filename=\"" . basename($filePath) . "\"");
		header("Content-Length: " . filesize($filePath));

		readfile($filePath);

		exit();
	}

	/**
	 * Xóa file CV đã upload.
	 *
	 * @param string $maCV
	 * @return bool
	 */
	public function deleteUploadedCV($maCV) {
		$cv = $this->cvService->getById($maCV);

		if (!$cv) {
			return false;
		}

		if (!empty($cv["DuongDanFileCV"])) {
			$filePath = __DIR__ . "/../" . $cv["DuongDanFileCV"];

			if (file_exists($filePath)) {
				unlink($filePath);
			}
		}

		$fileData = [
			"maCV" => $maCV,
			"tenFileCV" => "",
			"duongDanFileCV" => "",
			"loaiFile" => ""
		];

		return $this->cvService->updateFile($fileData);
	}

	/**
	 * Kiểm tra dữ liệu CV.
	 *
	 * @param array $cvData
	 * @return bool
	 */
	private function validateCVData(array $cvData) {
		if (empty(trim($cvData["tieuDe"]))) {
			return false;
		}

		if (!filter_var($cvData["email"], FILTER_VALIDATE_EMAIL)) {
			return false;
		}

		if (!preg_match("/^0[0-9]{9}$/", $cvData["sdt"])) {
			return false;
		}

		return true;
	}
}
