<?php

require_once __DIR__ . "/../models/CV.php";
require_once __DIR__ . "/../models/Education.php";
require_once __DIR__ . "/../models/Experience.php";
require_once __DIR__ . "/../models/Project.php";
require_once __DIR__ . "/../models/Certificate.php";

/**
 * Service điều phối CV và toàn bộ dữ liệu con của CV (học vấn, kinh nghiệm,
 * dự án, chứng chỉ). Gom 5 model (CV, Education, Experience, Project,
 * Certificate) vào 1 điểm truy cập duy nhất, để CVController chỉ cần phụ
 * thuộc vào service này thay vì tự new() cả 5 model (áp dụng Single
 * Responsibility Principle: CVController chỉ lo điều phối request/response,
 * còn việc phối hợp dữ liệu CV giữa các bảng do CvService đảm nhiệm).
 */
class CvService {
	private $cvModel;
	private $hocVanModel;
	private $kinhNghiemModel;
	private $duAnModel;
	private $chungChiModel;

	public function __construct() {
		$this->cvModel = new CV();
		$this->hocVanModel = new Education();
		$this->kinhNghiemModel = new Experience();
		$this->duAnModel = new Project();
		$this->chungChiModel = new Certificate();
	}

	/**
	 * Lấy toàn bộ danh sách CV.
	 *
	 * @return mysqli_result
	 */
	public function getAll() {
		return $this->cvModel->getAll();
	}

	/**
	 * Lấy 1 CV theo mã.
	 *
	 * @param string $maCV
	 * @return array|null
	 */
	public function getById($maCV) {
		return $this->cvModel->getById($maCV);
	}

	/**
	 * Lấy toàn bộ CV thuộc về một ứng viên.
	 *
	 * @param string $maUngVien
	 * @return array
	 */
	public function getByCandidate($maUngVien) {
		return $this->cvModel->getByCandidate($maUngVien);
	}

	/**
	 * Tạo mới CV (chỉ bảng cv chính, không kèm dữ liệu con).
	 *
	 * @param array $cvData
	 * @return bool
	 */
	public function create(array $cvData) {
		return $this->cvModel->create($cvData);
	}

	/**
	 * Cập nhật CV (chỉ bảng cv chính, không kèm dữ liệu con).
	 *
	 * @param array $cvData
	 * @return bool
	 */
	public function update(array $cvData) {
		return $this->cvModel->update($cvData);
	}

	/**
	 * Xóa CV.
	 *
	 * @param string $maCV
	 * @return bool
	 */
	public function delete($maCV) {
		return $this->cvModel->delete($maCV);
	}

	/**
	 * Cập nhật thông tin file CV đính kèm.
	 *
	 * @param array $fileData
	 * @return bool
	 */
	public function updateFile(array $fileData) {
		return $this->cvModel->updateFile($fileData);
	}

	/**
	 * Lấy toàn bộ thông tin chi tiết của 1 CV, gồm CV chính và 4 danh sách
	 * dữ liệu con (học vấn, kinh nghiệm, dự án, chứng chỉ).
	 *
	 * @param string $maCV
	 * @return array
	 */
	public function getFullDetail($maCV) {
		return [
			"cv" => $this->cvModel->getById($maCV),
			"hocVan" => $this->hocVanModel->getByCV($maCV),
			"kinhNghiem" => $this->kinhNghiemModel->getByCV($maCV),
			"duAn" => $this->duAnModel->getByCV($maCV),
			"chungChi" => $this->chungChiModel->getByCV($maCV)
		];
	}

	/**
	 * Tạo mới CV cùng toàn bộ dữ liệu con (học vấn, kinh nghiệm, dự án,
	 * chứng chỉ) trong 1 lần gọi.
	 *
	 * @param array $cvData
	 * @param array $hocVanList
	 * @param array $kinhNghiemList
	 * @param array $duAnList
	 * @param array $chungChiList
	 * @return bool
	 */
	public function createFullCv(
		array $cvData,
		array $hocVanList,
		array $kinhNghiemList,
		array $duAnList,
		array $chungChiList
	) {
		$result = $this->cvModel->create($cvData);

		if (!$result) {
			return false;
		}

		foreach ($hocVanList as $hocVan) {
			$this->hocVanModel->create($hocVan);
		}

		foreach ($kinhNghiemList as $kinhNghiem) {
			$this->kinhNghiemModel->create($kinhNghiem);
		}

		foreach ($duAnList as $duAn) {
			$this->duAnModel->create($duAn);
		}

		foreach ($chungChiList as $chungChi) {
			$this->chungChiModel->create($chungChi);
		}

		return true;
	}

	/**
	 * Cập nhật CV cùng toàn bộ dữ liệu con (học vấn, kinh nghiệm, dự án,
	 * chứng chỉ) trong 1 lần gọi.
	 *
	 * @param array $cvData
	 * @param array $hocVanList
	 * @param array $kinhNghiemList
	 * @param array $duAnList
	 * @param array $chungChiList
	 * @return bool
	 */
	public function updateFullCv(
		array $cvData,
		array $hocVanList,
		array $kinhNghiemList,
		array $duAnList,
		array $chungChiList
	) {
		$result = $this->cvModel->update($cvData);

		if (!$result) {
			return false;
		}

		foreach ($hocVanList as $hocVan) {
			$this->hocVanModel->update($hocVan);
		}

		foreach ($kinhNghiemList as $kinhNghiem) {
			$this->kinhNghiemModel->update($kinhNghiem);
		}

		foreach ($duAnList as $duAn) {
			$this->duAnModel->update($duAn);
		}

		foreach ($chungChiList as $chungChi) {
			$this->chungChiModel->update($chungChi);
		}

		return true;
	}

	/**
	 * Đồng bộ danh sách học vấn của 1 CV: mục có sẵn mã (đã tồn tại) thì cập
	 * nhật, mục không có mã (mới thêm trên form) thì tạo mới, mục cũ trong DB
	 * mà không còn xuất hiện trong danh sách gửi lên (người dùng đã bấm xóa
	 * trên form) thì xóa khỏi DB. Cách này cho phép form dùng khối lặp lại
	 * (+ Thêm / x Xóa) mà không cần biết trước người dùng sẽ nhập bao nhiêu mục.
	 *
	 * @param string $maCV
	 * @param array  $submittedList Danh sách học vấn gửi từ form (mỗi phần tử
	 *                               có thể có hoặc không có khóa 'maHocVan')
	 * @return void
	 */
	public function syncEducation($maCV, array $submittedList) {
		$existingIds = [];
		$existingResult = $this->hocVanModel->getByCV($maCV);
		while ($row = $existingResult->fetch_assoc()) {
			$existingIds[] = $row['MaHocVan'];
		}

		$keptIds = [];

		foreach ($submittedList as $item) {
			$maHocVan = trim($item['maHocVan'] ?? '');

			$data = [
				'maHocVan' => $maHocVan,
				'maCV' => $maCV,
				'tenTruong' => trim($item['tenTruong'] ?? ''),
				'chuyenNganh' => trim($item['chuyenNganh'] ?? ''),
				'gpa' => trim($item['gpa'] ?? ''),
				'hocLuc' => trim($item['hocLuc'] ?? ''),
				'namBatDau' => $item['namBatDau'] ?? null,
				'namKetThuc' => $item['namKetThuc'] ?? null
			];

			if ($maHocVan !== '' && in_array($maHocVan, $existingIds)) {
				$this->hocVanModel->update($data);
				$keptIds[] = $maHocVan;
			} else {
				$data['maHocVan'] = 'HV' . uniqid();
				$this->hocVanModel->create($data);
			}
		}

		foreach (array_diff($existingIds, $keptIds) as $idToDelete) {
			$this->hocVanModel->delete($idToDelete);
		}
	}

	/**
	 * Đồng bộ danh sách kinh nghiệm làm việc của 1 CV (cùng cơ chế như
	 * syncEducation() ở trên).
	 *
	 * @param string $maCV
	 * @param array  $submittedList
	 * @return void
	 */
	public function syncExperience($maCV, array $submittedList) {
		$existingIds = [];
		$existingResult = $this->kinhNghiemModel->getByCV($maCV);
		while ($row = $existingResult->fetch_assoc()) {
			$existingIds[] = $row['MaCongViec'];
		}

		$keptIds = [];

		foreach ($submittedList as $item) {
			$maCongViec = trim($item['maCongViec'] ?? '');

			$data = [
				'maCongViec' => $maCongViec,
				'maCV' => $maCV,
				'tenCongTy' => trim($item['tenCongTy'] ?? ''),
				'viTri' => trim($item['viTri'] ?? ''),
				'thoiGianLamViec' => trim($item['thoiGianLamViec'] ?? ''),
				'moTa' => trim($item['moTa'] ?? '')
			];

			if ($maCongViec !== '' && in_array($maCongViec, $existingIds)) {
				$this->kinhNghiemModel->update($data);
				$keptIds[] = $maCongViec;
			} else {
				$data['maCongViec'] = 'KN' . uniqid();
				$this->kinhNghiemModel->create($data);
			}
		}

		foreach (array_diff($existingIds, $keptIds) as $idToDelete) {
			$this->kinhNghiemModel->delete($idToDelete);
		}
	}

	/**
	 * Đồng bộ danh sách dự án của 1 CV (cùng cơ chế như syncEducation() ở trên).
	 *
	 * @param string $maCV
	 * @param array  $submittedList
	 * @return void
	 */
	public function syncProject($maCV, array $submittedList) {
		$existingIds = [];
		$existingResult = $this->duAnModel->getByCV($maCV);
		while ($row = $existingResult->fetch_assoc()) {
			$existingIds[] = $row['MaDuAn'];
		}

		$keptIds = [];

		foreach ($submittedList as $item) {
			$maDuAn = trim($item['maDuAn'] ?? '');

			$data = [
				'maDuAn' => $maDuAn,
				'maCV' => $maCV,
				'tenDuAn' => trim($item['tenDuAn'] ?? ''),
				'viTri' => trim($item['viTri'] ?? ''),
				'soLuongThanhVien' => (int) ($item['soLuongThanhVien'] ?? 0),
				'congNgheSuDung' => trim($item['congNgheSuDung'] ?? ''),
				'moTa' => trim($item['moTa'] ?? '')
			];

			if ($maDuAn !== '' && in_array($maDuAn, $existingIds)) {
				$this->duAnModel->update($data);
				$keptIds[] = $maDuAn;
			} else {
				$data['maDuAn'] = 'DA' . uniqid();
				$this->duAnModel->create($data);
			}
		}

		foreach (array_diff($existingIds, $keptIds) as $idToDelete) {
			$this->duAnModel->delete($idToDelete);
		}
	}

	/**
	 * Đồng bộ danh sách chứng chỉ của 1 CV (cùng cơ chế như syncEducation() ở trên).
	 *
	 * @param string $maCV
	 * @param array  $submittedList
	 * @return void
	 */
	public function syncCertificate($maCV, array $submittedList) {
		$existingIds = [];
		$existingResult = $this->chungChiModel->getByCV($maCV);
		while ($row = $existingResult->fetch_assoc()) {
			$existingIds[] = $row['MaChungChi'];
		}

		$keptIds = [];

		foreach ($submittedList as $item) {
			$maChungChi = trim($item['maChungChi'] ?? '');

			$data = [
				'maChungChi' => $maChungChi,
				'maCV' => $maCV,
				'tenChungChi' => trim($item['tenChungChi'] ?? ''),
				'toChucCap' => trim($item['toChucCap'] ?? ''),
				'ngayCap' => $item['ngayCap'] ?: null,
				'ngayHetHan' => $item['ngayHetHan'] ?: null,
				'maSoChungChi' => trim($item['maSoChungChi'] ?? ''),
				'duongLinkChungChi' => trim($item['duongLinkChungChi'] ?? '')
			];

			if ($maChungChi !== '' && in_array($maChungChi, $existingIds)) {
				$this->chungChiModel->update($data);
				$keptIds[] = $maChungChi;
			} else {
				$data['maChungChi'] = 'CC' . uniqid();
				$this->chungChiModel->create($data);
			}
		}

		foreach (array_diff($existingIds, $keptIds) as $idToDelete) {
			$this->chungChiModel->delete($idToDelete);
		}
	}
}
