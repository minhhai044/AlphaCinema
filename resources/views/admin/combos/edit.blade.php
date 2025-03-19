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
                            <div class="col-md-12 d-flex justify-content-between">
                                <label for="" class="form-label"></label>
                                <button type="button" class="btn btn-primary" id="add">Thêm đồ ăn</button>
                            </div>
                            <div id="food_list" class="col-md-12">
                                @if (old('combo_food'))
                                    <!-- Giữ giá trị cũ khi submit thất bại -->
                                    @foreach (old('combo_food') as $index => $foodId)
                                        <div class="col-md-12 mb-3 food-item d-flex align-items-center justify-content-between p-3 border rounded shadow-sm"
                                            id="food_{{ $index }}_item">
                                            <div class="col-md-5">
                                                <label for="food_{{ $index }}_select" class="form-label fw-bold">Đồ
                                                    ăn</label>
                                                <select name="combo_food[]" id="food_{{ $index }}_select"
                                                    class="form-control food-select" data-id="food_{{ $index }}">
                                                    <option value="">--Chọn đồ ăn--</option>
                                                    @foreach ($food as $itemId => $itemName)
                                                        <option value="{{ $itemId }}"
                                                            {{ $foodId == $itemId ? 'selected' : '' }}>
                                                            {{ $itemName }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger small" id="food_{{ $index }}_food_error">
                                                    @if ($errors->has("combo_food.$index"))
                                                        {{ $errors->first("combo_food.$index") }}
                                                    @endif
                                                </span>
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
                                                            max="10">
                                                        <button type="button"
                                                            class="pluss btn btn-success px-3 py-1">+</button>
                                                    </div>
                                                    <span class="text-danger small"
                                                        id="food_{{ $index }}_quantity_error">
                                                        @if ($errors->has("combo_quantity.$index"))
                                                            {{ $errors->first("combo_quantity.$index") }}
                                                        @endif
                                                    </span>
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
                                    <!-- Hiển thị dữ liệu hiện tại của combo -->
                                    @foreach ($combo->comboFood as $index => $item)
                                        <div class="col-md-12 mb-3 food-item d-flex align-items-center justify-content-between p-3 border rounded shadow-sm"
                                            id="food_{{ $index }}_item">
                                            <div class="col-md-5">
                                                <label for="food_{{ $index }}_select" class="form-label fw-bold">Đồ
                                                    ăn</label>
                                                <select name="combo_food[]" id="food_{{ $index }}_select"
                                                    class="form-control food-select" data-id="food_{{ $index }}">
                                                    @foreach ($food as $itemId => $itemName)
                                                        <option value="{{ $itemId }}" @selected($item->food_id == $itemId)>
                                                            {{ $itemName }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger small"
                                                    id="food_{{ $index }}_food_error"></span>
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
                                                            id="food_{{ $index }}" value="{{ $item->quantity }}"
                                                            min="0" max="10">
                                                        <button type="button"
                                                            class="pluss btn btn-success px-3 py-1">+</button>
                                                    </div>
                                                    <span class="text-danger small"
                                                        id="food_{{ $index }}_quantity_error"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-2 d-flex align-items-end justify-content-center mt-3">
                                                <button type="button" class="btn btn-danger remove-food">
                                                    <span class="bx bx-trash"></span>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="mb-3">
                                    <label for="name" class="form-label"><span class="text-danger">*</span>Tên
                                        Combo</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        value="{{ old('name', $combo->name) }}" placeholder="Nhập tên món ăn">
                                    @if ($errors->has('name'))
                                        <div class="text-danger">{{ $errors->first('name') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="price" class="form-label">Giá gốc (VNĐ)</label>
                                <input type="text" class="form-control" id="price" name="price"
                                    value="{{ formatPrice($combo->price) }}" readonly>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="mb-3">
                                    <label for="price_sale" class="form-label"><span class="text-danger">*</span>Giá
                                        Sale(VNĐ)</label>
                                    <input type="text" class="form-control" name="price_sale" id="price_sale"
                                        placeholder="Nhập giá tiền"
                                        value="{{ old('price_sale', formatPrice($combo->price_sale)) }}">
                                    @if ($errors->has('price_sale'))
                                        <div class="text-danger">{{ $errors->first('price_sale') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="food-description" class="form-label"><span class="required">*</span> Mô
                                tả</label>
                            <textarea class="form-control" name="description" rows="6" placeholder="Nhập mô tả">{{ old('description', $combo->description) }}</textarea>
                            @if ($errors->has('description'))
                                <div class="text-danger">{{ $errors->first('description') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="card-footer">
                        <button class="btn btn-primary">
                            Cập nhật <i class="bx bx-chevron-right ms-1"></i>
                        </button>
                        <a href="{{ route('admin.combos.index') }}" class="btn btn-danger" type="button"
                            onclick="return confirm('Bạn có chắc chắn hủy bỏ thao tác? Mọi thay đổi sẽ không được lưu')">
                            Hủy bỏ <i class="bx bx-chevron-right ms-1"></i>
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
                                    <div class="d-flex gap-2">
                                        <label for="cinema-description" class="form-label"><span
                                                class="required">*</span> Hoạt động</label>
                                        <div class="round-switch">
                                            <input type="checkbox" @checked(old('is_active', $combo->is_active)) id="switch6"
                                                switch="primary" value="1" name="is_active">
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
                                    <label for="combo-image" class="form-label"><span
                                            class="text-danger required">*</span>Ảnh</label>
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
                                    <div class="text-danger">{{ $errors->first('img_thumbnail') }}</div>
                                @endif
                                <div id="image-container" class="d-none position-relative mt-3 text-center">
                                    <img id="image-preview" src="" alt="Preview"
                                        class="img-fluid mb-2 text-center" style="max-width: 70%; max-height: 150px;">
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
            $('#delete-image').click(() => {
                $('#image-container').addClass('d-none');
                $('#combo-image').val('');
                $('#image-preview').attr('src', '');
            });
        }

        $(document).ready(function() {
            let foodCount = $('.food-item').length;
            const minFoodItems = 2;
            const maxFoodItems = 8;
            const foodList = $('#food_list');
            const foodPrices = @json($foodPrice->pluck('price', 'id'));

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
                            <span class="text-danger small" id="${id}_food_error"></span>
                        </div>
                        <div class="col-md-4 d-flex align-items-center justify-content-between">
                            <div class="w-100">
                                <label for="${id}" class="form-label fw-bold">Số lượng</label>
                                <div class="d-flex align-items-center rounded p-2">
                                    <button type="button" class="minuss btn btn-danger px-3 py-1">-</button>
                                    <input type="number" name="combo_quantity[]" class="food-quantity text-center border-0 bg-transparent"
                                        id="${id}" value="0" min="0" max="10">
                                    <button type="button" class="pluss btn btn-success px-3 py-1">+</button>
                                </div>
                                <span class="text-danger small" id="${id}_quantity_error"></span>
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
                        $(this).prop('disabled', $(this).val() !== currentValue && selectedValues.includes($(this).val()));
                    });
                });
            }

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
            }

            function updateEventHandlers() {
                $('.pluss').off('click').on('click', function() {
                    let $foodItem = $(this).closest('.food-item');
                    let $foodSelect = $foodItem.find('.food-select');
                    let $quantityInput = $foodItem.find('.food-quantity');
                    let $errorSpan = $foodItem.find('.text-danger[id$="_quantity_error"]');
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
                    }
                });

                $('.minuss').off('click').on('click', function() {
                    let $foodItem = $(this).closest('.food-item');
                    let $foodSelect = $foodItem.find('.food-select');
                    let $quantityInput = $foodItem.find('.food-quantity');
                    let $errorSpan = $foodItem.find('.text-danger[id$="_quantity_error"]');
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
                    }
                });

                $('.food-quantity').off('input').on('input', function() {
                    let newValue = parseInt($(this).val()) || 0;
                    if (newValue < 0) $(this).val(0);
                    if (newValue > 10) $(this).val(10);
                    updateTotalPrice();
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

            $('#updateFormCombo').on('submit', function(e) {
                let isValid = true;
                $('.food-item').each(function() {
                    let $foodSelect = $(this).find('.food-select');
                    let $quantityInput = $(this).find('.food-quantity');
                    let $foodError = $(this).find('.text-danger[id$="_food_error"]');
                    let $quantityError = $(this).find('.text-danger[id$="_quantity_error"]');

                    const quantityValue = parseInt($quantityInput.val().trim());
                    const selectedFood = $foodSelect.val();

                    if (selectedFood === '' || selectedFood === null) {
                        isValid = false;
                        $foodError.text('Vui lòng chọn món ăn');
                    } else {
                        $foodError.text('');
                    }

                    if (isNaN(quantityValue) || quantityValue <= 0) {
                        isValid = false;
                        $quantityError.text('Số lượng phải lớn hơn 0');
                    } else {
                        $quantityError.text('');
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                }
            });

            updateEventHandlers();
            updateSelectOptions();
            updateTotalPrice();
        });
    </script>
@endsection
