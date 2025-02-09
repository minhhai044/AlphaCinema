@extends('admin.layouts.master')
@section('title', 'Thêm mới mã giảm giá')

@section('style')
<style>
    .required {
        color: red;
        font-style: italic !important;
    }
    .card {
        box-shadow: 0px 1px 10px 3px #dedede;
    }
    .text-danger {
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
</style>
@endsection

@section('content')
<!-- Start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Thêm mới mã giảm giá</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.vouchers.index') }}">Mã giảm giá</a>
                    </li>
                    <li class="breadcrumb-item active">Thêm mới</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- End page title -->

<form action="{{ route('admin.vouchers.store') }}" method="POST">
    @csrf

    <div class="row">
        <!-- Left Content -->
        <div class="col-lg-9">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="code" class="form-label">
                                    <span class="required">*</span> Mã giảm giá (Tự động)
                                </label>
                                <input type="text" name="code" id="code" class="form-control" value="{{ old('code', Str::upper(Str::random(10))) }}" readonly>
                                @error('code')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="discount" class="form-label">
                                    <span class="required">*</span> Giảm giá (VNĐ)
                                </label>
                                <input type="number" name="discount" id="discount" class="form-control" placeholder="Nhập số tiền giảm giá" value="{{ old('discount') }}">
                                @error('discount')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="start_date_time" class="form-label">
                                    <span class="required">*</span> Ngày bắt đầu
                                </label>
                                <input type="datetime-local" name="start_date_time" id="start_date_time" class="form-control" value="{{ old('start_date_time') }}">
                                @error('start_date_time')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="end_date_time" class="form-label">
                                    <span class="required">*</span> Ngày kết thúc
                                </label>
                                <input type="datetime-local" name="end_date_time" id="end_date_time" class="form-control" value="{{ old('end_date_time') }}">
                                @error('end_date_time')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="quantity" class="form-label">
                                    <span class="required">*</span> Số lượng
                                </label>
                                <input type="number" name="quantity" id="quantity" class="form-control" placeholder="Nhập số lượng mã" value="{{ old('quantity') }}">
                                @error('quantity')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="limit_by_user" class="form-label">
                                    <span class="required">*</span> Giới hạn sử dụng
                                </label>
                                <input type="number" name="limit_by_user" id="limit_by_user" 
                                       class="form-control" 
                                       value="{{ old('limit_by_user', 1) }}" 
                                       placeholder="Nhập giới hạn sử dụng">
                                @error('limit_by_user')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        

                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="title" class="form-label">
                                    <span class="required">*</span> Tiêu đề
                                </label>
                                <input type="text" name="title" id="title" class="form-control" placeholder="Nhập tiêu đề" value="{{ old('title') }}">
                                @error('title')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="description" class="form-label">Mô tả</label>
                                <textarea name="description" id="description" class="form-control" rows="4" placeholder="Nhập mô tả">{{ old('description') }}</textarea>
                                @error('description')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button class="btn btn-primary">
                            Submit
                            <i class="bx bx-chevron-right ms-1"></i>
                        </button>
                        <button class="btn btn-danger" type="button">
                            Cancel
                            <i class="bx bx-chevron-right ms-1"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Content -->
        <div class="col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3 d-flex gap-2">
                                <label for="cinema-description" class="form-label">
                                    <span class="required">*</span>
                                    Active:
                                </label>
                                <div class="square-switch">
                                    <input type="checkbox" id="square-switch3" switch="bool" value="1" name="is_active" {{ old('is_active', 1) ? 'checked' : '' }}>
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
