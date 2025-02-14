@extends('admin.layouts.master')
@section('title', 'Quản lý Combo')

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
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Quản lý Combo</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Combo</a>
                        </li>
                        <li class="breadcrumb-item active">Danh sách Combo</li>
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
                                <h6 class="mb-sm-0 font-size-16">Danh sách Combo</h6>
                            </div>
                        </div>
                        <div class="col-sm-auto">
                            <div class="mb-4">
                                <a href="{{ route('admin.combos.create') }}"
                                   class="btn btn-light waves-effect waves-light">
                                    <i class="bx bx-plus me-1"></i>
                                    Thêm mới Combo
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="d-flex align-items-center flex-wrap gap-3">
                                <!-- Bộ lọc số lượng hiển thị -->
                                <div class="al-table-length">
                                    <label>
                                        Hiển thị
                                        <select name="example_length" aria-controls="example"
                                                class="form-select form-select-sm al-table-select">
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select>
                                        mục
                                    </label>
                                </div>

                                <!-- Form lọc -->
                                <form class="d-flex flex-wrap gap-4" id="form-filter">
                                    <!-- Lọc theo Tên đồ ăn -->
                                    <div class="form-group d-flex align-items-center">
                                        <label for="name" class="me-2">Lọc theo tên</label>
                                        <select class="form-control select-filter" id="name" name="name">
                                            <option selected>Tất cả</option>
                                            {{-- @foreach ($roles as $role => $value) --}}
                                            {{-- <option value="{{ $value }}" @if ((string) $value == $selectedRole) selected @endif> --}}
                                            {{-- {{ $role }} --}}
                                            {{-- </option> --}}
                                            {{-- @endforeach --}}
                                        </select>
                                    </div>

                                    <!-- Lọc theo Giá -->
                                    <div class="form-group d-flex align-items-center">
                                        <label for="price" class="me-2">Lọc theo giá</label>
                                        <select class="form-control select-filter" id="price" name="price">
                                            <option selected>Tất cả</option>
                                            {{-- @foreach ($cities as $city) --}}
                                            {{-- <option @if ($city == $selectedCity) selected @endif> --}}
                                            {{-- {{ $city }} --}}
                                            {{-- </option> --}}
                                            {{-- @endforeach --}}
                                        </select>
                                    </div>


                                </form>

                                <!-- Ô tìm kiếm -->
                                <div class="al-table-length ms-auto">
                                    <label>
                                        Tìm kiếm:
                                        <input type="search" class="form-control form-control-sm al-table-input"
                                               placeholder="Search đê">
                                    </label>
                                </div>
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
                                        <th>Tên Combo</th>
                                        <th>Hình ảnh</th>
                                        <th>Giá bán</th>
                                        <th>Hoạt động</th>
                                        <th>Hành động</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach ($data as $combo)
                                        <tr>
                                        <td class="sorting_1 dtr-control">
                                            <div class="d-none">{{ $combo->id }}</div>
                                            <div class="form-check font-size-{{ $combo->id }}">
                                                <input class="form-check-input" type="checkbox"
                                                       id="customerlistcheck-{{ $combo->id }}">
                                                <label class="form-check-label"
                                                       for="customerlistcheck-{{ $combo->id }}"></label>
                                            </div>
                                        </td>
                                        <td>
                                            {{ $combo->name }}
                                        </td>

                                        <td>
                                            @if($combo->img_thumbnail)
                                                <img class="img-thumbnail" alt="200x200" width="200"
                                                     src="{{ Storage::url($combo->img_thumbnail)  }}">
                                            @endif
                                        </td>
                                        <td>
                                            @if ($combo->price_sale > 0)
                                                <strong class="text-red-500">{{ number_format($combo->price_sale) }} VNĐ</strong>
                                                
                                            @else
                                                <strong>{{ number_format($combo->price) }} VNĐ</strong>
                                            @endif
                                        </td>

                                        <td>
                                            <div
                                                class="badge font-size-12 {{ $combo->is_active ? 'badge-soft-success' : 'badge-soft-danger' }}">
                                                {{ $combo->is_active ? 'Hoạt động' : 'Dừng hoạt động' }}
                                            </div>
                                        </td>

                                        <td>
                                            <div class="dropdown">
                                                <a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown"
                                                   aria-expanded="false">
                                                    <i class="mdi mdi-dots-horizontal font-size-18"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end" style="">
                                                    <li>
                                                        <a href="{{ route('admin.combos.show', $combo) }}"
                                                           class="dropdown-item edit-list"
                                                           data-edit-id="{{ $combo->id }}">
                                                            <i class="mdi mdi-pencil font-size-16 text-success me-1"></i>
                                                            Chi tiết
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('admin.combos.edit', $combo) }}"
                                                           class="dropdown-item edit-list"
                                                           data-edit-id="{{ $combo->id }}">
                                                            <i class="mdi mdi-pencil font-size-16 text-warning me-1"></i>
                                                            Cập nhật
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('admin.combos.forceDestroy', $combo) }}"
                                                              method="POST">
                                                            @method('DELETE')
                                                            @csrf

                                                            <button type="submit" class="dropdown-item remove-list"
                                                                    onclick="return confirm('Có chắc chắn xóa không ?')">
                                                                <i class="mdi mdi-trash-can font-size-16 text-danger me-1"></i>
                                                                Xóa
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{--                <div class="row">--}}
                    {{--                    <div class="col-sm-12 col-md-5">--}}
                    {{--                        <div class="al-table-info" id="customerList-table_info" role="status" aria-live="polite">--}}
                    {{--                            Hiển trị 1 to 10 of 12 trang--}}
                    {{--                        </div>--}}
                    {{--                    </div>--}}
                    {{--                    <div class="col-sm-12 col-md-7">--}}
                    {{--                        <div class="al-table-paginate paging_simple_numbers pagination-rounded" id="customerList-table_paginate">--}}
                    {{--                            <ul class="pagination">--}}
                    {{--                                <li class="paginate_button page-item previous disabled" id="customerList-table_previous"><a aria-controls="customerList-table" aria-disabled="true" role="link" data-dt-idx="previous" tabindex="-1" class="page-link"><i class="mdi mdi-chevron-left"></i></a></li>--}}
                    {{--                                <li class="paginate_button page-item active"><a href="#" aria-controls="customerList-table" role="link" aria-current="page" data-dt-idx="0" tabindex="0" class="page-link">1</a></li>--}}
                    {{--                                <li class="paginate_button page-item "><a href="#" aria-controls="customerList-table" role="link" data-dt-idx="1" tabindex="0" class="page-link">2</a></li>--}}
                    {{--                                <li class="paginate_button page-item next" id="customerList-table_next"><a href="#" aria-controls="customerList-table" role="link" data-dt-idx="next" tabindex="0" class="page-link"><i class="mdi mdi-chevron-right"></i></a></li>--}}
                    {{--                            </ul>--}}
                    {{--                        </div>--}}
                    {{--                    </div>--}}
                    {{--                </div>--}}
                </div>
            </div>
        </div>
    </div>

@endsection

