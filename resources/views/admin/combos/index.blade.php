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

                                <a href="{{ route('admin.combos.create') }}" class="btn btn-light waves-effect waves-light">
                                    <i class="bx bx-plus me-1"></i>
                                    Thêm mới Combo
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
                                <table class="table align-middle table-nowrap table-bordered dt-responsive nowrap w-100"
                                    id="customerList-table">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Tên Combo</th>
                                            <th>Hình ảnh</th>
                                            <th>Thông tin Combo</th>
                                            <th>Giá bán</th>
                                            <th>Hoạt động</th>
                                            <th>Chức năng</th>
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

                                                <td class="text-center">
                                                    @if ($combo->img_thumbnail)
                                                        <img class="img-thumbnail" width="150px" height="60px"
                                                            src="{{ Storage::url($combo->img_thumbnail) }}">
                                                    @endif
                                                </td>
                                                <td>
                                                    @foreach ($combo->food as $itemFood)
                                                        <ul class="nav nav-sm flex-column">
                                                            <li class="nav-item mb-2">
                                                                {{ $itemFood->name }} x
                                                                ({{ $itemFood->pivot->quantity }})
                                                            </li>
                                                        </ul>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @if ($combo->price_sale > 0)
                                                        <strong class="text-red-500">{{ formatPrice($combo->price_sale) }}
                                                            VNĐ</strong>
                                                    @else
                                                        <strong>{{ formatPrice($combo->price) }} VNĐ</strong>
                                                    @endif
                                                </td>

                                                <td>
                                                    <input type="checkbox" id="is_active{{$combo->id}}"
                                                        data-publish="{{ $combo->is_publish }}" switch="success"
                                                        @checked($combo->is_active) />
                                                    <label for="is_active{{$combo->id}}"></label>
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
                                                                    <i
                                                                        class="mdi mdi-pencil font-size-16 text-success me-1"></i>
                                                                    Chi tiết
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="{{ route('admin.combos.edit', $combo) }}"
                                                                    class="dropdown-item edit-list"
                                                                    data-edit-id="{{ $combo->id }}">
                                                                    <i
                                                                        class="mdi mdi-pencil font-size-16 text-warning me-1"></i>
                                                                    Cập nhật
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <form action="{{ route('admin.combos.forceDestroy', $combo) }}"
                                                                    method="POST" id="delete-combo-{{ $combo->id }}">
                                                                    @method('DELETE')
                                                                    @csrf

                                                                    <button type="submit" class="dropdown-item remove-list"
                                                                        onclick="handleDelete({{ $combo->id }})">
                                                                        <i
                                                                            class="mdi mdi-trash-can font-size-16 text-danger me-1"></i>
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
                    <div class="row">
                        {{ $data->onEachSide(1)->links('admin.layouts.components.pagination') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="{{ asset('assets/js/combo/index.js') }}"></script>
@endsection
