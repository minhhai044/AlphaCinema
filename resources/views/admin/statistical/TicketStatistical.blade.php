@extends('admin.layouts.master')

@section('title', 'Thống kê Doanh Thu')

@section('content')
    <div class="container-fluid mt-4">
        <h3 class="mb-4">Thống kê Doanh Thu</h3>

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

        <form method="GET" action="{{ route('admin.ticket.revenue') }}" class="d-flex align-items-center gap-2"
            id="filterForm">
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

            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light text-muted border-0">
                    <i class="bi bi-calendar"></i>
                </span>
                <input type="date" name="date" class="form-control border-0 shadow-sm" value="{{ $date }}">
            </div>

            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light text-muted border-0">
                    <i class="bi bi-film"></i>
                </span>
                <select name="movie_id" class="form-select border-0 shadow-sm">
                    <option value="" {{ !$movieId ? 'selected' : '' }}>Tất cả phim</option>
                    @foreach ($movies as $movie)
                        <option value="{{ $movie->id }}" {{ $movieId == $movie->id ? 'selected' : '' }}>
                            {{ $movie->name }}
                        </option>
                    @endforeach
                </select>
            </div>

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

            <button type="submit"
                class="btn btn-sm btn-success rounded-circle p-2 d-flex align-items-center justify-content-center"
                title="Lọc dữ liệu" style="width: 36px; height: 36px;">
                <i class="bi bi-funnel fs-5"></i>
            </button>

            <button type="button"
                class="btn btn-sm btn-primary rounded-circle p-2 d-flex align-items-center justify-content-center"
                onclick="resetFilters()" title="Reset bộ lọc" style="width: 36px; height: 36px;">
                <i class="bi bi-arrow-counterclockwise fs-5"></i>
            </button>
        </form>

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted text-uppercase mb-3">Xu Hướng Bán Vé</h6>
                        <p class="text-muted small mb-3">Số lượng vé bán ra</p>
                        <div id="ticketTrendChart" style="width: 100%; height: 300px;"></div>
                    </div>
                </div>
            </div>

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

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted text-uppercase mb-3">Phân Loại Vé</h6>
                        <p class="text-muted small mb-3">Phân bổ theo loại vé</p>
                        <div id="ticketTypeChart" style="width: 100%; height: 300px;"></div>
                    </div>
                </div>
            </div>

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

        <div class="row g-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted text-uppercase mb-3">Tỷ Lệ Lấp Đầy Rạp</h6>
                        <p class="text-muted small mb-3">Tỷ lệ ghế đã đặt trong số ghế trống</p>
                        <div id="fillRateChart" style="width: 100%; height: 300px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Định nghĩa bảng màu tùy chỉnh cho Highcharts (áp dụng cho toàn bộ biểu đồ)
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
                    categories: @json($ticketTrendData['categories'])
                },
                yAxis: {
                    title: {
                        text: null
                    },
                    max: Math.max(...@json($ticketTrendData['data'])) + 5, // Tự động điều chỉnh max
                },
                series: [{
                    name: 'Số vé',
                    data: @json($ticketTrendData['data']),
                    color: '#191970' // Màu chủ đạo
                }],
                plotOptions: {
                    line: {
                        marker: {
                            enabled: true
                        }
                    }
                }
            });

            // Top Phim Bán Chạy (Pie Chart)
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
                    colorByPoint: true,
                    data: @json($topMoviesData)
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

            // Phân Loại Vé (Donut Chart)
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
                        innerSize: '50%',
                        dataLabels: {
                            enabled: true,
                            format: '{point.name}: {point.percentage:.1f}%'
                        }
                    }
                },
                series: [{
                    name: 'Số vé',
                    colorByPoint: true,
                    data: @json($ticketTypeData)
                }]
            });

            // Giờ Cao Điểm (Bar Chart)
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
                    },
                    max: Math.max(...@json($peakHoursData['data'])) + 5, // Tự động điều chỉnh max
                },
                series: [{
                    name: 'Số vé',
                    data: @json($peakHoursData['data'])
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

            // Tỷ Lệ Lấp Đầy Rạp (Grouped Bar Chart)
            Highcharts.chart('fillRateChart', {
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
                    categories: @json($fillRateData['categories'])
                },
                yAxis: {
                    title: {
                        text: null
                    },
                    max: 100
                },
                series: [{
                    name: 'GH đã đặt',
                    data: @json($fillRateData['booked']),
                    color: '#483D8B'
                }, {
                    name: 'GH trống',
                    data: @json($fillRateData['available']),
                    color: '#ADD8E6' // Tông nhạt hơn
                }],
                plotOptions: {
                    column: {
                        stacking: 'percent',
                        dataLabels: {
                            enabled: true,
                            format: '{point.percentage:.0f}%'
                        }
                    }
                }
            });

            function resetFilters() {
                window.location.href = "{{ route('admin.ticket.revenue') }}";
            }
        });

        // JavaScript động cho System Admin
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
                                const isSelected = cinemaId == "{{ $cinemaId }}" ?
                                    'selected' : '';
                                cinemaSelect.innerHTML +=
                                    `<option value="${cinemaId}" <span class="math-inline">\{isSelected\}\></span>{cinemaName}</option>`;
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