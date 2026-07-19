/**
 * File: assets//js/validate.js
 * Đường dẫn: Module5_UngTuyenVaQuanLyHoSo/assets//js/validate.js
 * Chức năng: Xác nhận trước khi nộp hồ sơ / cập nhật trạng thái,
 *            và validate đơn giản phía client (chỉ hỗ trợ UX,
 *            validate thật vẫn nằm ở Controller).
 */

/**
 * Xác nhận trước khi nộp hồ sơ ứng tuyển.
 * @param {Event} event
 * @returns {boolean}
 */
function confirmSubmitApplication(event) {
  var cvSelect = document.getElementById("maCV");

  if (cvSelect && cvSelect.value === "") {
    alert("Vui lòng chọn CV trước khi nộp hồ sơ.");
    event.preventDefault();
    return false;
  }

  var confirmed = confirm(
    "Bạn có chắc chắn muốn nộp hồ sơ ứng tuyển này không?",
  );
  if (!confirmed) {
    event.preventDefault();
    return false;
  }

  return true;
}

/**
 * Xác nhận trước khi cập nhật trạng thái hồ sơ.
 * @param {Event} event
 * @returns {boolean}
 */
function confirmUpdateStatus(event) {
  var statusSelect = document.getElementById("trangThai");
  var statusText = statusSelect
    ? statusSelect.options[statusSelect.selectedIndex].text
    : "";

  var confirmed = confirm(
    'Bạn có chắc chắn muốn cập nhật trạng thái thành "' +
      statusText +
      '"?\nHệ thống sẽ tự động gửi email thông báo cho ứng viên.',
  );
  if (!confirmed) {
    event.preventDefault();
    return false;
  }

  return true;
}

/**
 * Validate đơn giản độ dài Cover Letter dạng text ngay trên client
 * để phản hồi nhanh cho người dùng trước khi gửi request lên server.
 * @param {HTMLTextAreaElement} textarea
 */
function validateCoverLetterLength(textarea) {
  var maxLength = 3000;
  var counterEl = document.getElementById("coverLetterCounter");

  if (counterEl) {
    counterEl.textContent =
      textarea.value.length + " / " + maxLength + " ký tự";

    if (textarea.value.length > maxLength) {
      counterEl.style.color = "#dc2626";
    } else {
      counterEl.style.color = "#6b7280";
    }
  }
} /**
 * File: assets//js/validate.js
 * Đường dẫn: Module5_UngTuyenVaQuanLyHoSo/assets//js/validate.js
 * Chức năng: Xác nhận trước khi nộp hồ sơ / cập nhật trạng thái,
 *            và validate đơn giản phía client (chỉ hỗ trợ UX,
 *            validate thật vẫn nằm ở Controller).
 */

/**
 * Xác nhận trước khi nộp hồ sơ ứng tuyển.
 * @param {Event} event
 * @returns {boolean}
 */
function confirmSubmitApplication(event) {
  var cvSelect = document.getElementById("maCV");

  if (cvSelect && cvSelect.value === "") {
    alert("Vui lòng chọn CV trước khi nộp hồ sơ.");
    event.preventDefault();
    return false;
  }

  var confirmed = confirm(
    "Bạn có chắc chắn muốn nộp hồ sơ ứng tuyển này không?",
  );
  if (!confirmed) {
    event.preventDefault();
    return false;
  }

  return true;
}

/**
 * Xác nhận trước khi cập nhật trạng thái hồ sơ.
 * @param {Event} event
 * @returns {boolean}
 */
function confirmUpdateStatus(event) {
  var statusSelect = document.getElementById("trangThai");
  var statusText = statusSelect
    ? statusSelect.options[statusSelect.selectedIndex].text
    : "";

  var confirmed = confirm(
    'Bạn có chắc chắn muốn cập nhật trạng thái thành "' +
      statusText +
      '"?\nHệ thống sẽ tự động gửi email thông báo cho ứng viên.',
  );
  if (!confirmed) {
    event.preventDefault();
    return false;
  }

  return true;
}

/**
 * Validate đơn giản độ dài Cover Letter dạng text ngay trên client
 * để phản hồi nhanh cho người dùng trước khi gửi request lên server.
 * @param {HTMLTextAreaElement} textarea
 */
function validateCoverLetterLength(textarea) {
  var maxLength = 3000;
  var counterEl = document.getElementById("coverLetterCounter");

  if (counterEl) {
    counterEl.textContent =
      textarea.value.length + " / " + maxLength + " ký tự";

    if (textarea.value.length > maxLength) {
      counterEl.style.color = "#dc2626";
    } else {
      counterEl.style.color = "#6b7280";
    }
  }
}
