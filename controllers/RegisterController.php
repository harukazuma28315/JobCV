<?php
// Nhúng kết nối DB và Model
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/UserModel.php';

class RegisterController {
    private $userModel;
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->userModel = new UserModel($conn);
    }

    public function handleRegister() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            
            // 1. Lấy và làm sạch dữ liệu
            $taiKhoan = trim($_POST['TaiKhoan']);
            $matKhau = $_POST['MatKhau'];
            $hoTen = trim($_POST['HoTen']);
            $ngaySinh = !empty($_POST['NgaySinh']) ? $_POST['NgaySinh'] : NULL;
            $gioiTinh = isset($_POST['GioiTinh']) ? intval($_POST['GioiTinh']) : NULL;
            $email = !empty($_POST['Email']) ? trim($_POST['Email']) : NULL;
            $sdt = !empty($_POST['SDT']) ? trim($_POST['SDT']) : NULL;
            $diaChi = !empty($_POST['DiaChi']) ? trim($_POST['DiaChi']) : NULL;

            // 2. Tạo dữ liệu tự động bổ sung
            $maUser = "USR" . time() . rand(10, 99);
            $role = 0; 
            $matKhauHashed = password_hash($matKhau, PASSWORD_DEFAULT);

            // 3. Gọi Model để kiểm tra trùng lặp
            if ($this->userModel->isUsernameExists($taiKhoan)) {
                echo "<script>alert('Tài khoản này đã tồn tại! Vui lòng chọn tên khác.'); window.history.back();</script>";
                return;
            }

            // Gom nhóm dữ liệu chuẩn bị gửi qua Model
            $userData = [
                'maUser' => $maUser,
                'taiKhoan' => $taiKhoan,
                'matKhauHashed' => $matKhauHashed,
                'role' => $role,
                'hoTen' => $hoTen,
                'ngaySinh' => $ngaySinh,
                'gioiTinh' => $gioiTinh,
                'email' => $email,
                'sdt' => $sdt,
                'diaChi' => $diaChi
            ];

            // 4. Gọi Model để lưu vào Database
            if ($this->userModel->registerUser($userData)) {
                echo "<script>alert('Đăng ký tài khoản thành công!'); window.location.href='../views/dang-ky.html';</script>";
            } else {
                echo "<script>alert('Có lỗi xảy ra trong quá trình đăng ký: " . $this->conn->error . "'); window.history.back();</script>";
            }
        }
    }
}

// Khởi chạy Controller (Biến $conn lấy từ file ConnectDatabase.php)
$registerCtrl = new RegisterController($conn);
$registerCtrl->handleRegister();

// Đóng kết nối sau khi xử lý xong
$conn->close();
?>