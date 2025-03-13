@extends('admin.layouts.master')
@section('title', 'Thống kê')

@section('content')
    <div class="container-fluid mt-4">
        <h3 class="mb-4">Thống kê phim</h3>

        <form method="GET" action="{{ route('admin.statistical.cinemaRevenue') }}" id="filterForm">
            <div class="row align-items-end g-3">
                <div class="col-md-3 col-sm-6">
                    <label for="branch_id" class="form-label">Chi nhánh:</label>
                    <select name="branch_id" id="branch_id" class="form-select">
                        <option value="">Tất cả</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                {{-- <div class="col-md-3 col-sm-6">
                    <label for="cinema_id" class="form-label">Rạp:</label>
                    <select name="cinema_id" id="cinema_id" class="form-select">
                        <option value="">Tất cả</option>
                        @foreach ($cinemas as $cinema)
                            <option value="{{ $cinema->id }}" {{ request('cinema_id') == $cinema->id ? 'selected' : '' }}>
                                {{ $cinema->name }}
                            </option>
                        @endforeach
                    </select>
                </div> --}}
                <div class="col-md-2 col-sm-6">
                    <label for="start_date" class="form-label">Ngày bắt đầu:</label>
                    <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                        class="form-control">
                </div>
                <div class="col-md-2 col-sm-6">
                    <label for="end_date" class="form-label">Ngày kết thúc:</label>
                    <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                        class="form-control">
                </div>
                <div class="col-md-2 col-sm-6">
                    <button class="btn btn-success w-100" type="submit">
                        <i class="bi bi-sliders2"></i> Lọc
                    </button>
                </div>
            </div>
        </form>

        <div class="row">
            <div class="card col-md-6">
                <div class="card-body">
                    <canvas id="revenueChartmoive" style="height: 400px; width: 100%;"></canvas>
                </div>
            </div>
             <div class="card col-md-6">
                <div class="card-body">
                    <canvas id="revenueChart" style="height: 400px; width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById('revenueChartmoive').getContext('2d');
            var revenueData = @json($revenues);

            if (revenueData && revenueData.length > 0) {
                var labels = revenueData.map(function(item) {
                    return item.movie_name; // Thay date bằng movie_title
                });
                var values = revenueData.map(function(item) {
                    return item.revenue;
                });

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Doanh thu (VNĐ)',
                            data: values,
                            backgroundColor: 'rgba(54, 162, 235, 0.5)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 0.3
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return value.toLocaleString('vi-VN') + ' VNĐ';
                                    }
                                }
                            },
                            x: {
                                ticks: {
                                    autoSkip: false, // Đảm bảo tất cả tên phim được hiển thị
                                    maxRotation: 45, // Xoay nhãn nếu quá dài
                                    minRotation: 45
                                }
                            }
                        },
                        plugins: {
                            title: {
                                display: true,
                                text: 'Doanh Thu Theo Phim'
                            }
                        }
                    }
                });
            } else {
                ctx.canvas.style.display = 'none';
                alert("Không có dữ liệu để hiển thị biểu đồ.");
            }
        });
    </script>


 <script>
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById('revenueChart').getContext('2d');
            var revenueData = @json($revenuesx);

            if (revenueData && revenueData.length > 0) {
                var labels = revenueData.map(function(item) {
                    return item.movie_name;
                });
                var values = revenueData.map(function(item) {
                    return item.ticket_count;
                });

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Số vé',
                            data: values,
                            backgroundColor: 'rgba(255, 108, 55, 0.5)',
                            borderColor: 'rgba(255, 108, 55, 1)',
                            borderWidth: 0.3
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return value.toLocaleString('vi-VN');
                                    }
                                }
                            },
                            x: {
                                ticks: {
                                    autoSkip: false,
                                    maxRotation: 45,
                                    minRotation: 45
                                }
                            }
                        },
                        plugins: {
                            title: {
                                display: true,
                                text: 'Số Vé Bán Theo Phim'
                            }
                        }
                    }
                });
            } else {
                ctx.canvas.style.display = 'none';
                alert("Không có dữ liệu để hiển thị biểu đồ.");
            }
        });
    </script>
@endsection
