@extends('admin.layouts.master')

@section('title', 'Danh sách vai trò')

@section('style')
    <link href="{{ asset('theme/admin/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('theme/admin/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/admin/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('theme/admin/assets/css/preloader.min.css') }}" type="text/css" />
    <link href="{{ asset('theme/admin/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/admin/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />

    <style>
        .table {
            vertical-align: middle !important;
        }

        table.dataTable thead th,
        table.dataTable thead td,
        table.dataTable tfoot th,
        table.dataTable tfoot td {
            text-align: center;
        }
    </style>
@endsection

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Danh sách vai trò</h4>
                {{-- <div>
                    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary waves-effect waves-light">
                        <i class="bx bx-plus me-1"></i>
                        Thêm mới vai trò
                    </a>
                </div> --}}
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-sm-12">
                    <div class="table-responsive">
                        <table class="table align-middle  dt-responsive nowrap w-100 table-bordered w-100 text-center"
                            id="datatable">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Tên</th>
                                    <th>Quyền</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $role)
                                    <td class="sorting_1 dtr-control">
                                        {{-- <div class="d-none">{{ $role->id }}</div> --}}
                                        {{-- <div class="form-check font-size-{{ $role->id }}"> --}}
                                        <input class="form-check-input" type="checkbox"
                                            id="customerlistcheck-{{ $role->id }}">
                                        {{-- <label class="form-check-label"
                                                        for="customerlistcheck-{{ $role->id }}"></label> --}}
                    </div>
                    </td>

                    <td>
                        {{ $role->name }}
                    </td>
                    <td>
                        @if ($role->permissions->isNotEmpty())
                            @foreach ($role->permissions->take(3) as $permission)
                                <span class="badge rounded-pill bg-success">
                                    {{ $permission->name }}
                                </span>
                            @endforeach

                            @if ($role->permissions->count() > 3)
                                <span class="badge rounded-pill bg-primary"> +
                                    {{ $role->permissions->count() - 3 }} quyền </span>
                            @endif
                        @else
                            <span class="badge bg-danger-subtle text-danger text-uppercase">Không có
                                quyền</span>
                        @endif
                    </td>

                    <td>
                        <div class="form-check form-switch form-switch-md form-switch-success"
                            style="display: flex; justify-content: center;">
                            <input class="form-check-input switch-is-active changeIsActiveRole" type="checkbox"
                                data-role-id="{{ $role->id }}" data-user-type="{{ auth()->user()->type_user }}"
                                {{ $role->is_active === 1 ? 'checked' : '' }} onclick="changeIsActive(event)">
                        </div>
                    </td>
                    <td>
                        <a href="{{ route('admin.roles.edit', $role) }}">
                            <button title="xem" class="btn btn-warning btn-sm " type="button"><i
                                    class="fas fa-edit"></i></button>
                        </a>

                        {{-- <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" class="d-inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Bạn có muốn xóa không')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form> --}}
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
    @php
        $appUrl = env('APP_URL');
    @endphp
@endsection

@section('script')
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

    <script>
        let Url = @json($appUrl);
        var checkbox = null;
        var roleId = null;
        var is_active = null;

        function changeIsActive(event) {
            checkbox = event.target;
            roleId = $(checkbox).data('role-id');
            is_active = checkbox.checked ? 1 : 0;
            showModal();
        }

        function showModal() {
            // Kiểm tra nếu modal đã tồn tại trong DOM rồi, nếu chưa thì tạo mới
            if ($('#confirmChange').length === 0) {
                const html = `
                        <div class="modal fade" id="confirmChange" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="cancelChanges()"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <h5 class="modal-title">Xác nhận thay đổi trạng thái</h5>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" onclick="saveChanges()">Xác nhận</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="cancelChanges()">Hủy</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                // Thêm modal vào DOM
                $('body').append(html);
            }

            const modalElement = document.getElementById('confirmChange');
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
            $(".modal-backdrop").hide(); // Ẩn backdrop nếu cần
        }

        // Sử dụng Bootstrap 5 Modal API để hiển thị modal
        function saveChanges() {
            // Gửi AJAX request để thay đổi trạng thái
            $.ajax({
                url: `/api/v1/roles/change-active`,
                method: 'POST',
                data: {
                    role_id: roleId,
                    is_active: is_active,
                },
                success: function(response) {
                    console.log(response);
                    const modal = bootstrap.Modal.getInstance(document.getElementById('confirmChange'));
                    modal.hide(); // Đóng modal sau khi thực hiện thay đổi
                },
                error: function(xhr) {
                    const errorMessage = xhr.responseJSON?.message || 'Đã có lỗi xảy ra!';
                    alert(errorMessage);
                }
            });
        }

        function cancelChanges() {
            checkbox.checked = !checkbox.checked;
            // Đóng modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('confirmChange'));
            modal.hide();
        }
    </script>
@endsection
