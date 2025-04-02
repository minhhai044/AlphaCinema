@extends('admin.layouts.master')

@section('title', 'Thống kê đồ ăn')

@section('content')
    <div class="container-fluid mt-4">
        <h3 class="mb-4">Thống kê đồ ăn</h3>

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
        <form method="GET" action="{{ route('admin.food.revenue') }}" class="d-flex align-items-center gap-2" id="filterForm">
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
                <input type="date" name="date" class="form-control border-0 shadow-sm"
                    value="{{ $date ? $date->format('Y-m-d') : '' }}">
            </div>

            <!-- Bộ lọc phim -->
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

            <!-- Nút lọc dữ liệu -->
            <button type="submit"
                class="btn btn-sm btn-success rounded-circle d-flex align-items-center justify-content-center"
                title="Lọc dữ liệu" style="width: 36px; height: 36px;">
                <i class="bi bi-funnel" style="font-size: 20px;"></i>
            </button>

            <!-- Nút reset -->
            <button type="button"
                class="btn btn-sm btn-primary rounded-circle d-flex align-items-center justify-content-center"
                onclick="resetFilters()" title="Reset bộ lọc" style="width: 36px; height: 36px;">
                <i class="bi bi-arrow-counterclockwise" style="font-size: 20px;"></i>
            </button>
        </form>

        <!-- Tổng doanh thu và biểu đồ -->
        <div class="row g-4 mb-4 mt-2">
            <!-- Tổng doanh thu food -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card card-h-100">
                        <div class="card-body">
                            <div id="foodChart" style="width: 100%; height: 400px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div id="stackedBarChart" style="width: 100%; height: 400px;"></div>
                    </div>
                </div>
            </div>
            <!-- Tỷ lệ đơn hàng có food -->
            <div class="col-xl-6">
                <div class="card card-h-100">
                    <div class="card-body">
                        <div id="foodUsageChart" style="width: 100%; height: 400px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top 3 food doanh thu -->
    <div class="row">
        <div class="col-12">
            <div class="container mt-5">
                <h3 class="text-center mb-4" style="font-weight: bold; color: #5156be;">
                    Top 3 Doanh Thu Cao Nhất
                </h3>
                <div class="row justify-content-center">
                    @forelse ($top6Foods as $index => $food)
                        <div class="col-md-4 col-lg-3 mb-4">
                            <div class="card top6-card shadow-sm h-100 d-flex flex-column"
                                style="border: none; border-radius: 10px; overflow: hidden;">
                                <div class="position-relative flex-grow-1" style="height: 60%;">
                                    <img src="{{ Storage::url($food->img_thumbnail) }}" class="card-img-top w-100 h-100"
                                        style="object-fit: cover;" alt="{{ $food->food_name }}">
                                    <span class="top6-rank"
                                        style="position: absolute; top: 10px; left: 10px; background: #5156be; color: white; padding: 5px 10px; border-radius: 50%; font-size: 14px; font-weight: bold;">
                                        #{{ $index + 1 }}
                                    </span>
                                </div>
                                <div class="card-body text-center d-flex flex-column justify-content-center"
                                    style="height: 40%; padding: 15px;">
                                    <h5 class="card-title" style="font-size: 18px; color: #333; margin-bottom: 10px;">
                                        {{ $food->food_name }}
                                    </h5>
                                    <p class="mb-2" style="font-size: 14px; color: #6c757d;">
                                        <strong>Doanh thu:</strong> <span
                                            style="color: #5156be;">{{ number_format($food->total_price) }} đ</span>
                                    </p>
                                    <p class="mb-0" style="font-size: 14px; color: #6c757d;">
                                        <strong>Lượt bán:</strong> <span
                                            style="color: #5156be;">{{ $food->total_quantity }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-4">
                            <p style="font-size: 16px; color: #6c757d;">Không có dữ liệu đồ ăn để hiển thị.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // 1. Tổng doanh thu Food
            var foodNames = @json($foodNames);
            var foodRevenues = @json($foodRevenues);
            var foodSummaries = @json($foodSummaries);

            if (foodNames.length > 0 && foodRevenues.length > 0) {
                Highcharts.chart('foodChart', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: 'Doanh thu theo Đồ Ăn'
                    },
                    xAxis: {
                        categories: foodNames, // Sử dụng tên đồ ăn làm danh mục trục X
                        crosshair: true,
                        title: {
                            text: 'Tên Đồ Ăn'
                        },
                        accessibility: {
                            description: 'Tên Đồ Ăn'
                        }
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Doanh thu (VNĐ)' // Tiêu đề trục Y
                        }
                    },
                    tooltip: {
                        pointFormatter: function() {
                            var index = this.index; // Lấy chỉ số của điểm dữ liệu
                            return `${foodSummaries[index]}`; // Chỉ hiển thị tóm tắt, không lặp tên đồ ăn
                        }
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            borderWidth: 0,
                            color: '#5156be' // Giữ màu nền từ Chart.js
                        }
                    },
                    series: [{
                        name: 'Doanh thu (VNĐ)',
                        data: foodRevenues // Dữ liệu doanh thu từ PHP
                    }],
                    credits: {
                        enabled: false // Tắt dòng chữ "Highcharts.com"
                    }
                });
            } else {
                // Nếu không có dữ liệu, hiển thị thông báo trên container
                var container = document.getElementById('foodChart');
                container.innerHTML =
                    '<p style="font: 20px Arial; text-align: center; margin-top: 200px;">Không có dữ liệu để hiển thị</p>';
            }

            // 2. Tỷ lệ đơn hàng có Food
            Highcharts.setOptions({
                colors: Highcharts.getOptions().colors.map(function(color) {
                    return {
                        radialGradient: {
                            cx: 0.5,
                            cy: 0.3,
                            r: 0.7
                        },
                        stops: [
                            [0, color],
                            [1, Highcharts.color(color).brighten(-0.3).get(
                                'rgb')] // Làm tối màu
                        ]
                    };
                })
            });

            var foodUsage = @json($foodUsage);

            Highcharts.chart('foodUsageChart', {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {
                    text: 'Tỷ lệ đơn hàng có Đồ Ăn'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>' // Hiển thị % trong tooltip
                },
                accessibility: {
                    point: {
                        valueSuffix: '%'
                    }
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<span style="font-size: 1.2em"><b>{point.name}</b></span><br>' +
                                '<span style="opacity: 0.6">{point.percentage:.1f} %</span>',
                            connectorColor: 'rgba(128,128,128,0.5)'
                        }
                    }
                },
                credits: {
                    enabled: false // Tắt dòng chữ "Highcharts.com"
                },
                series: [{
                    name: 'Tỷ lệ',
                    data: [{
                            name: 'Có Đồ Ăn',
                            y: foodUsage
                        },
                        {
                            name: 'Không có Đồ Ăn',
                            y: 100 - foodUsage
                        }
                    ],
                    colors: ['#5156be',
                        '#8ECae6'
                    ] // Màu xanh tím và đỏ (thay #d3d3d3 thành #ff0000 để giống combo)
                }]
            });

            // 3. Doanh thu Food theo khung giờ
            var trendDates = @json($trendDates); // Khung giờ suất chiếu
            var trendRevenues = @json($trendRevenues); // Doanh thu tương ứng

            if (trendDates.length > 0 && trendRevenues.length > 0) {
                Highcharts.chart('stackedBarChart', {
                    chart: {
                        type: 'line' // Thay cột bằng dây
                    },
                    title: {
                        text: 'Doanh thu Đồ Ăn theo khung giờ (logarithmic scale)' // Thêm logarithmic scale
                    },
                    xAxis: {
                        categories: trendDates,
                        title: {
                            text: 'Khung giờ suất chiếu'
                        }
                    },
                    yAxis: {
                        type: 'logarithmic', // Sử dụng thang logarithmic thay vì tuyến tính
                        minorTickInterval: 0.1,
                        title: {
                            text: 'Doanh thu (VNĐ)'
                        },
                        labels: {
                            formatter: function() {
                                return Highcharts.numberFormat(this.value, 0, ',', '.') + ' VNĐ';
                            }
                        }
                    },
                    tooltip: {
                        pointFormatter: function() {
                            var total = this.series.data.reduce((sum, point) => sum + point.y,
                                0); // Tính tổng doanh thu
                            var percentage = total > 0 ? (this.y / total * 100).toFixed(1) :
                                0; // Tính tỷ lệ %
                            return `Doanh thu: ${Highcharts.numberFormat(this.y, 0, ',', '.')} VNĐ<br>Tỷ lệ: ${percentage}%`;
                        }
                    },
                    series: [{
                        name: 'Doanh thu Đồ Ăn',
                        keys: ['y', 'color'], // Thêm màu cho từng điểm
                        data: trendRevenues.map((revenue, index) => {
                            const colors = ['#0000ff', '#8d0073', '#ba0046', '#d60028',
                                '#eb0014', '#fb0004', '#ff0000'
                            ];
                            const colorIndex = Math.min(index, colors.length - 1);
                            return [revenue, colors[colorIndex]];
                        }),
                        color: {
                            linearGradient: {
                                x1: 0,
                                x2: 0,
                                y1: 1,
                                y2: 0
                            },
                            stops: [
                                [0, '#0000ff'], // Màu xanh cho giá trị thấp
                                [1, '#ff0000'] // Màu đỏ cho giá trị cao
                            ]
                        }
                    }],
                    legend: {
                        enabled: true,
                        align: 'center',
                        verticalAlign: 'top'
                    },
                    credits: {
                        enabled: false
                    }
                });
            } else {
                var container = document.getElementById('stackedBarChart');
                container.innerHTML =
                    '<p style="font: 20px Arial; text-align: center; margin-top: 100px;">Không có dữ liệu để hiển thị</p>';
            }
        });

        function resetFilters() {
            window.location.href = "{{ route('admin.food.revenue') }}";
        }

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
        .top6-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .top6-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15) !important;
        }

        .card-img-top {
            border-bottom: 2px solid #5156be;
        }

        .top6-rank {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .card {
            border-radius: 10px;
            overflow: hidden;
        }

        .card-title {
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .card-body {
                padding: 1rem;
            }

            canvas {
                height: 150px !important;
            }
        }
    </style>
@endsection
