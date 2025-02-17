const handleDelete = (id) => {
    showAlertConfirm(() => {
        $(`#delete-food-${id}`).submit();
    });
};

document.addEventListener("DOMContentLoaded", function () {
    /**
     * Xử lý logic modal create food
     */

    $(".openCreateFoodModal").on("click", function () {
        const modal = $("#createFoodModal");

        new bootstrap.Modal(modal[0]).show();
    });

    $("#createFoodBtn").on("click", function (event) {
        event.preventDefault();

        const url = `https://alphacinema.me/admin/foods`;

        // const formData = $("#createFoodForm").serializeArray();
        const formData = new FormData($("#createFoodForm")[0]);
        handleCreate(url, formData);
    });

    const handleCreate = async (url, data) => {
        return await $.ajax({
            url: url,
            type: "POST",
            data: data,
            dataType: "json",
            contentType: false,
            processData: false,
            success: function (res) {
                console.log(res);
                // $("#createFoodForm").modal("hide");
                const modal = $("#createFoodModal");

                new bootstrap.Modal(modal[0]).hide();
                location.reload();
            },
            error: function (err) {
                console.log(err);

                // Kiểm tra xem 'err.responseJSON' và 'err.responseJSON.errors' có tồn tại không
                if (err.responseJSON && err.responseJSON.errors) {
                    showErrors(err.responseJSON.errors, "create");
                } else {
                    console.error("Không có lỗi phản hồi từ server");
                }
            },
        });
    }
    const showErrors = (errors, prefix) => {
        console.log(errors);
        // return;

        document.getElementById(`${prefix}NameError`).innerText = "";
        document.getElementById(`${prefix}PriceError`).innerText = "";
        document.getElementById(`${prefix}ImgThumbnailError`).innerText = "";
        document.getElementById(`${prefix}DescriptionError`).innerText = "";
        document.getElementById(`${prefix}TypeError`).innerText = "";

        $(`#${prefix}Name`).removeClass("is-invalid");
        $(`#${prefix}Price`).removeClass("is-invalid");
        $(`#${prefix}ImgThumbnail`).removeClass("is-invalid");
        $(`#${prefix}Description`).removeClass("is-invalid");
        $(`#${prefix}Type`).removeClass("is-invalid");

        if (errors.name) {
            $(`#${prefix}Name`).addClass("is-invalid");
            $(`#${prefix}NameError`).text(errors.name);
        }

        if (errors.price) {
            $(`#${prefix}Price`).addClass("is-invalid");
            $(`#${prefix}PriceError`).text(errors.price);
        }

        if (errors.description) {
            $(`#${prefix}Description`).addClass("is-invalid");
            $(`#${prefix}DescriptionError`).text(errors.description);
        }

        if (errors.type) {
            $(`#${prefix}Type`).addClass("is-invalid");
            $(`#${prefix}TypeError`).text(errors.type);
        }

        if (errors.img_thumbnail) {
            $(`#${prefix}ImgThumbnail`).addClass("is-invalid");
            $(`#${prefix}ImgThumbnailError`).text(errors.img_thumbnail);
        }
    };
});

/**
 * Update
 */
$(".openUpdateFoodModal").on("click", function () {
    const modal = $("#updateFoodModal");

    const foodId = $(this).data("food-id");
    const foodName = $(this).data("food-name");
    const foodPrice = $(this).data("food-price");
    const foodDescription = $(this).data("food-description");
    let foodImgThumbnail = $(this).data("food-img_thumbnail");
    const foodType = $(this).data("food-type");

    $("#updateFoodId").val(foodId);
    $("#updateName").val(foodName);
    $("#updatePrice").val(foodPrice);
    $("#updateDescription").val(foodDescription);
    $("#updateType").val(foodType);

    // Hiển thị ảnh cũ thay vì cố gắng đặt value cho input file
    if (foodImgThumbnail) {
        $("#previewImgThumbnail").attr("src", foodImgThumbnail).show();
    } else {
        $("#previewImgThumbnail").hide();
    }
    // Kiểm tra và hiển thị đúng ảnh cũ với đường dẫn đầy đủ
    if (foodImgThumbnail) {
        foodImgThumbnail = `https://alphacinema.me/storage/${foodImgThumbnail.replace(
            "public/",
            ""
        )}`;
        $("#previewImgThumbnail").attr("src", foodImgThumbnail).show();
    } else {
        $("#previewImgThumbnail").hide();
    }
    new bootstrap.Modal(modal[0]).show();
    resetUpdateErrors("update");
});

// Xử lý khi chọn ảnh mới
$("#updateImgThumbnail").on("change", function () {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            $("#previewImgThumbnail").attr("src", e.target.result).show();
        };
        reader.readAsDataURL(file);
    }
});

// Xử lý cập nhật
$("#updateFoodBtn").on("click", function (event) {
    event.preventDefault();

    const url = `https://alphacinema.me/admin/foods/${$(
        "#updateFoodId"
    ).val()}`;
    const formData = new FormData($("#updateFoodForm")[0]);

    // Nếu không có ảnh mới, gửi đường dẫn ảnh cũ
    if (!$("#updateImgThumbnail")[0].files.length) {
        formData.append(
            "old_img_thumbnail",
            $("#previewImgThumbnail")
                .attr("src")
                .replace("https://alphacinema.me/storage/", "")
        );
    }

    handleUpdate(url, formData);
});
const handleUpdate = async (url, data) => {
    return await $.ajax({
        url: url,
        type: "POST",
        data: data,
        dataType: "json",
        contentType: false,
        processData: false,
        success: function (res) {
            console.log(res);
            // $("#updateFoodModal").modal("hide");
            // $("#updateFoodModal").modal("hide");

            const modal = $("#createFoodModal");

            new bootstrap.Modal(modal[0]).hide();
            location.reload();
        },
        error: function (err) {
            console.log(err.responseJSON);
            // console.log(err.responseJSON.errors);

            showErrorsEdit(err.responseJSON.errors, "update");
        },
    });
};

const resetUpdateErrors = (prefix) => {
    document.getElementById(`${prefix}NameError`).innerText = "";
    document.getElementById(`${prefix}PriceError`).innerText = "";
    document.getElementById(`${prefix}ImgThumbnailError`).innerText = "";
    document.getElementById(`${prefix}DescriptionError`).innerText = "";
    document.getElementById(`${prefix}TypeError`).innerText = "";

    $(`#${prefix}Name`).removeClass("is-invalid");
    $(`#${prefix}Price`).removeClass("is-invalid");
    $(`#${prefix}ImgThumbnail`).removeClass("is-invalid");
    $(`#${prefix}Description`).removeClass("is-invalid");
    $(`#${prefix}Type`).removeClass("is-invalid");
};

const showErrorsEdit = (errors = null, prefix = null) => {
    console.log(errors);
    // return;

    resetUpdateErrors(prefix);

    if (errors.name) {
        $(`#${prefix}Name`).addClass("is-invalid");
        $(`#${prefix}NameError`).text(errors.name);
    }

    if (errors.price) {
        $(`#${prefix}Price`).addClass("is-invalid");
        $(`#${prefix}PriceError`).text(errors.price);
    }

    if (errors.description) {
        $(`#${prefix}Description`).addClass("is-invalid");
        $(`#${prefix}DescriptionError`).text(errors.description);
    }

    if (errors.type) {
        $(`#${prefix}Type`).addClass("is-invalid");
        $(`#${prefix}TypeError`).text(errors.type);
    }

    if (errors.img_thumbnail) {
        $(`#${prefix}ImgThumbnail`).addClass("is-invalid");
        $(`#${prefix}ImgThumbnailError`).text(errors.img_thumbnail);
    }
};
