@extends('admin.layouts.master')
@section('title', 'Chỉnh sửa mã giảm giá')

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
                <h4 class="mb-sm-0 font-size-18">Chỉnh sửa mã giảm giá</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.vouchers.index') }}">Mã giảm giá</a>
                        </li>
                        <li class="breadcrumb-item active">Chỉnh sửa</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- End page title -->

    <form method="POST" action="{{ route('admin.vouchers.update', $voucher) }}">
        @csrf
        @method('PUT')

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
                                    <input type="text" name="code" id="code" class="form-control"
                                        value="{{ old('code', $voucher->code) }}">
                                    @error('code')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="formatted_discount" class="form-label">
                                        <span class="required">*</span> Giảm giá (VNĐ)
                                    </label>
                                    <input type="text" id="formatted_discount"
                                        class="form-control {{ $errors->has('discount') ? 'is-invalid' : (old('discount') ? 'is-valid' : '') }}"
                                        placeholder="Nhập số tiền giảm giá"
                                        value="{{ number_format(old('discount', $voucher->discount), 0, '.', ',') }}">

                                    <input type="hidden" name="discount" id="discount_hidden"
                                        value="{{ old('discount', $voucher->discount) }}">

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
                                    <input type="datetime-local" name="start_date_time" id="start_date_time"
                                        class="form-control"
                                        value="{{ old('start_date_time', $voucher->start_date_time ? $voucher->start_date_time->format('Y-m-d\TH:i') : '') }}">
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
                                    <input type="datetime-local" name="end_date_time" id="end_date_time"
                                        class="form-control"
                                        value="{{ old('end_date_time', $voucher->end_date_time ? $voucher->end_date_time->format('Y-m-d\TH:i') : '') }}">
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
                                    <input type="number" name="quantity" id="quantity" class="form-control"
                                        placeholder="Nhập số lượng mã" value="{{ old('quantity', $voucher->quantity) }}">
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
                                    <input type="number" name="limit_by_user" id="limit_by_user" class="form-control"
                                        value="{{ old('limit_by_user', $voucher->limit_by_user) }}"
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
                                    <input type="text" name="title" id="title" class="form-control"
                                        placeholder="Nhập tiêu đề" value="{{ old('title', $voucher->title) }}">
                                    @error('title')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Mô tả</label>
                                    <textarea name="description" id="description" class="form-control" rows="4"
                                        placeholder="Nhập mô tả">{{ old('description', $voucher->description) }}</textarea>
                                    @error('description')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button class="btn btn-primary">
                                Cập nhật
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
                                        Active:
                                    </label>
                                    <div class="round-switch">
                                        <input type="hidden" name="is_active" value="0">

                                        <input type="checkbox" id="switch6" switch="primary" value="1"
                                            name="is_active" {{ old('is_active', 1) ? 'checked' : '' }}>
                                        <label for="switch6" data-on-label="Yes" data-off-label="No"></label>


                                    </div>
                                </div>
                            </div>
                            <select name="type_voucher" class="form-control" required>
                                <option value="0" {{ $voucher->type_voucher == 0 ? 'selected' : '' }}>Áp dụng cho Ghế</option>
                                <option value="1" {{ $voucher->type_voucher == 1 ? 'selected' : '' }}>Áp dụng cho Food/Combo</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>



@endsection



<script>
    document.addEventListener("DOMContentLoaded", function () {
        const formattedInput = document.getElementById("formatted_discount");
        const hiddenInput = document.getElementById("discount_hidden");

        formattedInput.addEventListener("input", function (e) {
            let value = e.target.value.replace(/[^\d]/g, "");
            if (value !== "") {
                formattedInput.value = Number(value).toLocaleString('en-US');
                hiddenInput.value = value;
            } else {
                hiddenInput.value = "0";
            }
        });

        formattedInput.addEventListener("blur", function (e) {
            if (e.target.value === "") {
                e.target.value = "0";
                hiddenInput.value = "0";
            }
        });
    });
</script>
