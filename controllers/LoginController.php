<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/UserModel.php';

class LoginController
{
    private $userModel;
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->userModel = new UserModel($conn);
    }

    /**
     * Hiển thị trang đăng nhập
     */
    public function showLogin()
    {
        $content = __DIR__ . '/../views/page/auth/login-content.php';

        require_once __DIR__ . '/../views/page/layouts/main.php';
    }

    public function showForgotPassword()
    {
        require_once __DIR__ . '/../views/page/auth/forgot-password.php';
    }

    public function showResetPassword()
    {
        require_once __DIR__ . '/../views/page/auth/reset-password.php';
    }

    /**
     * Xử lý dữ liệu đăng nhập
     */
    public function handleLogin()
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            return;
        }

        $email = trim($_POST['Email'] ?? '');
        $matKhau = $_POST['MatKhau'] ?? '';

        if (!$this->userModel->isValidEmail($email)) {
            echo "<script>
                    alert('Định dạng địa chỉ Email không hợp lệ!');
                    window.history.back();
                  </script>";
            return;
        }

        $user = $this->userModel->getUserByEmail($email);

        if ($user && password_verify($matKhau, $user['MatKhau'])) {

                // ===== CHẶN TÀI KHOẢN BỊ KHÓA =====
            $isLocked = !empty($user['IsLocked']) || (($user['TrangThai'] ?? '') === 'BiKhoa');
            if ($isLocked) {
                echo "<script>
                        alert('Tài khoản của bạn đã bị khóa. Vui lòng liên hệ Admin!');
                        window.history.back();
                    </script>";
                return;
            }

            // (Tuỳ chọn) chặn cả tài khoản chờ duyệt
            // if (($user['TrangThai'] ?? '') === 'ChoDuyet') {
            //     echo "<script>
            //             alert('Tài khoản đang chờ duyệt. Vui lòng đợi Admin xác nhận!');
            //             window.history.back();
            //           </script>";
            //     return;
            // }

            $_SESSION['user_id'] = $user['MaUser'];
            $_SESSION['user_email'] = $user['Email'];
            $_SESSION['user_name'] = $user['HoTen'];

            $role = (int)$user['Role'];

            $_SESSION['user_role'] = $user['Role'];
            $_SESSION['role'] = $user['Role']; // Đồng bộ với AuthHelper::requireRole() đang đọc key 'role'

            // ===== Redirect theo quyền =====
            if ($role === ROLE_ADMIN) {          // 2 = Admin
                header('Location: /JobCV/index.php?route=admin/dashboard');
            } else {
                header('Location: /JobCV/index.php?route=home');
            }
            exit;

        } else {

            echo "<script>
                    alert('Email hoặc mật khẩu không chính xác!');
                    window.history.back();
                  </script>";
        }
    }
}
?>
