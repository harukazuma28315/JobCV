/**
 * reset-password.js
 * Quản lý tương tác giao diện và gửi yêu cầu AJAX đổi mật khẩu mới trong luồng Khôi phục mật khẩu.
 */

document.addEventListener('DOMContentLoaded', () => {
    const baseUrl = window.appConfig?.baseUrl || '/JobCV';
    
    const resetForm = document.getElementById('resetForm');
    const resetMessage = document.getElementById('resetMessage');
    const btnResetPassword = document.getElementById('btnResetPassword');

    if (!resetForm || !btnResetPassword) return;

    /**
     * Hiển thị thông báo phản hồi từ phía server hoặc phản hồi validation trực tiếp trên giao diện.
     */
    function showResetMessage(message, type = 'info') {
        if (!resetMessage) return;
        resetMessage.className = `small mt-2 text-${type === 'success' ? 'success' : type === 'danger' ? 'danger' : 'secondary'}`;
        resetMessage.innerText = message;
    }

    /**
     * Lắng nghe sự kiện SUBMIT của Form
     */
    resetForm.addEventListener('submit', function (e) {
        // 1. Luôn ngăn hành vi submit mặc định của trình duyệt
        e.preventDefault();

        // 2. Kích hoạt Validation mặc định của HTML5 (minlength, maxlength, pattern...)
        if (!resetForm.checkValidity()) {
            e.stopPropagation();
            resetForm.classList.add('was-validated');
            showResetMessage('Mật khẩu phải từ 6 đến 32 ký tự và không chứa khoảng trắng.', 'danger');
            return;
        }

        const password = document.getElementById('matKhau')?.value || '';
        const confirmPassword = document.getElementById('matKhauConfirm')?.value || '';

        // 3. Kiểm tra mật khẩu nhập lại trùng khớp
        if (password !== confirmPassword) {
            showResetMessage('Mật khẩu nhập lại không trùng khớp!', 'danger');
            return;
        }

        // 4. Nếu hợp lệ -> Tiến hành gửi AJAX
        const formData = new FormData(resetForm);
        
        btnResetPassword.disabled = true;
        btnResetPassword.innerText = 'Đang xử lý...';

        fetch(`${baseUrl}/index.php?route=auth/forgot-password-submit`, { 
            method: 'POST', 
            body: formData ,
            credentials: 'same-origin'
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