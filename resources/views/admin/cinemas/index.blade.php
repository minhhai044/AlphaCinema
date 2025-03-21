@extends('admin.layouts.master')
@section('title', 'Quản lý rạp')

@section('style')
    <link href="{{ asset('theme/admin/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('theme/admin/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/admin/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('theme/admin/assets/css/preloader.min.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('theme/admin/assets/css/preloader.min.css') }}" type="text/css" />

    <link href="{{ asset('theme/admin/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('theme/admin/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
@endsection
@section('style-libs')
    <!-- Datatable CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
@endsection
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
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="font-size-16"></h6>
                        <div class="d-flex gap-2">
                            <a id="openCreateCinemaModal" class="btn btn-light">
                                <i class="bx bx-plus me-1"></i> Thêm Mới
                            </a>
                            <a href="{{ route('admin.export', 'cinemas') }}" class="btn btn-warning">
                                <i class="bx bx-download me-1"></i> Xuất Excel
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive min-vh-100">
                        <table class="table table-striped table-bordered" id="datatable">
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
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ limitText($cinema->name, 20) }}</td>
                                        <td>{{ limitText($cinema->branch->name, 20) }}</td>
                                        <td>{{ limitText($cinema->address, 20) }}</td>
                                        <td>
                                            {{-- <span class="badge {{ $cinema->is_active ? 'badge-soft-success' : 'badge-soft-danger' }}">
                                                {{ $cinema->is_active ? 'Active' : 'No Active' }}
                                            </span> --}}
                                            {{-- <input type="checkbox" id="switch{{ $cinema->id }}" switch="primary"
                                                {{ $cinema->is_active ? 'checked' : '' }}
                                                onchange="toggleCinemaStatus({{ $cinema->id }}, this.checked)">
                                            <label for="switch{{ $cinema->id }}" data-on-label="Yes"
                                                data-off-label="No"></label> --}}
                                            <form action="{{ route('admin.cinemas.toggle', $cinema->id) }}" method="POST"
                                                id="toggleForm{{ $cinema->id }}">
                                                @csrf
                                                <input type="checkbox" name="is_active" id="switch{{ $cinema->id }}"
                                                    switch="success" {{ $cinema->is_active ? 'checked' : '' }}
                                                    onchange="confirmChange({{ $cinema->id }})">
                                                <label for="switch{{ $cinema->id }}" data-on-label="Yes"
                                                    data-off-label="No"></label>
                                            </form>


                                        </td>
                                        {{-- <td>
                                            <form action="{{ route('admin.vouchers.toggle', $voucher->id) }}" method="POST" id="toggleForm{{ $voucher->id }}">
                                                @csrf
                                                <input type="checkbox" name="is_active" id="switch{{ $voucher->id }}" switch="primary"
                                                       {{ $voucher->is_active ? 'checked' : '' }}
                                                       onchange="confirmChange({{ $voucher->id }})">
                                                <label for="switch{{ $voucher->id }}" data-on-label="Yes" data-off-label="No"></label>
                                            </form>
                                        </td> --}}
                                        <td>
                                            <div class="dropdown">
                                                <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
                                                    <i class="mdi mdi-dots-horizontal"></i>
                                                </a>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item openUpdateCinemaModal"
                                                            data-edit-id="{{ $cinema->id }}"
                                                            data-branch-id="{{ $cinema->branch_id }}"
                                                            data-name="{{ $cinema->name }}"
                                                            data-address="{{ $cinema->address }}"
                                                            data-description="{{ $cinema->description }}">
                                                            <i class="mdi mdi-pencil text-success me-1"></i> Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form id="delete-cinema-{{ $cinema->id }}" method="POST"
                                                            action="{{ route('admin.cinemas.destroy', $cinema) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="dropdown-item text-danger"
                                                                onclick="handleDelete({{ $cinema->id }})">
                                                                <i class="mdi mdi-trash-can me-1"></i> Delete
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
    <!-- Required datatable js -->
    <script src="{{ asset('theme/admin/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Buttons examples -->
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>

    <!-- Responsive examples -->
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}">
    </script>
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}">
    </script>

    <!-- Datatable init js -->
    <script src="{{ asset('theme/admin/assets/js/pages/datatables.init.js') }}"></script>

    <script src="{{ asset('theme/admin/assets/js/app.js') }}"></script>

    <script src="{{ asset('assets/js/common.js') }}"></script>
    <script src="{{ asset('assets/js/cinema/index.js') }}"></script>
    <script>
        function confirmChange(voucherId) {
            if (confirm("Bạn có muốn thay đổi trạng thái không?")) {
                document.getElementById('toggleForm' + voucherId).submit();
            } else {
                return false;
            }
        }
    </script>

@endsection
