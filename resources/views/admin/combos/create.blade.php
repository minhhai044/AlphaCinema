@extends('admin.layouts.master')
@section('title', 'Quản lý Combo')

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
                                <!-- Các phần tử food sẽ được thêm vào đây -->
                                <p></p>
                            </div>


                            <div class="col-md-4 mb-3">
                                <div class="mb-3">
                                    <label for="name" class="form-label">
                                        <span class="text-danger">*</span> Tên Combo
                                    </label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        value="{{ old('name') }}" placeholder="Nhập tên món ăn">

                                    @if ($errors->has('name'))
                                        <div class="text-danger">
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
                                    <span class="text-danger">*</span> Giá bán (VNĐ)
                                </label>
                                <input type="text" name="price_sale" id="price_sale" class="form-control"
                                    value="{{ old('price_sale') }}" placeholder="Nhập giá tiền">

                                @if ($errors->has('price_sale'))
                                    <div class="text-danger">
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
                                <div class="text-danger">
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
                                            <span class=" text-danger required">*</span>
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
                            @if ($errors->has('img_thumbnail'))
                                <div class="text-danger">
                                    {{ $errors->first('img_thumbnail') }}
                                </div>
                            @endif
                            <!-- Display selected image and delete button -->
                            <div id="image-container" class="d-none position-relative">
                                <img id="image-preview" src="" alt="Preview" class="img-fluid mb-2"
                                    style="max-width: 100%; max-height: 200px;">
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

        $(document).ready(function() {




            // Format giá tiền
            function formatPriceInput(inputSelector, hiddenInputSelector) {
                $(inputSelector).on("input", function() {
                    let value = $(this).val().replace(/\D/g, ""); // Chỉ giữ lại số
                    let formattedValue = value.replace(/\B(?=(\d{3})+(?!\d))/g, ","); // Thêm dấu ,

                    $(this).val(formattedValue); // Hiển thị dạng số có dấu ,
                    $(hiddenInputSelector).val(value || "0"); // Lưu dạng số không có dấu , vào input ẩn
                });

                $(inputSelector).on("blur", function() {
                    if (!$(this).val()) {
                        $(this).val("0");
                        $(hiddenInputSelector).val("0");
                    }
                });
            }

            // Áp dụng cho input giá gốc & giá sale
            formatPriceInput("#price", "#price_hidden");
            formatPriceInput("#price_sale", "#price_sale_hidden");

            let foodCount = 0; // Biến đếm số lượng món ăn được thêm vào
            const minFoodItems = 2; // Số lượng món ăn tối thiểu
            const maxFoodItems = 8; // Số lượng món ăn tối đa

            const foodList = $('#food_list'); // Lấy danh sách chứa các món ăn

            // Danh sách giá món ăn (Thay thế bằng dữ liệu thực tế)
            const foodPrices = @json($foodPrice->pluck('price', 'id'));

            // Hàm tính tổng giá tiền dựa trên số lượng và giá của món ăn
            function updateTotalPrice() {
                let totalPrice = 0;
                $('.food-item').each(function() {
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


            // Hàm thêm món ăn vào danh sách
            function addFoodItem() {
                if (foodCount >= maxFoodItems) {
                    showAlert('warning ', 'Chỉ được thêm tối đa 8 món ăn', 'AlphaCinema thông báo');
                    // alert('Chỉ được thêm tối đa ' + maxFoodItems + ' món ăn.');
                    return;
                }
                const id = 'food_' + Date.now(); // Tạo ID duy nhất cho món ăn
                const foodItemHtml = `
                     <div class="col-md-12 mb-3 food-item d-flex align-items-center justify-content-between p-3 border rounded shadow-sm" id="${id}_item">
                        <div class="col-md-5">
                            <label for="${id}_select" class="form-label fw-bold">Đồ ăn</label>
                            <select name="combo_food[]" id="${id}_select" class="form-control food-select">
                                <option value="">--Chọn đồ ăn--</option>
                                @foreach ($food as $itemId => $itemName)
                                    <option value="{{ $itemId }}">{{ $itemName }}</option>
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
                                        id="${id}" value="0" min="0" max="10" readonly>
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

                foodList.append(foodItemHtml); // Thêm HTML vào danh sách món ăn
                foodCount++; // Tăng số lượng món ăn hiện tại

                updateEventHandlers(); // Cập nhật lại sự kiện cho các phần tử mới
                updateSelectOptions(); // Cập nhật danh sách món ăn đã chọn
                updateTotalPrice(); // Cập nhật tổng giá tiền
            }

            // Hàm xóa món ăn khỏi danh sách
            function removeFoodItem(foodItem) {
                if (foodCount > minFoodItems) {
                    foodItem.remove();
                    foodCount--;
                    updateTotalPrice();
                    updateSelectOptions();
                } else {
                    showAlert('warning', 'Cần ít nhất 2 món ăn để tạo Combo!', 'AlphaCinema thông báo');
                }
            }

            // Hàm cập nhật danh sách các món ăn đã chọn để tránh trùng lặp
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


            // Cập nhật sự kiện click khi nhấn nút tăng/giảm số lượng
            function updateEventHandlers() {
                // Xử lý tăng số lượng
                $('.pluss').off('click').on('click', function() {
                    let $foodItem = $(this).closest('.food-item');
                    let $foodSelect = $foodItem.find('.food-select');
                    let $quantityInput = $foodItem.find('.food-quantity');
                    let $errorSpan = $foodItem.find('#' + $quantityInput.attr('id') + '_quantity_error');

                    if ($foodSelect.val() === '') {
                        $errorSpan.text('Vui lòng chọn món ăn trước!');

                        return;
                    } else {
                        $errorSpan.text('');
                    }

                    let currentValue = parseInt($quantityInput.val());
                    if (currentValue < 10) {
                        $quantityInput.val(currentValue + 1);
                        updateTotalPrice();
                    }
                });

                // Xử lý giảm số lượng
                $('.minuss').off('click').on('click', function() {
                    let $foodItem = $(this).closest('.food-item');
                    let $foodSelect = $foodItem.find('.food-select');
                    let $quantityInput = $foodItem.find('.food-quantity');
                    let $errorSpan = $foodItem.find('#' + $quantityInput.attr('id') + '_quantity_error');

                    if ($foodSelect.val() === '') {
                        $errorSpan.text('Vui lòng chọn món ăn trước!');

                        return;
                    } else {
                        $errorSpan.text('');
                    }

                    let currentValue = parseInt($quantityInput.val());
                    if (currentValue > 0) {
                        $quantityInput.val(currentValue - 1);
                        updateTotalPrice();
                    }
                });
                // Xử lý chọn món ăn
                $('.food-select').off('change').on('change', updateSelectOptions);

                // Xóa món ăn
                $('.remove-food').off('click').on('click', function() {
                    removeFoodItem($(this).closest('.food-item'));
                });
            }


            $('#add').on('click', addFoodItem);

            // Thêm sẵn 2 món ăn vào danh sách khi tải trang
            for (let i = 0; i < minFoodItems; i++) {
                addFoodItem();
            }

            $('#comboForm').on('submit', function(e) {
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



            // Validate các trường input
            function validateInput(selector, condition, errorMessage) {
                let input = $(selector);
                let value = input.val().trim();

            }

            // Validate tên combo
            $("#name").on("input", function() {
                validateInput(this, value => value.length > 0, "Tên combo không được để trống");
            });

            // Validate mô tả
            $("textarea[name='description']").on("input", function() {
                validateInput(this, value => value.length > 0, "Mô tả không được để trống");
            });

            // Validate ảnh
            $("#img_thumbnail").on("change", function() {
                validateInput(this, value => value.length > 0, "Vui lòng chọn ảnh");
            });

        });
    </script>
@endsection
