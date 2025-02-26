const { z } = Zod;

$(document).ready(function () {
  const modal = new bootstrap.Modal($("#createCinemaModal")[0]);
  const prefix = "create";

  const schema = z.object({
    branch_id: z.string().nonempty("Vui lòng chọn chi nhánh"),
    name: z.string().min(1, "Vui lòng điền tên").max(255, "Tối đa 255 kí tự"),
    address: z.string().min(1, "Vui lòng điền địa chỉ"),
    description: z.string().max(255, "Tối đa 255 kí tự").optional(),
  });

  /**
   * Mở modal
   */
  $("#openCreateCinemaModal").on("click", function () {
    resetErros(prefix);
    resetData(prefix);
    modal.show();
  });
  /**
   * Xử lý khi click button trong modal
   */
  $("#createCinemaBtn").on("click", () => {
    const formData = getFormData("#createCinemaForm");
    const validation = schema.safeParse(formData);

    if (!validation.success) {
      handleValidateErrors(prefix, validation.error);
      return;
    }

    console.log(formData);
    createCinema(`${APP_URL}/cinemas`, formData);
  });
  /**
   * Hiển thị lỗi khi có data thay đổi
   */
  $("#createCinemaForm").on(
    "input change",
    "input, textarea, select",
    function () {
      const field = $(this).attr("name");
      const value = $(this).val();

      handleValidateField(prefix, schema, field, value);
    }
  );
});

/**
 * Call api tạo chi nhánh
 *
 * @param {*} url
 * @param {*} data
 *
 */
const createCinema = async (url, data) => {
  return await $.ajax({
    url: url,
    type: "POST",
    data: data,
    dataType: "json",
    processData: true,
    success: function (res) {
      console.log(res);
      $("#createCinemaModal").modal("hide");
      location.reload();
    },
    error: function (err) {
      console.log(err.responseJSON);

      if (err && err.responseJSON.errors) {
        showErrors(err.responseJSON.errors, "create");
      }
    },
  });
};

/**
 * Xóa tất cả lỗi
 */
const resetErros = (prefix) => {
  $(`#${prefix}BranchError`).text("");
  $(`#${prefix}NameError`).text("");
  $(`#${prefix}AddressError`).text("");
  $(`#${prefix}DescriptionError`).text("");

  $(`#${prefix}Branch`).removeClass("is-invalid");
  $(`#${prefix}Name`).removeClass("is-invalid");
  $(`#${prefix}Address`).removeClass("is-invalid");
  $(`#${prefix}Description`).removeClass("is-invalid");
};

const resetData = (prefix) => {
  $(`#${prefix}Branch`).val("");
  $(`#${prefix}Name`).val("");
  $(`#${prefix}Address`).val("");
  $(`#${prefix}Description`).val("");
};

/**
 * Hiển thị lỗi
 */
const showErrors = (errors, prefix) => {
  resetErros(prefix);

  if (errors.branch_id) {
    $(`#${prefix}Branch`).addClass("is-invalid");
    $(`#${prefix}BranchError`).text(errors.branch_id);
  }

  if (errors.name) {
    $(`#${prefix}Name`).addClass("is-invalid");
    $(`#${prefix}NameError`).text(errors.name);
  }

  if (errors.address) {
    $(`#${prefix}Address`).addClass("is-invalid");
    $(`#${prefix}AddressError`).text(errors.address);
  }

  if (errors.description) {
    $(`#${prefix}Description`).addClass("is-invalid");
    $(`#${prefix}DescriptionError`).text(errors.description);
  }
};
/**
 * Xử lý xóa rạp chiếu
 *
 * @param {*} id
 */
const handleDelete = (id) => {
  showAlertConfirm(() => {
    $(`#delete-cinema-${id}`).submit();
  });
};
