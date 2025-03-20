@extends('admin.layouts.master')

@section('style')
    <link href="{{ asset('theme/admin/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/admin/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/admin/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('theme/admin/assets/css/preloader.min.css') }}" type="text/css" />
    <link href="{{ asset('theme/admin/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/admin/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />

    <style>
        .table { vertical-align: middle !important; }
        table.dataTable thead th, table.dataTable thead td, table.dataTable tfoot th, table.dataTable tfoot td {
            text-align: center;
        }
        .dashed-hr { border: 1px dashed #6c757d; margin: 10px 0; opacity: 0.5; background-color: #efefff }
    </style>
@endsection

@section('content')
    <h5 class="fw-bold">Quản lý hóa đơn</h5>
    <div class="rounded">
        <form action="{{ route('admin.tickets.index') }}" method="get" class="filter-row d-flex align-items-end gap-3 mb-4">
            <!-- Chi nhánh (Chỉ hiển thị dropdown cho System Admin) -->
            @if(auth()->user()->hasRole('system-admin'))
                <div class="form-group col-md-2">
                    <label for="branch_id" class="form-label fw-bold">
                        <i class="bi bi-geo-alt me-1"></i> Chi nhánh
                    </label>
                    <select name="branch_id" class="form-select" id="branch_id">
                        <option value="" {{ request('branch_id') ? '' : 'selected' }}>Chọn chi nhánh</option>
                        @if ($branches && $branches->isNotEmpty())
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
            @else
                @if(auth()->user()->branch_id)
                    <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id }}">
                    <div class="form-group col-md-2">
                        <label class="form-label fw-bold">
                            <i class="bi bi-geo-alt me-1"></i> Chi nhánh
                        </label>
                        <p class="form-control-static">{{ auth()->user()->branch->name ?? 'N/A' }}</p>
                    </div>
                @endif
            @endif

            <!-- Rạp (Hiển thị dropdown cho System Admin hoặc Quản lý chi nhánh) -->
            @if(auth()->user()->hasRole('system-admin') || auth()->user()->branch_id)
                <div class="form-group col-md-2">
                    <label for="cinema_id" class="form-label fw-bold">
                        <i class="bi bi-camera-reels me-1"></i> Rạp
                    </label>
                    <select name="cinema_id" class="form-select" id="cinema_id">
                        <option value="" {{ request('cinema_id') ? '' : 'selected' }}>Chọn rạp</option>
                        @if(auth()->user()->hasRole('system-admin') && request('branch_id') && isset($branchesRelation[request('branch_id')]))
                            @foreach ($branchesRelation[request('branch_id')] as $cinemaId => $cinemaName)
                                <option value="{{ $cinemaId }}" {{ request('cinema_id') == $cinemaId ? 'selected' : '' }}>
                                    {{ $cinemaName }}
                                </option>
                            @endforeach
                        @elseif(auth()->user()->branch_id && isset($branchesRelation[auth()->user()->branch_id]))
                            @foreach ($branchesRelation[auth()->user()->branch_id] as $cinemaId => $cinemaName)
                                <option value="{{ $cinemaId }}" {{ request('cinema_id') == $cinemaId ? 'selected' : '' }}>
                                    {{ $cinemaName }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
            @else
                @if(auth()->user()->cinema_id)
                    <input type="hidden" name="cinema_id" value="{{ auth()->user()->cinema_id }}">
                    <div class="form-group col-md-2">
                        <label class="form-label fw-bold">
                            <i class="bi bi-camera-reels me-1"></i> Rạp
                        </label>
                        <p class="form-control-static">{{ auth()->user()->cinema->name ?? 'N/A' }}</p>
                    </div>
                @endif
            @endif

            <!-- Ngày -->
            <div class="form-group col-md-2">
                <label for="date" class="form-label fw-bold">
                    <i class="bi bi-calendar me-1"></i> Ngày
                </label>
                <input type="date" name="date" id="date" class="form-control" value="{{ request('date') }}">
            </div>

            <!-- Phim -->
            <div class="form-group col-md-2">
                <label for="movie_id" class="form-label fw-bold">
                    <i class="bi bi-film me-1"></i> Phim
                </label>
                <select name="movie_id" class="form-select" id="movie_id">
                    <option value="" {{ request('movie_id') ? '' : 'selected' }}>Chọn phim</option>
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
                <select name="status_id" class="form-select" id="status_id">
                    <option value="" {{ request('status_id') ? '' : 'selected' }}>Tất cả</option>
                    <option value="confirmed" {{ request('status_id') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
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

        <!-- Modal danh sách phim -->
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

        <!-- Bảng dữ liệu -->
        <div class="table-responsive mt-3">
            <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                <thead>
                    <tr class="text-center">
                        <th>Mã vé</th>
                        <th>Thông tin người dùng</th>
                        <th>Thông tin vé</th>
                        <th>Trạng thái</th>
                        <th>Chức năng</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tickets as $ticket)
                        <tr>
                            <td>{{ $ticket->code }}</td>
                            <td>
                                <div><span class="fw-bold">Người dùng:</span> {{ $ticket->user->name ?? 'N/A' }}</div>
                                <div><span class="fw-bold">Chức vụ:</span>
                                    <span class="badge bg-success">{{ $ticket->user->role ?? 'member' }}</span>
                                </div>
                                <div><span class="fw-bold">Email:</span> {{ $ticket->user->email ?? 'N/A' }}</div>
                                <div><span class="fw-bold">Phương thức thanh toán:</span>
                                    {{ $ticket->payment_name ?? 'N/A' }}</div>
                            </td>
                            <td>
                                <div>Phim:</span> {{ $ticket->movie->name ?? 'N/A' }}</div>
                                <div><span class="fw-bold">Nơi chiếu:</span> {{ $ticket->branch->name ?? 'N/A' }} -
                                    {{ $ticket->cinema->name ?? 'N/A' }}</div>
                                <div>
                                    <span class="fw-bold">Ghế:</span>
                                    @php
                                        $seats = $ticket->ticket_seats ?? [];
                                        $seatNames = array_map(fn($seat) => $seat['seat_name'] ?? 'N/A', $seats);
                                        echo implode(', ', $seatNames) ?: 'N/A';
                                    @endphp
                                </div>
                                <div><span class="fw-bold">Tổng tiền:</span>
                                    {{ number_format($ticket->total_price, 0, ',', '.') }} VNĐ</div>
                                <div>
                                    <span class="fw-bold">Trạng thái:</span>
                                    <span id="statusTicket" class="badge {{ $ticket->status == 'confirmed' ? 'bg-success' : 'bg-warning' }}">
                                        {{ $ticket->status == 'confirmed' ? 'Đã xác nhận' : 'Chờ xác nhận' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="fw-bold">Lịch chiếu:</span>
                                    {{ $ticket->showtime->start_time ?? 'N/A' }} -
                                    {{ $ticket->showtime->end_time ?? 'N/A' }}
                                </div>
                                <div><span class="fw-bold">Ngày chiếu:</span> {{ $ticket->showtime->date ?? 'N/A' }}</div>
                                <div>
                                    <span class="fw-bold">Thời hạn sử dụng:</span>
                                    {{ \Carbon\Carbon::parse($ticket->showtime->end_time ?? '')->format('H:i') }},
                                    {{ \Carbon\Carbon::parse($ticket->showtime->date ?? '')->format('d/m/Y') }}
                                </div>
                            </td>
                            <td>
                                <div class="form-check form-switch form-switch-success" style="display: flex; justify-content: center;">
                                    <input class="form-check-input switch-is-active changeStatus" type="checkbox"
                                        data-ticket-id="{{ $ticket->id }}"
                                        data-user-type="{{ auth()->user()->type_user }}"
                                        {{ $ticket->status === 'confirmed' ? 'checked disabled' : '' }}
                                        onclick="changeStatus(event)">
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="btn-group justify-content-center align-items-center">
                                    <a href="{{ route('admin.tickets.show', $ticket) }}" class="btn btn-sm btn-success me-2">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if ($ticket->status === 'confirmed')
                                        <button class="btn btn-sm btn-primary me-2 btn-print-ticket printTicket" data-id="{{ $ticket->id }}">
                                            <i class="bi bi-printer-fill"></i> Vé
                                        </button>
                                        <button class="btn btn-sm btn-warning btn-print-combo printCombo" data-id="{{ $ticket->id }}">
                                            <i class="bi bi-printer-fill"></i> Combo
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-primary me-2 btn-print-ticket d-none printTicket" data-id="{{ $ticket->id }}">
                                            <i class="bi bi-printer-fill"></i> Vé
                                        </button>
                                        <button class="btn btn-sm btn-warning btn-print-combo d-none printCombo" data-id="{{ $ticket->id }}">
                                            <i class="bi bi-printer-fill"></i> Combo
                                        </button>
                                    @endif
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

        <!-- Modal thông tin vé -->
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
    <script src="{{ asset('theme/admin/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/js/pages/datatables.init.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/js/app.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>

    <script>
        function changeStatus(event) {
            let ticketId = $(event.target).data('ticket-id');
            let status = event.target.checked ? 'confirmed' : 'pending';
            let staff = $(event.target).data('user-type') == 1 ? 'Admin' : 'Member';

            if (!confirm('Bạn có chắc muốn thay đổi trạng thái vé?')) {
                event.target.checked = !event.target.checked;
                return;
            }

            $.ajax({
                url: '/admin/tickets/change-status',
                method: 'POST',
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                data: {
                    ticket_id: ticketId,
                    status: status,
                    staff: staff,
                },
                success: function(response) {
                    alert('Trạng thái ticket đã được thay đổi!');
                    let row = $(event.target).closest('tr');
                    let statusTicket = row.find("#statusTicket");
                    let newStatusText = status === 'confirmed' ? 'Đã xác nhận' : 'Chờ xác nhận';
                    let newBadgeClass = status === 'confirmed' ? 'bg-success' : 'bg-warning';

                    statusTicket.text(newStatusText)
                        .removeClass('bg-success bg-warning')
                        .addClass(newBadgeClass);

                    if (status === 'confirmed') {
                        row.find('.btn-print-ticket, .btn-print-combo').removeClass('d-none');
                        $(event.target).prop('disabled', true);
                    } else {
                        row.find('.btn-print-ticket, .btn-print-combo').addClass('d-none');
                    }
                },
                error: function() {
                    alert('Đã có lỗi xảy ra!');
                }
            });
        }
    </script>

    <script src="{{ asset('assets/js/ticket/index.js') }}"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const branchSelect = document.getElementById('branch_id');
        const cinemaSelect = document.getElementById('cinema_id');
        const branchesRelation = @json($branchesRelation);

        @if(auth()->user()->hasRole('System Admin')) // Sửa thành 'System Admin'
            branchSelect.addEventListener('change', function() {
                const branchId = this.value;
                cinemaSelect.innerHTML = '<option value="" selected>Chọn rạp</option>';

                if (branchId && branchesRelation[branchId]) {
                    const cinemas = branchesRelation[branchId];
                    for (const [cinemaId, cinemaName] of Object.entries(cinemas)) {
                        const option = document.createElement('option');
                        option.value = cinemaId;
                        option.text = cinemaName;
                        cinemaSelect.appendChild(option);
                    }
                } else if (branchId) {
                    cinemaSelect.innerHTML = '<option value="" selected>Không có rạp nào</option>';
                }
            });

            if (branchSelect.value) {
                branchSelect.dispatchEvent(new Event('change'));
            }
        @endif
    });
</script>
@endsection
