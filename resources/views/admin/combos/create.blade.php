@extends('admin.layouts.master')
@section('title', 'Thêm mới Combo')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Thêm mới Combo</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.combos.index') }}">Combo</a>
                        </li>
                        <li class="breadcrumb-item active">Thêm mới Combo</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <form action="{{ route('admin.combos.store') }}" method="POST" enctype="multipart/form-data" id="comboForm">
        @csrf
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
                                @if (old('combo_food'))
                                    @foreach (old('combo_food') as $index => $foodId)
                                        <div class="col-md-12 mb-3 food-item d-flex align-items-center justify-content-between p-3 border rounded shadow-sm"
                                            id="food_{{ $index }}_item">
                                            <div class="col-md-5">
                                                <label for="food_{{ $index }}_select" class="form-label fw-bold">Đồ
                                                    ăn</label>
                                                <select name="combo_food[]" id="food_{{ $index }}_select"
                                                    class="form-control food-select" data-id="food_{{ $index }}">
                                                    <option value="">--Chọn đồ ăn--</option>
                                                    @foreach ($foodPrice as $food)
                                                        <option value="{{ $food['id'] }}"
                                                            {{ $food['id'] == $foodId ? 'selected' : '' }}>
                                                            {{ $food['name'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has("combo_food.$index"))
                                                    <span
                                                        class="text-danger fw-medium small">{{ $errors->first("combo_food.$index") }}</span>
                                                @endif
                                            </div>

                                            <div class="col-md-4 d-flex align-items-center justify-content-between">
                                                <div class="w-100">
                                                    <label for="food_{{ $index }}" class="form-label fw-bold">Số
                                                        lượng</label>
                                                    <div class="d-flex align-items-center rounded p-2">
                                                        <button type="button"
                                                            class="minuss btn btn-danger px-3 py-1">-</button>
                                                        <input type="number" name="combo_quantity[]"
                                                            class="food-quantity text-center border-0 bg-transparent"
                                                            id="food_{{ $index }}"
                                                            value="{{ old('combo_quantity.' . $index, 0) }}" min="0"
                                                            max="10" readonly>
                                                        <button type="button"
                                                            class="pluss btn btn-success px-3 py-1">+</button>
                                                    </div>
                                                    @if ($errors->has("combo_quantity.$index"))
                                                        <span
                                                            class="text-danger fw-medium fw-medium small">{{ $errors->first("combo_quantity.$index") }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-2 d-flex align-items-end justify-content-center mt-3">
                                                <button type="button" class="btn btn-danger remove-food">
                                                    <span class="bx bx-trash"></span>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p></p>
                                @endif
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="mb-3">
                                    <label for="name" class="form-label">
                                        <span class="text-danger fw-medium">*</span> Tên Combo
                                    </label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        value="{{ old('name') }}" placeholder="Nhập tên món ăn">

                                    @if ($errors->has('name'))
                                        <div class="text-danger fw-medium ">
                                            {{ $errors->first('name') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="price" class="form-label ">Giá gốc (VNĐ)</label>
                                <input type="text" class="form-control bg-light" id="price" name="price" readonly>
                                <input type="hidden" id="price_hidden" name="price_hidden">
                            </div>


                            <div class="col-md-4 mb-3">
                                <label for="price_sale" class="form-label">
                                    <span class="text-danger fw-medium">*</span> Giá bán (VNĐ)
                                </label>
                                <input type="text" name="price_sale" id="price_sale" class="form-control"
                                    value="{{ old('price_sale') }}" placeholder="Nhập giá tiền">

                                @if ($errors->has('price_sale'))
                                    <div class="text-danger fw-medium">
                                        {{ $errors->first('price_sale') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="food-description" class="form-label">
                                <span class="required">*</span> Mô tả
                            </label>
                            <textarea class="form-control" name="description" rows="6" placeholder="Nhập mô tả">{{ old('description') }}</textarea>


                            @if ($errors->has('description'))
                                <div class="text-danger fw-medium">
                                    {{ $errors->first('description') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="card-footer">
                        <button class="btn btn-primary">
                            Thêm mới
                            <i class="bx bx-chevron-right ms-1"></i>
                        </button>
                        <a href="{{ route('admin.combos.index') }}" class="btn btn-danger" type="button">
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
                                            <span class=" text-danger fw-medium required">*</span>
                                            Hoạt động
                                        </label>
                                        <div class="round-switch">
                                            <input type="checkbox" id="switch6" switch="primary" value="1"
                                                name="is_active" {{ old('is_active', 1) ? 'checked' : '' }}>
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
                                        <span class=" text-danger fw-medium required">*</span>Ảnh
                                        <input class="form-control" type="file" name="img_thumbnail" id="combo-image"
                                            onchange="previewImage(event)">
                                </div>
                                @if (old('img_thumbnail'))
                                    <img src="{{ Storage::url(old('img_thumbnail')) }}" alt="Thumbnail" class="img-fluid"
                                        width="200" style="max-width: 70%; max-height: 150px;">
                                @endif
                                @error('img_thumbnail')
                                    <span class="text-danger fw-medium fw-medium">
                                        {{ $message }}
                                    </span>
                                @enderror
                                <!-- Display selected image and delete button -->
                                <div id="image-container" class="d-none position-relative text-center">
                                    <img id="image-preview" src="" alt="Preview" class="img-fluid mb-2"
                                        style="max-width: 70%; max-height: 100px;">
                                    <!-- Icon thùng rác ở góc phải -->
                                    <button type="button" id="delete-image"
                                        class="btn btn-danger position-absolute top-0 end-0 p-1" onclick="deleteImage()">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
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

        function deleteImage() {
            $('#image-container').addClass('d-none');
            $('#combo-image').val('');
            $('#image-preview').attr('src', '');
        }

        $(document).ready(function() {
            // Format giá tiền
            function formatPriceInput(inputSelector, hiddenInputSelector) {
                $(inputSelector).on("input", function() {
                    let value = $(this).val().replace(/\D/g, "");
                    let formattedValue = value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    $(this).val(formattedValue);
                    $(hiddenInputSelector).val(value || "0");
                });

                $(inputSelector).on("blur", function() {
                    if (!$(this).val()) {
                        $(this).val("0");
                        $(hiddenInputSelector).val("0");
                    }
                });
            }

            formatPriceInput("#price", "#price_hidden");
            formatPriceInput("#price_sale", "#price_sale_hidden");

            let foodCount = $('.food-item').length; // Đếm số món ăn hiện tại từ HTML
            const minFoodItems = 2;
            const maxFoodItems = 8;
            const foodList = $('#food_list');
            const foodPrices = @json($foodPrice->pluck('price', 'id'));

            function updateTotalPrice() {
                let totalPrice = 0;
                $('.food-item').each(function() {
                    const foodId = $(this).find('.food-select').val();
                    const quantity = parseInt($(this).find('.food-quantity').val()) || 0;
                    if (foodId && quantity > 0) {
                        totalPrice += (foodPrices[foodId] || 0) * quantity;
                    }
                });
                let formattedPrice = totalPrice.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                $('#price').val(formattedPrice);
                $('#price_hidden').val(totalPrice);
            }

            function addFoodItem() {
                if (foodCount >= maxFoodItems) {
                    showAlert('warning', 'Chỉ được thêm tối đa 8 món ăn', 'AlphaCinema thông báo');
                    return;
                }
                const id = 'food_' + Date.now();
                const foodItemHtml = `
                    <div class="col-md-12 mb-3 food-item d-flex align-items-center justify-content-between p-3 border rounded shadow-sm" id="${id}_item">
                        <div class="col-md-5">
                            <label for="${id}_select" class="form-label fw-bold">Đồ ăn</label>
                            <select name="combo_food[]" id="${id}_select" class="form-control food-select" data-id="${id}">
                                <option value="">--Chọn đồ ăn--</option>
                                @foreach ($foodPrice as $food)
                                    <option value="{{ $food['id'] }}">{{ $food['name'] }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger fw-medium small" id="${id}_food_error"></span>
                        </div>
                        <div class="col-md-4 d-flex align-items-center justify-content-between">
                            <div class="w-100">
                                <label for="${id}" class="form-label fw-bold">Số lượng</label>
                                <div class="d-flex align-items-center rounded p-2">
                                    <button type="button" class="minuss btn btn-danger px-3 py-1">-</button>
                                    <input type="number" name="combo_quantity[]" class="food-quantity text-center border-0 bg-transparent"
                                        id="${id}" value="0" min="0" max="10" readonly>
                                    <button type="button" class="pluss btn btn-success px-3 py-1">+</button>
                                </div>
                                <span class="text-danger fw-medium small" id="${id}_quantity_error"></span>
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-end justify-content-center mt-3">
                            <button type="button" class="btn btn-danger remove-food">
                                <span class="bx bx-trash"></span>
                            </button>
                        </div>
                    </div>
                `;
                foodList.append(foodItemHtml);
                foodCount++;
                updateEventHandlers();
                updateSelectOptions();
                updateTotalPrice();
            }

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

            function updateSelectOptions() {
                const selectedValues = $('.food-select').map(function() {
                    return $(this).val();
                }).get().filter(value => value !== "");
                $('.food-select').each(function() {
                    const currentValue = $(this).val();
                    $(this).find('option').each(function() {
                        $(this).prop('disabled', $(this).val() !== currentValue && selectedValues
                            .includes($(this).val()));
                    });
                });
            }
            // nếu đã chọn đồ ăn ẩn -chọn đồ ăn-
            $(document).on('change', '.food-select', function() {
                let selectedValue = $(this).val();
                if (selectedValue) {
                    $(this).find('option[value=""]').hide();
                }
            });

            function updateEventHandlers() {
                $('.pluss').off('click').on('click', function() {
                    let $foodItem = $(this).closest('.food-item');
                    let $foodSelect = $foodItem.find('.food-select');
                    let $quantityInput = $foodItem.find('.food-quantity');
                    let $errorSpan = $foodItem.find(`#${$quantityInput.attr('id')}_quantity_error`);
                    if ($foodSelect.val() === '') {
                        $errorSpan.text('Vui lòng chọn món ăn trước!');
                        return;
                    } else {
                        $errorSpan.text('');
                    }

                    let currentValue = parseInt($quantityInput.val()) || 0;
                    if (currentValue < 10) {
                        $quantityInput.val(currentValue + 1);
                        updateTotalPrice();
                        $errorSpan.text('');
                    } else if (currentValue >= 10) {
                        $errorSpan.removeClass('text-danger fw-medium').addClass(
                            'text-warning'); // Xanh cho thành công
                        $errorSpan.text('Số lượng tối đa là 10');
                    }
                });

                $('.minuss').off('click').on('click', function() {
                    let $foodItem = $(this).closest('.food-item');
                    let $foodSelect = $foodItem.find('.food-select');
                    let $quantityInput = $foodItem.find('.food-quantity');
                    let $errorSpan = $foodItem.find(`#${$quantityInput.attr('id')}_quantity_error`);
                    if ($foodSelect.val() === '') {
                        $errorSpan.text('Vui lòng chọn món ăn trước!');
                        return;
                    } else {
                        $errorSpan.text('');
                    }
                    let currentValue = parseInt($quantityInput.val()) || 0;
                    if (currentValue > 0) {
                        $quantityInput.val(currentValue - 1);
                        updateTotalPrice();
                        $errorSpan.text(''); // Xóa lỗi nếu giảm thành công
                    } else if (currentValue <= 0) {
                        $errorSpan.removeClass('text-danger fw-medium').addClass(
                            'text-danger fw-medium'); // Xanh cho thành công
                        $errorSpan.text('Số lượng phải lớn hơn 0');
                    }
                });

                $('.food-select').off('change').on('change', function() {
                    updateSelectOptions();
                    updateTotalPrice();
                });

                $('.remove-food').off('click').on('click', function() {
                    removeFoodItem($(this).closest('.food-item'));
                });
            }

            $('#add').on('click', addFoodItem);

            // Chỉ thêm 2 món mặc định nếu không có dữ liệu cũ
            if ($('.food-item').length === 0) {
                for (let i = 0; i < minFoodItems; i++) {
                    addFoodItem();
                }
            } else {
                updateEventHandlers(); // Gắn sự kiện cho các món ăn từ old()
                updateTotalPrice(); // Cập nhật giá ngay khi tải trang
                updateSelectOptions(); // Cập nhật danh sách chọn
            }

            $('#comboForm').on('submit', function(e) {
                let isValid = true;
                $('.food-item').each(function() {
                    let $foodSelect = $(this).find('.food-select');
                    let $quantityInput = $(this).find('.food-quantity');
                    let $foodError = $(this).find(`#${$foodSelect.attr('id')}_food_error`);
                    let $quantityError = $(this).find(
                        `#${$quantityInput.attr('id')}_quantity_error`);

                    if (!$foodSelect.val()) {
                        isValid = false;
                        $foodError.text('Vui lòng chọn món ăn');
                    } else {
                        $foodError.text('');
                    }
                    // const quantity = parseInt($quantityInput.val());
                    // if (isNaN(quantity) || quantity <= 0) {
                    //     isValid = false;
                    //     $quantityError.text('Số lượng phải lớn hơn 0');
                    // } else {
                    //     $quantityError.text('');
                    // }
                    // Kiểm tra price_sale so với price
                    const price = parseInt($('#price_hidden').val() || 0); // Giá gốc từ input ẩn
                    const priceSaleInput = $('#price_sale');
                    const priceSale = parseInt(priceSaleInput.val().replace(/\D/g, '')) ||
                        0; // Giá bán, bỏ dấu phẩy
                    // Chỉ kiểm tra khi priceSale > 0
                    if (priceSale > 0) {
                        if (priceSale >= price) {
                            isValid = false;
                            if ($priceSaleError.length) {
                                $priceSaleError.text('Giá bán phải nhỏ hơn giá gốc');
                            } else {
                                priceSaleInput.after(
                                    '<span id="price_sale_error" class="text-danger fw-medium small">Giá bán phải nhỏ hơn giá gốc</span>'
                                );
                            }
                        } else {
                            if ($priceSaleError.length) {
                                $priceSaleError.text('');
                            }
                        }
                    } else {
                        // Nếu priceSale chưa nhập hoặc bằng 0, không hiển thị lỗi
                        if ($priceSaleError.length) {
                            $priceSaleError.text('');
                        }
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection
