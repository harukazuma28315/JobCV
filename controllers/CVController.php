<?php

require_once __DIR__ . "/../models/CV.php";
require_once __DIR__ . "/../models/HocVan.php";
require_once __DIR__ . "/../models/KinhNghiem.php";
require_once __DIR__ . "/../models/DuAn.php";
require_once __DIR__ . "/../models/ChungChi.php";

/**
 * Controller quản lý CV ứng viên.
 */
class CVController
{
	private $cvModel;
	private $hocVanModel;
	private $kinhNghiemModel;
	private $duAnModel;
	private $chungChiModel;

	public function __construct()
	{
		$this->cvModel = new CV();
		$this->hocVanModel = new HocVan();
		$this->kinhNghiemModel = new KinhNghiem();
		$this->duAnModel = new DuAn();
		$this->chungChiModel = new ChungChi();
	}

    /**
     * Lấy toàn bộ danh sách CV.
     *
     * @return mysqli_result
     */
    public function index()
    {
        return $this->cvModel->getAll();
    }

    /**
     * Lấy toàn bộ thông tin chi tiết CV.
     *
     * @param string $maCV
     * @return array
     */
    public function detail($maCV)
    {
        return [
            "cv" => $this->cvModel->getById($maCV),
            "hocVan" => $this->hocVanModel->getByCV($maCV),
            "kinhNghiem" => $this->kinhNghiemModel->getByCV($maCV),
            "duAn" => $this->duAnModel->getByCV($maCV),
            "chungChi" => $this->chungChiModel->getByCV($maCV)
        ];
    }
    /**
     * Tạo mới CV.
     *
     * @param array $cvData
     * @return bool
     */
    public function create(array $cvData)
    {
        if (!$this->validateCVData($cvData)) {
            return false;
        }

        return $this->cvModel->create($cvData);
    }
    /**
     * Cập nhật CV.
     *
     * @param array $cvData
     * @return bool
     */
    public function update(array $cvData)
    {
        if (!$this->validateCVData($cvData)) {
            return false;
        }

        return $this->cvModel->update($cvData);
    }
    /**
     * Xóa CV.
     *
     * @param string $maCV
     * @return bool
     */
    public function delete($maCV)
    {
        return $this->cvModel->delete($maCV);
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
    )
    {
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
    )
    {
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
     * Upload file CV.
     *
     * @param string $maCV
     * @param array $file
     * @return bool
     */
    public function uploadCV($maCV, $file)
    {
        if ($file["error"] != 0) {
            return false;
        }

        $extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));

        $allowExtensions = [
            "pdf",
            "doc",
            "docx"
        ];

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

        return $this->cvModel->updateFile($fileData);
    }
    /**
     * Lấy thông tin file CV đã upload.
     *
     * @param string $maCV
     * @return array|null
     */
    public function getUploadedFile($maCV)
    {
        $cv = $this->cvModel->getById($maCV);

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
    public function downloadCV($maCV)
    {
        $cv = $this->cvModel->getById($maCV);

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
    public function deleteUploadedCV($maCV)
    {
        $cv = $this->cvModel->getById($maCV);

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

        return $this->cvModel->updateFile($fileData);
    }
    /**
     * Kiểm tra dữ liệu CV.
     *
     * @param array $cvData
     * @return bool
     */
    private function validateCVData(array $cvData)
    {
        if (empty(trim($cvData["tieuDe"]))) {
            return false;
        }

        if (!filter_var($cvData["email"], FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        if (!preg_match("/^[0-9]{10,11}$/", $cvData["sdt"])) {
            return false;
        }

        return true;
    }
}