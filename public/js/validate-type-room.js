
$(document).ready(function () {
    function validateForm() {
        let name = $("#name").val().trim();
        let surcharge = $("input[name='surcharge']").val().trim();
        const submitButton = document.querySelector(".btn-submit");

        let isValid = true;
        submitButton.prop("disabled", !isValid);

        // Reset lỗi trước khi kiểm tra
        $(".error").text("");
        $("input").removeClass("is-invalid");

        // Kiểm tra name
        if (name === "") {
            $("#name").addClass("is-invalid");
            $("#nameError").text("Vui lòng nhập tên loại phòng.");
            isValid = false;
        } 
        // else {
        //     checkDuplicateName(name); 
        //     // Gọi hàm kiểm tra trùng tên
        // }

        // Kiểm tra surcharge
        if (surcharge === "") {
            $("input[name='surcharge']").addClass("is-invalid");
            $(".invalid-feedback").text("Vui lòng nhập giá.");
            isValid = false;
        } else if (isNaN(surcharge) || surcharge < 0 || surcharge > 9999999999) {
            $("input[name='surcharge']").addClass("is-invalid");
            $(".invalid-feedback").text("Giá phải là số hợp lệ.");
            isValid = false;

        }

        return isValid;
    }

    // Kiểm tra khi nhập dữ liệu vào input
    $("#name, input[name='surcharge']").on("keyup change", function () {
        validateForm();
    });

    // Ngăn submit nếu form không hợp lệ
    $("#formadd").submit(function (e) {
        if (!validateForm()) {
            e.preventDefault();
        }
    });

    // Hiển thị modal nếu có lỗi từ server
    if ($("#addModal .is-invalid").length > 0) {
        $("#addModal").modal("show");
    }
});
