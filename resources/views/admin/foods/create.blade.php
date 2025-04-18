@extends('admin.layouts.master')
@section('title', 'Thêm mới đồ ăn')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Thêm mới đồ ăn</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.foods.index') }}">Đồ ăn</a>
                        </li>
                        <li class="breadcrumb-item active">Thêm mới đồ ăn</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->


    <form action="{{ route('admin.foods.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="is_active" value="1">
        <div class="row">
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="mb-3">
                                    <label for="name" class="form-label">
                                        <span class="text-danger fw-medium">*</span> Tên đồ ăn
                                    </label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        value="{{ old('name') }}" placeholder="Nhập tên món ăn">

                                    @error('name')
                                        <span class="text-danger fw-medium">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="price" class="form-label">
                                    <span class="text-danger fw-medium">*</span> Giá bán (VNĐ)
                                </label>
                                <input type="text" name="price" id="price" class="form-control"
                                    value="{{ old('price') }}" placeholder="Nhập giá tiền">

                                @error('price')
                                    <span class="text-danger fw-medium">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="type" class="form-label">
                                    <span class="text-danger fw-medium">*</span> Loại đồ ăn
                                </label>
                                <select name="type" id="typeFood" class="form-select">
                                    <option value="">Chọn loại đồ ăn</option>
                                    @foreach ($typeFoods as $typeFood)
                                        <option value="{{ $typeFood }}" @selected(old('type') == $typeFood)>{{ $typeFood }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('type')
                                    <span class="text-danger fw-medium">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="food-description" class="form-label">
                                <span class="required">*</span> Mô tả
                            </label>
                            <textarea class="form-control" name="description" rows="6" placeholder="Nhập mô tả">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button class="btn btn-primary" type="submit">
                            Thêm mới
                            <i class="bx bx-chevron-right ms-1"></i>
                        </button>
                        <a href="{{ route('admin.foods.index') }}" class="btn btn-danger" type="button">
                            Hủy bỏ
                            <i class="bx bx-chevron-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-2">
                            <label for="combo-image" class="form-label">
                                <span class=" text-danger fw-medium required">*</span> Ảnh
                                <input class="form-control" type="file" name="img_thumbnail" id="combo-image"
                                    onchange="previewImage(event)">
                        </div>
                        @if (old('img_thumbnail'))
                            <img src="{{ Storage::url(old('img_thumbnail')) }}" alt="Thumbnail" class="img-fluid"
                                width="200" style="max-width: 70%; max-height: 150px;">
                        @endif
                        @error('img_thumbnail')
                            <span class="text-danger fw-medium">
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

        });
    </script>
@endsection
