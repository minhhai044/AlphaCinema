@extends('admin.layouts.master')

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
    <link href="{{ asset('assets/css/ticketPrint.css') }}" id="app-style" rel="stylesheet" type="text/css" />

    <style>
        .dashed-hr {
            border: 1px dashed #6c757d;
            /* Màu sắc của đường kẻ đứt */
            margin: 10px 0;
            /* Khoảng cách trên và dưới */
            opacity: 0.5;
            /* Độ mờ nếu muốn */
        }
    </style>
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
                                <option value="{{ $branch->id }}"
                                    {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
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
                                <option value="{{ $cinemaId }}"
                                    {{ request('cinema_id') == $cinemaId ? 'selected' : '' }}>
                                    {{ $cinemaName }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>


                <div class="col-lg-2">
                    <label for="date" class="form-label fw-bold">Ngày chiếu</label>
                    <input type="date" name="date" id="date" class="form-control" value="{{ request('date') }}"
                        required>
                </div>
                <div class="col-lg-3">
                    <label for="movie_id" class="form-label fw-bold">Phim</label>
                    <select name="movie_id" class="form-select" required id="movie_id">
                        <option value="" disabled selected>Chọn phim</option>
                        @if ($movies && $movies->isNotEmpty())
                            @foreach ($movies as $movie)
                                <option value="{{ $movie->id }}"
                                    {{ request('movie_id') == $movie->id ? 'selected' : '' }}>
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
                                    <button class="btn btn-sm btn-success printTicket" data-id="{{ $ticket->id }}">In
                                        vé</button>
                                    <button class="btn btn-sm btn-success printCombo" data-id="{{ $ticket->id }}">In
                                        combo</button>
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


        <div id="ticketModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel">Thông tin vé</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <!-- Container chứa tất cả các vé -->
                        <div id="ticketContainer">
                            <!-- Các vé sẽ được thêm vào đây bằng JavaScript -->
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="button" class="btn btn-primary" id="printAllTickets">In tất cả vé</button>
                    </div>
                </div>
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
        <script src="{{ asset('theme/admin/assets/js/pages/modal.init.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>


        <script>
            $(document).ready(function() {
                // Khi người dùng thay đổi chi nhánh
                $('#branch_id').change(function() {
                    var branchId = $(this).val(); // Lấy giá trị chi nhánh đã chọn

                    // Ẩn tất cả các rạp phim và phim
                    $('#cinema_id').empty().append('<option value="" disabled selected>Chọn rạp phim</option>');
                    $('#movie_id').empty().append('<option value="" disabled selected>Chọn phim</option>');

                    if (branchId) {
                        // Gửi yêu cầu AJAX để lấy danh sách rạp phim theo chi nhánh
                        $.ajax({
                            url: '{{ route('api.get-cinemas') }}', // Đảm bảo route này tồn tại
                            type: 'GET',
                            data: {
                                branch_id: branchId
                            },
                            success: function(response) {
                                // Thêm các rạp phim vào select
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
                    var cinemaId = $(this).val(); // Lấy giá trị rạp phim đã chọn

                    // Ẩn tất cả các phim
                    $('#movie_id').empty().append('<option value="" disabled selected>Chọn phim</option>');

                    if (cinemaId) {
                        // Gửi yêu cầu AJAX để lấy danh sách phim theo rạp
                        $.ajax({
                            url: '{{ route('api.get-movies') }}', // Đảm bảo route này tồn tại
                            type: 'GET',
                            data: {
                                cinema_id: cinemaId
                            },
                            success: function(response) {
                                // Thêm các phim vào select
                                $.each(response.movies, function(index, movie) {
                                    $('#movie_id').append('<option value="' + movie.id +
                                        '">' + movie.name + '</option>');
                                });
                            }
                        });
                    }
                });

                $(document).on("click", ".printTicket", async function() {
                    var ticketID = $(this).data("id");

                    try {
                        let response = await $.ajax({
                            url: `/api/v1/tickets/${ticketID}`,
                            type: "GET"
                        });



                        console.log("Dữ liệu nhận được:", response.data);

                        const data = response.data;

                        // Xóa nội dung cũ của modal trước khi thêm mới
                        $("#ticketContainer").empty();

                        // Lấy giá cho mỗi ghế
                        const seatCount = data.ticket_seats ? data.ticket_seats.length : 0;
                        const pricePerSeat = seatCount > 0 ? data.ticket.total_price / seatCount : data
                            .ticket.total_price;

                        // Tạo vé cho mỗi ghế
                        if (data.ticket_seats && data.ticket_seats.length > 0) {
                            data.ticket_seats.forEach((seat, index) => {
                                // Tạo một vé mới cho mỗi ghế
                                const ticketHtml = `
                                    <div class="ticket-item mb-3 ${index > 0 ? 'mt-4' : ''}">
                                        <h3 class="text-center fw-bold">Chi tiết vé xem phim</h3>
                                        <div class="mb-1">
                                            <div class="mb-1 fs-5 fw-bold">Chi nhánh công ty Alpha Cinema tại ${data.branch}</div>
                                            <div class="mb-1 fw-semibold">MST: 012147901412</div>
                                        </div>
                                        <hr class="dashed-hr">

                                        <div class="mb-1">
                                            <h5 class="fw-bold">Thông tin rạp</h5>
                                            <div class="mb-1"><strong>Rạp chiếu:</strong> ${data.cinema}</div>
                                            <div class="mb-1"><strong>Địa chỉ:</strong> ${data.address}</div>
                                            <div class="mb-1"><strong>Thời gian:</strong> (${data.start_time} - ${data.end_time}) -- ${data.showtime}</div>
                                        </div>
                                        <hr class="dashed-hr">

                                        <div class="mb-1">
                                            <h5 class="fw-bold">Thông tin phim</h5>
                                            <div class="mb-1"><strong>Phim:</strong> ${data.movie}</div>
                                            <div class="mb-1"><strong>Thể loại:</strong> ${data.category_movie} - ${data.type_movie}</div>
                                            <div class="mb-1"><strong>Thời lượng:</strong> ${data.duration} phút</div>
                                        </div>
                                        <hr class="dashed-hr">

                                        <div class="mb-1">
                                            <div class="mb-1"><strong>Phòng:</strong> ${data.room}</div>
                                            <div class="mb-1"><strong>Ghế:</strong> ${seat.seat_name}</div>
                                        </div>
                                        <hr class="dashed-hr">

                                        <div class="mb-1">
                                            <h5 class="fw-bold">Thanh toán</h5>
                                            <div class="mb-1"><strong>Phương thức thanh toán:</strong> ${data.ticket.payment_name}</div>
                                            <div class="mb-1"><strong>Giá vé:</strong> ${formatCurrency( seat.price)} VND</div>
                                        </div>
                                        ${index < data.ticket_seats.length - 1 ? '<hr class="border-2 border-dark my-4">' : ''}
                                    </div>
                                `;

                                // Thêm vé vào container
                                $("#ticketContainer").append(ticketHtml);
                            });
                        } else {
                            $("#ticketContainer").html("<p>Không có thông tin ghế nào được tìm thấy.</p>");
                        }

                        // Hiển thị modal
                        var myModal = new bootstrap.Modal($('#ticketModal'));
                        myModal.show();

                    } catch (error) {
                        console.error("Lỗi khi lấy dữ liệu vé:", error);
                    }
                });

                $(document).on("click", ".printCombo", async function() {
                    var ticketID = $(this).data("id");

                    try {
                        let response = await $.ajax({
                            url: `/api/v1/tickets/${ticketID}`,
                            type: "GET"
                        });

                        console.log("Dữ liệu nhận được:", response.data);

                        const data = response.data;

                        // Xóa nội dung cũ của modal trước khi thêm mới
                        $("#ticketContainer").empty();

                        // Tính toán tổng tiền cho combo và món ăn
                        let totalComboPrice = 0;
                        let totalFoodPrice = 0;
                        let discount = 0; // Giảm giá (nếu có)
                        let totalPrice = 0;

                        // Tính tổng giá của các combo
                        if (data.ticket_combos && data.ticket_combos.length > 0) {
                            data.ticket_combos.forEach(combo => {
                                totalComboPrice += combo.price * combo.quantity;
                                combo.foods.forEach(item => {
                                    totalFoodPrice += item.price * item.quantity;
                                });
                            });
                        }

                        // Tính tổng giá của các món ăn
                        if (data.ticket_foods && data.ticket_foods.length > 0) {
                            data.ticket_foods.forEach(food => {
                                totalFoodPrice += food.price * food.quantity;
                            });
                        }

                        // Giả sử giảm giá là 10% của tổng tiền
                        discount = (totalComboPrice + totalFoodPrice) * 0.1; // Ví dụ giảm giá 10%
                        totalPrice = totalComboPrice + totalFoodPrice - discount;

                        // Tạo vé cho mỗi ghế
                        const ticketHtml = `
                            <div class="ticket-item mb-3">
                                <h3 class="text-center fw-bold">Hóa Đơn Đồ Ăn</h3>
                                <div class="mb-1">
                                    <div class="mb-1 fs-5 fw-bold">Chi nhánh công ty Alpha Cinema tại ${data.branch}</div>
                                    <div class="mb-1 fw-semibold">MST: 012147901412</div>
                                </div>
                                <hr class="dashed-hr">

                                <div class="mb-1">
                                    <div class="mb-1 fw-semibold fs-5">Alpha Cinema ${data.cinema} - ${data.branch}</div>
                                    <div class="mb-1">Thời gian đặt vé: ${data.created_at}</div>
                                </div>
                                <hr class="dashed-hr">

                                ${data.ticket_combos && data.ticket_combos.length > 0 ? data.ticket_combos.map(combo => `
                                            <div class="mb-1 fw-semibold">${combo.name} x ${combo.quantity} (${formatCurrency(combo.price * combo.quantity)} VND)</div>
                                            <ul>
                                                ${combo.foods.map(item => `
                                            <li>${item.name} x ${item.quantity} (${formatCurrency(item.price * item.quantity)} VND)</li>
                                        `).join('')}
                                            </ul>
                                        `).join('') : ''}

                                ${data.ticket_foods && data.ticket_foods.length > 0 ? data.ticket_foods.map(food => `
                                            <div class="mb-1 fw-semibold">${food.name} x ${food.quantity} (${formatCurrency(food.price * food.quantity)} VND)</div>
                                        `).join('') : ''}

                                <hr class="dashed-hr">

                               <div class="mb-1">
                                    <div class="mb-1 d-flex justify-content-between">
                                        <div class="fw-semibold">Tổng cộng:</div>
                                        <div class="ms-2">${formatCurrency(totalComboPrice + totalFoodPrice)} VND</div>
                                    </div>
                                    <div class="mb-1 d-flex justify-content-between">
                                        <div class="fw-semibold">Giảm giá::</div>
                                        <div class="ms-2">${formatCurrency(discount)} VND</div>
                                    </div>

                                    <div class="mb-1 d-flex justify-content-between">
                                        <div class="fw-semibold">Thành tiền:</div>
                                        <div>${formatCurrency(totalPrice)} VND</div>
                                    </div>
                                </div>
                            </div>
                        `;

                        // Thêm vé vào container
                        $("#ticketContainer").append(ticketHtml);

                        // Hiển thị modal
                        var myModal = new bootstrap.Modal($('#ticketModal'));
                        myModal.show();

                    } catch (error) {
                        console.error("Lỗi khi lấy dữ liệu vé:", error);
                    }
                });

                // Hàm định dạng tiền tệ



                // Thêm sự kiện click cho nút in
                $("#printAllTickets").on("click", function() {
                    // Lưu nội dung hiện tại của body
                    const originalContent = $("body").html();

                    // Chỉ lấy phần nội dung vé để in
                    const printContent = $("#ticketContainer").html();
                    const {
                        jsPDF
                    } = window.jspdf;
                    const doc = new jsPDF();

                    // Thêm nội dung vào PDF
                    doc.html(printContent, {
                        callback: function(doc) {
                            // Tạo tên file tùy chỉnh, ví dụ: "Ticket_<timestamp>.pdf"
                            const fileName = `Ticket_${new Date().getTime()}.pdf`;

                            // Lưu tệp PDF với tên đã chỉ định
                            doc.save(fileName); // Lưu tệp PDF với tên file tự động
                        },
                        margin: [10, 10, 10, 10], // Điều chỉnh margin nếu cần
                        x: 10,
                        y: 10
                    });

                    // Thay đổi nội dung của modal để chỉ có nội dung cần in
                    $("body").html(`
                        <div class="print-container">
                            <style>
                                @media print {
                                    .ticket-item {
                                        page-break-after: auto; /* Chỉ ngắt trang khi cần thiết */
                                        margin-bottom: 0;
                                    }
                                    .ticket-item:not(:last-child) {
                                        page-break-after: always; /* Chỉ áp dụng ngắt trang cho các vé không phải cuối cùng */
                                    }
                                    .dashed-hr {
                                        border-top: 1px dashed #000;
                                    }
                                    .no-print {
                                        display: none;
                                    }
                                }
                                body {
                                    font-family: Arial, sans-serif;
                                }
                                .ticket-item {
                                    padding: 15px;
                                    margin-bottom: 20px;
                                }
                            </style>
                            ${printContent}
                        </div>
                    `);

                    // Gọi lệnh in của trình duyệt
                    window.print();
                    window.location.reload();
                });

                // function formatCurrency(amount) {
                //     return amount.toLocaleString('vi-VN', {
                //         style: 'currency',
                //         currency: 'VND'
                //     });
                // }

                // Định dạng số thành dạng có dấu phẩy ngăn cách hàng nghìn và hai số thập phân
                const formatCurrency = (number) => {
                    return new Intl.NumberFormat('vi-VN', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }).format(number);
                };

            });
        </script>
    @endsection
