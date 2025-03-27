@extends('admin.layouts.master')
@section('title', 'Thống kê')

@section('content')
    <div class="container-fluid mt-4">
        <h3 class="mb-4">Thống kê phim</h3>

        <form method="GET" action="{{ route('admin.statistical.cinemaRevenue') }}"
      class="filter-row d-flex align-items-end gap-3 mb-4">
    <!-- Chi nhánh (Chỉ hiển thị dropdown cho System Admin) -->
    @if (auth()->user()->hasRole('System Admin'))
        <div class="form-group col-md-2">
            <select name="branch_id" class="form-select" id="branch_id">
                <option value="" {{ !$branchId ? 'selected' : '' }}>Chọn chi nhánh</option>
                @if ($branches && $branches->isNotEmpty())
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}"
                            {{ $branchId == $branch->id ? 'selected' : '' }}>
                            {{ $branch->name }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>
    @else
        @if (auth()->user()->branch_id)
            <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id }}">
            <div class="form-group col-md-2">
                <label class="form-label fw-bold">
                    <i class="bi bi-geo-alt me-1"></i> Chi nhánh
                </label>
                <p class="form-control-static">{{ auth()->user()->branch->name ?? 'N/A' }}</p>
            </div>
        @endif
    @endif

    <!-- Rạp (Hiển thị dropdown cho System Admin hoặc Quản lý chi nhánh) -->
    @if (auth()->user()->hasRole('System Admin') || auth()->user()->branch_id)
        <div class="form-group col-md-2">
            <select name="cinema_id" class="form-select" id="cinema_id">
                <option value="" {{ !$cinemaId ? 'selected' : '' }}>Chọn rạp</option>
                @if (auth()->user()->hasRole('System Admin') && $branchId && isset($branchesRelation[$branchId]))
                    @foreach ($branchesRelation[$branchId] as $id => $name)
                        <option value="{{ $id }}"
                            {{ $cinemaId == $id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                @elseif(auth()->user()->branch_id && isset($branchesRelation[auth()->user()->branch_id]))
                    @foreach ($branchesRelation[auth()->user()->branch_id] as $id => $name)
                        <option value="{{ $id }}"
                            {{ $cinemaId == $id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>
    @else
        @if (auth()->user()->cinema_id)
            <input type="hidden" name="cinema_id" value="{{ auth()->user()->cinema_id }}">
            <div class="form-group col-md-2">
                <label class="form-label fw-bold">
                    <i class="bi bi-camera-reels me-1"></i> Rạp
                </label>
                <p class="form-control-static">{{ auth()->user()->cinema->name ?? 'N/A' }}</p>
            </div>
        @endif
    @endif

    <!-- Ngày bắt đầu -->
    <div class="form-group col-md-2">
        <input type="date" name="start_date" id="start_date"
               class="form-control" value="{{ $startDate }}">
    </div>

    <!-- Ngày kết thúc -->
    <div class="form-group col-md-2">
        <input type="date" name="end_date" id="end_date"
               class="form-control" value="{{ $endDate }}">
    </div>

    <!-- Nút Lọc -->
    <div class="form-group col-md-1 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-50">
            <i class="bx bx-search-alt-2"></i>
        </button>
    </div>

    <!-- Nút Reset -->
    <div class="form-group col-md-1 d-flex align-items-end">
        <a href="{{ route('admin.statistical.cinemaRevenue') }}"
           class="btn btn-outline-secondary w-50">
            <i class="bi bi-arrow-repeat"></i>
        </a>
    </div>
</form>

        @if ($message)
            <div class="alert alert-info text-center my-4">
                {{ $message }}
            </div>
        @else
            <!-- Biểu đồ Doanh thu và Số vé -->
            <div class="card mt-4 mb-4">
                <div class="card-body">
                    <canvas id="revenueChart" style="height: 400px; width: 100%;"></canvas>
                </div>
            </div>

            <!-- Biểu đồ Số lượng suất chiếu -->
            <div class="card mt-4 mb-4">
                <div class="card-body">
                    <canvas id="showtimeChart" style="height: 400px; width: 100%;"></canvas>
                </div>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if (auth()->user()->hasRole('System Admin'))
                // AJAX để cập nhật danh sách rạp khi chọn chi nhánh
                document.getElementById('branch_id').addEventListener('change', function() {
                    let branchId = this.value;
                    let cinemaSelect = document.getElementById('cinema_id');
                    cinemaSelect.innerHTML = '<option value="">Tất cả rạp</option>'; // Reset danh sách

                    if (branchId) {
                        fetch('{{ route("admin.statistical.cinemas") }}?branch_id=' + branchId, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(cinema => {
                                let option = document.createElement('option');
                                option.value = cinema.id;
                                option.text = cinema.name;
                                cinemaSelect.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Error fetching cinemas:', error));
                    }
                });
            @endif

            @if(!$message)
                // Biểu đồ Doanh thu và Số vé
                var revenueCtx = document.getElementById('revenueChart').getContext('2d');
                var revenueData = @json($revenues);
                if (revenueData && revenueData.length > 0) {
                    var labels = revenueData.map(item => item.movie_name);
                    var revenueValues = revenueData.map(item => item.revenue);
                    var ticketValues = revenueData.map(item => item.ticket_count);

                    new Chart(revenueCtx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [
                                {
                                    label: 'Doanh thu (VNĐ)',
                                    data: revenueValues,
                                    type: 'bar',
                                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    borderWidth: 1,
                                    yAxisID: 'y-revenue',
                                    barThickness: 20,
                                    borderRadius: 5
                                },
                                {
                                    label: 'Số vé',
                                    data: ticketValues,
                                    type: 'line',
                                    fill: false,
                                    backgroundColor: 'rgba(255, 108, 55, 1)',
                                    borderColor: 'rgba(255, 108, 55, 1)',
                                    borderWidth: 2,
                                    tension: 0.3,
                                    yAxisID: 'y-tickets',
                                    pointRadius: 5,
                                    pointHoverRadius: 8
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
                                    title: { display: true, text: 'Doanh thu (VNĐ)', font: { size: 14 } },
                                    ticks: { callback: value => value.toLocaleString('vi-VN') + ' VNĐ', color: '#333' },
                                    grid: { color: 'rgba(0, 0, 0, 0.1)' }
                                },
                                'y-tickets': {
                                    type: 'linear',
                                    position: 'right',
                                    beginAtZero: true,
                                    title: { display: true, text: 'Số vé', font: { size: 14 } },
                                    ticks: { callback: value => value.toLocaleString('vi-VN'), color: '#333' },
                                    grid: { drawOnChartArea: false }
                                },
                                x: {
                                    ticks: { autoSkip: false, maxRotation: 45, minRotation: 45, font: { size: 12 }, color: '#333' },
                                    grid: { display: false }
                                }
                            },
                            plugins: {
                                title: { display: true, text: 'Thống Kê Doanh Thu và Số Vé Theo Phim', font: { size: 18, weight: 'bold' }, padding: 20, color: '#333' },
                                legend: { position: 'top', labels: { font: { size: 14 }, padding: 10, usePointStyle: true } },
                                tooltip: { mode: 'index', intersect: false, backgroundColor: 'rgba(0, 0, 0, 0.8)', titleFont: { size: 14 }, bodyFont: { size: 12 }, padding: 10 }
                            },
                            interaction: { mode: 'index', intersect: false }
                        }
                    });
                }

                // Biểu đồ Số lượng suất chiếu (Bar Chart)
                var showtimeCtx = document.getElementById('showtimeChart').getContext('2d');
                var showtimeData = @json($showtimes);
                if (showtimeData && showtimeData.length > 0) {
                    var labels = showtimeData.map(item => item.movie_name);
                    var showtimeValues = showtimeData.map(item => item.showtime_count);

                    new Chart(showtimeCtx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Số suất chiếu',
                                data: showtimeValues,
                                backgroundColor: 'rgba(75, 192, 192, 0.7)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1,
                                barThickness: 20,
                                borderRadius: 5
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: { display: true, text: 'Số lượng suất chiếu', font: { size: 14 } },
                                    ticks: { color: '#333' },
                                    grid: { color: 'rgba(0, 0, 0, 0.1)' }
                                },
                                x: {
                                    title: { display: true, text: 'Phim', font: { size: 14 } },
                                    ticks: { autoSkip: false, maxRotation: 45, minRotation: 45, font: { size: 12 }, color: '#333' },
                                    grid: { display: false }
                                }
                            },
                            plugins: {
                                title: { display: true, text: 'Số Lượng Suất Chiếu Theo Phim', font: { size: 18, weight: 'bold' }, padding: 20, color: '#333' },
                                legend: { position: 'top', labels: { font: { size: 14 }, padding: 10, usePointStyle: true } },
                                tooltip: { backgroundColor: 'rgba(0, 0, 0, 0.8)', titleFont: { size: 14 }, bodyFont: { size: 12 }, padding: 10 }
                            }
                        }
                    });
                }
            @endif
        });
    </script>
@endsection
