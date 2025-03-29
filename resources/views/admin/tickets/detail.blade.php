@extends('admin.layouts.master')

@section('style')
    <!-- Bootstrap CSS -->
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"> --}}
    <style>
        /* Tổng thể */

        /* Responsive */
        .text-16 {
            font-size: 16px;
            margin-bottom: 12px
        }

        @media (max-width: 768px) {

            .movie-section,
            .info-container {
                margin-bottom: 20px;
            }

            .text-16 {
                font-size: 14px
            }

            .movie-thumbnail {
                height: 200px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Ticket</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.tickets.index') }}">Danh sách vé</a></li>
                        <li class="breadcrumb-item active">Chi tiết</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid row G-5">

        <!-- Movie Section (75%) -->
        <div class="col-md-9">

            {{-- <a href="{{ route('admin.tickets.index') }}" class="btn btn-primary mb-4">
                <i class="bi bi-arrow-left me-2"></i> Quay lại
            </a> --}}

            <div class="card shadow-sm ">
                <div class="card-header bg-light-subtle">
                    <div class="row">
                        <div class="col-6  fs-5 fw-semibold">Thông tin vé</div>
                        <div class="col-4  text-end">
                            <span
                                class="badge p-2 rounded-pill   {{ $ticketData['status'] === 'Đã xác nhận' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }}">{{ $ticketData['status'] }}</span>
                        </div>
                        <div class="col-2 text-end">
                            <button class="btn btn-primary btn-sm "><i class="bx bx-download"></i> In vé</button>
                        </div>
                    </div>

                </div>
                <div class="card-body row g-3">
                    <div class="col-4">
                        <img src="{{ $ticketData['movie']['thumbnail'] ?? 'https://via.placeholder.com/150' }}"
                            alt="Movie Thumbnail" class="movie-thumbnail w-100 h-75">
                    </div>
                    <div class="col-8 ">
                        <div class="row">
                            <div class="text-primary mb-4 fs-3 fw-bold">
                                {{ $ticketData['movie']['name'] ?? '' }}</div>


                            <div class="col-3 text-16">Lịch chiếu</div>
                            <div class="col-9 text-start text-16 fw-medium">
                                {{ $ticketData['showtime']['start_time'] ?? '04:48' }} -
                                {{ $ticketData['showtime']['end_time'] ?? '07:07' }}
                                ({{ $ticketData['showtime']['date'] ?? '14/02/2025' }})
                            </div>
                            <div class="col-3 text-16">Thời lượng</div>
                            <div class="col-9 text-start text-16 fw-medium">
                                {{ $ticketData['movie']['duration'] ?? '160 divhút' }}</div>

                            <div class="col-3 text-16">Định dạng</div>
                            <div class="col-9 text-start text-16 fw-medium">
                                {{ $ticketData['movie']['format'] ?? '2D Lồng Tiếng' }}</div>

                            <div class="col-3 text-16">Phòng chiếu</div>
                            <div class="col-9 text-start text-16 fw-medium">
                                {{ $ticketData['cinema']['room'] ?? '' }}</div>

                            <div class="col-3 text-16">Thể loại </div>
                            <div class="col-9 text-start text-16 fw-medium">{{ $ticketData['movie']['genre'] ?? 'Tâm lý' }}
                            </div>

                            <div class="col-3 text-16">Ghế ngồi</div>
                            @if (is_array($ticketData['seats']) && !empty($ticketData['seats']['details']))
                                <div class="col-9 text-start text-16 fw-medium">
                                    <div class="row">
                                        @foreach ($ticketData['seats']['details'] as $seat)
                                            <span
                                                class="col-2 border border-secondary-subtle rouder-3 mx-1 mb-2 text-center">{{ $seat['name'] }}</span>
                                        @endforeach

                                    </div>
                                </div>
                            @else
                                <p>{{ $ticketData['seats']['names'] }}</p>
                            @endif
                        </div>
                    </div>

                    @if (!empty($ticketData['combos']) || !empty($ticketData['foods']))
                        <div class="combo-section mt-2">
                            <div class="fs-5 fw-semibold"> Đồ ăn</div>
                            <div class="row">
                                @forelse ($ticketData['combos'] as $combo)
                                    <div class="col-md-6 mb-3">
                                        <div class="card h-100">
                                            <div class="card-body row">
                                                <div class="col-4">
                                                    <img class="w-100 h-75" src="{{ $combo['image'] }}"
                                                        alt="{{ $combo['name'] }}">
                                                </div>
                                                <div class="col-8">
                                                    <h6 class="card-title">{{ $combo['name'] }}</h6>
                                                    <ul class="list-unstyled mb-2">
                                                        @foreach ($combo['foods'] as $food)
                                                            <li>{{ $food }}</li>
                                                        @endforeach
                                                    </ul>
                                                    <p class="mb-0">{{ $combo['quantity'] }} x {{ $combo['price'] }} =
                                                        <span
                                                            class="price">{{ number_format((float) $combo['total_price'], 0, ',', '.') }}</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-center text-muted py-3"></div>
                                @endforelse
                            </div>
                            <div class="row">
                                @forelse ($ticketData['foods'] as $food)
                                    <div class="col-md-6 mb-3">
                                        <div class="card h-100">
                                            <div class="card-body row">
                                                <div class="col-4">
                                                    <img class="w-100 h-75" src="{{ $food['image'] }}"
                                                        alt="{{ $food['name'] }}">
                                                </div>
                                                <div class="col-8">
                                                    <h6 class="card-title">{{ $food['name'] }}</h6>
                                                    {{-- <ul class="list-unstyled mb-2">
                                                        @foreach ($food['foods'] as $food)
                                                            <li>{{ $food }}</li>
                                                        @endforeach
                                                    </ul> --}}
                                                    <p class="mb-0">{{ $food['quantity'] }} x {{ $food['price'] }} =
                                                        <span
                                                            class="price">{{ number_format((float) $food['total_price'], 0, ',', '.') }}</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-center text-muted py-3"></div>
                                @endforelse
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>


        <!-- Info Container (25%) -->
        <div class="col-md-3 d-flex flex-column gap-1">
            <!-- Ticket Info Section -->
            <div class="card shadow-sm">
                <div class="card-header bg-light-subtle ">
                    <div class="fw-semibold fs-5"> Thông tin người đặt</div>
                </div>
                <div class="card-body">
                    <div class="mb-1">
                        <span class="text-body-secondary"> Tên: </span>
                        <span class="fw-medium">{{ $ticketData['user']['name'] ?? 'PhungHuy' }}</span>
                    </div>
                    <div class="mb-1">
                        <span class="text-body-secondary"> Email: </span>
                        <span class="fw-medium">{{ $ticketData['user']['email'] ?? 'huyphung@gmail.com' }}</span>
                    </div>
                    <div class="mb-1">
                        <span class="text-body-secondary"> SĐT: </span>
                        <span class="fw-medium">{{ $ticketData['user']['phone'] ?? '0987654326' }}</span>
                    </div>
                </div>
            </div>

            <!-- Payment Section -->
            <div class="card shadow-sm">
                <div class="card-header bg-light-subtle">
                    <div class="fw-semibold fs-5"> Thông tin thanh toán</h6>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-1">
                        <span class="text-body-secondary"> Thời gian: </span>
                        <span class="fw-medium">{{ $ticketData['payment_time'] ?? '21:31 - 05/03/2025' }}</span>
                    </div>
                    <div class="mb-1">
                        <span class="text-body-secondary"> Phương thức: </span>
                        <span class="fw-medium">{{ $ticketData['payment_name'] ?? 'ZaloPay' }}</span>
                    </div>
                    <div class="mb-1">
                        <span class="text-body-secondary"> Tiền Vé: </span>
                        <span class="fw-medium">{{ $ticketData['ticket_price'] ?? '0 VND' }}</span>
                    </div>
                    @if (!empty($ticketData['total_combo']) || $ticketData['total_food'])
                        <div class="mb-1">
                            <span class="text-body-secondary"> Tiền đồ ăn: </span>
                            <span
                                class="fw-medium">{{ number_format($ticketData['total_combo'] + $ticketData['total_food'], 0, '.', '.') . ' VND' ?? '0 VND' }}</span>
                        </div>
                    @endif

                    <div class="mb-1">
                        <span class="text-body-secondary"> Tổng tiền: </span>
                        <span class="fw-medium">{{ $ticketData['total_amount'] ?? '0 VND' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap JS -->
        {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script> --}}
    @endsection
