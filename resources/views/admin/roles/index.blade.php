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
                <h4 class="mb-sm-0 font-size-18">Roles Managers</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Danh sách</a>
                        </li>
                        <li class="breadcrumb-item active">Vai trò</li>
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
                                <h6 class="mb-sm-0 font-size-16"> Danh sách vai trò</h6>
                            </div>
                        </div>
                        <div class="col-sm-auto">
                            <div class="mb-4">
                                <a href="{{ route('admin.roles.create') }}" class="btn btn-light waves-effect waves-light">
                                    <i class="bx bx-plus me-1"></i>
                                    Thêm mới vai trò
                                </a>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table
                                    class="table align-middle  dt-responsive nowrap w-100 table-bordered w-100 text-center"
                                    id="datatable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Tên</th>
                                            <th>Vai trò</th>
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
                                <a href="{{ route('admin.roles.edit', $role) }}">
                                    <button title="xem" class="btn btn-warning btn-sm " type="button"><i
                                            class="fas fa-edit"></i></button>
                                </a>

                                <form method="POST" action="{{ route('admin.roles.destroy', $role) }}"
                                    class="d-inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Bạn có muốn xóa không')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
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
