$("#multiSelect").select2({
  placeholder: "Chọn một hoặc nhiều mục",
  allowClear: true,
  width: "100%",
});

/**
 * Xử lý in ra lỗi
 */
const handleValidateErrors = (prefix, error) => {
  error.errors.forEach((err) => {
    const field = err.path[0];
    $(`.${field}-error`).text(err.message);
    $(`.${prefix}-${field}`).addClass("is-invalid");
  });
};

const handleValidateField = (prefix, schema, field, value) => {
  try {
    schema.pick({ [field]: true }).parse({ [field]: value });
    $(`.${field}-error`).text("");
    $(`.${prefix}-${field}`).removeClass("is-invalid");
  } catch (error) {
    $(`.${field}-error`).text(error.errors[0].message);
    $(`.${prefix}-${field}`).addClass("is-invalid");
  }
};
/**
 * Lấy tất cả data ra, chuyển về dạng obejct, hỗ trợ hiển thị lỗi luôn khi dùng zod
 */
const getFormData = (formSelector) => {
  const form = $(formSelector);
  let data = {};

  form.find("input, textarea, select").each(function () {
    const name = $(this).attr("name");
    if (name) {
      data[name] = $(this).val().trim();
    }
  });

  return data;
};
