@extends('admin.layouts.master')
@section('title', 'Thống kê')

@section('content')
    <div class="container-fluid mt-4">
        <h3 class="mb-4">Thống kê phim</h3>

        <form method="GET" action="{{ route('admin.statistical.cinemaRevenue') }}" id="filterForm">
    <div class="row g-3 align-items-end mb-4">
        <!-- Chi nhánh -->
        <div class="col-md-3 col-sm-6">
            <label for="branch_id" class="form-label fw-bold text-muted">
                <i class="bi bi-geo-alt-fill me-1"></i> Chi nhánh
            </label>
            <select name="branch_id" id="branch_id" class="form-select shadow-sm">
                <option value="">Tất cả chi nhánh</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                        {{ $branch->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Ngày bắt đầu -->
        <div class="col-md-2 col-sm-6">
            <label for="start_date" class="form-label fw-bold text-muted">
                <i class="bi bi-calendar-range me-1"></i> Ngày bắt đầu
            </label>
            <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                   class="form-control shadow-sm">
        </div>

        <!-- Ngày kết thúc -->
        <div class="col-md-2 col-sm-6">
            <label for="end_date" class="form-label fw-bold text-muted">
                <i class="bi bi-calendar-check me-1"></i> Ngày kết thúc
            </label>
            <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                   class="form-control shadow-sm">
        </div>

        <!-- Nút Lọc -->
        <div class="col-md-2 col-sm-6">
            <button type="submit" class="btn btn-success w-100 shadow-sm">
                <i class="bi bi-funnel-fill me-1"></i> Lọc dữ liệu
            </button>
        </div>

        <!-- Nút Reset -->
        <div class="col-md-2 col-sm-6">
            <a href="{{ route('admin.statistical.cinemaRevenue') }}" class="btn btn-outline-secondary w-100 shadow-sm">
                <i class="bi bi-arrow-repeat me-1"></i> Reset
            </a>
        </div>
    </div>
</form>

        <div class="card mt-4">
            @if($message)
                    <div class="alert alert-info text-center">
                        {{ $message }}
                    </div>
                @else
                    <canvas id="revenueChart" style="height: 400px; width: 100%;"></canvas>
                @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var ctx = document.getElementById('revenueChart').getContext('2d');
        var revenueData = @json($revenues);

        if (revenueData && revenueData.length > 0) {
            var labels = revenueData.map(item => item.movie_name);
            var revenueValues = revenueData.map(item => item.revenue);
            var ticketValues = revenueData.map(item => item.ticket_count);

            new Chart(ctx, {
                type: 'bar', // Loại mặc định là bar, nhưng sẽ kết hợp với line
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Doanh thu (VNĐ)',
                            data: revenueValues,
                            type: 'bar', // Cột cho doanh thu
                            backgroundColor: 'rgba(54, 162, 235, 0.7)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1,
                            yAxisID: 'y-revenue',
                            barThickness: 20, // Độ dày cột
                            borderRadius: 5 // Bo góc cột
                        },
                        {
                            label: 'Số vé',
                            data: ticketValues,
                            type: 'line', // Đường cho số vé
                            fill: false,
                            backgroundColor: 'rgba(255, 108, 55, 1)', // Màu điểm
                            borderColor: 'rgba(255, 108, 55, 1)', // Màu đường
                            borderWidth: 2,
                            tension: 0.3, // Độ cong của đường
                            yAxisID: 'y-tickets',
                            pointRadius: 5, // Kích thước điểm
                            pointHoverRadius: 8 // Kích thước điểm khi hover
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        'y-revenue': {
                            type: 'linear',
                            position: 'left',
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Doanh thu (VNĐ)',
                                font: { size: 14 }
                            },
                            ticks: {
                                callback: value => value.toLocaleString('vi-VN') + ' VNĐ',
                                color: '#333'
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        },
                        'y-tickets': {
                            type: 'linear',
                            position: 'right',
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Số vé',
                                font: { size: 14 }
                            },
                            ticks: {
                                callback: value => value.toLocaleString('vi-VN'),
                                color: '#333'
                            },
                            grid: {
                                drawOnChartArea: false
                            }
                        },
                        x: {
                            ticks: {
                                autoSkip: false,
                                maxRotation: 45,
                                minRotation: 45,
                                font: { size: 12 },
                                color: '#333'
                            },
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Thống Kê Doanh Thu và Số Vé Theo Phim',
                            font: { size: 18, weight: 'bold' },
                            padding: 20,
                            color: '#333'
                        },
                        legend: {
                            position: 'top',
                            labels: {
                                font: { size: 14 },
                                padding: 10,
                                usePointStyle: true // Hiển thị điểm thay vì hộp
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleFont: { size: 14 },
                            bodyFont: { size: 12 },
                            padding: 10
                        }
                    },
                    interaction: {
                        mode: 'index',
                        intersect: false
                    }
                }
            });
        } else {
            ctx.canvas.parentNode.innerHTML = '<div class="alert alert-info text-center my-4">Không có dữ liệu để hiển thị biểu đồ.</div>';
        }
    });
</script>
@endsection
