@extends('admin.layouts.master')
@section('title', 'Quản lý chi nhánh')

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

    <style>
        #datatable_length select {
            width: 60px;
        }

        #datatable thead th {
            text-align: center;
            vertical-align: middle;
        }
    </style>
@endsection
@section('style-libs')
    <!-- Datatable CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="mb-sm-0 font-size-20 fw-semibold ms-3 mt-3">Thêm mới chi nhánh</div>

                <div class="card-body">
                    <form action="{{ route('admin.branches.store') }}" method="post">
                        @csrf
                        <div class="row ">
                            <div class="mb-2">
                                <label for="name" class="form-label">
                                    <span class="text-danger">*</span>Tên chi nhánh:
                                </label>
                                <input type="text"
                                    class="form-control {{ $errors->has('name') ? 'is-invalid' : (old('name') ? 'is-valid' : '') }}"
                                    name="name" value="{{ old('name') }}" placeholder="Nhập tên chi nhánh">
                                <div class="form-text">
                                    Tên chi nhánh của rạp chiếu phim.
                                </div>
                                <div class="{{ $errors->has('name') ? 'invalid-feedback' : 'valid-feedback' }}">
                                    @if ($errors->has('name'))
                                        {{ $errors->first('name') }}
                                    @endif
                                </div>
                            </div>
                            <div class="mb-2">
                                <label for="surcharge" class="form-label">
                                    <span class="text-danger">*</span>Phụ phí:
                                </label>
                                <input type="number"
                                    class="form-control {{ $errors->has('surcharge') ? 'is-invalid' : (old('surcharge') ? 'is-valid' : '') }}"
                                    name="surcharge" value="{{ old('surcharge') }}" placeholder="Nhập phụ phí">
                                <div class="form-text">Số tiền phụ phí cho chi nhánh.</div>
                                <div class="{{ $errors->has('surcharge') ? 'invalid-feedback' : 'valid-feedback' }}">
                                    @if ($errors->has('surcharge'))
                                        {{ $errors->first('surcharge') }}
                                    @endif
                                </div>
                            </div>

                            <div class="text-end mt-2">
                                <button type="submit" class="btn btn-primary mx-1">+ Thêm mới</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-sm-0 font-size-20 fw-semibold">Danh sách chi nhánh</h4>
                    <div class="table-responsive">
                        <table id="datatable" class="table table-bordered w-100 text-center">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên chi nhánh</th>
                                    <th>Phụ phí</th>
                                    <th>Hoạt động</th>
                                    <th>Chức năng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($branches as $branch)
                                    <tr>
                                        <td>{{ $branch->id }}</td>
                                        <td>{{ $branch->name }}</td>
                                        <td>{{ number_format($branch->surcharge) }} VND</td>
                                        <td class="d-flex justify-content-center align-items-center">
                                            <form action="{{ route('admin.branches.toggle', $branch->id) }}"
                                                class="form-check form-switch form-switch-success" method="POST"
                                                id="toggleForm{{ $branch->id }}">
                                                @csrf
                                                <input type="checkbox" name="is_active" id="switch{{ $branch->id }}"
                                                    class="form-check-input switch-is-active changeActive"
                                                    style="width: 55px; height: 25px;"
                                                    {{ $branch->is_active ? 'checked' : '' }}
                                                    onchange="confirmChange({{ $branch->id }})">
                                                <label for="switch{{ $branch->id }}" data-on-label="Yes"
                                                    data-off-label="No"></label>
                                            </form>
                                        </td>

                                        <td class="text-center">
                                            <button class="btn btn-warning btn-sm editBranch" data-id="{{ $branch->id }}"
                                                data-name="{{ $branch->name }}" data-surcharge="{{ $branch->surcharge }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal chỉnh sửa -->
    </div>
    <div class="modal fade" id="editBranchModal" tabindex="-1" aria-labelledby="editBranchModalLabel" aria-hidden="true">
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

                        <label for="branchName" class="form-label">Tên chi nhánh</label>
                        <input type="text" class="form-control" id="branchName" name="name"
                            placeholder="Nhập tên chi nhánh" required>

                    </div>
                    <div class="modal-body">

                        <label for="branchSurcharge" class="form-label">Phụ phí</label>
                        <input type="number" class="form-control" id="branchSurcharge" name="surcharge"
                            placeholder="Nhập phụ phí" required>

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
        document.querySelectorAll('.editBranch').forEach(function(button) {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                const name = this.dataset.name;
                const surcharge = this.dataset.surcharge;

                // Cập nhật form trong modal
                const form = document.getElementById('editBranchForm');
                form.action = `/admin/branches/${id}`;
                document.getElementById('branchName').value = name;
                document.getElementById('branchSurcharge').value = surcharge;



                // Hiển thị modal
                const modal = new bootstrap.Modal(document.getElementById('editBranchModal'));
                modal.show();
            });
        });

        function confirmChange(branchId) {
            if (confirm("Bạn có muốn thay đổi trạng thái không?")) {
                document.getElementById('toggleForm' + branchId).submit();
            } else {
                return false;
            }
        }
    </script>

@endsection
