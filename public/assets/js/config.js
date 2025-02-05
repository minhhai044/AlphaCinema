/**
 * Hiển thị alert, có những type success, error, info, warning, question
 *
 * @param {string} icon Biểu tượng của alert
 * @param {*} message Mô tả của alert
 * @param {*} title Tiêu đều của alert
 */
const showAlert = (icon = null, message = null, title = null) => {
  Swal.fire({
    title: `${title ?? ""}`,
    text: `${message}`,
    icon: `${icon}`,
  });
};

/**
 * Hiển thị alert xác nhận
 *
 * @param {callback} callback Viết callback sau khi xác nhận.
 */
const showAlertConfirm = (callback) => {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Xác Nhận",
    cancelButtonText: "Hủy",
  }).then((result) => {
    if (result.isConfirmed) {
      callback();
    }
  });
};
