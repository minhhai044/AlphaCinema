@extends('admin.layouts.master')
@section('title', 'Chỉnh sửa User Voucher')

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
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<!-- Start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Chỉnh sửa User Voucher</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.user-vouchers.index') }}">User Voucher</a>
                    </li>
                    <li class="breadcrumb-item active">Chỉnh sửa</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- End page title -->

<form action="{{ route('admin.user-vouchers.update', $User_voucher) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row">
        <!-- Left Content -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="user_id" class="form-label">
                                    <span class="required">*</span> Người dùng
                                </label>
                                <select name="user_id_disabled" id="user_id" class="form-select" disabled>
                                    <option value="">Chọn người dùng</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" {{ $User_voucher->user_id == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                
                                <input type="hidden" name="user_id" value="{{ $User_voucher->user_id }}">
                                @error('user_id')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="voucher_id" class="form-label">
                                    <span class="required">*</span> Voucher
                                </label>
                                <select name="voucher_id" id="voucher_id" class="form-select">
                                    <option value="">Chọn voucher</option>
                                    @foreach ($vouchers as $voucher)
                                        <option value="{{ $voucher->id }}" {{ $User_voucher->voucher_id == $voucher->id ? 'selected' : '' }}>
                                            {{ $voucher->title }} ({{ $voucher->code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('voucher_id')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="usage_count" class="form-label">
                                    <span class="required">*</span> Số lần sử dụng
                                </label>
                                <input type="number" name="usage_count" id="usage_count" 
                                       class="form-control" 
                                       placeholder="Nhập số lần sử dụng" 
                                       value="{{ old('usage_count', $User_voucher->usage_count) }}">
                                @error('usage_count')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button class="btn btn-primary">
                            Cập nhật
                        </button>
                        <button class="btn btn-danger" type="button" onclick="window.history.back();">
                            Hủy
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
