@extends('admin.layouts.master')
@section('title', 'Quản lý loại phòngphòng')

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
                <h4 class="mb-sm-0 font-size-18">Thêm mới loại phòng</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.typerooms.index') }}">Loại phòng </a>
                        </li>
                        <li class="breadcrumb-item active">Thêm mới loại phòng </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <form action="{{ route('admin.typerooms.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div>
                                    <div class="mb-3">
                                        <label for="typeroom-name" class="form-label">
                                            <span class="required">*</span>
                                            Tên loại phòng
                                        </label>
                                        <input class="form-control" type="text" name="name" id="typeroom-name"
                                            placeholder="Nhập tên loại phòng ">
                                    </div>
                                    @error('name')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <div class="mb-3">
                                        <label for="surcharge" class="form-label">
                                            <span class="required">*</span>
                                            Chênh lệch giá
                                        </label>
                                        <input class="form-control" type="number" name="surcharge" id="surcharge"
                                            placeholder="Nhập giá chênh lệch  ">
                                    </div>
                                    @error('surcharge')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
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
    </form>

@endsection
