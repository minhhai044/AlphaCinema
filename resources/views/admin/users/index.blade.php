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
            <div class="d-flex justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Danh sách người dùng</h4>
                <div class="d-flex flex-row-reverse mb-3">
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary me-2">+ Thêm mới</a>
                </div>
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
                            <th>Chức năng</th>
                            <th>Giới tính</th>
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

                                        <td>
                                            <a href="{{ route('admin.users.show', $user) }}">
                                                <button title="xem" class="btn btn-success btn-sm" type="button"><i
                                                        class="bi bi-eye"></i></button>
                                            </a>
                                            @if ($user->hasRole(['Nhân viên', 'Quản lý cơ sở']))
                                                @can('Sửa tài khoản')
                                                    <a href="{{ route('admin.users.edit', $user) }}">
                                                        <button title="xem" class="btn btn-warning btn-sm" type="button"><i
                                                                class="fas fa-edit"></i></button>
                                                    </a>
                                                @endcan

                                                @can('Xóa tài khoản')
                                                    <form method="POST" action="{{ route('admin.users.softDestroy', $user) }}"
                                                        class="d-inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                            onclick="return confirm('Bạn có muốn xóa không')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @elseif (auth()->user()->hasRole('Quản lý cơ sở'))
                                {{-- Facility Manager: Show users from the same cinema --}}
                                @if ($user->cinema_id == auth()->user()->cinema_id)
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
                                            <a href="{{ route('admin.users.show', $user) }}">
                                                <button title="xem" class="btn btn-success btn-sm" type="button"><i
                                                        class="bi bi-eye"></i></button>
                                            </a>
                                            @if ($user->hasRole(['Nhân viên', 'Quản lý cơ sở']))
                                                @can('Sửa tài khoản')
                                                    <a href="{{ route('admin.users.edit', $user) }}">
                                                        <button title="xem" class="btn btn-warning btn-sm" type="button"><i
                                                                class="fas fa-edit"></i></button>
                                                    </a>
                                                @endcan

                                                @can('Xóa tài khoản')
                                                    <form method="POST" action="{{ route('admin.users.softDestroy', $user) }}"
                                                        class="d-inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                            onclick="return confirm('Bạn có muốn xóa không')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @else
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

                                                @can('Xóa tài khoản')
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
                                                @endcan
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
    @endsection
