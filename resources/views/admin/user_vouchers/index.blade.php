@extends('admin.layouts.master')
@section('title', 'Quản lý User Vouchers')

@section('style')
    <style>
        .card {
            box-shadow: 0px 1px 10px 3px #dedede;
        }

        .al-table-length label {
            font-weight: normal;
            text-align: left;
            white-space: nowrap;
        }

        .al-table-length .al-table-select {
            width: auto;
            display: inline-block;
        }

        .al-table-length .al-table-input {
            margin-left: .5em;
            display: inline-block;
            width: auto;
        }

        .al-table-info {
            padding-top: .85em;
        }

        .al-table-paginate {
            margin: 0;
            white-space: nowrap;
            text-align: right;
        }

        .al-table-paginate .pagination {
            margin: 2px 0;
            white-space: nowrap;
            justify-content: flex-end;
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

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Quản lý User Vouchers</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">User Vouchers</a>
                        </li>
                        <li class="breadcrumb-item active">Danh sách</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm">
                            <div class="mb-4">
                                <h6 class="mb-sm-0 font-size-16">Danh sách User Vouchers</h6>
                            </div>
                        </div>
                        <div class="col-sm-auto">
                            <div class="mb-4">
                                <a href="{{ route('admin.user-vouchers.create') }}" class="btn btn-light waves-effect waves-light">
                                    <i class="bx bx-plus me-1"></i> Thêm mới
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="al-table-length">
                                <label>
                                    Hiển thị
                                    <select name="example_length" aria-controls="example"
                                        class="form-select form-select-sm al-table-select">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                    
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="al-table-length text-end">
                                <form method="GET" action="{{ route('admin.user-vouchers.index') }}" id="searchForm">
                                    <label>
                                        Tìm kiếm:
                                        <input type="search" name="search" id="searchInput"
                                            class="form-control form-control-sm al-table-input"
                                            placeholder="Nhập tên người dùng hoặc mã voucher" value="{{ request('search') }}">
                                    </label>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table class="table align-middle table-nowrap dt-responsive nowrap w-100"
                                    id="customerList-table">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Người dùng</th>

                                            <th>Mã voucher</th>
                                            <th>Tên voucher</th>


                                            <th>Số lần sử dụng</th>
                                            <th>Chức năng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($User_vouchers as $User_voucher)
                                    <tr>
                                        <td class="sorting_1 dtr-control">
                                            <div class="d-none">{{ $User_voucher->id }}</div>
                                            <div class="form-check font-size-{{ $User_voucher->id }}">
                                                <input class="form-check-input" type="checkbox"
                                                    id="customerlistcheck-{{ $User_voucher->id }}">
                                                <label class="form-check-label"
                                                    for="customerlistcheck-{{ $User_voucher->id }}"></label>
                                            </div>
                                        </td>
                                        <td>{{ $User_voucher->user->name }}</td>

                                        <td>{{ $User_voucher->voucher->code }}</td>
                                        <td>{{ $User_voucher->voucher->title }}</td>
                                        <td>{{ $User_voucher->usage_count }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="mdi mdi-dots-horizontal font-size-18"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end" style="">
                                                    <li>
                                                        <a href="{{ route('admin.user-vouchers.edit', $User_voucher) }}" class="dropdown-item edit-list" data-edit-id="{{ $User_voucher->id }}">
                                                            <i class="mdi mdi-pencil font-size-16 text-success me-1"></i>
                                                            Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form method="POST"
                                                            action="{{ route('admin.user-vouchers.destroy', $User_voucher) }}"
                                                            class="d-inline-block">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item edit-list"
                                                                onclick="return confirm('Bạn có muốn xóa không')">
                                                                <i class="mdi mdi-trash-can font-size-16 text-danger me-1"></i>
                                                                Xóa
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        
                                    </tr>
                                @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 col-md-5">
                            <div class="al-table-info" id="customerList-table_info" role="status" aria-live="polite">
                                Showing 1 to 10 of 12 entries
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-7">
                            <div class="al-table-paginate paging_simple_numbers pagination-rounded"
                                id="customerList-table_paginate">
                                <ul class="pagination">
                                    <li class="paginate_button page-item previous disabled"
                                        id="customerList-table_previous"><a aria-controls="customerList-table"
                                            aria-disabled="true" role="link" data-dt-idx="previous" tabindex="-1"
                                            class="page-link"><i class="mdi mdi-chevron-left"></i></a>
                                    </li>
                                    <li class="paginate_button page-item active"><a href="#"
                                            aria-controls="customerList-table" role="link" aria-current="page"
                                            data-dt-idx="0" tabindex="0" class="page-link">1</a></li>
                                    <li class="paginate_button page-item "><a href="#"
                                            aria-controls="customerList-table" role="link" data-dt-idx="1"
                                            tabindex="0" class="page-link">2</a></li>
                                    <li class="paginate_button page-item next" id="customerList-table_next"><a
                                            href="#" aria-controls="customerList-table" role="link"
                                            data-dt-idx="next" tabindex="0" class="page-link"><i
                                                class="mdi mdi-chevron-right"></i></a></li>
                                </ul>

                                {{-- {{ $User_voucher->appends(request()->query())->links('pagination::bootstrap-4') }} --}}

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
