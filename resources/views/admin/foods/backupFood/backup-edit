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
                <h4 class="mb-sm-0 font-size-18">Cập nhật đồ ăn</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.foods.index') }}">Đồ ăn</a>
                        </li>
                        <li class="breadcrumb-item active">Cập nhật đồ ăn: {{ $data->name }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <form action="{{ route('admin.foods.update', $data->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')  <!-- Đảm bảo phương thức PUT -->
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
                                            value="{{ $data->name }}" placeholder="Nhập tên món ăn">

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
                                            <label class="form-label" for="type">Loại đồ ăn </label>
                                            <select name="type" id="type" class="form-select">
                                                @foreach ($typeFoods as $typeFood)
                                                    <option value="{{ $typeFood }}" @selected($data->type== $typeFood))>
                                                        {{ $typeFood }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div>
                                        <div class="mb-3">
                                            <label class="form-label" for="price"><span class="text-danger">*</span> Giá
                                                tiền</label>
                                            <input type="text"
                                                class="form-control {{ $errors->has('price') ? 'is-invalid' : (old('price') ? 'is-valid' : '') }}"
                                                name="price" id="price" placeholder="Nhập giá tiền"
                                                value="{{ $data->price }}">

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
                                        placeholder="Nhập mô tả">{{ $data->description }}</textarea>

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
                            Cập nhật
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
                                                <input type="checkbox" @checked($data->is_active) id="square-switch3" switch="bool" value="1"
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
                                    <input type="file" name="img_thumbnail" id="img_thumbnail" class="form-control {{ $errors->has('img_thumbnail') ? 'is-invalid' : (old('img_thumbnail') ? 'is-valid' : '') }}">
                                    <div
                                        class="{{ $errors->has('img_thumbnail') ? 'invalid-feedback' : 'valid-feedback' }}">
                                        @if ($errors->has('img_thumbnail'))
                                            {{ $errors->first('img_thumbnail') }}
                                        @endif
                                    </div>
                                    @if($data->img_thumbnail)
                                    <div class="mt-3 text-center">
                                        <img src="{{ Storage::url($data->img_thumbnail) }}" alt="Thumbnail"
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
