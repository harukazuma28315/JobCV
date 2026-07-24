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
     * Hiển thị trang tạo CV
     */
    public function createPage()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /JobCV/index.php?route=auth/login');
            exit;
        }

        $maUngVien = $_SESSION['user_id'];

        $cv = $this->cvModel->getByUngVien($maUngVien);

        require_once __DIR__ . '/../views/page/layouts/createcv.php';
    }

    /**
     * Lưu CV nhập thủ công
     */
    public function createSubmit($post)
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /JobCV/index.php?route=auth/login');
            exit;
        }

        $maUngVien = $_SESSION['user_id'];

        $cvData = [

            'maCV' => 'CV' . time(),

            'maUngVien' => $maUngVien,

            'tieuDe' => trim($post['tieuDe'] ?? ''),

            'kyNang' => trim($post['kyNang'] ?? ''),

            'soThich' => trim($post['soThich'] ?? ''),

            'mucTieu' => trim($post['mucTieu'] ?? ''),

            'trangThai' => 1,

            'viTriMongMuon' => trim($post['viTriMongMuon'] ?? ''),

            'email' => trim($post['email'] ?? ''),

            'sdt' => trim($post['sdt'] ?? '')

        ];


        $success = $this->cvModel->create($cvData);

        if ($success) {

            echo "
                <script>

                    alert('Tạo CV thành công!');

                    window.location.href =
                        '/JobCV/index.php?route=cv/create';

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
    public function updateSubmit($post)
    {
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
        $cv = $this->cvModel->getById($maCV);

        if (!$cv) {
            exit('Không tìm thấy CV.');
        }

        // Kiểm tra CV có đúng của user đang đăng nhập không
        if ($cv['MaUngVien'] != $_SESSION['user_id']) {
            exit('Bạn không có quyền chỉnh sửa CV này.');
        }

        $cvData = [

            'maCV' => $maCV,

            'maUngVien' => $_SESSION['user_id'],

            'tieuDe' => trim($post['tieuDe'] ?? ''),

            'kyNang' => trim($post['kyNang'] ?? ''),

            'soThich' => trim($post['soThich'] ?? ''),

            'mucTieu' => trim($post['mucTieu'] ?? ''),

            'trangThai' => 1,

            'viTriMongMuon' => trim($post['viTriMongMuon'] ?? ''),

            'email' => trim($post['email'] ?? ''),

            'sdt' => trim($post['sdt'] ?? '')

        ];


        $success = $this->cvModel->update($cvData);

        if ($success) {

            echo "
                <script>

                    alert('Cập nhật CV thành công!');

                    window.location.href =
                        '/JobCV/index.php?route=cv/create';

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

    public function changeFile($maCV, $file)
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /JobCV/index.php?route=auth/login');
            exit;
        }

        if (!$file || $file['error'] !== 0) {
            exit('File không hợp lệ.');
        }

        $cv = $this->cvModel->getById($maCV);

        if (!$cv) {
            exit('Không tìm thấy CV.');
        }

        // Kiểm tra CV có đúng user đang đăng nhập không
        if ($cv['MaUngVien'] != $_SESSION['user_id']) {
            exit('Bạn không có quyền chỉnh sửa CV này.');
        }

        $extension = strtolower(
            pathinfo($file['name'], PATHINFO_EXTENSION)
        );

        $allowedExtensions = [
            'pdf',
            'doc',
            'docx'
        ];

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

        $success = $this->cvModel->updateFile($fileData);

        if ($success) {


            if ($oldPath && file_exists($oldPath)) {
                unlink($oldPath);
            }

            echo "
                <script>

                    alert('Thay đổi file CV thành công!');

                    window.location.href =
                        '/JobCV/index.php?route=cv/create';

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
    public function uploadSubmit($file)
    {
        if (!isset($_SESSION['user_id'])) {

            header(
                'Location: /JobCV/index.php?route=auth/login'
            );

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


        $extension = strtolower(
            pathinfo(
                $file['name'],
                PATHINFO_EXTENSION
            )
        );


        $allowed = [

            'pdf',
            'doc',
            'docx'

        ];


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

            'tieuDe' => 'CV cá nhân - ' . $_SESSION['user_name'],

            'kyNang' => '',

            'soThich' => '',

            'mucTieu' => '',

            'trangThai' => 1,

            'viTriMongMuon' => '',

            'email' => $_SESSION['user_email'],

            'sdt' => ''

        ];


        $created = $this->cvModel->create($cvData);


        if (!$created) {

            die('Không thể tạo CV.');

        }


        $success = $this->uploadCV(

            $maCV,

            $file

        );


        if ($success) {

            echo "
                <script>

                    alert('Tải CV lên thành công!');

                    window.location.href =
                        '/JobCV/index.php?route=cv/create';

                </script>
            ";

        } else {

            $this->cvModel->delete($maCV);


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