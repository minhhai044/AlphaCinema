@extends('admin.layouts.master')
@section('title', 'Quản lý rạp')

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
            <h4 class="mb-sm-0 font-size-18">Quản lý chi nhánh</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <a href="javascript: void(0);">Chi nhánh</a>
                    </li>
                    <li class="breadcrumb-item active">Danh sách chi nhánh</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->
<form action="{{ route('admin.branches.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-lg-9">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div>
                                <div class="mb-3">
                                    <label for="cinema-name" class="form-label">
                                        <span class="required">*</span>
                                        Name
                                    </label>
                                    <input class="form-control" type="text" name="name" id="cinema-name" placeholder="Enter cinema name">
                                </div>
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
                                    <input type="checkbox" id="square-switch3" switch="bool" value="1" name="is_active">
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
