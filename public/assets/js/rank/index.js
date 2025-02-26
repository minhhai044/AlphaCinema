/**
 * Update
 */

document.addEventListener("DOMContentLoaded", function () {
  $(".openUpdateRankModal").on("click", function () {
    const modal = new bootstrap.Modal($("#updateRankModal")[0]);

    const rankId = $(this).data("rank-id");
    const rankName = $(this).data("rank-name");
    const rankTotalSpent = $(this).data("rank-total-spent");
    const rankTicketPercentage = $(this).data("rank-ticket-percentage");
    const rankComboPercentage = $(this).data("rank-combo-percentage");
    const rankDefault = $(this).data("rank-default");

    $("#updateRankId").val(rankId);
    $("#updateName").val(rankName);
    $("#updateTotalSpent").val(rankTotalSpent);
    $("#updateTicketPercentage").val(rankTicketPercentage);
    $("#updateComboPercentage").val(rankComboPercentage);

    $("#spanDefault").text(rankDefault ? "Mặc định" : "");
    $("#updateTotalSpent")
      .prop("disabled", rankDefault)
      .toggleClass("disabled", rankDefault);

    modal.show();
  });

  $("#updateRankBtn").on("click", function (event) {
    event.preventDefault();
    const formData = $("#updateRankForm").serializeArray();

    handleUpdate(`${APP_URL}/ranks/${$("#updateRankId").val()}`, formData);
  });
});

/**
 * Xử lý xóa rank
 * - Hiển thị alert xác nhận xóa
 * - Đồng ý mới submit form xóa
 *
 * @param {number|string} id - ID rank cần xóa
 */
const handleDelete = (id) => {
  showAlertConfirm(() => $(`#delete-rank-${id}`).submit());
};

const handleUpdate = async (url, data) => {
  return await $.ajax({
    url: url,
    type: "POST",
    data: data,
    dataType: "json",
    processData: true,
    success: function (res) {
      console.log(res);
      const modal = new bootstrap.Modal($("#updateRankModal")[0]);
      modal.hide();
      location.reload();
    },
    error: function (err) {
      console.log(err.responseJSON);
      showErrors(err.responseJSON.errors, "update");
    },
  });
};

const showErrors = (errors, prefix) => {
  console.log(errors);

  document.getElementById(`${prefix}NameError`).innerText = "";
  document.getElementById(`${prefix}TotalSpentError`).innerText = "";
  document.getElementById(`${prefix}TicketPercentageError`).innerText = "";
  document.getElementById(`${prefix}ComboPercentageError`).innerText = "";

  if (errors.name) {
    $(`#${prefix}NameError`).text(errors.name);
  }
  if (errors.total_spent) {
    $(`#${prefix}TotalSpentError`).text(errors.total_spent);
  }
  if (errors.ticket_percentage) {
    $(`#${prefix}TicketPercentageError`).text(errors.ticket_percentage);
  }
  if (errors.combo_percentage) {
    $(`#${prefix}ComboPercentageError`).text(errors.combo_percentage);
  }
};
