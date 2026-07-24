<?php

require_once __DIR__ . '/../models/JobApply.php';
require_once __DIR__ . '/../models/CV.php';
require_once __DIR__ . '/../models/TinTuyenDung.php';

class JobApplyController
{
    private $jobApplyModel;
    private $cvModel;
    private $tinModel;

    public function __construct()
    {
        $this->jobApplyModel = new JobApply();
        $this->cvModel = new CV();
        $this->tinModel = new TinTuyenDung();
    }

    /**
     * Hiển thị trang ứng tuyển
     */
    public function apply($maTinTuyenDung)
    {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user_id'])) {
            header('Location: /JobCV/index.php?route=auth/login');
            exit;
        }

        $maUser = $_SESSION['user_id'];

        // Lấy tin tuyển dụng
        $job = $this->tinModel->getById($maTinTuyenDung);

        if (!$job) {
            http_response_code(404);
            echo "Không tìm thấy tin tuyển dụng.";
            exit;
        }

        // Kiểm tra tin còn mở không
        if ($job['TrangThai'] !== 'DangMo') {
            echo "
                <script>
                    alert('Tin tuyển dụng này đã đóng.');
                    window.history.back();
                </script>
            ";
            exit;
        }

        // Lấy CV của ứng viên
        $cv = $this->cvModel->getByUngVien($maUser);

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

        // Kiểm tra đã ứng tuyển chưa
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

        // Hiển thị trang ứng tuyển
        require_once __DIR__ . '/../views/page/jobs/apply.php';
    }

    /**
     * Xử lý gửi hồ sơ
     */
    public function submit()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /JobCV/index.php');
            exit;
        }

        // Kiểm tra đăng nhập
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

        // Lấy CV ứng viên
        $cv = $this->cvModel->getByUngVien($maUser);

        if (!$cv) {
            die('Bạn chưa có CV.');
        }

        $maCV = $cv['MaCV'];

        // Kiểm tra ứng tuyển trùng
        if (
            $this->jobApplyModel->hasApplied(
                $maCV,
                $maTinTuyenDung
            )
        ) {
            die('Bạn đã ứng tuyển công việc này rồi.');
        }

        // Sinh mã hồ sơ
        $maHS = $this->jobApplyModel->generateId();

        // Lưu hồ sơ
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
}