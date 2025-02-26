@extends('admin.layouts.master')
@section('title', 'Quản lý chi nhánh')

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
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Thêm mới chi nhánh</h4>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="live-preview">
                        <form action="{{ route('admin.branches.store') }}" method="post">
                            @csrf
                            <div class="row ">
                                <div class="mb-2">
                                    <label for="name" class="form-label">
                                        <span class="text-danger">*</span>Tên chi nhánh:
                                    </label>
                                    <input type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : (old('name') ? 'is-valid' : '') }}" name="name" value="{{ old('name') }}"
                                        placeholder="Nhập tên chi nhánh">
                                    <div class="form-text">
                                        Tên chi nhánh của rạp chiếu phim.
                                    </div>
                                    <div class="{{ $errors->has('name') ? 'invalid-feedback' : 'valid-feedback' }}">
                                        @if ($errors->has('name'))
                                            {{ $errors->first('name') }}
                                        @endif
                                    </div>
                                </div>


                                <div class="text-end mt-2">
                                    <button type="submit" class="btn btn-primary mx-1">Thêm mới</button>
                                </div>
                            </div>
                        </form>

                        <!--end row-->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="row">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm">
                                <div class="mb-4">
                                    <h6 class="mb-sm-0 font-size-16">Danh sách chi nhánh</h6>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="al-table-length">
                                    <label>
                                        Show
                                        <select name="example_length" aria-controls="example"
                                            class="form-select form-select-sm al-table-select">
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select>
                                        entries
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="al-table-length text-end">
                                    <form method="GET" action="{{ route('admin.branches.index') }}" id="searchForm">
                                        <label>
                                            Tìm kiếm:
                                            <input type="search" name="search" id="searchInput"
                                                class="form-control form-control-sm al-table-input"
                                                placeholder="Nhập tên chi nhánh" value="{{ request('search') }}">
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
                                                <th>Tên chi nhánh</th>
                                                <th>Hoạt động</th>
                                                <th>Ngày tạo</th>
                                                <th>Ngày cập nhật</th>
                                                <th>Chức năng</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($branches as $branch)
                                                <tr>
                                                    <td class="sorting_1 dtr-control">
                                                        <div class="d-none">{{ $branch->id }}</div>
                                                        <div class="form-check font-size-{{ $branch->id }}">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="customerlistcheck-{{ $branch->id }}">
                                                            <label class="form-check-label"
                                                                for="customerlistcheck-{{ $branch->id }}"></label>
                                                        </div>
                                                    </td>
                                                    <td class="branch-name">
                                                        {{ $branch->name }}
                                                        <br>
                                                        {{-- <a href="javascript:void(0);" class="text-primary editBranch"
                                                            data-id="{{ $branch->id }}" data-name="{{ $branch->name }}"
                                                            data-active="{{ $branch->is_active }}">
                                                            Sửa
                                                        </a> --}}
                                                    </td>
                                                    <td>
                                                        <div
                                                            class="badge font-size-12 {{ $branch->is_active ? 'badge-soft-success' : 'badge-soft-danger' }}">
                                                            {{ $branch->is_active ? 'Active' : 'No Active' }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        {{ $branch->created_at->format('d/m/Y') }}<br>{{ $branch->created_at->format('H:i:s') }}
                                                    </td>
                                                    <td>
                                                        {{ $branch->updated_at->format('d/m/Y') }}<br>{{ $branch->updated_at->format('H:i:s') }}
                                                    </td>

                                                    <td>
                                                        <div class="dropdown">
                                                            <a href="#" class="dropdown-toggle card-drop"
                                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="mdi mdi-dots-horizontal font-size-18"></i>
                                                            </a>
                                                            <ul class="dropdown-menu dropdown-menu-end" style="">
                                                                {{-- Button edit --}}
                                                                <li>
                                                                    <button type="button" data-bs-toggle="modal"
                                                                        class="edit_type_room dropdown-item editBranch"
                                                                        data-bs-target="#exampleModal" data-id={{ $branch->id }}
                                                                        data-name="{{ $branch->name }}"
                                                                        data-active="{{ $branch->is_active }}">
                                                                        <i
                                                                            class="mdi mdi-pencil font-size-16 text-success me-1"></i>
                                                                        Edit
                                                                    </button>
                                                                </li>
                                                                {{-- xoas --}}
                                                                <li>
                                                                    <form method="POST"
                                                                        action="{{ route('admin.branches.destroy', $branch) }}"
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
                                                    </td>
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
                                    {{-- <ul class="pagination">
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
                                    </ul> --}}

                                    {{ $branches->appends(request()->query())->links('pagination::bootstrap-4') }}

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal chỉnh sửa -->
    <div class="modal fade" id="editBranchModal" tabindex="-1" aria-labelledby="editBranchModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered"> <!-- Thêm modal-dialog-centered -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editBranchModalLabel">Cập nhật chi nhánh</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editBranchForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="branchName" class="form-label">Tên chi nhánh</label>
                            <input type="text" class="form-control" id="branchName" name="name"
                                placeholder="Nhập tên chi nhánh" required>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="branchActive" name="is_active">
                            <label class="form-check-label" for="branchActive">Hoạt động</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput'); // Lấy ô tìm kiếm
        const rows = document.querySelectorAll('#customerList-table tbody tr'); // Lấy tất cả hàng trong bảng

        // Tìm kiếm ngay khi nhập
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const keyword = searchInput.value
                    .toLowerCase(); // Lấy từ khóa tìm kiếm và chuyển về chữ thường

                rows.forEach(row => {
                    const branchName = row.querySelector('.branch-name').textContent
                        .toLowerCase(); // Lấy tên chi nhánh

                    // So sánh từ khóa với tên chi nhánh
                    if (branchName.includes(keyword)) {
                        row.style.display = ''; // Hiện hàng nếu khớp
                    } else {
                        row.style.display = 'none'; // Ẩn hàng nếu không khớp
                    }
                });
            });
        }

        // Modal sửa chi nhánh
        document.querySelectorAll('.editBranch').forEach(function(button) {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                const name = this.dataset.name;
                const isActive = this.dataset.active == 1;

                // Cập nhật form trong modal
                const form = document.getElementById('editBranchForm');
                form.action = `/admin/branches/${id}`;
                document.getElementById('branchName').value = name;
                document.getElementById('branchActive').checked = isActive;

                // Hiển thị modal
                const modal = new bootstrap.Modal(document.getElementById('editBranchModal'));
                modal.show();
            });
        });
    });
</script>
