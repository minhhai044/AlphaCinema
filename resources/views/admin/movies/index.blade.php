@extends('admin.layouts.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="">
                <div class="card-body">
                    <h4 class="card-title">Danh sách phim</h4>

                    <!-- Container chứa các action -->
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
                            <a href="{{ route('admin.movies.create') }}" class="btn btn-primary me-2">+ Thêm mới</a>
                            <button class="btn btn-outline-secondary me-2" type="button" data-bs-toggle="collapse"
                                data-bs-target="#searchForm">
                                <i class="fas fa-filter"></i> Bộ lọc
                            </button>
                            <button class="btn btn-warning waves-effect waves-light">
                                <a href="{{ route('admin.export', 'movies') }}">
                                    <i class="bx bx-download me-1"></i> Xuất Excel</a>
                            </button>
                        </div>
                    </div>

                    <!-- Form lọc -->
                    <div class="collapse" id="searchForm">
                        <div class="card card-body mb-3">
                            <form id="filterForm">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">Tìm nhanh theo ID</label>
                                        <input type="text" name="id" class="form-control" placeholder="Nhập ID">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Phiên bản</label>
                                        <select name="movie_versions" class="form-control">
                                            <option value="">-- Chọn phiên bản --</option>
                                            <option value="2D">2D</option>
                                            <option value="3D">3D</option>
                                            <option value="4D">4D</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Thể loại</label>
                                        <select name="movie_genres" class="form-control">
                                            <option value="">-- Chọn thể loại --</option>
                                            <option value="Action">Hành Động</option>
                                            <option value="Horror">Kinh Dị</option>
                                            <option value="Comedy">Hài Hước</option>
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
                        <table id="movieTable" class="table table-bordered w-100 ">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên phim</th>
                                    <th>Ảnh</th>
                                    <th>Hoạt động</th>
                                    <th>Nổi bật</th>
                                    <th>Xuất bản</th>
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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script>
        $(document).ready(function() {
            var table = $('#movieTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,
                order: [],
                ajax: {
                    url: "{{ route('api.movies.index') }}",
                    type: "GET",
                    data: function(d) {
                        d.id = $('input[name="id"]').val();
                        d.movie_versions = $('select[name="movie_versions"]').val();
                        d.movie_genres = $('select[name="movie_genres"]').val();
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
                        name: 'name',
                        render: function(data, type, row) {
                            console.log("Dữ liệu row:", row); // Kiểm tra dữ liệu

                            if (!row) return '<span style="color: gray;">Không có dữ liệu</span>';

                            // Bảng ánh xạ thể loại phim từ tiếng Anh sang tiếng Việt
                            let genreMapping = {
                                "Action": "Hành động",
                                "Horror": "Kinh dị",
                                "Comedy": "Hài",
                                "Drama": "Chính kịch",
                                "Sci-Fi": "Khoa học viễn tưởng",
                                "Fantasy": "Giả tưởng",
                                "Romance": "Lãng mạn",
                                "Thriller": "Giật gân",
                                "Adventure": "Phiêu lưu",
                                "Animation": "Hoạt hình"
                            };

                            // Xử lý danh sách thể loại phim (movie_genres)
                            let genres = Array.isArray(row.movie_genres) ? row.movie_genres : [];
                            let genreHtml = genres.map(genre => {
                                let vietnameseGenre = genreMapping[genre] ||
                                    genre; // Nếu không có trong danh sách thì giữ nguyên
                                return `<span style="background-color: green; color: white; padding: 3px 5px; border-radius: 5px; margin-right: 5px;">
                ${vietnameseGenre}
            </span>`;
                            }).join(' ');

                            // Xử lý danh sách phiên bản phim (movie_versions)
                            let versions = Array.isArray(row.movie_versions) ? row.movie_versions :
                                [];
                            let versionHtml = versions.map(version =>
                                `<span style="background-color: blue; color: white; padding: 3px 5px; border-radius: 5px; margin-right: 5px;">
                ${version}
            </span>`).join(' ');

                            return `
            <div style="padding: 15px; border-radius: 8px;">
                <h3 style="margin: 0 0 10px; color: #007bff; font-weight: bold;">${data || '<span style="color: gray;">Chưa có tên phim</span>'}</h3>

                <p style="margin: 5px 0; font-size: 14px;">
                    <strong style="color: #333;">Đạo diễn:</strong> ${row.director || '<span style="color: gray;">Đang cập nhật</span>'}
                </p>

                <p style="margin: 5px 0; font-size: 14px;">
                    <strong style="color: #333;">Thời lượng:</strong> ${row.duration || '<span style="color: gray;">Chưa có</span>'} phút
                </p>

                <p style="margin: 5px 0; font-size: 14px;">
                    <strong style="color: #333;">Thể loại:</strong> ${genreHtml || '<span style="color: gray;">Chưa cập nhật</span>'}
                </p>

                <p style="margin: 5px 0; font-size: 14px;">
                    <strong style="color: #333;">Ngày khởi chiếu:</strong> ${row.release_date || '<span style="color: gray;">Chưa có</span>'}
                </p>

                <p style="margin: 5px 0; font-size: 14px;">
                    <strong style="color: #333;">Ngày kết thúc:</strong> ${row.end_date || '<span style="color: gray;">Chưa có</span>'}
                </p>

                <p style="margin: 5px 0; font-size: 14px;">
                    <strong style="color: #333;">Danh mục:</strong> ${row.category || '<span style="color: gray;">Chưa có</span>'}
                </p>

                <p style="margin: 5px 0; font-size: 14px;">
                    <strong style="color: #333;">Phiên bản:</strong> ${versionHtml || '<span style="color: gray;">Chưa cập nhật</span>'}
                </p>

                <p style="margin: 5px 0; font-size: 14px;">
                    <strong style="color: #333;">Mã YouTube Trailer:</strong>
                    <input type="text" value="${row.trailer_url || 'Không có'}" readonly
                        style="border: 1px solid #ccc; padding: 5px; width: 100%; border-radius: 4px; background: #fff; font-size: 13px;">
                </p>
            </div>
        `;
                        }
                    },

                    {
                        data: 'img_thumbnail',
                        render: function(data) {
                            return data ?
                                `<img src="/storage/${data}" style="max-width: 200px; height: auto; display: block; margin: 0 auto;">` :
                                'No image';
                        }
                    },
                    {
                        data: 'is_active',
                        render: function(data, type, row) {
                            return `
                                <div class="form-check form-switch form-switch-success">
                                    <input class="form-check-input changeStatus"
                                           type="checkbox"
                                           data-movie-id="${row.id}"
                                           data-field="is_active"
                                           ${data ? 'checked' : ''}
                                           onclick="return confirm('Bạn có chắc muốn thay đổi trạng thái Hoạt động?')">
                                </div>
                            `;
                        },
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'is_hot',
                        render: function(data, type, row) {
                            return `
                                <div class="form-check form-switch form-switch-success">
                                    <input class="form-check-input changeStatus"
                                           type="checkbox"
                                           data-movie-id="${row.id}"
                                           data-field="is_hot"
                                           ${data ? 'checked' : ''}
                                           onclick="return confirm('Bạn có chắc muốn thay đổi trạng thái Nổi bật?')">
                                </div>
                            `;
                        },
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'is_publish',
                        render: function(data, type, row) {
                            return `
                                <div class="form-check form-switch form-switch-success">
                                    <input class="form-check-input changeStatus"
                                           type="checkbox"
                                           data-movie-id="${row.id}"
                                           data-field="is_publish"
                                           ${data ? 'checked' : ''}
                                           onclick="return confirm('Bạn có chắc muốn thay đổi trạng thái Xuất bản?')">
                                </div>
                            `;
                        },
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'id',
                        render: function(data) {
                            return `
                                <div class="text-center">
                                    <a href="/admin/movies/${data}" class="btn btn-success">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="/admin/movies/${data}/edit" class="btn btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                </div>
                            `;
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
                        next: "Next",
                        previous: "Previous"
                    },
                    lengthMenu: "Hiển thị _MENU_ mục",
                    info: "Hiển thị từ _START_ đến _END_ trong tổng số _TOTAL_ mục",
                    emptyTable: "Không có dữ liệu để hiển thị",
                    zeroRecords: "Không tìm thấy kết quả phù hợp"
                }
            });

            // Xử lý sự kiện thay đổi trạng thái switch
            $('#movieTable').on('change', '.changeStatus', function() {
                let movieId = $(this).data('movie-id');
                let field = $(this).data('field');
                let value = $(this).is(':checked') ? 1 : 0;

                $.ajax({
                    url: `/api/admin/movies/${movieId}/update-status`,
                    method: 'PATCH',
                    data: {
                        field: field,
                        value: value,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            table.ajax.reload(null, false);
                            alert('Cập nhật trạng thái thành công!');
                        } else {
                            alert('Có lỗi xảy ra khi cập nhật trạng thái.');
                        }
                    },
                    error: function(xhr) {
                        console.error('Lỗi:', xhr.responseText);
                        alert('Đã xảy ra lỗi khi cập nhật. Vui lòng thử lại!');
                        $(this).prop('checked', !value); // Hoàn tác nếu lỗi
                    }
                });
            });

            // Thay đổi số dòng hiển thị theo dropdown tùy chỉnh
            $('#pageLength').on('change', function() {
                table.page.len($(this).val()).draw();
            });

            // Lọc dữ liệu
            $('#filterForm').on('submit', function(e) {
                e.preventDefault();
                table.ajax.reload();
            });

            // Reset lọc
            $('#resetFilter').on('click', function() {
                setTimeout(function() {
                    table.ajax.reload();
                }, 50);
            });
        });
    </script>
@endsection

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
        #movieTable thead th {
            text-align: center;
            vertical-align: middle;
        }

        .form-check.form-switch {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            background-color: #ffffff !important;
            border: 1px solid #e9ecef !important;
            margin: 0 2px;
            padding: 6px 12px !important;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: transparent !important;
            color: inherit !important;
            border: none !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:active {
            background-color: transparent !important;
            color: inherit !important;
            border: none !important;
            box-shadow: none !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background-color: #565ccf !important;
            color: #ffffff !important;
            border-color: #565ccf !important;
            box-shadow: 0 2px 6px rgba(86, 92, 207, 0.2);
            transform: translateY(-1px);
            font-weight: 600;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
            color: #ced4da !important;
            background-color: #f8f9fa !important;
            border-color: #e9ecef !important;
            box-shadow: none !important;
            cursor: not-allowed;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.previous,
        .dataTables_wrapper .dataTables_paginate .paginate_button.next {
            background-color: #f8f9fa !important;
            border: 1px solid #e9ecef !important;
            border-radius: 4px !important;
            padding: 6px 12px !important;
            text-transform: uppercase;
            font-weight: 500;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.previous:hover,
        .dataTables_wrapper .dataTables_paginate .paginate_button.next:hover {
            background-color: #ffffff !important;
            border-color: #ced4da !important;
            border-radius: 0 !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        td img {
            max-width: 100px;
            height: auto;
        }

        #pageLength {
            font-size: 12px;
            padding: 5px 10px;
            width: 100px;
        }

        /* Tùy chỉnh switch */
        .form-switch .form-check-input {
            width: 2em;
            height: 1em;
            margin-top: 0.25em;
        }
    </style>
@endsection
