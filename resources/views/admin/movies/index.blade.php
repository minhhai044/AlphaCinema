@extends('admin.layouts.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h4 class="card-title">Danh sách phim</h4>

                    <!-- Container chứa các action -->
                    <div class="row align-items-center mb-3">
                        <!-- Cột bên trái: Hiển thị -->
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
                        <!-- Cột bên phải: Thêm mới và Bộ lọc -->
                        <div class="col-md-6 text-end">
                            <a href="{{ route('admin.movies.create') }}" class="btn btn-primary me-2">+ Thêm mới</a>
                            <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse"
                                data-bs-target="#searchForm">
                                <i class="fas fa-filter"></i> Bộ lọc
                            </button>
                        </div>
                    </div>

                    <!-- Form lọc  -->
                    <div class="collapse" id="searchForm">
                        <div class="card card-body mb-3">
                            <form id="filterForm">
                                <div class="row">
                                    <!-- Tìm nhanh theo ID -->
                                    <div class="col-md-4">
                                        <label class="form-label">Tìm nhanh theo ID</label>
                                        <input type="text" name="id" class="form-control" placeholder="Nhập ID">
                                    </div>
                                    <!-- Phiên bản -->
                                    <div class="col-md-4">
                                        <label class="form-label">Phiên bản</label>
                                        <select name="movie_versions" class="form-control">
                                            <option value="">-- Chọn phiên bản --</option>
                                            <option value="2D">2D</option>
                                            <option value="3D">3D</option>
                                            <option value="4D">4D</option>
                                        </select>
                                    </div>
                                    <!-- Thể loại -->
                                    <div class="col-md-4">
                                        <label class="form-label">Thể loại</label>
                                        <select name="movie_genres" class="form-control">
                                            <option value="">-- Chọn thể loại --</option>
                                            <option value="Action">Action</option>
                                            <option value="Horror">Horror</option>
                                            <option value="Comedy">Comedy</option>
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
                        <table id="movieTable" class="table table-bordered w-100">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên phim</th>
                                    <th>Ảnh</th>
                                    {{-- <th>Thể loại</th> --}}
                                    {{-- <th>Phiên bản</th> --}}
                                    <th>Hoạt động</th>
                                    <th>Nổi bật</th>
                                    <th>Thời lượng</th>
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
    {{-- Styles cho DataTables --}}
    {{--
    <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet"
        type="text/css" /> --}}

    <script>
        $(document).ready(function () {
            // Khởi tạo DataTable
            var table = $('#movieTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,
                order: [],
                ajax: {
                    url: "{{ route('api.movies.index') }}",
                    type: "GET",
                    data: function (d) {
                        // Lấy dữ liệu từ form filter
                        d.id = $('input[name="id"]').val();
                        d.movie_versions = $('select[name="movie_versions"]').val();
                        d.movie_genres = $('select[name="movie_genres"]').val();
                    },
                    error: function (xhr, status, error) {
                        console.error("Lỗi API:", xhr.responseText);
                    }
                },
                columns: [
                    { data: 'id', name: 'id' },
                    {
                        data: 'name',
                        name: 'name',
                        render: function (data, type, row) {
                            // Xử lý danh sách phiên bản phim (movie_versions)
                            let versions = Array.isArray(row.movie_versions) ? row.movie_versions : [];
                            let versionHtml = versions.map(version =>
                                `<span style="background-color: blue; color: white; padding: 3px 5px; border-radius: 5px; margin-right: 5px;">
                    ${version}
                </span>`).join(' ');

                            // Xử lý danh sách thể loại phim (movie_genres)
                            let genres = Array.isArray(row.movie_genres) ? row.movie_genres : [];
                            let genreHtml = genres.map(genre =>
                                `<span style="background-color: green; color: white; padding: 3px 5px; border-radius: 5px; margin-right: 5px;">
                    ${genre}
                </span>`).join(' ');

                            return `
                <div>
                    <h3 style="margin: 0; color: #007bff;">${data}</h3>
                    <p><strong>Đạo diễn:</strong> ${row.director || 'Đang cập nhật'}</p>
                    <p><strong>Thể loại:</strong> ${genreHtml || 'Chưa rõ'}</p>
                    <p><strong>Ngày khởi chiếu:</strong> ${row.release_date || 'Chưa có'}</p>
                    <p><strong>Ngày kết thúc:</strong> ${row.end_date || 'Chưa có'}</p>
                    <p><strong>Danh mục:</strong>  ${row.category || 'Chưa rõ'}</p>
                    <p><strong>Phiên bản:</strong> ${versionHtml || 'Chưa cập nhật'}</p>
                    <p><strong>Code Youtube:</strong>
                        <input type="text" value="${row.trailer_url || 'Không có'}" readonly
                            style="border: 1px solid #ccc; padding: 5px; width: 100%;">
                    </p>
                </div>
            `;
                        }
                    },

                    {
                        data: 'img_thumbnail',
                        render: function (data) {
                            return `<img src="/storage/${data}"
                                                     style="max-width: 100px; height: auto; display: block; margin: 0 auto;">`;
                        }
                    },

                    {
                        data: 'is_active',
                        render: function (data) {
                            return data
                                ? '<span class="badge bg-success">Active</span>'
                                : '<span class="badge bg-danger">Inactive</span>';
                        }
                    },
                    {
                        data: 'is_hot',
                        render: function (data) {
                            return data
                                ? '<span class="badge bg-success">Hot</span>'
                                : '<span class="badge bg-danger">No Hot</span>';
                        }
                    },
                    { data: 'duration', name: 'duration' },
                    {
                        data: 'id',
                        render: function (data) {
                            return `
                                            <div class="dropdown text-center">
                                                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    ...
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a href="/admin/movies/${data}" class="dropdown-item text-info">
                                                            <i class="fas fa-eye"></i> Xem
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="/admin/movies/${data}/edit" class="dropdown-item text-warning">
                                                            <i class="fas fa-edit"></i> Sửa
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form action="/admin/movies/${data}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger"
                                                                onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                                                <i class="fas fa-trash-alt"></i> Xóa
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        `;
                        },
                        orderable: false,
                        searchable: false
                    }
                ],
                pageLength: 5,      // Số bản ghi hiển thị mặc định
                lengthChange: false, // Tắt dropdown mặc định của DataTable
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

            // Thay đổi số dòng hiển thị theo dropdown tùy chỉnh
            $('#pageLength').on('change', function () {
                table.page.len($(this).val()).draw();
            });

            // Lọc dữ liệu
            $('#filterForm').on('submit', function (e) {
                e.preventDefault();
                table.ajax.reload();
            });

            // Reset lọc
            $('#resetFilter').on('click', function () {
                // Thêm một chút độ trễ để form reset xong
                setTimeout(function () {
                    table.ajax.reload();
                }, 50);
            });
        });
    </script>
@endsection

<style>
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        background-color: #fff !important;
        color: #000 !important;
        border-radius: 0.25rem !important;
        margin: 0 2px;
        padding: 0.5rem 0.75rem;
        transition: background-color 0.2s, color 0.2s, border-color 0.2s;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background-color: #3b82f6;
        color: #fff !important;
        border-color: #fff !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background-color: #2C77F4 !important;
        color: #fff !important;
        border-color: #3b82f6 !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
        color: #aaa !important;
        background-color: #f8f9fa !important;
        border-color: #ddd !important;
        pointer-events: none;
    }

    td img {
        max-width: 100px;
        height: auto;
        display: block;
        margin: 0 auto;
    }

    #pageLength {
        font-size: 14px;
        padding: 2px 8px;
        width: 80px;
        background-color: white;
        color: black;
        border: 1px solid #ccc;
        transition: background-color 0.3s, color 0.3s;
    }
</style>
