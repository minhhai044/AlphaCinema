@extends('admin.layouts.master')
@section('title', 'Quản lý đồ ăn')

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
                <h4 class="mb-sm-0 font-size-18">Thêm mới đồ ăn</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.foods.index') }}">Đồ ăn</a>
                        </li>
                        <li class="breadcrumb-item active">Thêm mới đồ ăn</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <form action="{{ route('admin.foods.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-body">
                        <div class="col-md-12">
                            <div class="row p-3">
                                <div class="col-md-4 mb-3">
                                    <div class="mb-3">
                                        <label for="name" class="form-label"><span class="text-danger">*</span>Tên món
                                            ăn</label>
                                        <input type="text" name="name" id="name"
                                            class="form-control {{ $errors->has('name') ? 'is-invalid' : (old('name') ? 'is-valid' : '') }}"
                                            value="{{ old('name') }}" placeholder="Nhập tên món ăn">

                                        <div class="{{ $errors->has('name') ? 'invalid-feedback' : 'valid-feedback' }}">
                                            @if ($errors->has('name'))
                                                {{ $errors->first('name') }}
                                            @endif
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-4 mb-3">
                                    <div>
                                        <div class="mb-3">
                                            <label class="form-label" for="type">Loại đồ ăn</label>
                                            <select name="type" id="type"
                                                class="form-select {{ $errors->has('type') ? 'is-invalid' : (old('type') ? 'is-valid' : '') }}">
                                                <option value="" disabled selected>Chọn loại đồ ăn</option>
                                                @foreach ($typeFoods as $typeFood)
                                                    <option value="{{ $typeFood }}" @selected(old('type') == $typeFood)>
                                                        {{ $typeFood }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            <div class="invalid-feedback" id="type-error">
                                                @if ($errors->has('type'))
                                                    {{ $errors->first('type') }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div>
                                        <div class="mb-3">
                                            <label class="form-label" for="type"><span class="text-danger">*</span> Giá
                                                tiền</label>
                                            <input type="text"
                                                class="form-control {{ $errors->has('price') ? 'is-invalid' : (old('price') ? 'is-valid' : '') }}"
                                                name="price" id="price" placeholder="Nhập giá tiền"
                                                value="{{ old('price') }}">

                                            <div
                                                class="{{ $errors->has('price') ? 'invalid-feedback' : 'valid-feedback' }}">
                                                @if ($errors->has('price'))
                                                    {{ $errors->first('price') }}
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
                                        name="description" rows="6"
                                        placeholder="Nhập mô tả">{{ old('description') }}</textarea>

                                    <div class="{{ $errors->has('description') ? 'invalid-feedback' : 'valid-feedback' }}">
                                        @if ($errors->has('description'))
                                            {{ $errors->first('description') }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary">
                            Thêm mới
                            <i class="bx bx-chevron-right ms-1"></i>
                        </button>
                        <a href="{{ route('admin.foods.index') }}" class="btn btn-danger" type="button"
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
                                                <input type="checkbox" id="square-switch3" switch="bool" value="1"
                                                    name="is_active">
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
    $(document).ready(function () {
    $("#type").on("change", function () {
        let value = $(this).val();

        if (value) {
            $(this).removeClass("is-invalid").addClass("is-valid");
            $("#type-error").hide(); // Ẩn thông báo lỗi
        } else {
            $(this).removeClass("is-valid").addClass("is-invalid");
            $("#type-error").show(); // Hiện thông báo lỗi
        }
    });
});
$(document).ready(function () {
        // Validate tên món ăn (không được để trống)
        $("#name").on("input", function () {
            let value = $(this).val().trim();
            if (value.length === 0) {
                $(this).removeClass("is-valid").addClass("is-invalid");
                $(this).next(".invalid-feedback").text("Tên món ăn không được để trống").show();
            } else {
                $(this).removeClass("is-invalid").addClass("is-valid");
                $(this).next(".invalid-feedback").hide();
            }
        });

        // Validate Loại đồ ăn (phải chọn)
        $("#type").on("change", function () {
            let value = $(this).val();
            if (!value) {
                $(this).removeClass("is-valid").addClass("is-invalid");
                $("#type-error").text("Vui lòng chọn loại đồ ăn").show();
            } else {
                $(this).removeClass("is-invalid").addClass("is-valid");
                $("#type-error").hide();
            }
        });

        // Validate Giá tiền (phải là số)
        $("#price").on("input", function () {
            let value = $(this).val().trim();
            if (!/^\d+(\.\d{1,2})?$/.test(value)) { // Chỉ chấp nhận số nguyên hoặc số thập phân
                $(this).removeClass("is-valid").addClass("is-invalid");
                $(this).next(".invalid-feedback").text("Giá tiền phải là số hợp lệ").show();
            } else {
                $(this).removeClass("is-invalid").addClass("is-valid");
                $(this).next(".invalid-feedback").hide();
            }
        });

        // Validate Mô tả (không được để trống)
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
    });
 </script>
@endsection
