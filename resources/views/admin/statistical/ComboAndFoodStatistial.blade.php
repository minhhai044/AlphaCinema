@extends('admin.layouts.master')

@section('title', 'Thống kê Combo và Đồ ăn')

@section('style-libs')
    <!-- ApexCharts CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.41.1/apexcharts.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="container-fluid mt-4">
        <h3 class="mb-4">Thống kê Combo và Đồ ăn</h3>

        {{-- Form lọc --}}
        <form method="GET" action="{{ route('admin.statistical.combAndFoodRevenue') }}">
            <div class="row align-items-center">
                <div class="col-md-2 mb-3">
                    <label>Chi nhánh:</label>
                    <select name="branch_id" class="form-control">
                        <option value="">Tất cả</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}" {{ $branchId == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 mb-3">
                    <label>Ngày bắt đầu:</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="form-control">
                </div>

                <div class="col-md-2 mb-3">
                    <label>Ngày kết thúc:</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="form-control">
                </div>

                <div class="col-md-2 d-flex mt-3 py-4">
                    <button class="btn btn-success" type="submit">
                        <i class="bi bi-sliders2"></i> Lọc
                    </button>
                </div>
            </div>
        </form>

        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Combo</h4>

                        <div class="row text-center">
                            <div class="col-4">
                                <h5 class="mb-0">{{ array_sum(array_column($comboStatistics, 'total_quantity')) }}</h5>
                                <p class="text-muted text-truncate">Tổng số lượng</p>
                            </div>
                            <div class="col-4">
                                <h5 class="mb-0">{{ number_format(array_sum($comboRevenue), 0) }} VND</h5>
                                <p class="text-muted text-truncate">Doanh thu</p>
                            </div>
                            <div class="col-4">
                                <h5 class="mb-0">{{ count($comboStatistics) }}</h5>
                                <p class="text-muted text-truncate">Số loại combo</p>
                            </div>
                        </div>

                        <div id="distributed-column-combo" class="apex-charts" data-colors="#727cf5" style="height: 395px;">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 mt-3">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Đồ ăn</h4>

                        <div class="row text-center">
                            <div class="col-4">
                                <h5 class="mb-0">{{ array_sum(array_column($foodStatistics, 'total_quantity')) }}</h5>
                                <p class="text-muted text-truncate">Tổng số lượng</p>
                            </div>
                            <div class="col-4">
                                <h5 class="mb-0">{{ number_format(array_sum($foodRevenue), 0) }} VND</h5>
                                <p class="text-muted text-truncate">Doanh thu</p>
                            </div>
                            <div class="col-4">
                                <h5 class="mb-0">{{ count($foodStatistics) }}</h5>
                                <p class="text-muted text-truncate">Số loại đồ ăn</p>
                            </div>
                        </div>

                        <div id="distributed-column-food" class="apex-charts" data-colors="#39afd1" style="height: 395px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- ApexCharts CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.41.1/apexcharts.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const comboChartElement = document.getElementById("distributed-column-combo");
            const foodChartElement = document.getElementById("distributed-column-food");

            // Dữ liệu từ controller
            let comboQuantities = @json($comboQuantities); // Số lượng
            let comboNames = @json($comboNames);
            let comboSummaries = @json($comboSummaries); // Summary cho tooltip
            let foodQuantities = @json($foodQuantities); // Số lượng
            let foodNames = @json($foodNames);
            let foodSummaries = @json($foodSummaries); // Summary cho tooltip

            let colorsCombo = comboChartElement.getAttribute("data-colors")?.split(",") || ["#727cf5"];
            let colorsFood = foodChartElement.getAttribute("data-colors")?.split(",") || ["#39afd1"];

            function createChartCombo(element, colors, quantities, names, summaries) {
                const options = {
                    chart: { height: 395, type: 'bar' },
                    plotOptions: {
                        bar: {
                            distributed: true,
                            horizontal: false,
                            dataLabels: { position: 'top' }
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        offsetY: -10,
                        style: { fontSize: '12px', colors: ["#304758"] },
                        formatter: function (val, opt) {
                            return val; // Hiển thị số lượng trên cột
                        }
                    },
                    series: [{ name: 'Số lượng Combo', data: quantities }],
                    colors: colors,
                    xaxis: {
                        categories: names,
                        labels: { style: { fontSize: '14px' } }
                    },
                    yaxis: {
                        title: { text: 'Số lượng (lượt)' },
                        labels: { formatter: function (val) { return Math.round(val); } } // Đảm bảo số nguyên
                    },
                    tooltip: {
                        y: {
                            formatter: function (val, { dataPointIndex }) {
                                return summaries[dataPointIndex]; // Hiển thị summary trong tooltip
                            }
                        }
                    },
                    legend: { show: false }
                };
                const chart = new ApexCharts(element, options);
                chart.render();
            }

            function createChartFood(element, colors, quantities, names, summaries) {
                const options = {
                    chart: { height: 395, type: 'bar' },
                    plotOptions: {
                        bar: {
                            distributed: true,
                            horizontal: false,
                            dataLabels: { position: 'top' }
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        offsetY: -10,
                        style: { fontSize: '12px', colors: ["#304758"] },
                        formatter: function (val, opt) {
                            return val; // Hiển thị số lượng trên cột
                        }
                    },
                    series: [{ name: 'Số lượng Đồ ăn', data: quantities }],
                    colors: colors,
                    xaxis: {
                        categories: names,
                        labels: { style: { fontSize: '14px' } }
                    },
                    yaxis: {
                        title: { text: 'Số lượng (lượt)' },
                        labels: { formatter: function (val) { return Math.round(val); } } // Đảm bảo số nguyên
                    },
                    tooltip: {
                        y: {
                            formatter: function (val, { dataPointIndex }) {
                                return summaries[dataPointIndex]; // Hiển thị summary trong tooltip
                            }
                        }
                    },
                    legend: { show: false }
                };
                const chart = new ApexCharts(element, options);
                chart.render();
            }

            createChartCombo(comboChartElement, colorsCombo, comboQuantities, comboNames, comboSummaries);
            createChartFood(foodChartElement, colorsFood, foodQuantities, foodNames, foodSummaries);
        });
    </script>
@endsection