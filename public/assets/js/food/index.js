const handleDelete = (id) => {
    showAlertConfirm(() => {
        $(`#delete-food-${id}`).submit();
    });
};
document.addEventListener("DOMContentLoaded", function () {
    // const appURL = "https://alphacinema.me"; // Đổi URL nếu cần

    // Hàm reset lỗi
    const resetErrors = (prefix) => {
        ["Name", "Price", "ImgThumbnail", "Description", "Type"].forEach(field => {
            document.getElementById(`${prefix}${field}Error`).innerText = "";
            $(`#${prefix}${field}`).removeClass("is-invalid");
        });
    };

    // Format giá tiền
    const formatPriceInput = (input, hiddenInput) => {
        input.addEventListener("input", function (e) {
            let value = e.target.value.replace(/\D/g, "");
            input.value = value ? Number(value).toLocaleString("en-US") : "";
            hiddenInput.value = value || "0";
        });

        input.addEventListener("blur", function (e) {
            if (!e.target.value) {
                e.target.value = "0";
                hiddenInput.value = "0";
            }
        });
    };

    formatPriceInput(document.getElementById("createPrice"), document.getElementById("price_hidden"));
    formatPriceInput(document.getElementById("updatePrice"), document.getElementById("price_hidden"));

    // Mở modal tạo món ăn
    $(".openCreateFoodModal").on("click", function () {
        resetErrors("create");
        new bootstrap.Modal($("#createFoodModal")[0]).show();
    });

    // Mở modal cập nhật món ăn
    $(".openUpdateFoodModal").on("click", function () {
        const modal = $("#updateFoodModal");
        resetErrors("update");
        
        const foodId = $(this).data("food-id");
        $("#updateFoodId").val(foodId);
        ["Name", "Price", "Description", "Type"].forEach(field => {
            $("#update" + field).val($(this).data("food-" + field.toLowerCase()));
        });
        
        let foodImg = $(this).data("food-img_thumbnail");
        if (foodImg) {
            $("#previewImgThumbnail").attr("src", `${APP_URL}/storage/${foodImg.replace("public/", "")}`).show();
        } else {
            $("#previewImgThumbnail").hide();
        }
        
        new bootstrap.Modal(modal[0]).show();
    });

    // Xử lý chọn ảnh mới trong update
    $("#updateImgThumbnail").on("change", function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = e => $("#previewImgThumbnail").attr("src", e.target.result).show();
            reader.readAsDataURL(file);
        }
    });

    // Hàm gửi request
    const sendRequest = async (url, method, formData, prefix) => {
        return await $.ajax({
            url,
            type: method,
            data: formData,
            dataType: "json",
            contentType: false,
            processData: false,
            success: () => {
                new bootstrap.Modal($("#" + prefix + "FoodModal")[0]).hide();
                location.reload();
            },
            error: (err) => {
                if (err.responseJSON && err.responseJSON.errors) {
                    showErrors(err.responseJSON.errors, prefix);
                }
            }
        });
    };

    // Hiển thị lỗi
    const showErrors = (errors, prefix) => {
        resetErrors(prefix);
        Object.keys(errors).forEach(key => {
            $(`#${prefix}${key.charAt(0).toUpperCase() + key.slice(1)}`).addClass("is-invalid");
            $(`#${prefix}${key.charAt(0).toUpperCase() + key.slice(1)}Error`).text(errors[key]);
        });
    };

    // Xử lý tạo món ăn
    $("#createFoodBtn").on("click", function (event) {
        event.preventDefault();
        sendRequest(`${APP_URL}/admin/foods`, "POST", new FormData($("#createFoodForm")[0]), "create");
    });

    // Xử lý cập nhật món ăn
    $("#updateFoodBtn").on("click", function (event) {
        event.preventDefault();
        const foodId = $("#updateFoodId").val();
        const formData = new FormData($("#updateFoodForm")[0]);
        
        if (!$("#updateImgThumbnail")[0].files.length) {
            formData.append("old_img_thumbnail", $("#previewImgThumbnail").attr("src").replace(`${APP_URL}/storage/`, ""));
        }
        
        sendRequest(`${APP_URL}/admin/foods/${foodId}`, "POST", formData, "update");
    });
});
