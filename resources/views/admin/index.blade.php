@extends('admin.layouts.master')

@section('content')
    <!-- Start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <h4 class="mb-0 font-size-18 text-primary fw-bold">
                        <i class="bi bi-bar-chart-line me-2"></i> Thống kê trên toàn hệ thống
                    </h4>
                    <form method="GET" action="" class="d-flex align-items-center gap-2 flex-wrap" id="filterForm">
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
    @else
        @if (auth()->user()->branch_id)
            <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id }}">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light text-muted border-0">
                    <i class="bi bi-geo-alt-fill"></i>
                </span>
                <span class="form-control border-0 shadow-sm">{{ auth()->user()->branch->name ?? 'N/A' }}</span>
            </div>
        @elseif (auth()->user()->cinema_id)
            @php
                $cinema = $cinemas->firstWhere('id', auth()->user()->cinema_id);
            @endphp
            <input type="hidden" name="branch_id" value="{{ $cinema->branch_id ?? '' }}">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light text-muted border-0">
                    <i class="bi bi-geo-alt-fill"></i>
                </span>
                <span class="form-control border-0 shadow-sm">{{ $cinema->branch->name ?? 'Không xác định' }}</span>
            </div>
        @endif
    @endif

    <!-- Bộ lọc rạp -->
    @if (auth()->user()->hasRole('System Admin') || auth()->user()->branch_id)
        <div class="input-group input-group-sm">
            <span class="input-group-text bg-light text-muted border-0">
                <i class="bi bi-camera-reels"></i>
            </span>
            <select name="cinema_id" class="form-select border-0 shadow-sm">
                <option value="" {{ !$cinemaId ? 'selected' : '' }}>Tất cả rạp</option>
                @if (auth()->user()->hasRole('System Admin') && $branchId && isset($branchesRelation[$branchId]))
                    @foreach ($branchesRelation[$branchId] as $id => $name)
                        <option value="{{ $id }}" {{ $cinemaId == $id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                @elseif (auth()->user()->branch_id && isset($branchesRelation[auth()->user()->branch_id]))
                    @foreach ($branchesRelation[auth()->user()->branch_id] as $id => $name)
                        <option value="{{ $id }}" {{ $cinemaId == $id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                @else
                    <option value="" disabled>Không có rạp nào</option>
                @endif
            </select>
        </div>
    @elseif (auth()->user()->cinema_id)
        @php
            $cinema = $cinemas->firstWhere('id', auth()->user()->cinema_id);
        @endphp
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

    <!-- Bộ lọc trạng thái -->
    <div class="input-group input-group-sm">
        <span class="input-group-text bg-light text-muted border-0">
            <i class="bi bi-check-circle"></i>
        </span>
        <select name="status_id" class="form-select border-0 shadow-sm">
            <option value="" {{ !$statusId ? 'selected' : '' }}>Tất cả trạng thái</option>
            <option value="confirmed" {{ $statusId == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
            <option value="pending" {{ $statusId == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
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
                <option value="{{ $i }}" {{ $selectedMonth == $i ? 'selected' : '' }}>
                    Tháng {{ $i }}
                </option>
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
                <option value="{{ $i }}" {{ $selectedYear == $i ? 'selected' : '' }}>
                    Năm {{ $i }}
                </option>
            @endfor
        </select>
    </div>

    <!-- Nút lọc -->
    <button type="submit" class="btn btn-sm btn-success rounded-circle p-2 d-flex align-items-center justify-content-center" title="Lọc dữ liệu" style="width: 36px; height: 36px;">
        <i class="bi bi-funnel fs-5"></i>
    </button>

    <!-- Nút reset -->
    <button type="button" class="btn btn-sm btn-primary rounded-circle p-2 d-flex align-items-center justify-content-center" onclick="resetFilters()" title="Reset bộ lọc" style="width: 36px; height: 36px;">
        <i class="bi bi-arrow-counterclockwise fs-5"></i>
    </button>
</form>
                </div>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Thống kê</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- End page title -->

    <!-- Phần còn lại của nội dung giữ nguyên -->
    <div class="row g-4">
        <!-- Card Doanh thu hôm nay -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-custom">
                <div class="card-header-custom">
                    <i class="bi bi-currency-dollar me-1"></i> Doanh thu hôm nay
                </div>
                <div class="card-body-custom">
                    <div class="row align-items-center">
                        <div class="col-7">
                            <h4 class="mb-3">
                                <span class="counter-value" data-target="{{ $revenueToday }}">{{ $formattedRevenueToday }}</span> VNĐ
                            </h4>
                        </div>
                    </div>
                    <div class="text-nowrap">
                        <span class="badge bg-success-subtle text-success badge-custom"><i class="bi bi-arrow-up"></i> 0%</span>
                        <span class="ms-1 text-muted">So với hôm qua</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Vé hôm nay -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-custom">
                <div class="card-header-custom">
                    <i class="bi bi-ticket-perforated me-1"></i> Vé hôm nay
                </div>
                <div class="card-body-custom">
                    <div class="row align-items-center">
                        <div class="col-7">
                            <h4 class="mb-3">
                                <span class="counter-value" data-target="{{ $ticketsToday }}">{{ $ticketsToday }}</span> VÉ
                            </h4>
                        </div>
                    </div>
                    <div class="text-nowrap">
                        <span class="badge bg-success-subtle text-success badge-custom"><i class="bi bi-arrow-up"></i> 0%</span>
                        <span class="ms-1 text-muted">So với hôm qua</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Doanh thu tháng -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-custom">
                <div class="card-header-custom">
                    <i class="bi bi-calendar-month me-1"></i> Doanh thu tháng {{ $selectedMonth ?: Carbon::now()->month }}
                </div>
                <div class="card-body-custom">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <h4 class="mb-3">
                                <span class="counter-value" data-target="{{ $totalRevenue }}">{{ $formattedRevenue }}</span> VNĐ
                            </h4>
                        </div>
                    </div>
                    <div class="text-nowrap">
                        @if ($revenueChange < 0)
                            <span class="badge bg-danger-subtle text-danger badge-custom"><i class="bi bi-arrow-down"></i> {{ abs(round($revenueChange, 1)) }}%</span>
                        @else
                            <span class="badge bg-success-subtle text-success badge-custom"><i class="bi bi-arrow-up"></i> {{ round($revenueChange, 1) }}%</span>
                        @endif
                        <span class="ms-1 text-muted">So với tháng trước</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Phim đang hoạt động -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-custom">
                <div class="card-header-custom">
                    <i class="bi bi-film me-1"></i> Phim đang hoạt động
                </div>
                <div class="card-body-custom">
                    <div class="row align-items-center">
                        <div class="col-7">
                            <h4 class="mb-3">
                                <span class="counter-value" data-target="{{ $activeMoviesCount }}">{{ $activeMoviesCount }}</span> Phim
                            </h4>
                        </div>
                    </div>
                    <div class="text-nowrap">
                        <span class="badge bg-success-subtle text-success badge-custom"><i class="bi bi-arrow-up"></i> 0%</span>
                        <span class="ms-1 text-muted">So với hôm qua</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-4">
            <div class="card card-h-100">
                <div class="card-body">
                    <h5 class="card-title">Tỷ lệ đặt ghế - Tháng {{ $selectedMonth ?: Carbon::now()->month }}</h5>
                    <div id="seat-booking-chart"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card card-h-100">
                <div class="card-body">
                    <h5 class="card-title">Doanh thu - Năm {{ $selectedYear }}</h5>
                    <div id="revenue-chart"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bảng doanh thu theo rạp -->
    <div class="row mt-4 g-4">
        <div class="col-12">
            <div class="card card-custom">
                <div class="card-header-custom">
                    <i class="bi bi-bar-chart-line me-1"></i> Doanh thu theo rạp - Tháng {{ $selectedMonth ?: Carbon::now()->month }}/{{ $selectedYear }}
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-custom align-middle">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Tên rạp</th>
                                    <th scope="col">Doanh thu (VNĐ)</th>
                                    <th scope="col">Tỷ lệ (%)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalRevenueForTable = array_sum(json_decode($revenueSeriesJson, true)) * 1000000; // Chuyển từ triệu về VND
                                @endphp
                                @forelse (json_decode($cinemaLabelsJson) as $index => $cinemaName)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $cinemaName }}</td>
                                        <td>{{ number_format(json_decode($revenueSeriesJson)[$index] * 1000000, 0, ',', '.') }}</td>
                                        <td>
                                            @php
                                                $revenue = json_decode($revenueSeriesJson)[$index] * 1000000;
                                                $percentage = $totalRevenueForTable > 0 ? ($revenue / $totalRevenueForTable) * 100 : 0;
                                            @endphp
                                            <div class="progress progress-custom">
                                                <div class="progress-bar progress-bar-custom" role="progressbar"
                                                    style="width: {{ round($percentage, 1) }}%;"
                                                    aria-valuenow="{{ round($percentage, 1) }}" aria-valuemin="0"
                                                    aria-valuemax="100">
                                                    {{ round($percentage, 1) }}%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Không có dữ liệu</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2">Tổng cộng</th>
                                    <th>{{ number_format($totalRevenueForTable, 0, ',', '.') }}</th>
                                    <th>100%</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- Dashboard init -->
    <script src="{{ asset('theme/admin/assets/js/pages/dashboard.init.js') }}"></script>

    <!-- Biểu đồ tỷ lệ đặt ghế -->
    <script>
        var seatOptions = {
            series: {!! $seatSeries !!},
            chart: { type: 'donut', height: 300 },
            labels: {!! $seatLabels !!},
            colors: ['#2C91F7', '#2EE5AC', '#FF5733'],
            dataLabels: {
                enabled: true,
                formatter: function(val) { return val.toFixed(1) + "%"; },
                style: { fontSize: '12px', fontWeight: 'bold' }
            },
            legend: {
                position: 'bottom',
                markers: { width: 10, height: 10, radius: 10 },
                fontSize: '12px'
            },
            tooltip: {
                y: { formatter: function(val) { return val.toFixed(1) + "%"; } }
            }
        };
        var seatChart = new ApexCharts(document.querySelector("#seat-booking-chart"), seatOptions);
        seatChart.render();
    </script>

    <!-- Biểu đồ doanh thu -->
    <script>
        const revenueData = {!! $revenueDataJson !!};
        var revenueOptions = {
            series: [{ name: "Doanh thu (Triệu VND)", data: revenueData }],
            chart: { type: 'bar', height: 350 },
            plotOptions: { bar: { horizontal: false, columnWidth: '55%' } },
            stroke: { width: 2, curve: 'smooth' },
            xaxis: {
                categories: ["Tháng 1", "Tháng 2", "Tháng 3", "Tháng 4", "Tháng 5", "Tháng 6",
                            "Tháng 7", "Tháng 8", "Tháng 9", "Tháng 10", "Tháng 11", "Tháng 12"]
            },
            yaxis: { labels: { formatter: function(value) { return value + " triệu VND"; } } },
            tooltip: { y: { formatter: function(val) { return val + " triệu VND"; } } },
            colors: ['#5156be']
        };
        var revenueChart = new ApexCharts(document.querySelector("#revenue-chart"), revenueOptions);
        revenueChart.render();

        function resetFilters() {
            document.getElementById('filterForm').reset();
            window.location.href = "{{ route('admin.index') }}";
        }

        document.addEventListener('DOMContentLoaded', function() {
            const branchSelect = document.querySelector('select[name="branch_id"]');
            const cinemaSelect = document.querySelector('select[name="cinema_id"]');
            const branchesRelation = @json($branchesRelation);

            @if (auth()->user()->hasRole('System Admin'))
                if (branchSelect && cinemaSelect) {
                    branchSelect.addEventListener('change', function() {
                        const branchId = this.value;
                        cinemaSelect.innerHTML = '<option value="" ' + (!branchId ? 'selected' : '') + '>Tất cả rạp</option>';

                        if (branchId && branchesRelation[branchId]) {
                            const cinemas = branchesRelation[branchId];
                            for (const [cinemaId, cinemaName] of Object.entries(cinemas)) {
                                const isSelected = cinemaId == "{{ $cinemaId }}" ? 'selected' : '';
                                cinemaSelect.innerHTML += `<option value="${cinemaId}" ${isSelected}>${cinemaName}</option>`;
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

    <!-- Animation cho counter -->
    <script>
        $('.counter-value').each(function() {
            var $this = $(this),
                target = parseFloat($this.data('target')) || 0;
            $this.prop('Counter', 0).animate({
                Counter: target
            }, {
                duration: 2000,
                easing: 'swing',
                step: function(now) { $this.text(Math.ceil(now).toLocaleString('vi-VN')); },
                complete: function() { $this.text(target.toLocaleString('vi-VN')); }
            });
        });
    </script>
@endsection

@section('style')
    <style>
        .card-custom {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 10px;
            background: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        .card-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        .card-header-custom {
            background: linear-gradient(135deg, #5156be 0%, #34c38f 100%);
            color: white;
            padding: 10px 15px;
            border-radius: 10px 10px 0 0;
            font-weight: 600;
            font-size: 14px;
        }
        .card-body-custom { padding: 15px; }
        .counter-value { font-size: 24px; font-weight: 700; color: #343a40; }
        .text-muted { font-size: 13px; font-weight: 500; }
        .badge-custom { font-size: 12px; padding: 5px 10px; }
        .table-custom { margin-bottom: 0; border-collapse: separate; border-spacing: 0; }
        .table-custom thead th {
            background: #f8f9fa;
            font-weight: 600;
            color: #343a40;
            border-bottom: 2px solid #dee2e6;
        }
        .table-custom tbody tr { transition: background-color 0.3s ease; }
        .table-custom tbody tr:hover { background-color: #f1f3f5; }
        .table-custom td, .table-custom th { vertical-align: middle; padding: 12px 15px; }
        .progress-custom { height: 20px; border-radius: 10px; background: #e9ecef; overflow: hidden; }
        .progress-bar-custom { background: linear-gradient(90deg, #5156be, #34c38f); font-weight: 600; font-size: 12px; }
        .table-custom tfoot th { background: #f8f9fa; font-weight: 700; color: #343a40; }
        .btn-primary.rounded-circle, .btn-success.rounded-circle { transition: all 0.3s ease; }
        .btn-primary.rounded-circle:hover, .btn-success.rounded-circle:hover {
            background-color: #0d6efd;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transform: scale(1.1);
        }
    </style>
@endsection
