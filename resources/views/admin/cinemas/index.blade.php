@extends('admin.layouts.master')
@section('title', 'Quản lý rạp')

@section('content')
{{-- <a href="{{ route('export', ['table' => 'cinemas']) }}" class="btn btn-success">
    Xuất Excel
</a> --}}

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Cinemas Managers</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Cinemas</a>
                        </li>
                        <li class="breadcrumb-item active">Cinema List</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card al-card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm">
                            <div class="mb-4">
                                <h6 class="mb-sm-0 font-size-16">Cinema List</h6>
                            </div>
                        </div>
                        <div class="col-sm-auto d-flex gap-2">
                            <div class="mb-4">
                                <a id="openCreateCinemaModal" class="btn btn-light waves-effect waves-light">
                                    <i class="bx bx-plus me-1"></i>
                                    Thêm Mới
                                </a>
                            </div>
                            <div class="mb-4">
                                <a href="{{ route('admin.export', 'cinemas') }}"
                                    class="btn btn-warning waves-effect waves-light">
                                    <i class="bx bx-download me-1"></i>
                                    Xuất Excel
                                </a>
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
                                <label>
                                    Search:
                                    <input type="search" class="form-control form-control-sm al-table-input"
                                        placeholder="Search đê">
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive min-vh-100">
                                <table class="table align-middle table-nowrap dt-responsive nowrap w-100"
                                    id="customerList-table">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Branch</th>
                                            <th>Address</th>
                                            <th>Active</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cinemas as $cinema)
                                            <td class="sorting_1 dtr-control">
                                                <div class="d-none">{{ $cinema->id }}</div>
                                                <div class="form-check font-size-{{ $cinema->id }}">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="customerlistcheck-{{ $cinema->id }}">
                                                    <label class="form-check-label"
                                                        for="customerlistcheck-{{ $cinema->id }}"></label>
                                                </div>
                                            </td>
                                            <td>
                                                {{ $cinema->name }}
                                            </td>
                                            <td>
                                                {{ $cinema->branch->name }}
                                            </td>
                                            <td>
                                                {{ $cinema->address }}
                                            </td>
                                            <td>
                                                <div
                                                    class="badge font-size-12 {{ $cinema->is_active ? 'badge-soft-success' : 'badge-soft-danger' }}">
                                                    {{ $cinema->is_active ? 'Active' : 'No Active' }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <a href="#" class="dropdown-toggle card-drop"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="mdi mdi-dots-horizontal font-size-18"></i>
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-menu-end" style="">
                                                        <li>
                                                            <a class="dropdown-item edit-list cursor-pointer openUpdateCinemaModal"
                                                                data-edit-id="{{ $cinema->id }}"
                                                                data-branch-id="{{ $cinema->branch_id }}"
                                                                data-name="{{ $cinema->name }}"
                                                                data-address="{{ $cinema->address }}"
                                                                data-description="{{ $cinema->description }}">
                                                                <i
                                                                    class="mdi mdi-pencil font-size-16 text-success me-1"></i>
                                                                Edit
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <form id="delete-cinema-{{ $cinema->id }}"
                                                                action="{{ route('admin.cinemas.destroy', $cinema) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="button" class="dropdown-item remove-list"
                                                                    onclick="handleDelete({{ $cinema->id }})">
                                                                    <i
                                                                        class="mdi mdi-trash-can font-size-16 text-danger me-1"></i>
                                                                    Delete
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
                        {{ $cinemas->onEachSide(1)->links('admin.layouts.components.pagination') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createCinemaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createCinemaModalLabel">
                        Thêm mới rạp chiếu
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createCinemaForm" method="POST">
                        @csrf
                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label for="updateName" class="form-label">
                                    <span class="text-danger">*</span>
                                    Tên rạp chiếu:
                                </label>
                                <input type="text" class="form-control create-name" id="createName" name="name"
                                    required placeholder="Nhập tên cấp bậc">
                                <small class="fst-italic text-danger mt-3 name-error" id="createNameError"></small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="cinema-branch" class="form-label">
                                    <span class="text-danger">*</span>
                                    Chi nhánh
                                </label>
                                <select id="createBranch" class="form-control create-branch_id"
                                    placeholder="Chọn chi nhánh" name="branch_id">
                                    <option selected value="" class="text-secondary">Select branch</option>
                                    @foreach ($branchs as $branch)
                                        <option value="{{ $branch->id }}">
                                            {{ $branch->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="fst-italic text-danger mt-3 branch_id-error" id="createBranchError"></small>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="createAddress" class="form-label">
                                    <span class="text-danger">*</span>
                                    Địa chỉ
                                </label>
                                <input class="form-control create-address" type="text" name="address"
                                    id="createAddress" placeholder="Địa chỉ rạp chiếu">
                                <small class="fst-italic text-danger mt-3 address-error" id="createAddressError"></small>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="createDescription" class="form-label">
                                    Mô tả
                                </label>
                                <textarea id="createDescription" class="form-control create-description" name="description" rows="6"
                                    placeholder="Mô tả"></textarea>
                                <small class="fst-italic text-danger mt-3 description-error"
                                    id="createDescriptionError"></small>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" id="createCinemaBtn">Thêm</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="updateCinemaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateCinemaModalLabel">
                        Chỉnh sửa rạp chiếu
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form id="updateCinemaForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <input type="hidden" id="updatedId">

                            <div class="col-md-6 mb-3">
                                <label for="updateName" class="form-label">
                                    <span class="text-danger">*</span>
                                    Tên rạp chiếu:
                                </label>
                                <input type="text" class="form-control update-name" id="updateName" name="name"
                                    required placeholder="Nhập tên cấp bậc">
                                <small class="fst-italic text-danger mt-3 name-error" id="updateNameError"></small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="cinema-branch" class="form-label">
                                    <span class="text-danger">*</span>
                                    Chi nhánh
                                </label>
                                <select id="updateBranch" class="form-control update-branch_id"
                                    placeholder="Chọn chi nhánh" name="branch_id">
                                    <option selected value="" class="text-secondary">Select branch</option>
                                    @foreach ($branchs as $branch)
                                        <option value="{{ $branch->id }}">
                                            {{ $branch->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="fst-italic text-danger mt-3 branch_id-error" id="updateBranchError"></small>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="updateAddress" class="form-label">
                                    <span class="text-danger">*</span>
                                    Địa chỉ
                                </label>
                                <input class="form-control update-address" type="text" name="address"
                                    id="updateAddress" placeholder="Địa chỉ rạp chiếu">
                                <small class="fst-italic text-danger mt-3 address-error" id="updateAddressError"></small>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="updateDescription" class="form-label">
                                    Mô tả
                                </label>
                                <textarea id="updateDescription" class="form-control update-description" name="description" rows="6"
                                    placeholder="Mô tả"></textarea>
                                <small class="fst-italic text-danger mt-3 description-error"
                                    id="updateDescriptionError"></small>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" id="updateCinemaBtn">Sửa</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="{{ asset('assets/js/common.js') }}"></script>
    <script src="{{ asset('assets/js/cinema/index.js') }}"></script>
@endsection
