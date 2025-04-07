@extends('admin.layouts.master')

@section('title')
    In vé
@endsection

@section('style-libs')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        .ticket-container1 {
            width: 300px;
            background-color: #ffe5e5;
            padding: 15px;
            border-radius: 5px;
        }

        .ticket-container2 {
            width: 300px;
            background-color: #ffe5e5;
            padding: 15px;
            border-radius: 5px;
        }

        .ticket-container3 {
            width: 300px;
            background-color: #ffe5e5;
            padding: 15px;
            border-radius: 5px;
        }

        .ticket-header {
            text-align: center;
            margin-bottom: 10px;
        }

        .ticket-header h2 {
            font-size: 18px;
            font-weight: bold;
            color: #d63384;
        }

        .ticket-info {
            font-size: 14px;
        }

        .ticket-info p {
            margin-bottom: 5px;
        }

        .ticket-info strong {
            font-weight: bold;
        }

        .ticket-info .highlight {
            font-weight: bold;
            color: #d63384;
        }

        @media print {
            body * {
                visibility: hidden;
            }

            .ticket-container1, .ticket-container1 * {
                visibility: visible;
            }

            .ticket-container2, .ticket-container2 * {
                visibility: visible;
            }

            .ticket-container3, .ticket-container3 * {
                visibility: visible;
            }

            .ticket-container1 {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%; /* Chiếm toàn bộ chiều rộng trang in */
                margin: 0; /* Loại bỏ lề */
                border: none; /* Loại bỏ viền */
                box-shadow: none; /* Loại bỏ bóng */
            }

            .ticket-container2 {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%; /* Chiếm toàn bộ chiều rộng trang in */
                margin: 0; /* Loại bỏ lề */
                border: none; /* Loại bỏ viền */
                box-shadow: none; /* Loại bỏ bóng */
            }

            .ticket-container3 {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%; /* Chiếm toàn bộ chiều rộng trang in */
                margin: 0; /* Loại bỏ lề */
                border: none; /* Loại bỏ viền */
                box-shadow: none; /* Loại bỏ bóng */
            }

            @page {
                size: auto; /*  Tự động điều chỉnh khổ giấy */
                margin: 10mm; /* Lề 10mm cho tất cả các cạnh */
            }

            .no-print {
                display: none;
            }
        }
    </style>
@endsection
@section('content')
    <div class="d-flex justify-content-around">
        <div class="ticket-container1">
            <div>
                <div class="flex-shrink-0 no-print">
                    <button onclick="window.print()" class="btn btn-success btn-sm">
                        <i class="ri-download-2-fill align-middle me-1"></i> In hóa đơn
                    </button>
                </div>

                {{-- Header Information --}}
                <div class="ticket-header">
                    <h2>Hóa đơn chi tiết</h2>
                </div>

                {{-- Company Information --}}
                <div class="ticket-info border-bottom-dashed">
                    <p><strong>Chi nhánh công ty Poly Cinemas vietnam tại {{ $ticket->cinema->branch->name }}</strong></p>
                    <p>Địa chỉ: 1 Quang Trung - {{ $ticket->cinema->name }} - {{ $ticket->cinema->branch->name }}</p>
                    <p>mst: 012147901412</p>
                </div>

                {{-- Cinema Information --}}
                <div class="ticket-info border-bottom-dashed mt-2">
                    <p><strong>Poly Cinemas {{ $ticket->cinema->name }} - {{ $ticket->cinema->branch->name }}</strong></p>
                    <p>Thời gian: {{ $ticket->ticketSeats->first()->showtime->start_time }}</p>
                </div>

                {{-- Movie Information --}}
                <div class="ticket-info border-bottom-dashed mt-2">
                    <p><strong>{{ $ticket->movie->name }} ({{ $ticket->movie->movieVersions->first()->name }})</strong></p>
                    <p>
                        <strong>
                            {{ $ticket->movie->rating }}
                            @if($ratingDescription)
                                <span>({{ $ratingDescription }})</span>
                            @endif
                        </strong>
                    </p>
                    <p><strong>Phòng:</strong> {{ $ticket->room->name }}</p>
                    <p><strong>Ghế:</strong> {{ $ticket->ticketSeats->pluck('seat.name')->implode(', ') }}</p>
                </div>

                {{-- Combo Information --}}
                @if($ticket->ticketCombos->isNotEmpty())
                    <div class="ticket-info border-bottom-dashed mt-2">
                        @foreach($ticket->ticketCombos as $ticketCombo)
                            <p>
                                <strong>
                                    {{ $ticketCombo->combo->name }} x {{ $ticketCombo->quantity }}
                                    ({{ number_format($ticketCombo->combo->price * $ticketCombo->quantity) }} vnđ)
                                </strong>
                            </p>
                            <ul>
                                @foreach($ticketCombo->combo->food as $food)
                                    <li>{{ $food->name }} x {{ $food->pivot->quantity }}</li>
                                @endforeach
                            </ul>
                        @endforeach
                    </div>
                @endif

                {{-- Price Summary --}}
                <div class="ticket-info mt-2">
                    <div class="d-flex justify-content-between">
                        <span><strong>Giá vé:</strong></span>
                        <span><strong>{{ number_format($totalPriceSeat, 0, ',', '.') }} VND</strong></span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span><strong>Giá combo:</strong></span>
                        <span><strong>{{ number_format($totalComboPrice, 0, ',', '.') }} VND</strong></span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span><strong>Giảm giá:</strong></span>
                        <span><strong>{{ number_format($ticket->voucher_discount, 0, ',', '.') }} VND</strong></span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span><strong>Thành tiền:</strong></span>
                        <span><strong>{{ number_format($ticket->total_price, 0, ',', '.') }} VND</strong></span>
                    </div>
                </div>

                {{-- Barcode --}}
                <div class="mt-4 border-top-double">
                    <div class="d-flex justify-content-center mt-2">{!! $barcode !!}</div>
                    <div class="d-flex justify-content-center">
                        <p><strong>{{ $ticket->code }}</strong></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="ticket-container2">
            <div>
                <div class="flex-shrink-0 no-print">
                    @if($ticket->status == 'Đã xuất vé')
                        <a href="#" class="btn btn-success btn-sm"
                           onclick="window.print()"><i
                                    class="ri-download-2-fill align-middle me-1"></i> In hóa đơn</a>
                    @endif
                </div>
                <div class="ticket-header">
                    <h2>HÓA ĐƠN ĐỒ ĂN</h2>
                </div>
                <div class="ticket-info border-bottom-dashed">
                    <p><strong>Chi nhánh công ty Poly Cinemas vietnam tại {{ $ticket->cinema->branch->name }}</strong></p>
                    <p>Địa chỉ: 1 Quang Trung - {{ $ticket->cinema->name }} - {{ $ticket->cinema->branch->name }}</p>
                    <p>mst: 012147901412</p>
                </div>
                <div class="ticket-info border-bottom-dashed mt-2">
                    <p><strong>Poly Cinemas {{ $ticket->cinema->name }} - {{ $ticket->cinema->branch->name }}  </strong></p>
                    <p>Thời gian: {{ $ticket->ticketCombos->first()->created_at }}</p>
                </div>
                <div class="ticket-info border-bottom-dashed mt-2">
                    @foreach ($ticket->ticketCombos as $ticketCombo)
                        @php
                            $combo = $ticketCombo->combo;
                        @endphp

                        <p><b>{{ $combo->name }} x {{ $ticketCombo->quantity }}
                                ( {{ number_format($combo->price * $ticketCombo->quantity) }}
                                vnđ )</b></p>

                        <ul>
                            @foreach ($combo->food as $food)
                                <li>{{ $food->name }} x {{ $food->pivot->quantity }}</li>
                            @endforeach
                        </ul>
                    @endforeach
                </div>
                <div class="ticket-info mt-2">
                    <p><strong>Tổng cộng:</strong> {{ number_format($ticket->ticketCombos->sum(function ($ticketCombo) {
                return $ticketCombo->combo->price * $ticketCombo->quantity;
            }), 0, ',', '.') }} vnđ</p>

                </div>
                <div class="mt-4 border-top-double">
                    <div class="d-flex justify-content-center mt-2">{!! $barcode !!}</div>
                    <div class="d-flex justify-content-center">
                        <p><strong>{{ $ticket->code }}</strong></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="ticket-container3">
            <div>
                <div class="flex-shrink-0 no-print">
                    <a href="#" class="btn btn-success btn-sm"
                       onclick="window.print()"><i
                                class="ri-download-2-fill align-middle me-1"></i> In hóa đơn</a>
                </div>
                <div class="ticket-header">
                    <h2>Hóa đơn vé</h2>
                </div>
                <div class="ticket-info border-bottom-dashed">
                    <p><strong>Chi nhánh công ty Poly Cinemas vietnam tại {{ $ticket->cinema->branch->name }}</strong></p>
                    <p>Địa chỉ: 1 Quang Trung - {{ $ticket->cinema->name }} - {{ $ticket->cinema->branch->name }}</p>
                    <p>mst: 012147901412</p>
                </div>
                <div class="ticket-info border-bottom-dashed mt-2">
                    <p><strong>Poly Cinemas {{ $ticket->cinema->name }} - {{ $ticket->cinema->branch->name }}  </strong></p>
                    <p>Thời gian: {{ $ticket->ticketSeats->first()->showtime->start_time }}</p>
                </div>
                <div class="ticket-info border-bottom-dashed mt-2">
                    <p><strong>{{ $ticket->movie->name }} ({{ $ticket->movie->movieVersions->first()->name }})</strong></p>
                    @php
                        $rating = $ticket->movie->rating;
                        $description = null;

                        if ($rating == 'P') {
                            $description = 'Mọi độ tuổi';
                        } elseif ($rating == 'T13') {
                            $description = 'Dưới 13 tuổi và có người bảo hộ đi kèm';
                        } elseif ($rating == 'T16') {
                            $description = '13+';
                        } elseif ($rating == 'T18') {
                            $description = '16+';
                        } elseif ($rating == 'K') {
                            $description = '18+';
                        }
                    @endphp
                    <p><strong>{{ $ticket->movie->rating }} @if ($description)
                                <span>({{ $description }})</span>
                            @endif</strong></p>


                    <p><strong>Phòng:</strong> {{ $ticket->room->name }}</p>
                    <p><strong>Ghế:</strong>
                        {{ implode(', ', $ticket->ticketSeats->pluck('seat.name')->toArray()) }}
                    </p>
                </div>
                <div class="ticket-info border-bottom-dashed mt-2">
                    @foreach ($ticket->ticketCombos as $ticketCombo)
                        @php
                            $combo = $ticketCombo->combo;
                        @endphp

                        <p><b>{{ $combo->name }} x {{ $ticketCombo->quantity }}
                                ( {{ number_format($combo->price * $ticketCombo->quantity) }}
                                vnđ )</b></p>

                        <ul>
                            @foreach ($combo->food as $food)
                                <li>{{ $food->name }} x {{ $food->pivot->quantity }}</li>
                            @endforeach
                        </ul>
                    @endforeach
                </div>

                <div class="ticket-info mt-2">
                    <div class="d-flex justify-content-between">
                    <span>
                        <strong>Giá vé:</strong>
                    </span>
                        <span><strong>{{ number_format($ticket->total_price, 0, ',', '.') }} VND</strong></span>
                    </div>
                    <div class="d-flex justify-content-between">
                    <span>
                        <strong>Giá combo:</strong>
                    </span>
                        <span><strong>{{ number_format($ticket->ticketCombos->sum(function ($ticketCombo) {
                return $ticketCombo->combo->price * $ticketCombo->quantity;
            }), 0, ',', '.') }} VND</strong></span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span><strong>Giảm giá:</strong> </span>
                        <span><strong>{{ number_format($ticket->voucher_discount, 0, ',', '.') }} VND</strong></span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span><strong>Thành tiền:</strong> </span>
                        <span><strong>{{ number_format($ticket->price, 0, ',', '.') }} VND</strong></span>
                    </div>
                </div>

                <div class="mt-4 border-top-double">
                    <div class="d-flex justify-content-center mt-2">{!! $barcode !!}</div>
                    <div class="d-flex justify-content-center">
                        <p><strong>{{ $ticket->code }}</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Xử lý in lần lượt từng vé
        document.addEventListener('DOMContentLoaded', function() {
            let currentTicketIndex = 1;
            const totalTickets = 3;

            // Ẩn tất cả các nút in ban đầu
            document.querySelectorAll('.no-print').forEach(btn => {
                btn.style.display = 'none';
            });

            // Hàm in vé
            function printTicket() {
                // Ẩn tất cả các container
                document.querySelectorAll('.ticket-container1, .ticket-container2, .ticket-container3').forEach(container => {
                    container.style.display = 'none';
                });

                // Hiển thị container hiện tại
                const currentContainer = document.querySelector(`.ticket-container${currentTicketIndex}`);
                if (currentContainer) {
                    currentContainer.style.display = 'block';

                    // Đợi DOM cập nhật
                    setTimeout(() => {
                        window.print();
                    }, 500);
                }
            }

            // Xử lý sau khi in
            window.onafterprint = function() {
                if (currentTicketIndex < totalTickets) {
                    currentTicketIndex++;
                    // Đợi 1 giây trước khi hiển thị hộp thoại in tiếp theo
                    setTimeout(printTicket, 1000);
                } else {
                    /*// Hoàn thành in tất cả vé, hiển thị lại các nút in
                    document.querySelectorAll('.no-print').forEach(btn => {
                        btn.style.display = 'block';
                    });
                    // Hiển thị lại tất cả container
                    document.querySelectorAll('.ticket-container1, .ticket-container2, .ticket-container3').forEach(container => {
                        container.style.display = 'block';
                    });*/
                    window.location.href = "{{ route('admin.tickets.show', ['ticket' => $ticket->id]) }}";

                }
            };

            // Bắt đầu quá trình in khi trang tải xong
            window.onload = function() {
                // Đợi một chút để đảm bảo trang đã tải hoàn toàn
                setTimeout(printTicket, 1000);
            };

            // Xử lý nút in thủ công (nếu cần)
            document.querySelectorAll('.btn-success').forEach(btn => {
                btn.onclick = function(e) {
                    e.preventDefault();
                    currentTicketIndex = 1;
                    printTicket();
                };
            });
        });
    </script>

@endsection

