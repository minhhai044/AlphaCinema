@extends('admin.layouts.master')
@section('title', 'Thống kê')

@section('content')
    <div class="container-fluid mt-4"> 
        <h3 class="mb-4">Thống kê phim</h3>

        <form method="GET" action="{{ route('admin.statistical.cinemaRevenue') }}">
            <div class="row align-items-center">
                <div class="col-md-2 mb-3"> 
                    <label>Chi nhánh:</label>
                    <select name="branch_id" class="form-control">
                        <option value="">Tất cả</option>
                    </select>
                </div>

               
                <div class="col-md-2 mb-3"> 
                    <label>Rạp:</label>
                    <select name="cinema_id" class="form-control">
                        <option value="">Tất cả</option>
                    </select>
                </div>

                
                <div class="col-md-2 mb-3">
                    <label>Ngày bắt đầu:</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
                </div>

                
                <div class="col-md-2 mb-3"> 
                    <label>Ngày kết thúc:</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
                </div>

                
                <div class="col-md-2 mb-3">
                    <button class="btn btn-success" type="submit">
                        <i class="bi bi-sliders2"></i>Lọc
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
            <h2 class="mb-3">Top 5 Món Bán Chạy Nhất</h2>
            <table class="table align-middle table-nowrap dt-responsive nowrap w-100">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Tên Món</th>
                        <th>Số Lượng Bán</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- @foreach($top5Items as $index => $item) --}}
                        <tr>
                            <td>a</td>
                            <td>a</td>
                            <td>a</td>
                        </tr>
                    {{-- @endforeach --}}
                </tbody>
            </table>
        </div>

    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById('revenueChart').getContext('2d');
            var revenueData = @json($revenues);

            
            if (revenueData && revenueData.length > 0) {
                var labels = revenueData.map(function(item) {
                    return item.date;
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
                            borderWidth: 1
                        }]
                    },
                });

            } else {
                alert("Không có dữ liệu để hiển thị biểu đồ.");
            }
        });
    </script>
@endsection
