<?php

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

            $_SESSION['user_id'] = $user['MaUser'];
            $_SESSION['user_email'] = $user['Email'];
            $_SESSION['user_name'] = $user['HoTen'];
            $_SESSION['user_role'] = $user['Role'];

            header('Location: /JobCV/?route=home');
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
