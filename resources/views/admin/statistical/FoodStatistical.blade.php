@extends('admin.layouts.master')

@section('title', 'Thống kê Đồ ăn')

@section('content')
    <div class="container-fluid mt-4">
        <h3 class="mb-4">Thống kê Đồ ăn</h3>

        {{-- Form lọc --}}
        <form method="GET" action="{{ route('admin.statistical.foodRevenue') }}">
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

        <div class="card">
            <div class="card-body">
                <canvas id="revenueChart" style="height: 400px; width: 100%;"></canvas>
            </div>
        </div>

        <div class="mt-4">
            <h4 class="mb-3 text-bg-light">Top 5 doanh thu cao nhất</h4>
            <table class="table align-middle table-nowrap dt-responsive nowrap w-100 text-center">
                <thead class="">
                    <tr>
                        <th>#</th>
                        <th>Tên Đồ Ăn</th>
                        <th>Tổng doanh thu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($foodStatistics as $index => $food)
                        <tr >
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $food->food_name }}</td>
                            <td>Lượt bán {{ $food->total_quantity }} - {{ number_format($food->total_price, 0) }} VND </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById('revenueChart').getContext('2d');
            var foodNames = @json($foodNames); // Tên đồ ăn
            var foodQuantities = @json($foodQuantities); // Số lượng bán
            var foodSummaries = @json($foodSummaries); // Chuỗi "X lượt - Y VND"

            if (foodNames.length > 0 && foodQuantities.length > 0) {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: foodNames, // Tên đồ ăn làm nhãn trục X
                        datasets: [{
                            label: 'Số lượng bán',
                            data: foodQuantities, // Số lượng bán làm dữ liệu
                            backgroundColor: 'rgba(54, 162, 235, 0.5)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Số lượng'
                                },
                                ticks: {
                                    stepSize: 1, // Bước nhảy là 1, chỉ hiển thị số nguyên
                                    callback: function(value) {
                                        if (Number.isInteger(value)) {
                                            return value; // Chỉ hiển thị số nguyên
                                        }
                                    }
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Tên Đồ Ăn'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        const index = tooltipItem.dataIndex;
                                        return `${foodNames[index]}: ${foodSummaries[index]}`;
                                    }
                                }
                            }
                        }
                    }
                });
            } else {
                alert("Không có dữ liệu để hiển thị biểu đồ.");
            }
        });
    </script>
@endsection