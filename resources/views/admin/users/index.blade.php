@extends('admin.layouts.master')

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

    <div class="row">
        <div class="col-12">

            <div class="d-flex justify-content-between mb-3">
                <h4 class="mb-sm-0 font-size-18">Danh sách người dùng</h4>

                <form action="{{ route('admin.users.index') }}" method="GET" class="d-flex gap-3 align-items-center me-3">
                    <!-- Select chi nhánh -->
                    @if (auth()->user()->hasRole('System Admin'))
                        <select name="branch_id" class="form-select">
                            <option value="">Chọn chi nhánh</option>
                            @foreach ($branchs as $branch)
                                <option value="{{ $branch->id }}"
                                    {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>

                        <!-- Nút tìm kiếm -->
                        <div class="form-group col-md-1 d-flex align-items-end me-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-search-alt-2"></i>
                            </button>
                        </div>
                    @endif


                    <!-- Nút thêm mới -->
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary flex-shrink-0">+ Thêm mới</a>
                </form>
            </div>

            <div class="collapse" id="searchForm">
                <div class=" mb-3">
                    <form id="filterForm">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label">Tìm nhanh theo ID</label>
                                <input type="text" name="id" class="form-control" placeholder="Nhập ID">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Giới tính</label>
                                <select name="gender" class="form-control">
                                    <option value="">-- Chọn giới tính --</option>
                                    <option value="0">Nam</option>
                                    <option value="1">Nữ</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Loại tài khoản</label>
                                <select name="type_user" class="form-control">
                                    <option value="">-- Chọn loại tài khoản --</option>
                                    <option value="1">Admin</option>
                                    <option value="0">User</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Lọc</button>
                            <button type="reset" class="btn btn-secondary" id="resetFilter">Reset</button>
                        </div>
                    </form>
                </div>
            </div>


            <div class="table-responsive">
                <table id="datatable" class="table table-bordered w-100 text-center">
                    <thead>
                        <tr class="text-center">
                            <th>ID</th>
                            <th>Tên</th>
                            <th>Ảnh</th>
                            <th>Email</th>
                            <th>Quyền</th>
                            <th>Giới tính</th>
                            @if (auth()->user()->hasRole(['System Admin', 'Quản lý chi nhánh']))
                                <th>Trạng thái</th>
                            @endif

                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            @if (auth()->user()->hasRole('Quản lý chi nhánh'))
                                {{-- Branch Manager: Show users from the same branch --}}
                                @if ($user->cinema !== null && $user->name != 'System Admin' && $user->cinema->branch_id === auth()->user()->branch_id)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>

                                        @if ($user->avatar && Storage::exists($user->avatar))
                                            <td><img src="{{ Storage::url($user->avatar) }}"
                                                    style="max-width: 100px; height: auto; display: block; margin: 0 auto;">
                                            </td>
                                        @else
                                            <td><img src="https://graph.facebook.com/4/picture?type=small"
                                                    style="max-width: 100px; height: auto; display: block; margin: 0 auto;">
                                            </td>
                                        @endif

                                        <td>{{ $user->email }}</td>

                                        <td>
                                            @foreach ($user->roles as $role)
                                                <span class="badge bg-primary me-1">{{ $role->name }}</span>
                                            @endforeach
                                        </td>

                                        <td>
                                            {!! $user->gender === 0
                                                ? '<span class="badge bg-primary">Nam</span>'
                                                : '<span class="badge bg-danger">Nữ</span>' !!}
                                        </td>

                                        @if (auth()->user()->hasRole(['System Admin', 'Quản lý chi nhánh']))
                                            <td>
                                                <div class="form-check form-switch form-switch-md form-switch-success"
                                                    style="display: flex; justify-content: center;">
                                                    <input class="form-check-input switch-is-active changeIsActiveUser"
                                                        type="checkbox" data-user-id="{{ $user->id }}"
                                                        data-user-type="{{ auth()->user()->type_user }}"
                                                        {{ $user->is_active === 1 ? 'checked' : '' }}
                                                        onclick="changeIsActiveUser(event)">
                                                </div>
                                            </td>
                                        @endif

                                        <td>
                                            <a href="{{ route('admin.users.show', $user) }}">
                                                <button title="xem" class="btn btn-success btn-sm" type="button"><i
                                                        class="bi bi-eye"></i></button>
                                            </a>
                                            @if ($user->hasRole(['Nhân viên', 'Quản lý rạp']))
                                                @can('Sửa tài khoản')
                                                    <a href="{{ route('admin.users.edit', $user) }}">
                                                        <button title="xem" class="btn btn-warning btn-sm" type="button"><i
                                                                class="fas fa-edit"></i></button>
                                                    </a>
                                                @endcan

                                                {{-- @can('Xóa tài khoản')
                                                    <form method="POST" action="{{ route('admin.users.softDestroy', $user) }}"
                                                        class="d-inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                            onclick="return confirm('Bạn có muốn xóa không')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @endcan --}}
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @elseif (auth()->user()->hasRole('Quản lý rạp'))
                                {{-- Facility Manager: Show users from the same cinema --}}
                                @if ($user->cinema_id == auth()->user()->cinema_id && $user->hasRole('Nhân viên'))
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>

                                        @if ($user->avatar && Storage::exists($user->avatar))
                                            <td><img src="{{ Storage::url($user->avatar) }}"
                                                    style="max-width: 100px; height: auto; display: block; margin: 0 auto;">
                                            </td>
                                        @else
                                            <td><img src="https://graph.facebook.com/4/picture?type=small"
                                                    style="max-width: 100px; height: auto; display: block; margin: 0 auto;">
                                            </td>
                                        @endif

                                        <td>{{ $user->email }}</td>

                                        <td>
                                            @foreach ($user->roles as $role)
                                                <span class="badge bg-primary me-1">{{ $role->name }}</span>
                                            @endforeach
                                        </td>

                                        <td>
                                            {!! $user->gender === 0
                                                ? '<span class="badge bg-primary">Nam</span>'
                                                : '<span class="badge bg-danger">Nữ</span>' !!}
                                        </td>

                                        @if (auth()->user()->hasRole(['System Admin', 'Quản lý chi nhánh']))
                                            <td>
                                                <div class="form-check form-switch form-switch-md form-switch-success"
                                                    style="display: flex; justify-content: center;">
                                                    <input class="form-check-input switch-is-active changeIsActiveUser"
                                                        type="checkbox" data-user-id="{{ $user->id }}"
                                                        data-user-type="{{ auth()->user()->type_user }}"
                                                        {{ $user->is_active === 1 ? 'checked' : '' }}
                                                        onclick="changeIsActiveUser(event)">
                                                </div>
                                            </td>
                                        @endif

                                        <td>
                                            <a href="{{ route('admin.users.show', $user) }}">
                                                <button title="xem" class="btn btn-success btn-sm" type="button"><i
                                                        class="bi bi-eye"></i></button>
                                            </a>
                                            @if ($user->hasRole('Nhân viên'))
                                                @can('Sửa tài khoản')
                                                    <a href="{{ route('admin.users.edit', $user) }}">
                                                        <button title="xem" class="btn btn-warning btn-sm"
                                                            type="button"><i class="fas fa-edit"></i></button>
                                                    </a>
                                                @endcan

                                                {{-- @can('Xóa tài khoản')
                                                    <form method="POST" action="{{ route('admin.users.softDestroy', $user) }}"
                                                        class="d-inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                            onclick="return confirm('Bạn có muốn xóa không')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @endcan --}}
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @elseif(auth()->user()->hasRole('System Admin'))
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>

                                    @if ($user->avatar && Storage::exists($user->avatar))
                                        <td><img src="{{ Storage::url($user->avatar) }}"
                                                style="max-width: 100px; height: auto; display: block; margin: 0 auto;">
                                        </td>
                                    @else
                                        <td><img src="https://graph.facebook.com/4/picture?type=small"
                                                style="max-width: 100px; height: auto; display: block; margin: 0 auto;">
                                        </td>
                                    @endif

                                    <td>{{ $user->email }}</td>

                                    <td>
                                        @foreach ($user->roles as $role)
                                            <span class="badge bg-primary me-1">{{ $role->name }}</span>
                                        @endforeach
                                    </td>

                                    <td>
                                        {!! $user->gender === 0
                                            ? '<span class="badge bg-primary">Nam</span>'
                                            : '<span class="badge bg-danger">Nữ</span>' !!}
                                    </td>

                                    <td>
                                        <div class="form-check form-switch form-switch-md form-switch-success"
                                            style="display: flex; justify-content: center;">
                                            <input class="form-check-input switch-is-active changeIsActiveUser"
                                                type="checkbox" data-user-id="{{ $user->id }}"
                                                data-user-type="{{ auth()->user()->type_user }}"
                                                {{ $user->is_active === 1 ? 'checked' : '' }}
                                                onclick="changeIsActiveUser(event)">
                                        </div>
                                    </td>


                                    <td>
                                        <a href="{{ route('admin.users.show', $user) }}">
                                            <button title="xem" class="btn btn-success btn-sm" type="button"><i
                                                    class="bi bi-eye"></i></button>
                                        </a>
                                        @if (Auth::user()->hasRole(['System Admin', 'Quản lý chi nhánh']))
                                            @if ($user->name != 'System Admin')
                                                @can('Sửa tài khoản')
                                                    <a href="{{ route('admin.users.edit', $user) }}">
                                                        <button title="xem" class="btn btn-warning btn-sm"
                                                            type="button"><i class="fas fa-edit"></i></button>
                                                    </a>
                                                @endcan

                                                {{-- @can('Xóa tài khoản')
                                                    <form method="POST"
                                                        action="{{ route('admin.users.softDestroy', $user) }}"
                                                        class="d-inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                            onclick="return confirm('Bạn có muốn xóa không')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @endcan --}}
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
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
            // Hàm xác nhận trước khi thay đổi (sử dụng SweetAlert)
            function confirmChange(text = 'Bạn có chắc chắn muốn thay đổi trạng thái nhân viên?', title =
                'AlphaCinema thông báo') {
                return Swal.fire({
                    icon: 'warning',
                    title: title,
                    text: text,
                    showCancelButton: true,
                    confirmButtonText: 'Xác nhận',
                    cancelButtonText: 'Hủy',
                }).then(result => result.isConfirmed);
            }

            // Hàm thay đổi trạng thái nhân viên
            var checkbox = null;
            var userId = null;
            var is_active = null;

            function changeIsActiveUser(event) {
                checkbox = event.target;
                userId = $(checkbox).data('user-id');
                is_active = checkbox.checked ? 1 : 0;

                // Sử dụng SweetAlert để xác nhận thay đổi
                confirmChange('Bạn có chắc chắn muốn thay đổi trạng thái nhân viên ?').then((confirmed) => {
                    if (confirmed) {
                        saveChangeStatus(); // Lưu thay đổi trạng thái nếu xác nhận
                    } else {
                        checkbox.checked = !checkbox.checked; // Hoàn tác thay đổi nếu hủy
                    }
                });
            }

            // Lưu thay đổi trạng thái nhân viên
            function saveChangeStatus() {
                // Gửi AJAX request để thay đổi trạng thái
                $.ajax({
                    url: `/api/v1/users/change-active`,
                    method: 'POST',
                    data: {
                        user_id: userId,
                        is_active: parseInt(is_active),
                    },
                    success: function(response) {
                        console.log(response);
                        toastr.success('Trạng thái nhân viên đã được cập nhật.');
                    },
                    error: function(xhr) {
                        const errorMessage = xhr.responseJSON?.message || 'Đã có lỗi xảy ra!';
                        toastr.error(errorMessage);
                        checkbox.checked = !checkbox.checked; // Hoàn tác thay đổi nếu có lỗi
                    }
                });
            }
        </script>
    @endsection
