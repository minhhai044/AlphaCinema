@extends('admin.layouts.master')
@section('style')
    <link href="{{ asset('theme/admin/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/admin/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/admin/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('theme/admin/assets/css/preloader.min.css') }}" type="text/css" />
    <link href="{{ asset('theme/admin/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/admin/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <style>
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            background-color: #ffffff !important;
            color: #495057 !important;
            border: 1px solid #e0e0e0 !important;
            border-radius: 4px !important;
            margin: 0 2px;
            padding: 4px 8px !important;
            /* Giảm padding */
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s ease !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: #2563eb !important;
            /* Màu xanh dương đậm hơn */
            color: #ffffff !important;
            border-color: #2563eb !important;
            box-shadow: 0 2px 6px rgba(89, 137, 240, 0.2);
            transform: translateY(-1px);
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background-color: #6ff3d4 !important;
            /* Màu xanh đậm cho active */
            color: #ffffff !important;
            border-color: #fbfbfb !important;
            box-shadow: 0 2px 6px rgba(30, 64, 175, 0.2);
            transform: translateY(-1px);
            font-weight: 600;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
            color: #ced4da !important;
            background-color: #f1f3f5 !important;
            border-color: #e9ecef !important;
            box-shadow: none !important;
            cursor: not-allowed;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.previous,
        .dataTables_wrapper .dataTables_paginate .paginate_button.next {
            background-color: #f1f3f5 !important;
            color: #495057 !important;
            border-radius: 4px !important;
            padding: 4px 10px !important;
            /* Giảm padding cho trước/sau */
            text-transform: uppercase;
            font-weight: 500;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.previous:hover,
        .dataTables_wrapper .dataTables_paginate .paginate_button.next:hover {
            background-color: #ffffff !important;
            color: #ffffff !important;
            border-color: #e7e7e7 !important;
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.2);
        }

        td img {
            max-width: 100px;
            height: auto;
        }

        #pageLength {
            font-size: 14px;
            padding: 5px 10px;
            width: 100px;
        }
    </style>
@endsection
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
                            <button class="btn btn-outline-secondary me-2" type="button" data-bs-toggle="collapse"
                                data-bs-target="#searchForm">
                                <i class="fas fa-filter"></i> Bộ lọc
                            </button>
                            <button class="btn btn-warning waves-effect waves-light">
                                <a href="{{ route('admin.export', 'movies') }}">
                                    <i class="bx bx-download me-1"></i>
                                    Xuất Excel</a>
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

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
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
                columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'name',
                    render: function (data, type, row) {
                        // Xử lý danh sách phiên bản phim (movie_versions)
                        let versions = Array.isArray(row.movie_versions) ? row.movie_versions :
                            [];
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
                        return data ?
                            '<span class="badge bg-success">Active</span>' :
                            '<span class="badge bg-danger">Inactive</span>';
                    }
                },
                {
                    data: 'is_hot',
                    render: function (data) {
                        return data ?
                            '<span class="badge bg-success">Hot</span>' :
                            '<span class="badge bg-danger">No Hot</span>';
                    }
                },
                {
                    data: 'duration',
                    name: 'duration'
                },
                {
                    data: 'id',
                    render: function (data) {
                        return `
        <div class="text-center">
            <a href="/admin/movies/${data}" class="btn btn-success">
                <i class="fas fa-eye"></i>
            </a>
            <a href="/admin/movies/${data}/edit" class="btn btn-warning">
                <i class="fas fa-edit"></i>
            </a>
            <form action="/admin/movies/${data}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"
                    onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </form>
        </div>
                                                    `;
                    },
                    orderable: false,
                    searchable: false
                }
                ],
                pageLength: 5, // Số bản ghi hiển thị mặc định
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
