/**
 * reset-password.js
 * Quản lý tương tác giao diện và gửi yêu cầu AJAX đổi mật khẩu mới trong luồng Khôi phục mật khẩu.
 */

/**
 * Đăng ký các sự kiện lắng nghe và điều phối luồng xử lý giao diện cho trang Đặt lại mật khẩu.
 */
document.addEventListener('DOMContentLoaded', () => {
    const baseUrl = window.appConfig?.baseUrl || '/JobCV';
    
    const resetForm = document.getElementById('resetForm');
    const resetMessage = document.getElementById('resetMessage');
    const btnResetPassword = document.getElementById('btnResetPassword');

    if (!resetForm || !btnResetPassword) return;

    /**
     * Hiển thị thông báo phản hồi từ phía server hoặc phản hồi validation trực tiếp trên giao diện.
     * 
     * @param {string} message - Nội dung thông báo cần hiển thị cho người dùng.
     * @param {string} type - Loại thông báo ('success', 'danger', hoặc 'info') để chọn định dạng màu tương ứng.
     */
    function showResetMessage(message, type = 'info') {
        if (!resetMessage) return;
        resetMessage.className = `small mt-2 text-${type === 'success' ? 'success' : type === 'danger' ? 'danger' : 'secondary'}`;
        resetMessage.innerText = message;
    }

    /**
     * Lắng nghe sự kiện click nút bấm để gửi yêu cầu đổi mật khẩu mới.
     * Áp dụng kiểm tra độ trùng khớp mật khẩu ở client trước khi tạo AJAX request để tiết kiệm tài nguyên server.
     */
    btnResetPassword.addEventListener('click', () => {
        const password = document.getElementById('matKhau')?.value || '';
        const confirmPassword = document.getElementById('matKhauConfirm')?.value || '';

        if (!password || !confirmPassword) {
            showResetMessage('Vui lòng nhập đầy đủ các trường mật khẩu.', 'danger');
            return;
        }

        if (password !== confirmPassword) {
            showResetMessage('Mật khẩu nhập lại không trùng khớp!', 'danger');
            return;
        }

        const formData = new FormData(resetForm);
        
        btnResetPassword.disabled = true;
        btnResetPassword.innerText = 'Đang xử lý...';

        fetch(`${baseUrl}/index.php?route=auth/forgot-password-submit`, { 
            method: 'POST', 
            body: formData 
        })
        .then(response => response.json())
        .then(data => {
            showResetMessage(data.message, data.status === 'success' ? 'success' : 'danger');
            if (data.status === 'success') {
                // Trì hoãn chuyển hướng ngắn để người dùng kịp đọc thông báo thành công
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