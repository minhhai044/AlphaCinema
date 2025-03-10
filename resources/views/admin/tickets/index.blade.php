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
    <link href="{{ asset('assets/css/ticketPrint.css') }}" rel="stylesheet" type="text/css" />

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

        .dashed-hr {
            border: 1px dashed #6c757d;
            margin: 10px 0;
            opacity: 0.5;
            background-color: #efefff
        }
    </style>
@endsection

{{-- @section('content')
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
                        <!-- Sửa lại: Chỉ để lại một cột "Giới tính" nếu cần -->
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tickets as $ticket)
                        <tr>
                            <td>{{ $ticket->code }}</td>
                            <td>
                                <strong>Người dùng:</strong> {{ $ticket->user->name ?? 'N/A' }}<br>
                                <strong>Chức vụ:</strong><span
                                    class="badge bg-success">{{ $ticket->user->role ?? 'member' }}</span><br>
                                <strong>Email:</strong> {{ $ticket->user->email ?? 'N/A' }}<br>
                                <strong>Phương thức thanh toán:</strong> {{ $ticket->payment_name ?? 'N/A' }}
                            </td>
                            <td>
                                <strong>Phim:</strong> {{ $ticket->movie->name ?? 'N/A' }}<br>
                                <strong>Nơi chiếu:</strong> {{ $ticket->branch->name ?? 'N/A' }} -
                                {{ $ticket->cinema->name ?? 'N/A' }} - P{{ $ticket->cinema->room ?? 'N/A' }}<br>
                                <strong>Ghế:</strong>
                                @php
                                    $seats = $ticket->ticket_seats ?? [];
                                    $seatNames = array_map(fn($seat) => $seat['name'] ?? 'N/A', $seats);
                                    echo implode(', ', $seatNames) ?: 'N/A';
                                @endphp<br>
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
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="{{ route('admin.tickets.show', $ticket['id']) }}"
                                        class="btn btn-sm btn-success"><i class="fas fa-eye"></i></a>
                                    @if ($ticket->status === 'confirmed')
                                        <button class="btn btn-sm btn-success printTicket" data-id="{{ $ticket->id }}"><i
                                                class="fas fa-print"></i> Vé</button>
                                        <button class="btn btn-sm btn-success printCombo" data-id="{{ $ticket->id }}"><i
                                                class="fas fa-print"></i> Combo</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Không có vé nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div id="ticketModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel">Thông tin vé</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="ticketContainer"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="button" class="btn btn-primary" id="printAllTickets">In tất cả vé</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection --}}

@section('content')
    <h5 class="fw-bold">Quản lý hóa đơn</h5>
    <div class="rounded">
        <form action="{{ route('admin.tickets.index') }}" method="get"
            class="filter-row d-flex align-items-end gap-3 mb-4">
            <!-- Chi nhánh -->
            <div class="form-group col-md-2">
                <label for="branch_id" class="form-label fw-bold">
                    <i class="bi bi-geo-alt me-1"></i> Chi nhánh
                </label>
                <select name="branch_id" class="form-select" id="branch_id" required>
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

            <!-- Rạp -->
            <div class="form-group col-md-2">
                <label for="cinema_id" class="form-label fw-bold">
                    <i class="bi bi-camera-reels me-1"></i> Rạp
                </label>
                <select name="cinema_id" class="form-select" id="cinema_id" required>
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

            <!-- Ngày -->
            <div class="form-group col-md-2">
                <label for="date" class="form-label fw-bold">
                    <i class="bi bi-calendar me-1"></i> Ngày
                </label>
                <input type="date" name="date" id="date" class="form-control" value="{{ request('date') }}"
                    required>
            </div>

            <!-- Phim -->
            <div class="form-group col-md-2">
                <label for="movie_id" class="form-label fw-bold">
                    <i class="bi bi-film me-1"></i> Phim
                </label>
                <select name="movie_id" class="form-select" id="movie_id" required>
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

            <!-- Trạng thái -->
            <div class="form-group col-md-2">
                <label for="status_id" class="form-label fw-bold">
                    <i class="bi bi-check-circle me-1"></i> Trạng thái
                </label>
                <select name="status_id" class="form-select" id="status_id" required>
                    <option value="" selected>Tất cả</option>
                    <option value="confirmed" {{ request('status_id') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận
                    </option>
                    <option value="pending" {{ request('status_id') == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                </select>
            </div>

            <!-- Nút Lọc -->
            <div class="form-group col-md-1 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-funnel me-1"></i> Lọc
                </button>
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
                        <!-- Sửa lại: Chỉ để lại một cột "Giới tính" nếu cần -->
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tickets as $ticket)
                        <tr>
                            <td>{{ $ticket->code }}</td>
                            <td>
                                <strong>Người dùng:</strong> {{ $ticket->user->name ?? 'N/A' }}<br>
                                <strong>Chức vụ:</strong><span
                                    class="badge bg-success">{{ $ticket->user->role ?? 'member' }}</span><br>
                                <strong>Email:</strong> {{ $ticket->user->email ?? 'N/A' }}<br>
                                <strong>Phương thức thanh toán:</strong> {{ $ticket->payment_name ?? 'N/A' }}
                            </td>
                            <td>
                                <strong>Phim:</strong> {{ $ticket->movie->name ?? 'N/A' }}<br>
                                <strong>Nơi chiếu:</strong> {{ $ticket->branch->name ?? 'N/A' }} -
                                {{ $ticket->cinema->name ?? 'N/A' }} <br>
                                <strong>Ghế:</strong>
                                @php
                                    $seats = $ticket->ticket_seats ?? [];
                                    $seatNames = array_map(fn($seat) => $seat['name'] ?? 'N/A', $seats);
                                    echo implode(', ', $seatNames) ?: 'N/A';
                                @endphp<br>
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
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="{{ route('admin.tickets.show', $ticket['id']) }}"
                                        class="btn btn-sm btn-success"><i class="fas fa-eye"></i></a>
                                    <button class="btn btn-sm btn-success printTicket" data-id="{{ $ticket->id }}"><i
                                            class="fas fa-print"></i> Vé</button>
                                    <button class="btn btn-sm btn-success printCombo" data-id="{{ $ticket->id }}"><i
                                            class="fas fa-print"></i> Combo</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Không có vé nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div id="ticketModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel">Thông tin vé</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="ticketContainer"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="button" class="btn btn-primary" id="printAllTickets">In tất cả vé</button>
                    </div>
                </div>
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
    {{-- <script src="{{ asset('theme/admin/assets/js/pages/modal.init.js') }}"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>

    <script src="{{ asset('assets/js/ticket/index.js') }}">
        $(document).ready(function() {
            // Hàm định dạng tiền tệ
            const formatCurrency = (number) => {
                return new Intl.NumberFormat('vi-VN', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(number);
            };

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
                            if (response.cinemas && Array.isArray(response.cinemas)) {
                                $.each(response.cinemas, function(index, cinema) {
                                    $('#cinema_id').append('<option value="' + cinema
                                        .id + '">' + cinema.name + '</option>');
                                });
                            } else {
                                alert('Không có rạp nào được tìm thấy.');
                            }
                        },
                        error: function() {
                            alert('Lỗi khi lấy danh sách rạp phim.');
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
                            if (response.movies && Array.isArray(response.movies)) {
                                $.each(response.movies, function(index, movie) {
                                    $('#movie_id').append('<option value="' + movie.id +
                                        '">' + movie.name + '</option>');
                                });
                            } else {
                                alert('Không có phim nào được tìm thấy.');
                            }
                        },
                        error: function() {
                            alert('Lỗi khi lấy danh sách phim.');
                        }
                    });
                }
            });
        });
    </script>
@endsection
