// $("#multiSelect").select2({
//   placeholder: "Chọn một hoặc nhiều mục",
//   allowClear: true,
//   //   width: "100%",
// });

/** base validate with zod */

/**
 * In ra lỗi
 * @param {*} error
 */
const handleValidateErrors = (prefixIdInput, error) => {
  /**
   * field - Là các name truyền vào ở schema / đặt đúng id
   */
  error.errors.forEach((err) => {
    const field = err.path[0];
    $(`#${field}-error`).text(err.message);
    $(`#${prefixIdInput}-${field}`).addClass("is-invalid");
  });
};

/**
 *
 */
const handleValidateField = (prefixIdInput, schema, field, value) => {
  try {
    schema.pick({ [field]: true }).parse({ [field]: value });
    $(`#${field}-error`).text("");
    $(`#${prefixIdInput}-${field}`).removeClass("is-invalid");
  } catch (error) {
    $(`#${field}-error`).text(error.errors[0].message);
    $(`#${prefixIdInput}-${field}`).addClass("is-invalid");
  }
};
