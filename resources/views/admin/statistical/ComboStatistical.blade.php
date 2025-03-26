@extends('admin.layouts.master')

@section('title', 'Thống kê Combo')

@section('content')
    <div class="container-fluid mt-4">
        <h3 class="mb-4">Thống kê Combo</h3>

        {{-- Form lọc --}}
        <form method="GET" action="{{ route('admin.statistical.comboRevenue') }}" class="mb-4">
            <div class="row align-items-end g-3">
                <div class="col-md-2 col-sm-6">
                    <label class="form-label">Chi nhánh:</label>
                    <select name="branch_id" class="form-control">
                        <option value="">Tất cả</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}" {{ $branchId == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 col-sm-6">
                    <label class="form-label">Ngày bắt đầu:</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="form-control">
                </div>
                <div class="col-md-2 col-sm-6">
                    <label class="form-label">Ngày kết thúc:</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="form-control">
                </div>
                <div class="col-md-2 col-sm-6">
                    <button class="btn btn-success w-100" type="submit">
                        <i class="bi bi-sliders2"></i> Lọc
                    </button>
                </div>
            </div>
        </form>
        <!-- Hàng 1: Tổng doanh thu và Tỷ lệ combo -->
        <div class="row g-4 mb-4 mt-2">
            <!-- Tổng doanh thu Combo -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card card-h-100">
                        <div class="card-body">
                            <h5 class="card-title text-center">Tổng doanh thu Combo</h5>
                            <canvas id="topComboChart" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-center">Doanh thu Combo theo khung giờ</h5>
                        <canvas id="stackedBarChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
            <!-- Tỷ lệ đơn hàng có Combo -->
            <div class="col-xl-6">
                <div class="card card-h-100">
                    <div class="card-body">
                        <h5 class="card-title text-center">Tỷ lệ đơn hàng có Combo</h5>
                        <canvas id="comboUsageChart" style="height: 200px; max-width: 300px; margin: 0 auto;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hàng 4: Bảng Top 5 doanh thu -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-center mb-3">Top 5 Combo doanh thu cao nhất</h5>
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Tên Combo</th>
                                    <th>Số Lượng Bán</th>
                                    <th>Tổng Doanh Thu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($comboStatistics as $index => $combo)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $combo->combo_name }}</td>
                                        <td>{{ $combo->total_quantity }}</td>
                                        <td>{{ number_format($combo->total_price, 0) }} VND</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Biểu đồ doanh thu -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var comboRevenue = @json($comboRevenue);
            var comboNames = @json($comboNames);
            var comboQuantities = @json($comboQuantities);
            var comboRevenues = @json($comboRevenues);
            var trendDates = @json($trendDates);
            var trendRevenues = @json($trendRevenues);
            var comboUsage = @json($comboUsage);

            // 1. Tổng doanh thu Combo
            var revenueCtx = document.getElementById('comboRevenueChart').getContext('2d');
            new Chart(revenueCtx, {
                type: 'bar',
                data: {
                    labels: ['Tổng doanh thu'],
                    datasets: [{
                        label: 'Doanh thu (VND)',
                        data: [comboRevenue],
                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                        barThickness: 50, // Giới hạn chiều rộng cột
                    }]
                },
                options: {
                    // responsive: true,
                    // maintainAspectRatio: false, // Tắt tỷ lệ cố định
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Doanh thu (VND)'
                            }
                        },
                        x: {
                            ticks: {
                                autoSkip: false
                            } // Đảm bảo nhãn không bị bỏ qua
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        } // Ẩn legend vì không cần thiết
                    }
                }
            });

            // 2. Doanh thu Combo theo khung giờ
            var usageCtx = document.getElementById('comboUsageChart').getContext('2d');
            new Chart(usageCtx, {
                type: 'pie',
                data: {
                    labels: ['Có Combo', 'Không có Combo'],
                    datasets: [{
                        data: [comboUsage, 100 - comboUsage],
                        backgroundColor: ['rgba(255, 206, 86, 0.5)', 'rgba(150, 150, 150, 0.5)'],
                        borderColor: ['rgba(255, 206, 86, 1)', 'rgba(150, 150, 150, 1)'],
                        borderWidth: 1
                    }]
                },
                options: {
                    // responsive: true,
                    // maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return `${tooltipItem.label}: ${tooltipItem.raw}%`;
                                }
                            }
                        }
                    }
                }
            });
        });

        // 3. Doanh thu Combo theo khung giờ
        const ctxStacked = document.getElementById('stackedBarChart').getContext('2d');
        const stackedBarChart = new Chart(ctxStacked, {
            type: 'bar',
            data: {
                labels: ['11:42', '13:42', '16:42', '18:42'],
                datasets: [{
                        label: 'Combo',
                        data: [360000, 0, 390000, 744000],
                        backgroundColor: '#36A2EB'
                    },
                ]
            },
            options: {
                scales: {
                    x: {
                        stacked: true,
                        title: {
                            display: true,
                            text: 'Khung giờ suất chiếu'
                        }
                    },
                    y: {
                        stacked: true,
                        title: {
                            display: true,
                            text: 'Doanh thu (VND)'
                        }
                    }
                }
            }
        });
    </script>

    <style>
        .card {
            border-radius: 10px;
            overflow: hidden;
        }

        .card-title {
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }

        .table {
            margin-bottom: 0;
        }

        .card-header-custom {
            background: linear-gradient(135deg, #5156be 0%, #34c38f 100%);
            color: white;
            padding: 10px 15px;
            border-radius: 10px 10px 0 0;
            font-weight: 600;
            font-size: 14px;
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
