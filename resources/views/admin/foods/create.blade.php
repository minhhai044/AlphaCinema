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
                        <div class="row">

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Tên món ăn</label>
                                    <input
                                        type="text"
                                        name="name"
                                        id="name"
                                        class="form-control {{ $errors->has('name') ? 'is-invalid' : (old('name') ? 'is-valid' : '') }}"
                                        value="{{ old('name') }}"
                                        placeholder="Nhập tên món ăn"
                                    >

                                    <div class="{{ $errors->has('name') ? 'invalid-feedback' : 'valid-feedback' }}">
                                        @if ($errors->has('name'))
                                            {{ $errors->first('name') }}
                                        @endif
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="img_thumbnail" class="form-label">Chọn hình ảnh</label>
                                    <input
                                        class="form-control {{ $errors->has('img_thumbnail') ? 'is-invalid' : (old('img_thumbnail') ? 'is-valid' : '') }}"
                                        type="file"
                                        id="img_thumbnail"
                                        name="img_thumbnail"
                                    >

                                    <div
                                        class="{{ $errors->has('img_thumbnail') ? 'invalid-feedback' : 'valid-feedback' }}">
                                        @if ($errors->has('img_thumbnail'))
                                            {{ $errors->first('img_thumbnail') }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <div class="mb-3">
                                        <label class="form-label" for="price">Giá tiền</label>
                                        <input
                                            type="text"
                                            class="form-control {{ $errors->has('price') ? 'is-invalid' : (old('price') ? 'is-valid' : '') }}"
                                            name="price"
                                            id="price"
                                            placeholder="Nhập giá tiền"
                                            value="{{ old('price') }}">

                                        <div
                                            class="{{ $errors->has('price') ? 'invalid-feedback' : 'valid-feedback' }}">
                                            @if ($errors->has('price'))
                                                {{ $errors->first('price') }}
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="food-description" class="form-label">
                                        <span class="required">*</span> Mô tả
                                    </label>
                                    <textarea
                                        class="form-control {{ $errors->has('description') ? 'is-invalid' : (old('description') ? 'is-valid' : '') }}"
                                        name="description"
                                        rows="6"
                                        placeholder="Nhập mô tả">{{ old('description') }}</textarea>

                                    <div
                                        class="{{ $errors->has('description') ? 'invalid-feedback' : 'valid-feedback' }}">
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
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3 d-flex gap-2">
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
        </div>
    </form>

@endsection
