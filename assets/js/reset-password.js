/**
 * reset-password.js
 * Xử lý kiểm tra mật khẩu khớp và gọi API cập nhật mật khẩu mới
 */
document.addEventListener('DOMContentLoaded', () => {
    // Đọc baseUrl từ cấu hình hệ thống (mặc định là /JobCV)
    const baseUrl = window.appConfig?.baseUrl || '/JobCV';
    
    const resetForm = document.getElementById('resetForm');
    const resetMessage = document.getElementById('resetMessage');
    const btnResetPassword = document.getElementById('btnResetPassword');

    if (!resetForm || !btnResetPassword) return;

    function showResetMessage(message, type = 'info') {
        if (!resetMessage) return;
        resetMessage.className = `small mt-2 text-${type === 'success' ? 'success' : type === 'danger' ? 'danger' : 'secondary'}`;
        resetMessage.innerText = message;
    }

    btnResetPassword.addEventListener('click', () => {
        const matKhau = document.getElementById('matKhau')?.value || '';
        const matKhauConfirm = document.getElementById('matKhauConfirm')?.value || '';

        // Validate cơ bản phía Client trước khi gửi request
        if (!matKhau || !matKhauConfirm) {
            showResetMessage('Vui lòng nhập đầy đủ các trường mật khẩu.', 'danger');
            return;
        }

        if (matKhau !== matKhauConfirm) {
            showResetMessage('Mật khẩu nhập lại không trùng khớp!', 'danger');
            return;
        }

        const formData = new FormData(resetForm);
        
        // Khóa nút để tránh spam click
        btnResetPassword.disabled = true;
        btnResetPassword.innerText = 'Đang xử lý...';

        fetch(`${baseUrl}/controllers/ForgotPasswordController.php`, { 
            method: 'POST', 
            body: formData 
        })
        .then(response => response.json())
        .then(data => {
            showResetMessage(data.message, data.status === 'success' ? 'success' : 'danger');
            if (data.status === 'success') {
                setTimeout(() => {
                    window.location.href = `${baseUrl}/index.php?route=auth/login`;
                }, 1200);
            }
        })
        .catch(() => {
            showResetMessage('Không thể kết nối tới máy chủ.', 'danger');
        })
        .finally(() => {
            btnResetPassword.disabled = false;
            btnResetPassword.innerText = 'Lưu Mật Khẩu Mới';
        });
    });
});