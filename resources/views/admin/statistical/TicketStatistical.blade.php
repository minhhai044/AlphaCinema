@extends('admin.layouts.master')

@section('title', 'Thống kê Vé')

@section('content')
    <div class="container-fluid mt-4">
        <h3 class="mb-4">Thống kê Vé</h3>

        <!-- Hiển thị vai trò người dùng -->
        <div class="alert alert-info mb-4">
            Bạn đang xem dữ liệu với vai trò:
            @if (auth()->user()->hasRole('System Admin'))
                System Admin (Tất cả chi nhánh và rạp)
            @elseif (auth()->user()->branch_id)
                Quản lý chi nhánh {{ auth()->user()->branch->name ?? 'N/A' }}
            @elseif (auth()->user()->cinema_id)
                Quản lý rạp {{ $cinemas->firstWhere('id', auth()->user()->cinema_id)->name ?? 'N/A' }}
            @endif
        </div>

        <!-- Form lọc -->
        <form method="GET" action="{{ route('admin.ticket.revenuenew') }}" class="d-flex align-items-center gap-2"
            id="filterForm">
            <!-- Bộ lọc chi nhánh -->
            @if (auth()->user()->hasRole('System Admin'))
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light text-muted border-0">
                        <i class="bi bi-geo-alt-fill"></i>
                    </span>
                    <select name="branch_id" class="form-select border-0 shadow-sm">
                        <option value="" {{ !$branchId ? 'selected' : '' }}>Tất cả chi nhánh</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}" {{ $branchId == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @elseif (auth()->user()->branch_id)
                <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id }}">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light text-muted border-0">
                        <i class="bi bi-geo-alt-fill"></i>
                    </span>
                    <span class="form-control border-0 shadow-sm">{{ auth()->user()->branch->name ?? 'N/A' }}</span>
                </div>
            @elseif (auth()->user()->cinema_id)
                @php $cinema = $cinemas->firstWhere('id', auth()->user()->cinema_id); @endphp
                <input type="hidden" name="branch_id" value="{{ $cinema->branch_id ?? '' }}">
            @endif

            <!-- Bộ lọc rạp -->
            @if (auth()->user()->hasRole('System Admin') || auth()->user()->branch_id)
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light text-muted border-0">
                        <i class="bi bi-camera-reels"></i>
                    </span>
                    <select name="cinema_id" class="form-select border-0 shadow-sm">
                        <option value="" {{ !$cinemaId ? 'selected' : '' }}>Tất cả rạp</option>
                        @foreach ($branchesRelation[$branchId] ?? [] as $id => $name)
                            <option value="{{ $id }}" {{ $cinemaId == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @elseif (auth()->user()->cinema_id)
                @php $cinema = $cinemas->firstWhere('id', auth()->user()->cinema_id); @endphp
                <input type="hidden" name="cinema_id" value="{{ $cinema->id ?? '' }}">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light text-muted border-0">
                        <i class="bi bi-camera-reels"></i>
                    </span>
                    <span class="form-control border-0 shadow-sm">{{ $cinema->name ?? 'Không xác định' }}</span>
                </div>
            @endif



            <!-- Bộ lọc ngày -->
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light text-muted border-0">
                    <i class="bi bi-calendar"></i>
                </span>
                <input type="date" name="date" class="form-control border-0 shadow-sm" value="{{ $date }}">
            </div>

            <!-- Bộ lọc tháng -->
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light text-muted border-0">
                    <i class="bi bi-calendar-month"></i>
                </span>
                <select name="month" class="form-select border-0 shadow-sm">
                    <option value="" {{ !$selectedMonth ? 'selected' : '' }}>Chưa chọn</option>
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ $selectedMonth == $i ? 'selected' : '' }}>Tháng
                            {{ $i }}</option>
                    @endfor
                </select>
            </div>

            <!-- Bộ lọc năm -->
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light text-muted border-0">
                    <i class="bi bi-calendar4"></i>
                </span>
                <select name="year" class="form-select border-0 shadow-sm">
                    @for ($i = 2020; $i <= Carbon\Carbon::now()->year; $i++)
                        <option value="{{ $i }}" {{ $selectedYear == $i ? 'selected' : '' }}>Năm
                            {{ $i }}</option>
                    @endfor
                </select>
            </div>

            <!-- Nút lọc -->
            <button type="submit"
                class="btn btn-sm btn-success rounded-circle p-2 d-flex align-items-center justify-content-center"
                title="Lọc dữ liệu" style="width: 36px; height: 36px;">
                <i class="bi bi-funnel fs-5"></i>
            </button>

            <!-- Nút reset -->
            <button type="button"
                class="btn btn-sm btn-primary rounded-circle p-2 d-flex align-items-center justify-content-center"
                onclick="resetFilters()" title="Reset bộ lọc" style="width: 36px; height: 36px;">
                <i class="bi bi-arrow-counterclockwise fs-5"></i>
            </button>
        </form>

        <script>
            function resetFilters() {
                window.location.href = "{{ route('admin.ticket.revenuenew') }}";
            }
        </script>

        <!-- Charts Section -->
        <div class="row g-4 mb-4">
            <!-- Xu Hướng Bán Vé -->
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted text-uppercase mb-3">Xu Hướng Bán Vé</h6>
                        <p class="text-muted small mb-3">Số lượng vé bán ra
                            {{ $date ? "ngày $date" : "tháng $selectedMonth năm $selectedYear" }}</p>
                        <div id="ticketTrendChart" style="width: 100%; height: 300px;"></div>
                    </div>
                </div>
            </div>

            <!-- Top Phim Bán Chạy -->
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted text-uppercase mb-3">Top Phim Bán Chạy</h6>
                        <p class="text-muted small mb-3">Số lượng vé bán ra theo phim</p>
                        <div id="topMoviesChart" style="width: 100%; height: 300px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Charts Section -->
        <div class="row g-4 mb-4">
            <!-- Phân Loại Vé -->
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted text-uppercase mb-3">Phân Loại Vé</h6>
                        <p class="text-muted small mb-3">Phân bổ theo loại phòng</p>
                        <div id="ticketTypeChart" style="width: 100%; height: 300px;"></div>
                    </div>
                </div>
            </div>

            <!-- Giờ Cao Điểm -->
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted text-uppercase mb-3">Giờ Cao Điểm</h6>
                        <p class="text-muted small mb-3">Số lượng vé bán ra theo giờ</p>
                        <div id="peakHoursChart" style="width: 100%; height: 300px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tỷ Lệ Lấp Đầy Rạp -->
        {{-- <div class="row g-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted text-uppercase mb-3">Tỷ Lệ Lấp Đầy Rạp</h6>
                        <p class="text-muted small mb-3">Tỷ lệ ghế đã đặt trong số ghế trống</p>
                        <div id="fillRateChart" style="width: 100%; height: 300px;"></div>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>

    <!-- Highcharts CDN -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>

    <!-- Chart Initialization -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Highcharts.setOptions({
                colors: ['#483D8B', '#4682B4', '#20B2AA', '#98FB98', '#FFDAB9']
            });
            // Xu Hướng Đặt Vé (Line Chart)
            Highcharts.chart('ticketTrendChart', {
                chart: {
                    type: 'line'
                },
                credits: {
                    enabled: false
                },
                title: {
                    text: null
                },
                xAxis: {
                    categories: @json($ticketTrendData['categories']) // Lấy categories từ dữ liệu thực tế
                },
                yAxis: {
                    title: {
                        text: null
                    },
                    max: null // Xóa max cố định để phù hợp với dữ liệu thực tế
                },
                series: [{
                    name: 'Số vé',
                    data: @json($ticketTrendData['values']), // Lấy values từ dữ liệu thực tế
                    color: '#191970' // Giữ nguyên màu chủ đạo
                }],
                plotOptions: {
                    line: {
                        marker: {
                            enabled: true
                        }
                    }
                }
            });

            // Top Phim Bán Chạy
            Highcharts.chart('topMoviesChart', {
                chart: {
                    type: 'pie'
                },
                credits: {
                    enabled: false
                },
                title: {
                    text: null
                },
                series: [{
                    name: 'Số vé',
                    colorByPoint: true, // Cho phép mỗi điểm dữ liệu có màu riêng
                    data: @json($topMoviesData), // Lấy dữ liệu thực tế từ $topMoviesData
                    colors: [
                        '#191970', // Màu cố định đầu tiên
                        ...Array(4).fill().map(() => '#' + Math.floor(Math.random() * 16777215)
                            .toString(16)) // Tạo 4 màu random
                    ] // Tùy chỉnh bảng màu, không dùng Highcharts.setOptions
                }],
                plotOptions: {
                    pie: {
                        dataLabels: {
                            enabled: true,
                            format: '{point.name}: {point.percentage:.1f}%'
                        }
                    }
                }
            });

            // Phân Loại Vé
            Highcharts.chart('ticketTypeChart', {
                chart: {
                    type: 'pie'
                },
                credits: {
                    enabled: false
                },
                title: {
                    text: null
                },
                plotOptions: {
                    pie: {
                        innerSize: '50%', // Giữ hiệu ứng Donut
                        dataLabels: {
                            enabled: true,
                            format: '{point.name}: {point.percentage:.1f}%' // Hiển thị tên và phần trăm
                        }
                    }
                },
                series: [{
                    name: 'Số vé',
                    colorByPoint: true, // Cho phép mỗi điểm dữ liệu có màu riêng
                    data: @json($ticketTypeData), // Đổ dữ liệu thực tế từ $ticketTypeData
                    colors: [
                        '#191970', // Màu cố định đầu tiên
                        ...Array(2).fill().map(() => '#' + Math.floor(Math.random() * 16777215)
                            .toString(16)) // Tạo 2 màu random
                    ]
                }]
            });

            //  Giờ Cao Điểm
            Highcharts.chart('peakHoursChart', {
                chart: {
                    type: 'column'
                },
                credits: {
                    enabled: false
                },
                title: {
                    text: null
                },
                xAxis: {
                    categories: @json($peakHoursData['categories'])
                },
                yAxis: {
                    title: {
                        text: null
                    }
                },
                series: [{
                    name: 'Số vé',
                    data: @json($peakHoursData['values'])
                }],
                plotOptions: {
                    column: {
                        colorByPoint: true,
                        dataLabels: {
                            enabled: false
                        }
                    }
                }
            });

            console.log(@json($fillRateData));

            //  Tỷ Lệ Lấp Đầy Rạp
            // Highcharts.chart('fillRateChart', {
            //     chart: {
            //         type: 'column'
            //     },
            //     credits: {
            //         enabled: false
            //     },
            //     title: {
            //         text: null
            //     },
            //     xAxis: {
            //         categories: @json($fillRateData['categories'])
            //     },
            //     yAxis: {
            //         title: {
            //             text: null
            //         },
            //         max: 100
            //     },
            //     series: [{
            //             name: 'GH đã đặt',
            //             data: @json($fillRateData['seats_sold']),
            //             color: '#483D8B'
            //         },
            //         {
            //             name: 'GH trống',
            //             data: @json($fillRateData['seats_empty']),
            //             color: 'rgb(70, 130, 180)'
            //         }
            //     ],

            //     plotOptions: {
            //         column: {
            //             stacking: 'percent',
            //             dataLabels: {
            //                 enabled: true,
            //                 format: '{point.percentage:.0f}%'
            //             }
            //         }
            //     }
            // });

            function resetFilters() {
                window.location.href = "{{ route('admin.ticket.revenue') }}";
            }
        });


        document.addEventListener('DOMContentLoaded', function() {
            const branchSelect = document.querySelector('select[name="branch_id"]');
            const cinemaSelect = document.querySelector('select[name="cinema_id"]');
            const branchesRelation = @json($branchesRelation);

            @if (auth()->user()->hasRole('System Admin'))
                if (branchSelect && cinemaSelect) {
                    branchSelect.addEventListener('change', function() {
                        const branchId = this.value;
                        cinemaSelect.innerHTML = '<option value="" ' + (!branchId ? 'selected' : '') +
                            '>Tất cả rạp</option>';
                        if (branchId && branchesRelation[branchId]) {
                            const cinemas = branchesRelation[branchId];
                            for (const [cinemaId, cinemaName] of Object.entries(cinemas)) {
                                const isSelected = cinemaId == "{{ $cinemaId }}" ? 'selected' : '';
                                cinemaSelect.innerHTML +=
                                    `<option value="${cinemaId}" ${isSelected}>${cinemaName}</option>`;
                            }
                        }
                    });
                    if (branchSelect.value) {
                        branchSelect.dispatchEvent(new Event('change'));
                    }
                }
            @endif
        });
    </script>

    <style>
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            padding: 1.5rem;
        }

        h6 {
            font-size: 0.9rem;
            font-weight: 600;
        }

        h4 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2C3E50;
        }

        .text-muted {
            font-size: 0.85rem;
        }

        .input-group-sm .form-control,
        .input-group-sm .form-select {
            font-size: 0.9rem;
        }

        .btn-sm {
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .card-body {
                padding: 1rem;
            }

            h4 {
                font-size: 1.2rem;
            }

            .text-muted {
                font-size: 0.75rem;
            }
        }
    </style>
@endsection
