@extends('admin.layouts.master')
@section('title', 'Quản lý rạp')
@section('style')
    <link href="{{ asset('theme/admin/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('theme/admin/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/admin/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <style>
        .table {
            vertical-align: middle !important;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-20 fw-semibold">Quản lý rạp chiếu</h4>

                <div class="page-title-right">
                    <a id="openCreateCinemaModal" class="btn btn-primary">
                        <i class="bx bx-plus me-1"></i> Thêm Mới
                    </a>
                    <a href="{{ route('admin.export', 'cinemas') }}" class="btn btn-warning">
                        <i class="bx bx-download me-1"></i> Xuất Excel
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive min-vh-100">
                <table id="datatable" class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th class="fw-semibold text-center">STT</th>
                            <th class="fw-semibold text-center">Tên rạp</th>
                            <th class="fw-semibold text-center">Chi nhánh</th>
                            <th class="fw-semibold text-center">Địa chỉ</th>
                            <th class="fw-semibold text-center">Hoạt động</th>
                            <th class="fw-semibold text-center">Chức năng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($cinemas->isNotEmpty())
                            @foreach ($cinemas as $cinema)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="fw-semibold">{{ limitText($cinema->name, 20) }}</td>
                                    <td class="fw-semibold">{{ limitText($cinema->branch->name, 20) }}</td>
                                    <td>{{ limitText($cinema->address, 20) }}</td>
                                    <td>

                                        {{-- <form action="{{ route('admin.cinemas.toggle', $cinema->id) }}" method="POST"
                                            id="toggleForm{{ $cinema->id }}">
                                            @csrf
                                            <input type="checkbox" name="is_active" id="switch{{ $cinema->id }}"
                                                switch="primary" {{ $cinema->is_active ? 'checked' : '' }}
                                                onchange="confirmChange({{ $cinema->id }})">
                                            <label for="switch{{ $cinema->id }}"></label>
                                        </form> --}}

                                        <div class="d-flex justify-content-center align-items-center">
                                            <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
                                                <input class="form-check-input switch-is-active changeActive" type="checkbox"
                                                    data-cinema-id="{{ $cinema->id }}" @checked($cinema->is_active)>
                                            </div>
                                        </div>


                                    </td>
                                    <td>
                                        <a class="dropdown-item openUpdateCinemaModal" data-edit-id="{{ $cinema->id }}"
                                            data-branch-id="{{ $cinema->branch_id }}" data-name="{{ $cinema->name }}"
                                            data-address="{{ $cinema->address }}"
                                            data-description="{{ $cinema->description }}"><button
                                                class="btn btn-warning btn-sm">
                                                <i class="bx bx-edit"></i>
                                            </button></a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif

                    </tbody>

                </table>

            </div>


        </div>
    </div>

    <div class="modal fade" id="createCinemaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
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

                                    Tên rạp chiếu <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control create-name" id="createName" name="name"
                                    required placeholder="Nhập tên rạp chiếu">
                                <small class="fst-italic text-danger mt-3 name-error" id="createNameError"></small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="cinema-branch" class="form-label">

                                    Chi nhánh <span class="text-danger">*</span>
                                </label>
                                <select id="createBranch" class="form-control create-branch_id" placeholder="Chọn chi nhánh"
                                    name="branch_id">
                                    <option selected value="" class="text-secondary">Chọn chi nhánh</option>
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

                                    Địa chỉ <span class="text-danger">*</span>
                                </label>
                                <input class="form-control create-address" type="text" name="address" id="createAddress"
                                    placeholder="Địa chỉ rạp chiếu">
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
        <div class="modal-dialog modal-lg">
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

                                    Tên rạp chiếu <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control update-name" id="updateName" name="name"
                                    required placeholder="Nhập tên rạp chiếu">
                                <small class="fst-italic text-danger mt-3 name-error" id="updateNameError"></small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="cinema-branch" class="form-label">

                                    Chi nhánh <span class="text-danger">*</span>
                                </label>
                                <select id="updateBranch" class="form-control update-branch_id"
                                    placeholder="Chọn chi nhánh" name="branch_id">
                                    <option selected value="" class="text-secondary">Chọn chi nhánh</option>
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

                                    Địa chỉ <span class="text-danger">*</span>
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
    <script src="{{ asset('theme/admin/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Buttons examples -->
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>

    <script src="{{ asset('theme/admin/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}">
    </script>
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}">
    </script>

    <script src="{{ asset('theme/admin/assets/js/pages/datatables.init.js') }}"></script>
    <script src="{{ asset('assets/js/common.js') }}"></script>
    <script src="{{ asset('assets/js/cinema/index.js') }}"></script>
    <script>
        // function confirmChange(voucherId) {
        //     if (confirm("Bạn có muốn thay đổi trạng thái không?")) {
        //         document.getElementById('toggleForm' + voucherId).submit();
        //     } else {
        //         return false;
        //     }
        // }

         // Hàm xác nhận trước khi thay đổi
         function confirmChange(text = 'Bạn có chắc chắn muốn thay đổi trạng thái Rạp không?', title =
            'AlphaCinema thông báo') {
            return Swal.fire({
                icon: 'warning',
                title: title,
                text: text,
                showCancelButton: true,
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Hủy',
            }).then(result => result.isConfirmed);
        }

        // Gắn sự kiện thay đổi trạng thái
        $(document).on("change", ".changeActive", function(e) {
            e.preventDefault();
            let $checkbox = $(this);
            let cinemaId = $checkbox.data("cinema-id");
            let is_active = $checkbox.is(":checked") ? 1 : 0;

            // Gọi xác nhận
            confirmChange().then((confirmed) => {
                if (confirmed) {
                    // Gửi AJAX nếu đồng ý
                    $.ajax({
                        url: "{{ route('cinemas.change-active') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: cinemaId,
                            is_active: is_active
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success('Trạng thái hoạt động đã được cập nhật.');
                            } else {
                                toastr.error(response.message || 'Có lỗi xảy ra.');
                                $checkbox.prop("checked", !is_active);
                            }
                        },
                        error: function() {
                            toastr.error('Lỗi kết nối server!');
                            $checkbox.prop("checked", !is_active);
                        }
                    });
                } else {
                    // Người dùng từ chối => hoàn tác lại checkbox
                    $checkbox.prop("checked", !is_active);
                }
            });
        });

    </script>

@endsection
