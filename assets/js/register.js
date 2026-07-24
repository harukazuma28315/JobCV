/**
 * register.js
 * Quản lý tương tác giao diện và xác thực dữ liệu cho form đăng ký (Ứng viên & Nhà tuyển dụng).
 */

/**
 * Gửi yêu cầu khởi tạo mã OTP xác thực email đến máy chủ qua AJAX.
 * Áp dụng đếm ngược thời gian (cooldown) ở giao diện để hạn chế người dùng bấm gửi liên tục gây quá tải server.
 * 
 * @param {string} type - Loại tài khoản đăng ký ('candidate' hoặc 'employer').
 */
window.requestOtp = function (type) {
  const baseUrl = window.appConfig?.baseUrl || "/JobCV";

  const emailId = type === "employer" ? "EmailEmployer" : "Email";
  const btnId = type === "employer" ? "btnGetOtpEmployer" : "btnGetOtp";

  const emailInput = document.getElementById(emailId);
  const btnGetOtp = document.getElementById(btnId);

  if (!emailInput || !emailInput.value.trim()) {
    alert("Vui lòng nhập địa chỉ Email trước khi yêu cầu mã xác thực!");
    return;
  }

  const emailValue = emailInput.value.trim();

  if (btnGetOtp) {
    btnGetOtp.disabled = true;
    btnGetOtp.innerText = "Đang gửi...";
  }

  const formData = new FormData();
  formData.append("action", "send_otp");
  formData.append("email", emailValue);

  fetch(`${baseUrl}/index.php?route=auth/send-otp`, {
    method: "POST",
    body: formData,
  })
    .then(async (response) => {
      const text = await response.text();
      try {
        return JSON.parse(text);
      } catch (error) {
        throw new Error(`Invalid JSON response from server:\n${text}`);
      }
    })
    .then((data) => {
      alert(data.message);
      if (data.status === "success" || data.status === "cooldown") {
        let countdown = data.remaining || 60;
        const timer = setInterval(() => {
          countdown--;
          if (btnGetOtp) {
            btnGetOtp.innerText = `Gửi lại sau (${countdown}s)`;
          }
          if (countdown <= 0) {
            clearInterval(timer);
            if (btnGetOtp) {
              btnGetOtp.disabled = false;
              btnGetOtp.innerText = "Lấy mã xác thực";
            }
          }
        }, 1000);
      } else {
        if (btnGetOtp) {
          btnGetOtp.disabled = false;
          btnGetOtp.innerText = "Lấy mã xác thực";
        }
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("Đã có lỗi kết nối xảy ra!");
      if (btnGetOtp) {
        btnGetOtp.disabled = false;
        btnGetOtp.innerText = "Lấy mã xác thực";
      }
    });
};

/**
 * Chuyển đổi trạng thái hiển thị của form đăng ký giữa hai vai trò Ứng viên và Nhà tuyển dụng.
 * Tự động bật/tắt các ràng buộc dữ liệu bắt buộc (như Mã số thuế) tương ứng với từng đối tượng.
 * 
 * @param {number} roleVal - Mã vai trò người dùng (1: Nhà tuyển dụng, 0/khác: Ứng viên).
 */
window.switchRole = function (roleVal) {
  document.getElementById("Role").value = roleVal;
  document.getElementById("RoleEmployer").value = roleVal;

  const personalFields = document.getElementById("personalFields");
  const employerFields = document.getElementById("employerFields");
  const btnSubmit = document.getElementById("btnSubmit");
  const labelHoTen = document.getElementById("labelHoTen");
  const inputHoTen = document.getElementById("HoTen");
  const labelEmail = document.getElementById("labelEmail");
  const labelSDT = document.getElementById("labelSDT");
  const labelDiaChi = document.getElementById("labelDiaChi");
  const maSoThue = document.getElementById("MaSoThue");

  const isEmployer = roleVal === 1;

  if (personalFields) personalFields.style.display = isEmployer ? "none" : "flex";
  if (employerFields) employerFields.style.display = isEmployer ? "block" : "none";

  if (labelHoTen) {
    labelHoTen.innerHTML = isEmployer 
      ? 'Tên công ty / Doanh nghiệp <span class="text-danger">*</span>'
      : 'Họ và Tên <span class="text-danger">*</span>';
  }
  if (inputHoTen) {
    inputHoTen.placeholder = isEmployer ? "Nhập tên công ty chính thức" : "Nhập họ và tên đầy đủ";
  }
  if (labelEmail) {
    labelEmail.innerHTML = isEmployer 
      ? 'Email công ty <span class="text-danger">*</span>' 
      : 'Email <span class="text-danger">*</span>';
  }
  if (labelSDT) labelSDT.innerText = isEmployer ? "Số điện thoại công ty" : "Số điện thoại";
  if (labelDiaChi) labelDiaChi.innerText = isEmployer ? "Địa chỉ trụ sở chính" : "Địa chỉ";

  if (btnSubmit) {
    btnSubmit.innerText = isEmployer ? "Đăng Ký Nhà Tuyển Dụng" : "Đăng Ký Ứng Viên";
    btnSubmit.className = isEmployer ? "btn btn-success btn-lg" : "btn btn-primary-blue btn-lg";
  }

  if (maSoThue) {
    isEmployer ? maSoThue.setAttribute("required", "required") : maSoThue.removeAttribute("required");
  }
};

/**
 * Đăng ký các sự kiện lắng nghe khi DOM đã tải hoàn tất.
 * Khởi tạo kiểm tra tính hợp lệ của Form Bootstrap và xác nhận mật khẩu khớp nhau trước khi Submit.
 */
document.addEventListener("DOMContentLoaded", () => {
  "use strict";
  const forms = document.querySelectorAll(".needs-validation");

  Array.prototype.slice.call(forms).forEach(function (form) {
    form.addEventListener(
      "submit",
      function (event) {
        const passwordInput = form.querySelector('input[type="password"][name="MatKhau"]');
        const confirmInput = form.querySelector('input[type="password"][name="MatKhauConfirm"]');

        if (passwordInput && confirmInput) {
          const password = passwordInput.value;
          const confirmPassword = confirmInput.value;

          if (password !== confirmPassword) {
            confirmInput.setCustomValidity("Mật khẩu không trùng khớp!");
          } else {
            confirmInput.setCustomValidity("");
          }
        }

        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add("was-validated");
      },
      false
    );
  });
});