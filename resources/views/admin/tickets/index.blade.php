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
        .filter-row {
            margin-bottom: 20px;
        }

        .filter-row .form-group {
            margin-right: 10px;
        }

        .btn-filter {
            background-color: #28a745;
            border-color: #28a745;
            color: white;
        }

        .btn-filter:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }

        .table th,
        .table td {
            vertical-align: middle;
            text-align: left;
            padding: 12px;
        }

        .table .movie-thumbnail {
            max-width: 60%;
            max-height: 60%;
            object-fit: cover;
            border-radius: 4px;
            /* margin-bottom: 10px; */
        }
    </style>
@endsection

@section('content')
    <h5 class="fw-bold">Quản lý hóa đơn</h5>
    <div class="rounded">
        <form action="{{ route('admin.tickets.index') }}" method="get" class="filter-row d-flex align-items-end">
            <div class="form-group">
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
            <div class="form-group">
                <label for="cinema_id" class="form-label fw-bold">Rạp</label>
                <select name="cinema_id" class="form-select" required id="cinema_id">
                    <option value="" disabled selected>Chọn rạp</option>
                    @if (!empty($cinemas))
                        @foreach ($cinemas as $cinemaId => $cinemaName)
                            <option value="{{ $cinemaId }}" {{ request('cinema_id') == $cinemaId ? 'selected' : '' }}>
                                {{ $cinemaName }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="form-group">
                <label for="date" class="form-label fw-bold">Ngày</label>
                <input type="date" name="date" id="date" class="form-control" value="{{ request('date') }}"
                    required>
            </div>
            <div class="form-group">
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
            <div class="form-group">
                <label for="status_id" class="form-label fw-bold">Trạng thái</label>
                <select name="status_id" class="form-select" required id="status_id">
                    <option value="" selected>Tất cả</option>
                    <option value="confirmed" {{ request('status_id') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận
                    </option>
                    <option value="pending" {{ request('status_id') == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                </select>
            </div>
            <div class="form-group mt-4">
                <button type="submit" class="btn btn-filter">Lọc</button>
            </div>
        </form>

        <div class="table-responsive mt-3">
            <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                <thead>
                    <tr class="text-center">
                        <th>Mã vé</th>
                        <th>Thông tin người dùng</th>
                        <th>Hình ảnh</th>
                        <th>Thông tin vé</th>
                        <th>Chức năng</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tickets as $ticket)
                        <tr>
                            <td>{{ $ticket->code }}</td>
                            <td class="">
                                <strong>Người dùng:</strong> {{ $ticket->user->name ?? 'N/A' }}<br>
                                <strong>Chức vụ:</strong><span
                                    class="badge bg-success">{{ $ticket->user->role ?? 'member' }}</span><br>
                                <strong>Email:</strong> {{ $ticket->user->email ?? 'N/A' }}<br>
                                <strong>Phương thức thanh toán:</strong> {{ $ticket->payment_name ?? 'N/A' }}
                            </td>
                            <td>
                                <img src="/storage/{{ $ticket->movie->img_thumbnail ?? asset('path/to/default/image.jpg') }}"
                                    alt="{{ $ticket->movie->name ?? 'Movie Thumbnail' }}" class="movie-thumbnail"><br>
                            </td>

                            <td class="ticket-info">
                                <strong>Phim:</strong> {{ $ticket->movie->name ?? 'N/A' }}<br>
                                <strong>Nơi chiếu:</strong> {{ $ticket->branch->name ?? 'N/A' }} -
                                {{ $ticket->cinema->name ?? 'N/A' }} -
                                P{{ $ticket->cinema->room ?? 'N/A' }}<br>
                                <strong>Ghế:</strong>
                                @php
                                    $seats = $ticket->ticket_seats ?? [];
                                    $seatNames = array_map(fn($seat) => $seat['name'] ?? 'N/A', $seats);
                                    echo implode(', ', $seatNames) ?: 'N/A';
                                @endphp
                                <strong>Tổng tiền:</strong> {{ number_format($ticket->total_price, 0, ',', '.') }} VNĐ<br>
                                <strong>Trạng thái:</strong>
                                {{ $ticket->status == 'confirmed' ? 'Đã xác nhận' : 'Chờ xác nhận' }}<br>
                                <strong>Lịch chiếu:</strong> {{ $ticket->showtime->start_time ?? 'N/A' }} -
                                {{ $ticket->showtime->end_time ?? 'N/A' }}<br>
                                <strong>Ngày chiếu:</strong> {{ $ticket->showtime->date ?? 'N/A' }}<br>
                                <strong>Thời hạn sử dụng:</strong>
                                {{ \Carbon\Carbon::parse($ticket->showtime->end_time ?? '')->format('H:i') }},
                                {{ \Carbon\Carbon::parse($ticket->showtime->date ?? '')->format('d/m/Y') }}
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.tickets.show', $ticket['id']) }}"
                                        class="btn btn-sm btn-success">Xem vé</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Không có vé nào.</td>
                        </tr>
                    @endforelse
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
        $(document).ready(function() {
            // Khi người dùng thay đổi chi nhánh
            $('#branch_id').change(function() {
                var branchId = $(this).val();
                $('#cinema_id').empty().append('<option value="" disabled selected>Chọn rạp</option>');
                $('#movie_id').empty().append('<option value="" disabled selected>Chọn phim</option>');

                if (branchId) {
                    $.ajax({
                        url: '{{ route('api.get-cinemas') }}',
                        type: 'GET',
                        data: {
                            branch_id: branchId
                        },
                        success: function(response) {
                            $.each(response.cinemas, function(index, cinema) {
                                $('#cinema_id').append('<option value="' + cinema.id +
                                    '">' + cinema.name + '</option>');
                            });
                        }
                    });
                }
            });

            // Khi người dùng thay đổi rạp phim
            $('#cinema_id').change(function() {
                var cinemaId = $(this).val();
                $('#movie_id').empty().append('<option value="" disabled selected>Chọn phim</option>');

                if (cinemaId) {
                    $.ajax({
                        url: '{{ route('api.get-movies') }}',
                        type: 'GET',
                        data: {
                            cinema_id: cinemaId
                        },
                        success: function(response) {
                            $.each(response.movies, function(index, movie) {
                                $('#movie_id').append('<option value="' + movie.id +
                                    '">' + movie.name + '</option>');
                            });
                        }
                    });
                }
            });

            // Khởi tạo DataTable
            $('#datatable').DataTable({
                "pageLength": 10,
                "order": [
                    [0, 'asc']
                ],
                "dom": 'Bfrtip',
                "buttons": ['copy', 'csv', 'excel', 'pdf', 'print'],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Vietnamese.json"
                }
            });
        });
    </script>
@endsection
