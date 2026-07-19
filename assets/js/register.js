/**
 * register.js
 * Xử lý chuyển tab vai trò (switch role), gửi OTP theo ngữ cảnh (type-aware), và xác thực dữ liệu đăng ký
 */

// Gắn hàm gửi OTP vào đối tượng window để gọi trực tiếp từ thuộc tính onclick ngoài HTML
window.requestOtp = function (type) {
  const baseUrl = window.appConfig?.baseUrl || "/JobCV";

  // Tìm phần tử dựa theo loại form (employer - Nhà tuyển dụng hoặc candidate - Ứng viên)
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

  fetch(`${baseUrl}/controllers/OtpController.php`, {
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

// Gắn hàm chuyển đổi giao diện ứng viên / nhà tuyển dụng
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

  if (roleVal === 1) {
    if (personalFields) personalFields.style.display = "none";
    if (employerFields) employerFields.style.display = "block";
    if (labelHoTen)
      labelHoTen.innerHTML =
        'Tên công ty / Doanh nghiệp <span class="text-danger">*</span>';
    if (inputHoTen) inputHoTen.placeholder = "Nhập tên công ty chính thức";
    if (labelEmail)
      labelEmail.innerHTML = 'Email công ty <span class="text-danger">*</span>';
    if (labelSDT) labelSDT.innerText = "Số điện thoại công ty";
    if (labelDiaChi) labelDiaChi.innerText = "Địa chỉ trụ sở chính";
    if (btnSubmit) {
      btnSubmit.innerText = "Đăng Ký Nhà Tuyển Dụng";
      btnSubmit.className = "btn btn-success btn-lg";
    }
    if (maSoThue) maSoThue.setAttribute("required", "required");
  } else {
    if (personalFields) personalFields.style.display = "flex";
    if (employerFields) employerFields.style.display = "none";
    if (labelHoTen)
      labelHoTen.innerHTML = 'Họ và Tên <span class="text-danger">*</span>';
    if (inputHoTen) inputHoTen.placeholder = "Nhập họ và tên đầy đủ";
    if (labelEmail)
      labelEmail.innerHTML = 'Email <span class="text-danger">*</span>';
    if (labelSDT) labelSDT.innerText = "Số điện thoại";
    if (labelDiaChi) labelDiaChi.innerText = "Địa chỉ";
    if (btnSubmit) {
      btnSubmit.innerText = "Đăng Ký Ứng Viên";
      btnSubmit.className = "btn btn-primary-blue btn-lg";
    }
    if (maSoThue) maSoThue.removeAttribute("required");
  }
};

// Bootstrap Custom Form Validation
document.addEventListener("DOMContentLoaded", () => {
  "use strict";
  const forms = document.querySelectorAll(".needs-validation");

  Array.prototype.slice.call(forms).forEach(function (form) {
    form.addEventListener(
      "submit",
      function (event) {
        // Chỉ tìm các ô nhập password của chính form đang submit hiện tại (Tránh lấy nhầm form kia)
        const passwordInput = form.querySelector(
          'input[type="password"][name="MatKhau"]'
        );
        const confirmInput = form.querySelector(
          'input[type="password"][name="MatKhauConfirm"]'
        );

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