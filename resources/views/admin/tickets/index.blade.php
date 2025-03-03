@extends('admin.layouts.master')

@section('style')
    <link href="{{ asset('theme/admin/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/admin/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/admin/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('theme/admin/assets/css/preloader.min.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('theme/admin/assets/css/preloader.min.css') }}" type="text/css" />

    <link href="{{ asset('theme/admin/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('theme/admin/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <h5 class="fw-bold">Quản lý hóa đơn</h5>
    <div class="rounded">
        <form action="{{ route('admin.tickets.index') }}" method="get">
            <div class="row align-items-end ">
                <div class="col-lg-2">
                    <label for="branch_id" class="form-label fw-bold">Chi nhánh</label>
                    <select name="branch_id" class="form-select" required id="branch_id">
                        <option value="" disabled selected>Chọn chi nhánh</option>
                        @if ($branches && $branches->isNotEmpty())
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-lg-2">
                    <label for="cinema_id" class="form-label fw-bold">Rạp phim</label>
                    <select name="cinema_id" class="form-select" required id="cinema_id">
                        <option value="" disabled selected>Chọn rạp phim</option>
                        @if (!empty($cinemas))
                            @foreach ($cinemas as $cinemaId => $cinemaName)
                                <option value="{{ $cinemaId }}" {{ request('cinema_id') == $cinemaId ? 'selected' : '' }}>
                                    {{ $cinemaName }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-lg-2">
                    <label for="date" class="form-label fw-bold">Ngày chiếu</label>
                    <input type="date" name="date" id="date" class="form-control" value="{{ request('date') }}" required>
                </div>
                <div class="col-lg-3">
                    <label for="movie_id" class="form-label fw-bold">Phim</label>
                    <select name="movie_id" class="form-select" required id="movie_id">
                        <option value="" disabled selected>Chọn phim</option>
                        @if ($movies && $movies->isNotEmpty())
                            @foreach ($movies as $movie)
                                <option value="{{ $movie->id }}" {{ request('movie_id') == $movie->id ? 'selected' : '' }}>
                                    {{ $movie->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-lg-2">
                    <label for="status_id" class="form-label fw-bold">Trạng thái</label>
                    <select name="status_id" class="form-select" required id="status_id">
                        <option value="" disabled selected>Tất cả</option>
                        <option value="confirmed" {{ request('status_id') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận
                        </option>
                        <option value="pending" {{ request('status_id') == 'pending' ? 'selected' : '' }}>Chờ xác nhận
                        </option>
                    </select>
                </div>
                <div class="col-lg-1">
                    <button type="submit" class="btn btn-primary"><i class="bx bx-search-alt-2"></i></button>
                </div>
            </div>
        </form>

        <div class="modal fade" id="movieModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-light">Danh sách phim đang hoạt động</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <ul class="list-group">
                            @if ($movies && $movies->isNotEmpty())
                                @foreach ($movies as $movie)
                                    <a href="{{ route('admin.showtimes.create', $movie->id) }}">
                                        <li class="list-group-item movie-item fw-semibold">{{ $movie->name }}</li>
                                    </a>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive mt-3">
            <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                <thead>
                    <tr class="text-center">
                        <th>Mã vé</th>
                        <th>Thông tin người dùng</th>
                        <th>Thông tin vé</th>
                        <th>Chức năng</th>
                        <th>Giới tính</th>
                        <th>Giới tính</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($tickets && $tickets->isNotEmpty())
                        @foreach ($tickets as $ticket)
                            <tr>
                                <td>{{ $ticket->code }}</td>
                                <td>
                                    {{ $ticket->user->name ?? 'N/A' }}<br>
                                    Email: {{ $ticket->user->email ?? 'N/A' }}<br>
                                    Phương thức thanh toán: {{ $ticket->payment_name ?? 'N/A' }}
                                </td>
                                <td>
                                    Phim: {{ $ticket->movie->name ?? 'N/A' }}<br>
                                    Rạp: {{ $ticket->cinema->name ?? 'N/A' }}<br>
                                    Chi nhánh: {{ $ticket->branch->name ?? 'N/A' }}<br>
                                    Ngày: {{ $ticket->date ?? 'N/A' }}<br>
                                    Trạng thái: {{ $ticket->status == 'confirmed' ? 'Đã xác nhận' : 'Chờ xác nhận' }}<br>
                                    Tổng tiền: {{ number_format($ticket->total_price, 0, ',', '.') }} VNĐ
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-success">Xem vé</button>
                                    <button class="btn btn-sm btn-success">In vé</button>
                                    <button class="btn btn-sm btn-success">In combo</button>
                                    <button class="btn btn-sm btn-danger">Hủy đặt</button>
                                </td>
                                <td>{{ $ticket->user->gender ?? 'N/A' }}</td>
                                <td>{{ $ticket->user->gender ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="text-center">Không có vé nào.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    @php
        $appUrl = env('APP_URL');
    @endphp
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

    <script>
        $(document).ready(function () {
            // Khi người dùng thay đổi chi nhánh
            $('#branch_id').change(function () {
                var branchId = $(this).val(); // Lấy giá trị chi nhánh đã chọn

                // Ẩn tất cả các rạp phim và phim
                $('#cinema_id').empty().append('<option value="" disabled selected>Chọn rạp phim</option>');
                $('#movie_id').empty().append('<option value="" disabled selected>Chọn phim</option>');

                if (branchId) {
                    // Gửi yêu cầu AJAX để lấy danh sách rạp phim theo chi nhánh
                    $.ajax({
                        url: '{{ route("api.get-cinemas") }}', // Đảm bảo route này tồn tại
                        type: 'GET',
                        data: {
                            branch_id: branchId
                        },
                        success: function (response) {
                            // Thêm các rạp phim vào select
                            $.each(response.cinemas, function (index, cinema) {
                                $('#cinema_id').append('<option value="' + cinema.id + '">' + cinema.name + '</option>');
                            });
                        }
                    });
                }
            });

            // Khi người dùng thay đổi rạp phim
            $('#cinema_id').change(function () {
                var cinemaId = $(this).val(); // Lấy giá trị rạp phim đã chọn

                // Ẩn tất cả các phim
                $('#movie_id').empty().append('<option value="" disabled selected>Chọn phim</option>');

                if (cinemaId) {
                    // Gửi yêu cầu AJAX để lấy danh sách phim theo rạp
                    $.ajax({
                        url: '{{ route("api.get-movies") }}', // Đảm bảo route này tồn tại
                        type: 'GET',
                        data: {
                            cinema_id: cinemaId
                        },
                        success: function (response) {
                            // Thêm các phim vào select
                            $.each(response.movies, function (index, movie) {
                                $('#movie_id').append('<option value="' + movie.id + '">' + movie.name + '</option>');
                            });
                        }
                    });
                }
            });

            // Khởi tạo DataTable
            $('#datatable').DataTable({
                "pageLength": 10,
                "order": [[0, 'asc']], // Sắp xếp theo cột Mã vé tăng dần
                "dom": 'Bfrtip',
                "buttons": [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Vietnamese.json"
                }
            });
        });
    </script>
@endsection
