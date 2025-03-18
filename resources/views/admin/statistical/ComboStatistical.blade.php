@extends('admin.layouts.master')

@section('title', 'Thống kê Combo')

@section('content')
    <div class="container-fluid mt-4">
        <h3 class="mb-4">Thống kê Combo</h3>

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
                <canvas id="comboChart" style="height: 400px; width: 100%;"></canvas>
            </div>
        </div>

        <div class="mt-4">
            <h4 class="mb-3 text-bg-light">Top 5 doanh thu cao nhất</h4>
            <table class="table align-middle table-nowrap dt-responsive nowrap w-100">
                <thead class="">
                    <tr>
                        <th>#</th>
                        <th>Tên Combo</th>
                        <th>Số Lượng Bán</th>
                        <th>Tổng Doanh Thu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($comboStatistics as $index => $combo)
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

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById('comboChart').getContext('2d');
            var comboNames = @json($comboNames);
            var comboQuantities = @json($comboQuantities);
            var comboSummaries = @json($comboSummaries);

            if (comboNames.length > 0 && comboQuantities.length > 0) {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: comboNames,
                        datasets: [{
                            label: 'Số lượng bán',
                            data: comboQuantities,
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
                                    stepSize: 1,
                                    callback: function(value) {
                                        if (Number.isInteger(value)) {
                                            return value;
                                        }
                                    }
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Tên Combo'
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
                                        return `${comboNames[index]}: ${comboSummaries[index]}`;
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