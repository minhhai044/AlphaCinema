@extends('admin.layouts.master')
@section('style')
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
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Danh sách người dùng</h4>

                    <div class="row align-items-center mb-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <span class="me-2">Hiển thị:</span>
                                <select id="pageLength" class="form-select w-auto">
                                    <option value="5" selected>5</option>
                                    <option value="10">10</option>
                                    <option value="15">15</option>
                                    <option value="20">20</option>
                                    <option value="1000">Tất cả</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="{{ route('admin.users.create') }}" class="btn btn-primary me-2">+ Thêm mới</a>
                            <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse"
                                data-bs-target="#searchForm">
                                <i class="fas fa-filter"></i> Bộ lọc
                            </button>
                        </div>
                    </div>

                    <div class="collapse" id="searchForm">
                        <div class="card card-body mb-3">
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
                        <table id="userTable" class="table table-bordered w-100 text-center">
                            <thead>
                                <tr class="text-center">
                                    <th>ID</th>
                                    <th>Tên</th>
                                    <th>Ảnh</th>
                                    <th>Thông tin vé</th>
                                    <th>Chức năng</th>
                                    <th>Loại</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            var table = $('#userTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,
                order: [],
                ajax: {
                    url: "{{ route('api.users.index') }}",
                    type: "GET",
                    data: function(d) {
                        d.id = $('input[name="id"]').val();
                        d.gender = $('select[name="gender"]').val();
                        d.type_user = $('select[name="type_user"]').val();
                    },
                    error: function(xhr, status, error) {
                        console.error("Lỗi API:", xhr.responseText);
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'avatar',
                        render: function(data) {
                            const avatarUrl = data ? '/storage/' + data :
                                "https://graph.facebook.com/4/picture?type=small";
                            return `<img src="${avatarUrl}"
                                                 style="max-width: 100px; height: auto; display: block; margin: 0 auto;">`;
                        }
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'gender',
                        name: 'gender',
                        render: function(data) {
                            if (data === 0) return '<span class="badge bg-primary">Nam</span>';
                            if (data === 1) return '<span class="badge bg-danger">Nữ</span>';
                            return '<span class="badge bg-secondary">Khác</span>';
                        }
                    },
                    {
                        data: 'roles',
                        name: 'roles',
                        render: function(data) {
                            if (!data || data.length === 0) {
                                return '<span class="badge bg-secondary">Không có vai trò</span>';
                            }
                            return data.map(role =>
                                `<span class="badge bg-primary me-1">${role.name}</span>`).join(
                                ' ');
                        }
                    },
                    {
                        data: 'id',
                        render: function(data) {
                            return `
                                <div class="dropdown text-center">
                                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">...
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a href="/admin/users/${data}" class="dropdown-item text-info"><i class="fas fa-eye"></i> Xem</a></li>
                                        <li><a href="/admin/users/${data}/edit" class="dropdown-item text-warning"><i class="fas fa-edit"></i> Sửa</a></li>
                                        <li>
                                            <form action="/admin/users/${data}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                                    <i class="fas fa-trash-alt"></i> Xóa
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>`;
                        },
                        orderable: false,
                        searchable: false
                    }
                ],
                pageLength: 5,
                lengthChange: false,
                language: {
                    search: "Tìm kiếm:",
                    paginate: {
                        next: ">",
                        previous: "<"
                    },
                    lengthMenu: "Hiển thị _MENU_ mục",
                    info: "Hiển thị từ _START_ đến _END_ trong tổng số _TOTAL_ mục",
                    emptyTable: "Không có dữ liệu để hiển thị",
                    zeroRecords: "Không tìm thấy kết quả phù hợp"
                }
            });

            $('#pageLength').on('change', function() {
                table.page.len($(this).val()).draw();
            });

            $('#filterForm').on('submit', function(e) {
                e.preventDefault();
                table.ajax.reload();
            });

            $('#resetFilter').on('click', function() {
                setTimeout(function() {
                    table.ajax.reload();
                }, 50);
            });
        });
    </script>
@endsection
