/** Config */
toastr.options = {
  closeButton: true,
  debug: false,
  newestOnTop: true,
  progressBar: true,
  positionClass: "toast-top-right",
  preventDuplicates: true,
  onclick: null,
  showDuration: "300",
  hideDuration: "1000",
  timeOut: "3000",
  extendedTimeOut: "1000",
  showEasing: "swing",
  hideEasing: "linear",
  showMethod: "fadeIn",
  hideMethod: "fadeOut",
};
/** Config */

/**
 * Hiển thị alert, có những type success, error, info, warning, question
 *
 * @param {string} icon     Biểu tượng của alert
 * @param {string}      message  Mô tả của alert
 * @param {string}      title    Tiêu đều của alert
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
/**
 * Hiển thị toastr / Type: success, error, warning, info
 *
 * @param {string} icon
 * @param {string} message
 * @param {string} title
 */
const showToastr = (icon = "success", message = null, title = null) => {
  toastr[icon](message, title);
};


