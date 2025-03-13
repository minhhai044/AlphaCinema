@extends('admin.layouts.master')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <!-- Thêm container chứa tiêu đề và dropdown -->
                <div class="d-flex align-items-center">
                    <h4 class="mb-sm-0 font-size-18 me-3">Thống kê trên toàn hệ thống</h4>

                    <!-- Dropdown lọc chi nhánh -->
                    <select id="branchSelect" class="form-select form-select-sm w-auto"
                        onchange="window.location.href='?branch='+this.value">
                        <option value="all" {{ $branch == 'all' ? 'selected' : '' }}>Tất cả</option>
                        @foreach ($cinemas as $cinema)
                            <option value="{{ $cinema->id }}" {{ $branch == $cinema->id ? 'selected' : '' }}>
                                {{ $cinema->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Thống kê</a></li>
                        <li class="breadcrumb-item active">Thống kê</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-h-100">
                <!-- card body -->
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-7">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Doanh thu Tháng
                                {{ $monthnow }}</span>
                            <h4 class="mb-6">
                                <span class="counter-value"
                                    data-target="{{ $totalRevenue }}">{{ $formattedRevenue }}</span>VND
                            </h4>
                        </div>

                        <div class="col-5">
                            <div id="mini-chart1" data-colors='["#5156be"]' class="apex-charts mb-2">
                            </div>
                        </div>
                    </div>
                    <div class="text-nowrap">
                        {{-- Nếu giảm thì dùng cái text-danger --}}
                        <span class="badge bg-danger-subtle text-danger"><i class="bi bi-arrow-up"></i> 0%</span>
                        {{-- Nếu tăng thì dùng cái text-success --}}
                        <span class="badge bg-success-subtle text-success"><i class="bi bi-arrow-up"></i> 0%</span>

                        <span class="ms-1 text-muted font-size-13">So với tháng trước</span>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-h-100">
                <!-- card body -->
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Số vé bán ra</span>
                            <h4 class="mb-3">
                                <span class="counter-value" data-target="{{ $ticketCount }}">{{ $ticketCount }}</span> VÉ
                            </h4>
                        </div>
                        <div class="col-6">
                            <div id="mini-chart2" data-colors='["#5156be"]' class="apex-charts mb-2">
                            </div>
                        </div>
                    </div>
                    <div class="text-nowrap">
                        {{-- Nếu giảm thì dùng cái text-danger --}}
                        <span class="badge bg-danger-subtle text-danger"><i class="bi bi-arrow-up"></i> 0%</span>
                        {{-- Nếu tăng thì dùng cái text-success --}}
                        <span class="badge bg-success-subtle text-success"><i class="bi bi-arrow-up"></i> 0%</span>

                        <span class="ms-1 text-muted font-size-13">So với tháng trước</span>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col-->

        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-h-100">
                <!-- card body -->
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Khách hàng mới</span>
                            <h4 class="mb-3">
                                <span class="counter-value" data-target="">0</span>
                            </h4>
                        </div>
                        <div class="col-6">
                            <div id="mini-chart3" data-colors='["#5156be"]' class="apex-charts mb-2">
                            </div>
                        </div>
                    </div>
                    <div class="text-nowrap">
                        {{-- Nếu giảm thì dùng cái text-danger --}}
                        <span class="badge bg-danger-subtle text-danger"><i class="bi bi-arrow-up"></i> 0%</span>
                        {{-- Nếu tăng thì dùng cái text-success --}}
                        <span class="badge bg-success-subtle text-success"><i class="bi bi-arrow-up"></i> 0%</span>
                        <span class="ms-1 text-muted font-size-13">So với tháng trước</span>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-h-100">
                <!-- card body -->
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Suất chiếu</span>
                            <h4 class="mb-3">
                                <span class="counter-value" data-target="">0</span>
                            </h4>
                        </div>
                        <div class="col-6">
                            <div id="mini-chart4" data-colors='["#5156be"]' class="apex-charts mb-2">
                            </div>
                        </div>
                    </div>
                    <div class="text-nowrap">
                        {{-- Nếu giảm thì dùng cái text-danger --}}
                        <span class="badge bg-danger-subtle text-danger"><i class="bi bi-arrow-up"></i> 0%</span>
                        {{-- Nếu tăng thì dùng cái text-success --}}
                        <span class="badge bg-success-subtle text-success"><i class="bi bi-arrow-up"></i> 0%</span>
                        <span class="ms-1 text-muted font-size-13">So với tháng trước</span>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->
    </div><!-- end row-->

    <div class="row">
        <div class="col-xl-4">
            <div class="card card-h-100">
                <div class="card-body">
                    <h5 class="card-title">Tỷ lệ đặt ghế</h5>
                    <div id="seat-booking-chart"></div>
                </div>
            </div>
        </div>

        {{-- <div class="col-xl-8">
            <div class="card card-h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title">Doanh thu</h5>
                        <select id="yearSelect" class="form-select w-auto">
                            <option value="2025" selected>Năm 2025</option>
                            <option value="2024">Năm 2024</option>
                        </select>
                    </div>
                    <div id="revenue-chart"></div>
                </div>
            </div>
        </div> --}}
        <div class="col-xl-8">
            <div class="card card-h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title">Doanh thu</h5>
                        <select id="yearSelect" class="form-select w-auto">
                            <option value="2025" selected>Năm 2025</option>
                            <option value="2024">Năm 2024</option>
                        </select>
                    </div>
                    <div id="revenue-chart"></div>
                </div>
            </div>
        </div>
    </div>
    </div> <!-- end row-->
@endsection

@section('script')
    <!-- dashboard init -->
    <script src="{{ asset('theme/admin/assets/js/pages/dashboard.init.js') }}"></script>

    {{-- cột phần trăm --}}
    <script>
        var options = {
            series: {!! $seatSeries !!}, // Dữ liệu tỷ lệ phần trăm từ controller
            chart: {
                type: 'donut',
            },
            labels: {!! $seatLabels !!}, // Dữ liệu nhãn từ controller
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

        var chart = new ApexCharts(document.querySelector("#seat-booking-chart"), options);
        chart.render();
    </script>


    {{-- Biểu đồ doanh thu --}}
    <script>
    // Sử dụng dữ liệu động từ controller thay vì dữ liệu cứng
    const revenueData = {!! $revenueDataJson !!};

    document.addEventListener("DOMContentLoaded", function() {
        var options = {
            series: [{
                name: "Doanh thu (Triệu VND)",
                data: revenueData["{{ $selectedYear }}"]
            }],
            chart: {
                type: 'line',
                height: 350
            },
            stroke: {
                width: 2,
                curve: 'smooth'
            },
            xaxis: {
                categories: ["Tháng 1", "Tháng 2", "Tháng 3", "Tháng 4", "Tháng 5", "Tháng 6", "Tháng 7",
                    "Tháng 8", "Tháng 9", "Tháng 10", "Tháng 11", "Tháng 12"
                ]
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
            }
        };
        var chart = new ApexCharts(document.querySelector("#revenue-chart"), options);
        chart.render();
        document.getElementById("yearSelect").addEventListener("change", function() {
            var selectedYear = this.value;
            chart.updateSeries([{
                name: "Doanh thu (Triệu VND)",
                data: revenueData[selectedYear]
            }]);
        });
    });


        //định dạng và load
        $('.counter-value').each(function() {
            var $this = $(this);
            var target = parseFloat($this.data('target')); // Lấy số từ data-target
            if (isNaN(target)) target = 0; // Xử lý NaN
            $this.prop('Counter', 0).animate({
                Counter: target
            }, {
                duration: 2000,
                easing: 'swing',
                step: function(now) {
                    // Định dạng số với dấu chấm phân cách phần nghìn
                    $this.text(Math.ceil(now).toLocaleString('vi-VN'));
                },
                complete: function() {
                    // Đảm bảo giá trị cuối cùng đúng định dạng
                    $this.text(target.toLocaleString('vi-VN'));
                }
            });
        });
    </script>
@endsection
