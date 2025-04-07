@extends('admin.layouts.master')
@section('title', 'Thống kê')

@section('content')
    <div class="container-fluid mt-4">
        <h3 class="mb-4 text-center">Thống kê phim</h3>

        <!-- Vai trò người dùng -->
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

        <!-- Form lọc (trả lại như cũ) -->
        <form method="GET" action="{{ route('admin.statistical.cinemaRevenue') }}"
            class="d-flex align-items-center gap-2 mb-4" id="filterForm">
            <!-- Chi nhánh -->
            @if (auth()->user()->hasRole('System Admin'))
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light text-muted border-0">
                        <i class="bi bi-geo-alt-fill"></i>
                    </span>
                    <select name="branch_id" class="form-select border-0 shadow-sm" id="branch_id">
                        <option value="" {{ !$branchId ? 'selected' : '' }}>Chọn chi nhánh</option>
                        @if ($branches && $branches->isNotEmpty())
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" {{ $branchId == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
            @else
                @if (auth()->user()->branch_id)
                    <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id }}">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light text-muted border-0">
                            <i class="bi bi-geo-alt-fill"></i>
                        </span>
                        <span class="form-control border-0 shadow-sm">
                            {{ auth()->user()->branch->name ?? 'N/A' }}
                        </span>
                    </div>
                @endif
            @endif

            <!-- Rạp -->
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light text-muted border-0">
                    <i class="bi bi-camera-reels"></i>
                </span>
                <select name="cinema_id" class="form-select border-0 shadow-sm" id="cinema_id">
                    <option value="" {{ !$cinemaId ? 'selected' : '' }}>Chọn rạp</option>
                    @if (auth()->user()->hasRole('System Admin') && $branchId && isset($branchesRelation[$branchId]))
                        @foreach ($branchesRelation[$branchId] as $id => $name)
                            <option value="{{ $id }}" {{ $cinemaId == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    @elseif(auth()->user()->branch_id && isset($branchesRelation[auth()->user()->branch_id]))
                        @foreach ($branchesRelation[auth()->user()->branch_id] as $id => $name)
                            <option value="{{ $id }}" {{ $cinemaId == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    @elseif(auth()->user()->cinema_id)
                        <option value="{{ auth()->user()->cinema_id }}" selected>
                            {{ auth()->user()->cinema->name ?? 'N/A' }}
                        </option>
                    @endif
                </select>
            </div>

            <!-- Ngày -->
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light text-muted border-0">
                    <i class="bi bi-calendar"></i>
                </span>
                <input type="date" name="date" id="date" class="form-control border-0 shadow-sm"
                    value="{{ $date ?? $today }}">
            </div>

            <!-- Tháng -->
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light text-muted border-0">
                    <i class="bi bi-calendar-month"></i>
                </span>
                <select name="month" class="form-select border-0 shadow-sm" id="month">
                    <option value="" {{ !$selectedMonth ? 'selected' : '' }}>Chọn tháng</option>
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ $selectedMonth == $i ? 'selected' : '' }}>
                            Tháng {{ $i }}
                        </option>
                    @endfor
                </select>
            </div>

            <!-- Năm -->
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light text-muted border-0">
                    <i class="bi bi-calendar4"></i>
                </span>
                <select name="year" class="form-select border-0 shadow-sm" id="year">
                    <option value="" {{ !$selectedYear ? 'selected' : '' }}>Chọn năm</option>
                    @for ($i = 2020; $i <= Carbon\Carbon::now()->year; $i++)
                        <option value="{{ $i }}" {{ $selectedYear == $i ? 'selected' : '' }}>
                            Năm {{ $i }}
                        </option>
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
                onclick="window.location.href='{{ route('admin.statistical.cinemaRevenue') }}'" title="Reset bộ lọc"
                style="width: 36px; height: 36px;">
                <i class="bi bi-arrow-counterclockwise fs-5"></i>
            </button>
        </form>

        @if ($message)
            <div class="alert alert-info text-center my-4">
                {{ $message }}
            </div>
        @else
            <!-- Biểu đồ -->
            <div class="row g-4 mb-4">
                <!-- Doanh thu và Số vé -->
                <div class="col-12 col-lg-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="text-muted text-uppercase mb-3">Thống Kê Doanh Thu và Số Vé Theo Phim</h6>
                            <p class="text-muted small mb-3">Doanh thu (VNĐ) và số lượng vé bán ra</p>
                            <div id="revenueChart" style="width: 100%; height: 350px;"></div>
                        </div>
                    </div>
                </div>

                <!-- Số lượng suất chiếu -->
                <div class="col-12 col-lg-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="text-muted text-uppercase mb-3">Số Lượng Suất Chiếu Theo Phim</h6>
                            <p class="text-muted small mb-3">Số lượng suất chiếu của từng phim</p>
                            <div id="showtimeChart" style="width: 100%; height: 350px;"></div>
                        </div>
                    </div>
                </div>

                <!-- Phim được xem lại -->
                <div class="col-12 col-lg-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="text-muted text-uppercase mb-3">Phim Được Xem Lại Nhiều Nhất</h6>
                            <p class="text-muted small mb-3">Số lần xem lại của từng phim</p>
                            <div id="rewatchChart" style="width: 100%; height: 350px;"></div>
                        </div>
                    </div>
                </div>

                <!-- Tỷ lệ lấp đầy -->
                <div class="col-12 col-lg-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="text-muted text-uppercase mb-3">Tỷ Lệ Lấp Đầy Theo Phim</h6>
                            <p class="text-muted small mb-3">Tỷ lệ ghế đã đặt của từng phim</p>
                            <div id="fillRateChart" style="width: 100%; height: 350px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top 6 Phim -->
            <div class="row">
                <div class="col-12">
                    <div class="container mt-5">
                        <h3 class="text-center mb-4" style="font-weight: bold; color: #2C3E50;">Top 3 Phim Doanh Thu Cao
                            Nhất</h3>
                        <div class="row justify-content-center">
                            @forelse ($top6Movies as $index => $movie)
                                <div class="col-md-4 col-lg-4 mb-4">
                                    <div class="card top6-card shadow-sm h-100">
                                        <div class="position-relative">
                                            <img src="{{ Storage::url($movie->img_thumbnail) }}" class="card-img-top"
                                                style="height: 240px; object-fit: cover;" alt="{{ $movie->movie_name }}">
                                            <span class="top6-rank"
                                                style="position: absolute; top: 10px; left: 10px; background: #483D8B; color: white; padding: 5px 10px; border-radius: 50%; font-size: 14px; font-weight: bold;">
                                                #{{ $index + 1 }}
                                            </span>
                                        </div>
                                        <div class="card-body text-center">
                                            <h5 class="card-title" style="font-size: 1rem;">{{ $movie->movie_name }}</h5>
                                            <p class="mb-2 text-muted small">
                                                <strong>Doanh thu:</strong> <span
                                                    style="color: #483D8B;">{{ number_format($movie->revenue) }} đ</span>
                                            </p>
                                            <p class="mb-0 text-muted small">
                                                <strong>Số vé:</strong> <span
                                                    style="color: #483D8B;">{{ $movie->ticket_count }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-4">
                                    <p class="text-muted small">Không có dữ liệu phim để hiển thị.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Highcharts CDN -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>

    <!-- Script Highcharts -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Highcharts.setOptions({
                colors: ['#483D8B', '#4682B4', '#20B2AA', '#98FB98', '#FFDAB9']
            });

            @if (auth()->user()->hasRole('System Admin'))
                const branchSelect = document.getElementById('branch_id');
                const cinemaSelect = document.getElementById('cinema_id');

                if (branchSelect && cinemaSelect) {
                    const selectedCinemaId = '{{ $cinemaId }}';
                    if (branchSelect.value) {
                        updateCinemas(branchSelect.value, selectedCinemaId);
                    }

                    branchSelect.addEventListener('change', function() {
                        updateCinemas(this.value);
                    });

                    function updateCinemas(branchId, preselectedCinemaId = '') {
                        cinemaSelect.innerHTML = '<option value="">Chọn rạp</option>';
                        if (branchId) {
                            fetch('{{ route('admin.statistical.cinemas') }}?branch_id=' + branchId, {
                                    method: 'GET',
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.length > 0) {
                                        data.forEach(cinema => {
                                            const option = document.createElement('option');
                                            option.value = cinema.id;
                                            option.text = cinema.name;
                                            if (preselectedCinemaId && cinema.id ==
                                                preselectedCinemaId) {
                                                option.selected = true;
                                            }
                                            cinemaSelect.appendChild(option);
                                        });
                                    } else {
                                        cinemaSelect.innerHTML +=
                                            '<option value="" disabled>Không có rạp nào</option>';
                                    }
                                })
                                .catch(error => {
                                    console.error('Error fetching cinemas:', error);
                                });
                        }
                    }
                }
            @endif

            @if (!$message)
                // Biểu đồ Doanh thu và Số vé
                var revenueData = @json($revenues);
                if (revenueData && Array.isArray(revenueData) && revenueData.length > 0) {
                    Highcharts.chart('revenueChart', {
                        chart: {
                            zooming: {
                                type: 'xy'
                            }
                        },
                        title: {
                            text: null // Xóa tiêu đề
                        },
                        credits: {
                            enabled: false,
                            text: 'Nguồn: Hệ thống quản lý rạp chiếu phim'
                        },
                        xAxis: [{
                            categories: revenueData.map(item => item.movie_name ||
                                'Không xác định'),
                            crosshair: true,
                            labels: {
                                rotation: -45
                            }
                        }],
                        yAxis: [{ // Primary yAxis (Doanh thu)
                            labels: {
                                format: '{value:,.0f} VNĐ',
                                style: {
                                    color: Highcharts.getOptions().colors[1]
                                }
                            },
                            title: {
                                text: 'Doanh thu (VNĐ)',
                                style: {
                                    color: Highcharts.getOptions().colors[1]
                                }
                            }
                        }, { // Secondary yAxis (Số vé)
                            title: {
                                text: 'Số vé',
                                style: {
                                    color: Highcharts.getOptions().colors[0]
                                }
                            },
                            labels: {
                                format: '{value:,.0f}',
                                style: {
                                    color: Highcharts.getOptions().colors[0]
                                }
                            },
                            opposite: true
                        }],
                        tooltip: {
                            shared: true
                        },
                        legend: {
                            align: 'left',
                            verticalAlign: 'top',
                            backgroundColor: Highcharts.defaultOptions.legend.backgroundColor ||
                                'rgba(255,255,255,0.25)'
                        },
                        series: [{
                            name: 'Doanh thu (VNĐ)',
                            type: 'column',
                            yAxis: 0,
                            data: revenueData.map(item => parseFloat(item.revenue) || 0),
                            tooltip: {
                                valueSuffix: ' VNĐ',
                                valueDecimals: 0
                            }
                        }, {
                            name: 'Số vé',
                            type: 'spline',
                            yAxis: 1,
                            data: revenueData.map(item => parseInt(item.ticket_count) || 0),
                            tooltip: {
                                valueSuffix: ' vé'
                            }
                        }],
                        plotOptions: {
                            column: {
                                borderRadius: 5
                            }
                        }
                    });
                } else {
                    document.getElementById('revenueChart').innerHTML =
                        '<p class="text-muted text-center">Không có dữ liệu doanh thu để hiển thị.</p>';
                }

                // Biểu đồ Số lượng suất chiếu
                var showtimeData = @json($showtimes);
                if (showtimeData && Array.isArray(showtimeData) && showtimeData.length > 0) {
                    Highcharts.chart('showtimeChart', {
                        chart: {
                            type: 'bar'
                        },
                        credits: {
                            enabled: false
                        },
                        title: {
                            text: null
                        },
                        xAxis: {
                            categories: showtimeData.map(item => item.movie_name ||
                                'Không xác định') // Giữ nguyên danh mục
                        },
                        yAxis: {
                            title: {
                                text: 'Số lượng suất chiếu'
                            }
                        },
                        series: [{
                            name: 'Số suất chiếu',
                            data: showtimeData.map(item => parseInt(item.showtime_count) ||
                                0), // Giữ nguyên dữ liệu
                            colors: [
                                '#191970', // Màu cố định đầu tiên
                                ...Array(showtimeData.length - 1).fill().map(() => '#' + Math
                                    .floor(Math.random() * 16777215).toString(16)
                                ) // Random màu cho các cột còn lại
                            ]
                        }],
                        plotOptions: {
                            bar: {
                                borderRadius: 5, // Giữ bo góc
                                colorByPoint: true // Cho phép mỗi cột có màu riêng
                            }
                        },
                        tooltip: {
                            valueSuffix: ' suất' // Giữ nguyên hậu tố
                        }
                    });
                } else {
                    document.getElementById('showtimeChart').innerHTML =
                        '<p class="text-muted text-center">Không có dữ liệu suất chiếu để hiển thị.</p>';
                }

                // Biểu đồ Phim được xem lại
                var rewatchData = @json($mostRewatchedMovies);
                if (rewatchData && Array.isArray(rewatchData) && rewatchData.length > 0) {
                    Highcharts.chart('rewatchChart', {
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
                            name: 'Số lần xem lại',
                            data: rewatchData.map(item => ({
                                name: item.movie_name || 'Không xác định',
                                y: parseInt(item.rewatch_count) || 0
                            })),
                            colorByPoint: true
                        }],
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: true,
                                    format: '<b>{point.name}</b>: {point.y} lần'
                                }
                            }
                        },
                        tooltip: {
                            valueSuffix: ' lần'
                        }
                    });
                } else {
                    document.getElementById('rewatchChart').innerHTML =
                        '<p class="text-muted text-center">Không có dữ liệu phim xem lại để hiển thị.</p>';
                }

                // Biểu đồ Tỷ lệ lấp đầy 
                var fillRateData = @json($fillRates);
                if (fillRateData && Array.isArray(fillRateData) && fillRateData.length > 0) {
                    // Tạo mảng màu random dựa trên số lượng dữ liệu
                    const colors = [
                        '#191970', // Màu cố định đầu tiên
                        ...Array(fillRateData.length - 1).fill().map(() => '#' + Math.floor(Math.random() *
                            16777215).toString(16))
                    ];

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
                            categories: fillRateData.map(item => item.movie_name || 'Không xác định'),
                            labels: {
                                rotation: -45
                            }
                        },
                        yAxis: {
                            max: 100,
                            title: {
                                text: 'Tỷ lệ lấp đầy (%)'
                            },
                            labels: {
                                format: '{value}%'
                            }
                        },
                        series: [{
                            name: 'Tỷ lệ lấp đầy',
                            data: fillRateData.map((item, index) => ({
                                y: parseFloat(item.fill_rate) || 0, // Giá trị tỷ lệ
                                color: colors[index] // Gán màu random cho từng cột
                            })),
                            colorByPoint: true // Cho phép mỗi cột có màu riêng
                        }],
                        plotOptions: {
                            column: {
                                borderRadius: 5,
                                pointWidth: 30
                            }
                        },
                        tooltip: {
                            formatter: function() {
                                // Hiển thị tên phim và màu trong tooltip
                                return `<span style="color:${this.point.color}">●</span> ${this.series.name}: <b>${this.y}%</b><br/>Phim: ${this.x}`;
                            }
                        },
                        legend: {
                            enabled: true, // Bật legend để hiển thị màu theo tên phim
                            labelFormatter: function() {
                                // Hiển thị tên phim trong legend với màu tương ứng
                                const index = this.index;
                                const movieName = fillRateData[index]?.movie_name || 'Không xác định';
                                return `<span style="color:${colors[index]}">${movieName}</span>`;
                            }
                        }
                    });
                } else {
                    document.getElementById('fillRateChart').innerHTML =
                        '<p class="text-muted text-center">Không có dữ liệu tỷ lệ lấp đầy để hiển thị.</p>';
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

        .top6-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .top6-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        @media (max-width: 768px) {
            .card-body {
                padding: 1rem;
            }

            .text-muted {
                font-size: 0.75rem;
            }
        }
    </style>
@endsection
