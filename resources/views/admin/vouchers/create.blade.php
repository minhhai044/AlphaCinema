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
                                    <input type="text" name="code" id="code"
                                        class="form-control {{ $errors->has('code') ? 'is-invalid' : (old('code') ? 'is-valid' : '') }}"
                                        value="{{ old('code', session('voucher_code', Str::upper(Str::random(10)))) }}"
                                        placeholder="Nhập mã giảm giá">

                                    <div class="{{ $errors->has('code') ? 'invalid-feedback' : 'valid-feedback' }}">
                                        @if ($errors->has('code'))
                                            {{ $errors->first('code') }}
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">

                                    <label for="formatted_discount" class="form-label">
                                        <span class="required">*</span> Giảm giá (VNĐ)
                                    </label>
                                    <input type="text" id="formatted_discount"
                                        class="form-control {{ $errors->has('discount') ? 'is-invalid' : (old('discount') ? 'is-valid' : '') }}"
                                        placeholder="Nhập số tiền giảm giá" value="{{ old('discount') }}">

                                    <input type="hidden" name="discount" id="discount_hidden"
                                        value="{{ old('discount') }}">

                                    <div class="{{ $errors->has('discount') ? 'invalid-feedback' : 'valid-feedback' }}">
                                        @if ($errors->has('discount'))
                                            {{ $errors->first('discount') }}
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="start_date_time" class="form-label">
                                        <span class="required">*</span> Ngày bắt đầu
                                    </label>
                                    <input type="datetime-local" name="start_date_time" id="start_date_time"
                                        class="form-control {{ $errors->has('start_date_time') ? 'is-invalid' : (old('start_date_time') ? 'is-valid' : '') }}"
                                        value="{{ old('start_date_time') }}">
                                    <div
                                        class="{{ $errors->has('start_date_time') ? 'invalid-feedback' : 'valid-feedback' }}">
                                        @if ($errors->has('start_date_time'))
                                            {{ $errors->first('start_date_time') }}
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="end_date_time" class="form-label">
                                        <span class="required">*</span> Ngày kết thúc
                                    </label>
                                    <input type="datetime-local" name="end_date_time" id="end_date_time"
                                        class="form-control {{ $errors->has('end_date_time') ? 'is-invalid' : (old('end_date_time') ? 'is-valid' : '') }}"
                                        value="{{ old('end_date_time') }}">
                                    <div
                                        class="{{ $errors->has('end_date_time') ? 'invalid-feedback' : 'valid-feedback' }}">
                                        @if ($errors->has('end_date_time'))
                                            {{ $errors->first('end_date_time') }}
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">
                                        <span class="required">*</span> Số lượng
                                    </label>
                                    <input type="number" name="quantity" id="quantity"
                                        class="form-control {{ $errors->has('quantity') ? 'is-invalid' : (old('quantity') ? 'is-valid' : '') }}"
                                        placeholder="Nhập số lượng mã" value="{{ old('quantity') }}">
                                    <div class="{{ $errors->has('quantity') ? 'invalid-feedback' : 'valid-feedback' }}">
                                        @if ($errors->has('quantity'))
                                            {{ $errors->first('quantity') }}
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="limit_by_user" class="form-label">
                                        <span class="required">*</span> Giới hạn sử dụng
                                    </label>
                                    <input type="number" name="limit_by_user" id="limit_by_user"
                                        class="form-control {{ $errors->has('limit_by_user') ? 'is-invalid' : (old('limit_by_user') ? 'is-valid' : '') }}"
                                        value="{{ old('limit_by_user', 1) }}" placeholder="Nhập giới hạn sử dụng">
                                    <div
                                        class="{{ $errors->has('limit_by_user') ? 'invalid-feedback' : 'valid-feedback' }}">
                                        @if ($errors->has('limit_by_user'))
                                            {{ $errors->first('limit_by_user') }}
                                        @endif
                                    </div>
                                </div>
                            </div>


                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="title" class="form-label">
                                        <span class="required">*</span> Tiêu đề
                                    </label>
                                    <input type="text" name="title" id="title"
                                        class="form-control {{ $errors->has('title') ? 'is-invalid' : (old('title') ? 'is-valid' : '') }}"
                                        placeholder="Nhập tiêu đề" value="{{ old('title') }}">
                                    <div class="{{ $errors->has('title') ? 'invalid-feedback' : 'valid-feedback' }}">
                                        @if ($errors->has('title'))
                                            {{ $errors->first('title') }}
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Mô tả</label>
                                    <textarea name="description" id="description"
                                        class="form-control {{ $errors->has('description') ? 'is-invalid' : (old('description') ? 'is-valid' : '') }}"
                                        rows="4" placeholder="Nhập mô tả">{{ old('description') }}</textarea>
                                    <div
                                        class="{{ $errors->has('description') ? 'invalid-feedback' : 'valid-feedback' }}">
                                        @if ($errors->has('description'))
                                            {{ $errors->first('description') }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button class="btn btn-primary">
                            Thêm
                            </button>
                            <a href="{{ route('admin.vouchers.index') }}" class="btn btn-danger">
                                Hủy
                            </a>
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
                                        Hoạt động:
                                    </label>
                                    <div class="round-switch">
                                        <input type="checkbox" id="switch6" switch="primary" value="1"
                                            name="is_active" {{ old('is_active', 1) ? 'checked' : '' }}>
                                        <label for="switch6" data-on-label="Yes" data-off-label="No"></label>
                                    </div>
                                </div>
                            </div>
                            <label for="">Áp dụng cho</label>
                            <select name="type_voucher" class="form-control select2" required>
                                <option value="0" selected>Áp dụng cho Ghế</option>
                                <option value="1">Áp dụng cho Food/Combo</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const formattedInput = document.getElementById("formatted_discount");
            const hiddenInput = document.getElementById("discount_hidden");

            formattedInput.addEventListener("input", function(e) {
                let value = e.target.value.replace(/\D/g, "");
                if (value !== "") {
                    formattedInput.value = Number(value).toLocaleString('en-US');
                    hiddenInput.value = value;
                } else {
                    hiddenInput.value = "0";
                }
            });

            formattedInput.addEventListener("blur", function(e) {
                if (e.target.value === "") {
                    e.target.value = "0";
                    hiddenInput.value = "0";
                }
            });
        });
    </script>
@endsection
