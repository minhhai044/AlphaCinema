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
    <form action="{{ route('admin.combos.update', $combo->id) }}" method="POST" enctype="multipart/form-data" id="updateFormCombo">
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
                                                                    <select name="combo_food[]" id="${id}_select" class="form-control food-select">
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
                                                                                value="{{ $item->quantity }}" min="0" max="10" readonly>
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
                                    <input type="text" name="name" id="name"
                                        class="form-control {{ $errors->has('name') ? 'is-invalid' : (old('name') ? 'is-valid' : '') }}"
                                        data-="" value="{{ $combo->name }}" placeholder="Nhập tên món ăn">

                                    <div class="{{ $errors->has('name') ? 'invalid-feedback' : 'valid-feedback' }}">
                                        @if ($errors->has('name'))
                                            {{ $errors->first('name') }}
                                        @endif
                                    </div>
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
                                        class="form-control {{ $errors->has('price_sale') ? 'is-invalid' : (old('price_sale') ? 'is-valid' : '') }}"
                                        name="price_sale" id="price_sale" placeholder="Nhập giá tiền"
                                        value="{{ formatPrice($combo->price_sale) }}">
                                    <div class="{{ $errors->has('price_sale') ? 'invalid-feedback' : 'valid-feedback' }}">
                                        @if ($errors->has('price_sale'))
                                            {{ $errors->first('price_sale') }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="food-description" class="form-label">
                                <span class="required">*</span> Mô tả
                            </label>
                            <textarea
                                class="form-control {{ $errors->has('description') ? 'is-invalid' : (old('description') ? 'is-valid' : '') }}"
                                name="description" rows="6" placeholder="Nhập mô tả">{{ $combo->description }}</textarea>

                            <div class="{{ $errors->has('description') ? 'invalid-feedback' : 'valid-feedback' }}">
                                @if ($errors->has('description'))
                                    {{ $errors->first('description') }}
                                @endif
                            </div>
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
                                    <div class="col-md-4">
                                        <div class="mb-2">
                                            <label for="food-description" class="form-label">
                                                <span class="required">*</span>
                                                Hoạt động:
                                            </label>
                                            <div class="square-switch">
                                                <input type="checkbox" @checked($combo->is_active) id="square-switch3"
                                                    switch="bool" value="1" name="is_active">
                                                <label for="square-switch3" data-on-label="Yes" data-off-label="No"></label>
                                            </div>
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
                                    <label for="img_thumbnail" class="form-label"> <span class="text-danger">*</span>Hình
                                        ảnh</label>
                                    <input type="file" name="img_thumbnail" id="img_thumbnail"
                                        class="form-control {{ $errors->has('img_thumbnail') ? 'is-invalid' : (old('img_thumbnail') ? 'is-valid' : '') }}">
                                    <div
                                        class="{{ $errors->has('img_thumbnail') ? 'invalid-feedback' : 'valid-feedback' }}">
                                        @if ($errors->has('img_thumbnail'))
                                            {{ $errors->first('img_thumbnail') }}
                                        @endif
                                    </div>
                                    @if ($combo->img_thumbnail)
                                        <div class="mt-3 text-center">
                                            <img src="{{ Storage::url($combo->img_thumbnail) }}" alt="Thumbnail"
                                                class="img-fluid" width="200">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </form>

@endsection
@section('script')
    <script>
        // Hàm thêm món ăn vào danh sách
        $(document).ready(function () {
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
                const id = 'food_' + Date.now(); // Tạo ID duy nhất cho món ăn mới
                const foodItemHtml = `
                        <div class="col-md-12 mb-3 food-item d-flex align-items-center justify-content-between p-3 border rounded" id="${id}_item">
                            <div class="col-md-6">
                                <label for="${id}_select" class="form-label">Đồ ăn</label>
                                <select name="combo_food[]" id="${id}_select" class="form-control food-select">
                                    <option value="">--Chọn đồ ăn--</option>
                                    @foreach ($food as $itemId => $itemName)
                                        <option value="{{ $itemId }}">{{ $itemName }}</option>
                                    @endforeach
                                </select>
                                 <span class="text-danger" id="${id}_food_error"></span>
                            </div>
                            <div class="col-md-3 mx-4">
                                <label for="${id}" class="form-label">Số lượng</label>
                                <div class="d-flex flex-wrap align-items-start">
                                    <div class="input-step step-primary full-width p-1 d-flex align-items-center border rounded">
                                        <button type="button" class="minuss btn btn-danger px-3 py-1">-</button>
                                        <input type="number" name="combo_quantity[]" class="food-quantity text-center border-0 mx-2" id="${id}" value="0" min="1" max="10" readonly>
                                        <button type="button" class="pluss btn btn-success px-3 py-1">+</button>
                                    </div>
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
                $('#price').val(totalPrice);
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

                    let currentValue = parseInt($quantityInput.val());
                    if (currentValue < 10) {
                        $quantityInput.val(currentValue + 1);
                        updateTotalPrice();
                    }
                });

                $('.minuss').off('click').on('click', function () {
                    let $foodItem = $(this).closest('.food-item');
                    let $foodSelect = $foodItem.find('.food-select');
                    let $quantityInput = $foodItem.find('.food-quantity');

                    if ($foodSelect.val() === '') {
                        showAlert('warning', 'Vui lòng chọn món ăn trước khi giảm số lượng!', 'Thông báo');
                        return;
                    }

                    let currentValue = parseInt($quantityInput.val());
                    if (currentValue > 0) {
                        $quantityInput.val(currentValue - 1);
                        updateTotalPrice();
                    }
                });

                $('.food-select').off('change').on('change', updateSelectOptions);
                $('.remove-food').off('click').on('click', function () {
                    removeFoodItem($(this).closest('.food-item'));
                });
            }

            // Xử lý khi nhấn nút thêm món ăn
            $('#add').on('click', addFoodItem);

            // Kiểm tra validate trước khi submit form
            $('#updateFormCombo').on('submit', function (e) {
            let isValid = true; // Biến đánh dấu trạng thái hợp lệ của form

           // Kiểm tra mỗi phần tử food-select trong food list
            $('.food-select').each(function () {
                const foodItemError = $(this).siblings('.food-item').find('.text-danger');
                if ($(this).val() === '') {
                    isValid = false; // Nếu chưa chọn món ăn, đặt trạng thái không hợp lệ
                    foodItemError.text('Vui lòng chọn món ăn'); // Hiển thị lỗi
                } else {
                    foodItemError.text(''); // Xóa lỗi nếu đã chọn món ăn
                }
                });

                // Nếu form không hợp lệ, ngừng submit
                if (!isValid) {
                    e.preventDefault();
                    showAlert('warning', 'Vui lòng chọn đồ ăn trước khi cập nhật!', 'Bạn chưa chọn đồ ăn');
                }
            });

        });
        $("#name").on("input", function () {
            let value = $(this).val().trim();
            if (value.length === 0) {
                $(this).removeClass("is-valid").addClass("is-invalid");
                $(this).next(".invalid-feedback").text("Tên combo không được để trống").show();
            } else {
                $(this).removeClass("is-invalid").addClass("is-valid");
                $(this).next(".invalid-feedback").hide();
            }
        });

        $("#price_sale").on("input", function () {
            let value = $(this).val().trim();
            if (!/^\d+(\.\d{1,2})?$/.test(value)) { // Chỉ chấp nhận số nguyên hoặc số thập phân
                $(this).removeClass("is-valid").addClass("is-invalid");
                $(this).next(".invalid-feedback").text("Giá bán Combo phải là số hợp lệ").show();
            } else {
                $(this).removeClass("is-invalid").addClass("is-valid");
                $(this).next(".invalid-feedback").hide();
            }
        });

        $("textarea[name='description']").on("input", function () {
            let value = $(this).val().trim();
            if (value.length === 0) {
                $(this).removeClass("is-valid").addClass("is-invalid");
                $(this).next(".invalid-feedback").text("Mô tả không được để trống").show();
            } else {
                $(this).removeClass("is-invalid").addClass("is-valid");
                $(this).next(".invalid-feedback").hide();
            }
        });

        $("#img_thumbnail").on("change", function () {
            let value = $(this).val().trim();
            if (value.length === 0) {
                $(this).removeClass("is-valid").addClass("is-invalid");
                $(this).next(".invalid-feedback").text("Vui lòng chọn ảnh").show();
            } else {
                $(this).removeClass("is-invalid").addClass("is-valid");
                $(this).next(".invalid-feedback").hide();
            }
        });

        // Validate giá gốc và giá bán Combo khi thay đổi
        $("#price, #price_sale").on("input", function () {
            let value = $(this).val();
            if (isNaN(value) || value <= 0) {
                $(this).removeClass("is-valid").addClass("is-invalid");
                $(this).next(".invalid-feedback").text("Vui lòng nhập giá hợp lệ").show();
            } else {
                $(this).removeClass("is-invalid").addClass("is-valid");
                $(this).next(".invalid-feedback").hide();
            }
        });

    </script>
@endsection
