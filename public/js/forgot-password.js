/**
 * forgot-password.js
 * Xử lý yêu cầu gửi và xác minh OTP khi quên mật khẩu
 */
document.addEventListener('DOMContentLoaded', () => {
    // Đọc baseUrl từ cấu hình hệ thống (nếu không có thì mặc định là /JobCV)
    const baseUrl = window.appConfig?.baseUrl || '/JobCV';
    
    const emailInput = document.getElementById('email');
    const otpInput = document.getElementById('otp');
    const btnGetOtp = document.getElementById('btnGetOtp');
    const btnVerifyOtp = document.getElementById('btnVerifyOtp');
    const formMessage = document.getElementById('formMessage');

    // Nếu không tồn tại các nút trên giao diện thì không chạy tiếp
    if (!btnGetOtp || !btnVerifyOtp) return;

    function setButtonState(button, disabled, text) {
        button.disabled = disabled;
        button.innerText = text;
    }

    function showMessage(message, type = 'info') {
        if (!formMessage) return;
        formMessage.className = `small mt-2 text-${type === 'success' ? 'success' : type === 'danger' ? 'danger' : 'secondary'}`;
        formMessage.innerText = message;
    }

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
        fetch(`${baseUrl}/controllers/ForgotPasswordController.php`, { method: 'POST', body: formData })
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
        fetch(`${baseUrl}/controllers/ForgotPasswordController.php`, { method: 'POST', body: formData })
            .then(response => response.json())
            .then(data => {
                showMessage(data.message, data.status === 'success' ? 'success' : 'danger');
                if (data.status === 'success') {
                    window.location.href = `${baseUrl}/views/page/auth/reset-password.php?email=${encodeURIComponent(email)}`;
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