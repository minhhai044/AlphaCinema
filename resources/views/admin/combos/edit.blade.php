@extends('admin.layouts.master')
@section('title', 'Quản lý Combo')

@section('style')
    <style>
        .required {
            color: red;
            font-style: italic !important;
        }
    </style>
@endsection

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Cập nhật Combo</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.combos.index') }}">Combo</a>
                        </li>
                        <li class="breadcrumb-item active">Cập nhật Combo: {{ $combo->name }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <form action="{{ route('admin.combos.update', $combo->id) }}" method="POST" enctype="multipart/form-data"
        id="updateFormCombo">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <!-- Combo details form -->
                            <div class="col-md-12 d-flex justify-content-between">
                                <label for="" class="form-label"></label>
                                <button type="button" class="btn btn-primary" id="add">Thêm đồ
                                    ăn</button>
                            </div>
                            <div id="food_list" class="col-md-12">
                                <!-- Các phần tử food sẽ được thêm vào đây -->
                                @foreach ($combo->comboFood as $item)
                                    <div class="col-md-12 mb-3 food-item d-flex align-items-center justify-content-between p-3 border rounded"
                                        id="${id}_item">
                                        <div class="col-md-6">
                                            <label for="${id}_select" class="form-label">Đồ ăn</label>
                                            <select name="combo_food[]" id="${id}_select" class="form-control food-select" data-id="${id}">
                                                @foreach ($food as $itemId => $itemName)
                                                    <option value="{{ $itemId }}" @selected($item->food_id == $itemId)>
                                                        {{ $itemName }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger" id="${id}_food_error"></span>
                                        </div>

                                        <div class="col-md-3 mx-4">
                                            <label for="${id}" class="form-label align-items-center">Số lượng</label>
                                            <div class="d-flex flex-wrap align-items-start">
                                                <div
                                                    class="input-step step-primary full-width p-1 d-flex align-items-center border rounded">
                                                    <button type="button" class="minuss btn btn-danger px-3 py-1">-</button>
                                                    <input type="number" name="combo_quantity[]"
                                                        class="food-quantity text-center border-0 mx-2" id="${id}"
                                                        value="{{ $item->quantity }}" min="0" max="10">
                                                    <button type="button" class="pluss btn btn-success px-3 py-1">+</button>
                                                </div>
                                            </div>
                                            <span class="text-danger" id="${id}_quantity_error"></span>
                                        </div>

                                        <div class="col-md-2 text-center">
                                            <button type="button" class="btn btn-danger remove-food">
                                                <span class="bx bx-trash"></span>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>


                            <div class="col-md-4 mb-3">
                                <div class="mb-3">
                                    <label for="name" class="form-label"><span class="text-danger">*</span>Tên
                                        Combo</label>
                                    <input type="text" name="name" id="name" class="form-control" data-=""
                                        value="{{ $combo->name }}" placeholder="Nhập tên món ăn">

                                    @if ($errors->has('name'))
                                        <div class="text-danger">
                                            {{ $errors->first('name') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="price" class="form-label ">Giá gốc (VNĐ)</label>
                                <input type="text" class="form-control" id="price" name="price"
                                    value="{{ formatPrice($combo->price) }}" readonly>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="mb-3">
                                    <label for="price_sale" class="form-label"><span class="text-danger">*</span>Giá
                                        Sale(VNĐ)</label>
                                    <input type="text"
                                        class="form-control "
                                        name="price_sale" id="price_sale" placeholder="Nhập giá tiền"
                                        value="{{ formatPrice($combo->price_sale) }}">
                                    @if ($errors->has('price_sale'))
                                        <div class="text-danger">
                                            {{ $errors->first('price_sale') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="food-description" class="form-label">
                                <span class="required">*</span> Mô tả
                            </label>
                            <textarea
                                class="form-control "
                                name="description" rows="6" placeholder="Nhập mô tả">{{ $combo->description }}</textarea>

                            @if ($errors->has('description'))
                                <div class="text-danger">
                                    {{ $errors->first('description') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="card-footer">
                        <button class="btn btn-primary">
                            Cập nhật
                            <i class="bx bx-chevron-right ms-1"></i>
                        </button>
                        <a href="{{ route('admin.combos.index') }}" class="btn btn-danger" type="button"
                            onclick="return confirm('Bạn có chắc chắn hủy bỏ thao tác? Mọi thay đổi sẽ không được lưu')">
                            Hủy bỏ
                            <i class="bx bx-chevron-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class=" d-flex gap-2">
                                        <label for="cinema-description" class="form-label">
                                            <span class="required">*</span>
                                            Hoạt động
                                        </label>
                                        <div class="round-switch">
                                            <input type="checkbox" @checked($combo->is_active) id="switch6" switch="primary" value="1"
                                                name="is_active" {{ old('is_active',1) ? 'checked' : '' }}>
                                            <label for="switch6" data-on-label="Yes" data-off-label="No"></label>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-2">
                                    <label for="combo-image" class="form-label">
                                        <span class=" text-danger required">*</span>Ảnh
                                        <input class="form-control" type="file" name="img_thumbnail" id="combo-image"
                                            onchange="previewImage(event)">
                                </div>
                                @if ($combo->img_thumbnail)
                                <div class="mt-3 text-center">
                                    <img src="{{ Storage::url($combo->img_thumbnail) }}" alt="Thumbnail"
                                        class="img-fluid" width="200">
                                </div>
                                @endif
                                @if ($errors->has('img_thumbnail'))
                                    <div class="text-danger">
                                        {{ $errors->first('img_thumbnail') }}
                                    </div>
                                @endif
                                <!-- Display selected image and delete button -->
                                <div id="image-container" class="d-none position-relative mt-3 text-center">
                                    {{-- <span>Ảnh mới</span> --}}
                                    <img id="image-preview" src="" alt="Preview" class="img-fluid mb-2 text-center"
                                        style="max-width: 70%; max-height: 150px;">
                                    <!-- Icon thùng rác ở góc phải -->
                                    <button type="button" id="delete-image"
                                        class="btn btn-danger position-absolute top-0 end-0 p-1" onclick="deleteImage()">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-2">
                                    <label for="img_thumbnail" class="form-label"> <span class="text-danger">*</span>Hình
                                        ảnh</label>
                                    <input type="file" name="img_thumbnail" id="img_thumbnail"
                                        class="form-control {{ $errors->has('img_thumbnail') ? 'is-invalid' : (old('img_thumbnail') ? 'is-valid' : '') }}">
                                    <div
                                     
                                    @if ($combo->img_thumbnail)
                                        <div class="mt-3 text-center">
                                            <img src="{{ Storage::url($combo->img_thumbnail) }}" alt="Thumbnail"
                                                class="img-fluid" width="200">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div> --}}

                </div>
            </div>
        </div>
    </form>

@endsection
@section('script')
    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            const reader = new FileReader();

            reader.onload = function() {
                $('#image-preview').attr('src', reader.result);
                $('#image-container').removeClass('d-none');
            }

            if (file) {
                reader.readAsDataURL(file);
            }
        }

        // Function to delete the selected image
        function deleteImage() {
            $('#delete-image').click(() => {
                $('#image-container').addClass('d-none');
                $('#combo-image').val('');
                $('#image-preview').attr('src', '');
            });
        }
        $(document).ready(function () {
            // Format giá tiền
            function formatPriceInput(inputSelector, hiddenInputSelector) {
                $(inputSelector).on("input", function () {
                    let value = $(this).val().replace(/\D/g, ""); // Chỉ giữ lại số
                    let formattedValue = value.replace(/\B(?=(\d{3})+(?!\d))/g, ","); // Thêm dấu ,

                    $(this).val(formattedValue); // Hiển thị dạng số có dấu ,
                    $(hiddenInputSelector).val(value || "0"); // Lưu dạng số không có dấu , vào input ẩn
                });

                $(inputSelector).on("blur", function () {
                    if (!$(this).val()) {
                        $(this).val("0");
                        $(hiddenInputSelector).val("0");
                    }
                });
            }

            // Áp dụng cho input giá gốc & giá sale
            formatPriceInput("#price", "#price_hidden");
            formatPriceInput("#price_sale", "#price_sale_hidden");
            let foodCount = $('.food-item').length; // Đếm số món ăn hiện tại
            const minFoodItems = 2; // Số lượng món ăn tối thiểu
            const maxFoodItems = 8; // Số lượng món ăn tối đa
            const foodList = $('#food_list'); // Vùng chứa danh sách món ăn
            const foodPrices = @json($foodPrice->pluck('price', 'id')); // Lấy danh sách giá món ăn

            // Hàm thêm món ăn mới
            function addFoodItem() {
                if (foodCount >= maxFoodItems) {
                    showAlert('warning', 'Chỉ được thêm tối đa 8 món ăn', 'AlphaCinema thông báo');
                    return;
                }
                const id = 'food_' + Date.now(); // Tạo ID duy nhất
                const foodItemHtml = `
                                        <div class="col-md-12 mb-3 food-item d-flex align-items-center justify-content-between p-3 border rounded" id="${id}_item">
                                            <div class="col-md-6">
                                                <label for="${id}_select" class="form-label">Đồ ăn</label>
                                                <select name="combo_food[]" id="${id}_select" class="form-control food-select" data-id="${id}">
                                                    <option value="">--Chọn đồ ăn--</option>
                                                    @foreach ($food as $itemId => $itemName)
                                                        <option value="{{ $itemId }}">{{ $itemName }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger food-error"></span>
                                            </div>
                                            <div class="col-md-3 mx-4">
                                                <label for="${id}" class="form-label">Số lượng</label>
                                                <div class="d-flex align-items-start">
                                                    <div class="input-step step-primary full-width p-1 d-flex align-items-center border rounded">
                                                        <button type="button" class="minuss btn btn-danger px-3 py-1">-</button>
                                                        <input type="number" name="combo_quantity[]" class="food-quantity text-center border-0 mx-2"
                                                            id="${id}" value="1" min="1" max="10" readonly>
                                                        <button type="button" class="pluss btn btn-success px-3 py-1">+</button>
                                                    </div>
                                                    <span class="text-danger small" id="${id}_quantity_error"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-2 text-center">
                                                <button type="button" class="btn btn-danger remove-food"><span class="bx bx-trash"></span></button>
                                            </div>
                                        </div>
                                    `;
                foodList.append(foodItemHtml);
                foodCount++;
                updateEventHandlers();
                updateSelectOptions();
                updateTotalPrice();
            }

            // Hàm xóa món ăn
            function removeFoodItem(foodItem) {
                if (foodCount > minFoodItems) {
                    foodItem.remove();
                    foodCount--;
                    updateTotalPrice();
                    updateSelectOptions();
                } else {
                    showAlert('warning', 'Cần ít nhất 2 món ăn để tạo Combo!', 'AlphaCinema thông báo');
                }
            }

            // Hàm cập nhật danh sách lựa chọn (không cho chọn trùng)
            function updateSelectOptions() {
                const selectedValues = $('.food-select').map(function () {
                    return $(this).val();
                }).get().filter(value => value !== "");

                $('.food-select').each(function () {
                    const currentValue = $(this).val();
                    $(this).find('option').each(function () {
                        $(this).prop('disabled', $(this).val() !== currentValue && selectedValues
                            .includes($(this).val()));
                    });
                });
            }

            // Hàm cập nhật tổng giá của combo
            function updateTotalPrice() {
                let totalPrice = 0;
                $('.food-item').each(function () {
                    const foodId = $(this).find('.food-select').val();
                    const quantity = parseInt($(this).find('.food-quantity').val()) || 0;
                    if (foodId && quantity > 0) {
                        totalPrice += (foodPrices[foodId] || 0) * quantity;
                    }
                });
                // Định dạng số có dấu "," ngăn cách
                let formattedPrice = totalPrice.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    // console.log(totalPrice);
                    // console.log(formattedPrice);
                // Cập nhật giá trị tổng (hiển thị có dấu ,)
                $('#price').val(formattedPrice);
                $('#price_hidden').val(totalPrice); // Lưu số không có dấu ,
            }

            // Hàm cập nhật sự kiện
            function updateEventHandlers() {
                $('.pluss').off('click').on('click', function () {
                    let $foodItem = $(this).closest('.food-item');
                    let $foodSelect = $foodItem.find('.food-select');
                    let $quantityInput = $foodItem.find('.food-quantity');

                    if ($foodSelect.val() === '') {
                        showAlert('warning', 'Vui lòng chọn món ăn trước khi tăng số lượng!', 'Thông báo');
                        return;
                    }

                    let currentValue = parseInt($quantityInput.val()) || 0;
                    if (currentValue < 10) {
                        $quantityInput.val(currentValue + 1).trigger('input');
                    }

                    updateTotalPrice();
                });

                $('.minuss').off('click').on('click', function () {
                    let $foodItem = $(this).closest('.food-item');
                    let $foodSelect = $foodItem.find('.food-select');
                    let $quantityInput = $foodItem.find('.food-quantity');
                    let $errorSpan = $foodItem.find('#' + $quantityInput.attr('id') + '_quantity_error');

                    if ($foodSelect.val() === '') {
                        $errorSpan.text('Vui lòng chọn món ăn trước!')
                        showAlert('warning', 'Vui lòng chọn món ăn trước khi giảm số lượng!', 'Thông báo');
                        return;
                    }

                    let currentValue = parseInt($quantityInput.val()) || 0;
                    if (currentValue > 1) {
                        $quantityInput.val(currentValue - 1).trigger('input');
                    }

                    updateTotalPrice();
                });

                $('.food-quantity').off('input').on('input', function () {
                    let newValue = parseInt($(this).val()) || 0;
                    if (newValue < 1) $(this).val(1);
                    if (newValue > 10) $(this).val(10);
                    updateTotalPrice();
                });

                $('.food-select').off('change').on('change', updateSelectOptions);
                $('.remove-food').off('click').on('click', function () {
                    removeFoodItem($(this).closest('.food-item'));
                });
            }

            // Xử lý khi nhấn nút thêm món ăn
            $('#add').on('click', addFoodItem);

            $('#updateFormCombo').on('submit', function(e) {
                let isValid = true; // Kiểm tra trạng thái form

                // Kiểm tra từng món ăn đã chọn chưa
                $('.food-item').each(function() {
                    let $foodSelect = $(this).find('.food-select');
                    let $quantityInput = $(this).find('.food-quantity');
                    let foodItemError = $(this).find('.text-danger');

                    const quantityValue = parseInt($quantityInput.val().trim());
                    const selectedFood = $foodSelect.val();

                    // Kiểm tra nếu món ăn chưa chọn
                    if (selectedFood === '' || selectedFood === null) {
                        isValid = false;
                        // Thêm lỗi nếu chưa có
                        if (foodItemError.length === 0) {
                            $foodSelect.after(
                                '<span class="text-danger small">Vui lòng chọn món ăn</span>');
                        } else {
                            foodItemError.text('Vui lòng chọn món ăn').show();
                        }
                    } else {
                        foodItemError.text('').hide();
                    }

                    // Thêm sự kiện xóa lỗi khi chọn lại món
                    $foodSelect.on('change', function() {
                        if ($(this).val() !== '') {
                            foodItemError.text('').hide();
                        }
                    });
                });

                // Kiểm tra số lượng món ăn
                $('.food-quantity').each(function() {
                    const quantityValue = parseInt($(this).val().trim());
                    let $errorSpan = $(this).closest('.w-100').find(
                        `#${$(this).attr('id')}_quantity_error`);

                    if (isNaN(quantityValue) || quantityValue <= 0) {
                        isValid = false;

                        // Kiểm tra nếu chưa có lỗi thì thêm mới
                        if ($errorSpan.length === 0) {
                            $(this).after(
                                `<span class="text-danger small" id="${$(this).attr('id')}_quantity_error">Số lượng chưa được chọn</span>`
                            );
                        } else {
                            $errorSpan.text('Số lượng chưa được chọn').show();
                        }
                    } else {
                        $errorSpan.text('').hide(); // Xóa lỗi nếu số lượng hợp lệ
                    }
                });

                // Chặn submit nếu form không hợp lệ và báo lỗi Toast
                if (!isValid) {
                    e.preventDefault();
                }
            });

            // Kiểm tra validate trước khi submit form
            // $('#updateFormCombo').on('submit', function (e) {
            //     let isValid = true;

            //     $('.food-select').each(function () {
            //         const foodItemError = $(this).siblings('.food-error');
            //         if ($(this).val() === '') {
            //             isValid = false;
            //             foodItemError.text('Vui lòng chọn món ăn');
            //         } else {
            //             foodItemError.text('');
            //         }
            //     });

            //     if (!isValid) {
            //         e.preventDefault();
            //         showAlert('warning', 'Vui lòng chọn đồ ăn trước khi cập nhật!', 'Thông báo');
            //     }
            // });

            function validateInput(selector, condition, errorMessage) {
                let input = $(selector);
                let value = input.val().trim();
            }

            // Validate tên combo
            $("#name").on("input", function () {
                validateInput(this, value => value.length > 0, "Tên combo không được để trống");
            });

            // Validate mô tả
            $("textarea[name='description']").on("input", function () {
                validateInput(this, value => value.length > 0, "Mô tả không được để trống");
            });

            // Validate ảnh
            $("#img_thumbnail").on("change", function () {
                validateInput(this, value => value.length > 0, "Vui lòng chọn ảnh");
            });


            updateEventHandlers(); // Cập nhật sự kiện ngay từ đầu
            updateSelectOptions();
        });
    </script>
@endsection