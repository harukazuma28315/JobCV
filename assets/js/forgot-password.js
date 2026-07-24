/**
 * forgot-password.js
 * Quản lý tương tác giao diện và gửi các yêu cầu AJAX cho quy trình quên mật khẩu (Gửi OTP, Xác thực OTP).
 */

/**
 * Đăng ký các sự kiện lắng nghe và điều phối luồng xử lý giao diện cho trang Quên mật khẩu.
 */
document.addEventListener('DOMContentLoaded', () => {
    const baseUrl = window.appConfig?.baseUrl || '/JobCV';
    
    const emailInput = document.getElementById('email');
    const otpInput = document.getElementById('otp');
    const btnGetOtp = document.getElementById('btnGetOtp');
    const btnVerifyOtp = document.getElementById('btnVerifyOtp');
    const formMessage = document.getElementById('formMessage');

    if (!btnGetOtp || !btnVerifyOtp) return;

    /**
     * Cập nhật trạng thái kích hoạt và nhãn hiển thị của nút bấm.
     * Áp dụng để khóa thao tác của người dùng trong lúc chờ xử lý AJAX nhằm tránh gửi yêu cầu trùng lặp.
     * 
     * @param {HTMLElement} button - Phần tử button cần thay đổi trạng thái.
     * @param {boolean} disabled - Trạng thái vô hiệu hóa (true: khóa, false: mở).
     * @param {string} text - Nội dung văn bản hiển thị trên nút.
     */
    function setButtonState(button, disabled, text) {
        button.disabled = disabled;
        button.innerText = text;
    }

    /**
     * Hiển thị thông báo phản hồi từ phía server lên giao diện người dùng.
     * 
     * @param {string} message - Nội dung thông báo cần hiển thị.
     * @param {string} type - Loại thông báo ('success', 'danger', hoặc 'info') để thiết định màu sắc tương ứng.
     */
    function showMessage(message, type = 'info') {
        if (!formMessage) return;
        formMessage.className = `small mt-2 text-${type === 'success' ? 'success' : type === 'danger' ? 'danger' : 'secondary'}`;
        formMessage.innerText = message;
    }

    /**
     * Đếm ngược thời gian chờ (cooldown) và vô hiệu hóa nút gửi mã.
     * Tự động ngăn chặn hành vi spam yêu cầu lấy mã OTP liên tục gây quá tải hệ thống.
     * 
     * @param {number} seconds - Số giây cần đếm ngược trước khi cho phép bấm lại.
     */
    function startCooldown(seconds) {
        let remaining = seconds;
        setButtonState(btnGetOtp, true, `Đợi (${remaining}s)`);
        const timer = setInterval(() => {
            remaining--;
            if (remaining <= 0) {
                clearInterval(timer);
                setButtonState(btnGetOtp, false, 'Lấy Mã Xác Thực');
                return;
            }
            setButtonState(btnGetOtp, true, `Đợi (${remaining}s)`);
        }, 1000);
    }

    /**
     * Gửi yêu cầu AJAX khởi tạo và gửi mã OTP khôi phục mật khẩu tới email người dùng.
     */
    function sendForgotOtp() {
        const email = emailInput.value.trim();
        if (!email) {
            showMessage('Vui lòng nhập email trước khi lấy mã.', 'danger');
            return;
        }

        const formData = new FormData();
        formData.append('action', 'send_forgot_otp');
        formData.append('email', email);

        setButtonState(btnGetOtp, true, 'Đang gửi...');
        fetch(`${baseUrl}/index.php?route=auth/forgot-password-submit`, { method: 'POST', body: formData })
            .then(response => response.json())
            .then(data => {
                showMessage(data.message, data.status === 'success' ? 'success' : 'danger');
                if (data.status === 'success' || data.status === 'cooldown') {
                    startCooldown(data.remaining || 60);
                } else {
                    setButtonState(btnGetOtp, false, 'Lấy Mã Xác Thực');
                }
            })
            .catch(() => {
                showMessage('Không thể kết nối tới máy chủ.', 'danger');
                setButtonState(btnGetOtp, false, 'Lấy Mã Xác Thực');
            });
    }

    /**
     * Gửi yêu cầu AJAX xác thực mã OTP nhập vào từ client.
     * Chuyển hướng người dùng sang trang đặt lại mật khẩu nếu mã OTP chính xác và còn thời hạn.
     */
    function verifyForgotOtp() {
        const email = emailInput.value.trim();
        const otp = otpInput.value.trim();

        if (!email || !otp) {
            showMessage('Vui lòng nhập đầy đủ email và mã OTP.', 'danger');
            return;
        }

        const formData = new FormData();
        formData.append('action', 'verify_otp');
        formData.append('email', email);
        formData.append('otp', otp);

        setButtonState(btnVerifyOtp, true, 'Đang xác nhận...');
        fetch(`${baseUrl}/index.php?route=auth/forgot-password-submit`, { method: 'POST', body: formData })
            .then(response => response.json())
            .then(data => {
                showMessage(data.message, data.status === 'success' ? 'success' : 'danger');
                if (data.status === 'success') {
                    window.location.href = `${baseUrl}/index.php?route=auth/reset-password&email=${encodeURIComponent(email)}`;
                } else {
                    setButtonState(btnVerifyOtp, false, 'Xác Nhận');
                }
            })
            .catch(() => {
                showMessage('Không thể kết nối tới máy chủ.', 'danger');
                setButtonState(btnVerifyOtp, false, 'Xác Nhận');
            });
    }

    btnGetOtp.addEventListener('click', sendForgotOtp);
    btnVerifyOtp.addEventListener('click', verifyForgotOtp);
});