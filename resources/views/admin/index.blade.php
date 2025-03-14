@extends('admin.layouts.master')
@section('content')
    <!-- Start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
    <h4 class="mb-0 font-size-18 text-primary fw-bold">
        <i class="bi bi-bar-chart-line me-2"></i> Thống kê trên toàn hệ thống
    </h4>
    <form method="GET" action="" class="d-flex align-items-center gap-2">
        <!-- Bộ lọc rạp -->
        <div class="input-group input-group-sm">
            <span class="input-group-text bg-light text-muted border-0">
                <i class="bi bi-geo-alt-fill"></i>
            </span>
            <select name="branch" class="form-select border-0 shadow-sm" onchange="this.form.submit()">
                <option value="all" {{ $branch == 'all' ? 'selected' : '' }}>Tất cả rạp</option>
                @foreach ($cinemas as $cinema)
                    <option value="{{ $cinema->id }}" {{ $branch == $cinema->id ? 'selected' : '' }}>
                        {{ $cinema->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Bộ lọc tháng -->
        <div class="input-group input-group-sm">
            <span class="input-group-text bg-light text-muted border-0">
                <i class="bi bi-calendar-month"></i>
            </span>
            <select name="month" class="form-select border-0 shadow-sm" onchange="this.form.submit()">
                @for ($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ $selectedMonth == $i ? 'selected' : '' }}>
                        Tháng {{ $i }}</option>
                @endfor
            </select>
        </div>

        <!-- Bộ lọc năm -->
        <div class="input-group input-group-sm">
            <span class="input-group-text bg-light text-muted border-0">
                <i class="bi bi-calendar4"></i>
            </span>
            <select name="year" class="form-select border-0 shadow-sm" onchange="this.form.submit()">
                @for ($i = 2020; $i <= Carbon\Carbon::now()->year; $i++)
                    <option value="{{ $i }}" {{ $selectedYear == $i ? 'selected' : '' }}>
                        Năm {{ $i }}</option>
                @endfor
            </select>
        </div>

        <!-- Nút reset cải tiến -->
        <button type="button" class="btn btn-sm btn-primary rounded-circle p-2 d-flex align-items-center justify-content-center"
                onclick="resetFilters()" title="Reset bộ lọc" style="width: 36px; height: 36px;">
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

    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card card-h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-7">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Doanh thu Tháng
                                {{ $selectedMonth }}</span>
                            <h4 class="mb-3">
                                <span class="counter-value"
                                    data-target="{{ $totalRevenue }}">{{ $formattedRevenue }}</span> VND
                            </h4>
                        </div>
                        <div class="col-5">
                            <div id="mini-chart1" data-colors='["#5156be"]' class="apex-charts mb-2"></div>
                        </div>
                    </div>
                    <div class="text-nowrap">
                        @if ($revenueChange < 0)
                            <span class="badge bg-danger-subtle text-danger"><i class="bi bi-arrow-down"></i>
                                {{ abs(round($revenueChange, 1)) }}%</span>
                        @else
                            <span class="badge bg-success-subtle text-success"><i class="bi bi-arrow-up"></i>
                                {{ round($revenueChange, 1) }}%</span>
                        @endif
                        <span class="ms-1 text-muted font-size-13">So với tháng trước</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Số vé bán ra Tháng
                                {{ $selectedMonth }}</span>
                            <h4 class="mb-3">
                                <span class="counter-value" data-target="{{ $ticketCount }}">{{ $ticketCount }}</span>
                                VÉ
                            </h4>
                        </div>
                        <div class="col-6">
                            <div id="mini-chart2" data-colors='["#5156be"]' class="apex-charts mb-2"></div>
                        </div>
                    </div>
                    <div class="text-nowrap">
                        <span class="badge bg-success-subtle text-success"><i class="bi bi-arrow-up"></i> 0%</span>
                        <span class="ms-1 text-muted font-size-13">So với tháng trước</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Khách hàng mới</span>
                            <h4 class="mb-3">
                                <span class="counter-value" data-target="0">0</span>
                            </h4>
                        </div>
                        <div class="col-6">
                            <div id="mini-chart3" data-colors='["#5156be"]' class="apex-charts mb-2"></div>
                        </div>
                    </div>
                    <div class="text-nowrap">
                        <span class="badge bg-success-subtle text-success"><i class="bi bi-arrow-up"></i> 0%</span>
                        <span class="ms-1 text-muted font-size-13">So với tháng trước</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Suất chiếu</span>
                            <h4 class="mb-3">
                                <span class="counter-value" data-target="0">0</span>
                            </h4>
                        </div>
                        <div class="col-6">
                            <div id="mini-chart4" data-colors='["#5156be"]' class="apex-charts mb-2"></div>
                        </div>
                    </div>
                    <div class="text-nowrap">
                        <span class="badge bg-success-subtle text-success"><i class="bi bi-arrow-up"></i> 0%</span>
                        <span class="ms-1 text-muted font-size-13">So với tháng trước</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4">
            <div class="card card-h-100">
                <div class="card-body">
                    <h5 class="card-title">Tỷ lệ đặt ghế - Tháng {{ $selectedMonth }}</h5>
                    <div id="seat-booking-chart"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card card-h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title">Doanh thu - Năm {{ $selectedYear }}</h5>
                        <select id="yearSelect" class="form-select w-auto" onchange="updateRevenueChart(this.value)">
                            @for ($i = 2020; $i <= Carbon\Carbon::now()->year; $i++)
                                <option value="{{ $i }}" {{ $selectedYear == $i ? 'selected' : '' }}>
                                    Năm {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div id="revenue-chart"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bảng doanh thu theo rạp -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Doanh thu theo rạp - Tháng {{ $selectedMonth }}/{{ $selectedYear }}</h5>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle">
                            <thead class="table-light">
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
                                        <td>{{ number_format(json_decode($revenueSeriesJson)[$index] * 1000000, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            @php
                                                $revenue = json_decode($revenueSeriesJson)[$index] * 1000000;
                                                $percentage =
                                                    $totalRevenueForTable > 0
                                                        ? ($revenue / $totalRevenueForTable) * 100
                                                        : 0;
                                            @endphp
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar bg-success" role="progressbar"
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
                                        <td colspan="4" class="text-center">Không có dữ liệu</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="2">Tổng cộng</th>
                                    <th>{{$totalRevenueForTable }}</th>
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
            chart: {
                type: 'donut',
                height: 300
            },
            labels: {!! $seatLabels !!},
            colors: ['#2C91F7', '#2EE5AC', '#FF5733'],
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return val.toFixed(1) + "%";
                },
                style: {
                    fontSize: '12px',
                    fontWeight: 'bold'
                }
            },
            legend: {
                position: 'bottom',
                markers: {
                    width: 10,
                    height: 10,
                    radius: 10
                },
                fontSize: '12px'
            },
            tooltip: {
                enabled: true,
                y: {
                    formatter: function(val) {
                        return val.toFixed(1) + "%";
                    }
                }
            }
        };
        var seatChart = new ApexCharts(document.querySelector("#seat-booking-chart"), seatOptions);
        seatChart.render();
    </script>

    <!-- Biểu đồ doanh thu -->
    <script>
        const revenueData = {!! $revenueDataJson !!};
        var revenueOptions = {
            series: [{
                name: "Doanh thu (Triệu VND)",
                data: revenueData["{{ $selectedYear }}"]
            }],
            chart: {
                type: 'bar',
                height: 350
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%'
                }
            },
            stroke: {
                width: 2,
                curve: 'smooth'
            },
            xaxis: {
                categories: ["Tháng {{ $selectedMonth }}"]
            },
            yaxis: {
                labels: {
                    formatter: function(value) {
                        return value + " triệu VND";
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val + " triệu VND";
                    }
                }
            },
            colors: ['#5156be']
        };
        var revenueChart = new ApexCharts(document.querySelector("#revenue-chart"), revenueOptions);
        revenueChart.render();

        function updateRevenueChart(year) {
            revenueChart.updateSeries([{
                name: "Doanh thu (Triệu VND)",
                data: revenueData[year]
            }]);
            const url = new URL(window.location);
            url.searchParams.set('year', year);
            window.history.pushState({}, '', url);
        }
    </script>

    <!-- Animation cho counter -->
    <script>
        $('.counter-value').each(function() {
            var $this = $(this);
            var target = parseFloat($this.data('target')) || 0;
            $this.prop('Counter', 0).animate({
                Counter: target
            }, {
                duration: 2000,
                easing: 'swing',
                step: function(now) {
                    $this.text(Math.ceil(now).toLocaleString('vi-VN'));
                },
                complete: function() {
                    $this.text(target.toLocaleString('vi-VN'));
                }
            });
        });

        function resetFilters() {
            const url = new URL(window.location);
            url.searchParams.delete('branch');
            url.searchParams.delete('month');
            url.searchParams.delete('year');
            window.location.href = url;
        }
    </script>
@endsection

@section('style')
<style>
    .btn-primary.rounded-circle {
        transition: all 0.3s ease;
    }
    .btn-primary.rounded-circle:hover {
        background-color: #0d6efd; /* Màu xanh đậm hơn khi hover */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transform: scale(1.1); /* Phóng to nhẹ khi hover */
    }
</style>
@endsection
