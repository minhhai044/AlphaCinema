@extends('admin.layouts.master')

@section('title', 'Thống kê Doanh Thu Food')

@section('content')
    <div class="container-fluid mt-4">
        <h3 class="mb-4">Thống kê Doanh Thu Food</h3>

        <!-- Hiển thị vai trò người dùng -->
        <div class="alert alert-info mb-4">
            Bạn đang xem dữ liệu với vai trò:
            @if (auth()->user()->hasRole('System Admin'))
                System Admin (Tất cả chi nhánh và rạp)
            @elseif (auth()->user()->branch_id)
                Quản lý chi nhánh {{ auth()->user()->branch->name ?? 'N/A' }}
            @elseif (auth()->user()->cinema_id)
                Quản lý rạp {{ $cinemas->firstWhere('id', auth()->user()->cinema_id)->name ?? 'N/A' }}
            @endif
        </div>

        <!-- Form lọc -->
        <form method="GET" action="{{ route('admin.food.revenue') }}" class="d-flex align-items-center gap-2 mb-4" id="filterForm">
            <!-- Bộ lọc chi nhánh -->
            @if (auth()->user()->hasRole('System Admin'))
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light text-muted border-0">
                        <i class="bi bi-geo-alt-fill"></i>
                    </span>
                    <select name="branch_id" class="form-select border-0 shadow-sm">
                        <option value="" {{ !$branchId ? 'selected' : '' }}>Tất cả chi nhánh</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}" {{ $branchId == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @elseif (auth()->user()->branch_id)
                <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id }}">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light text-muted border-0">
                        <i class="bi bi-geo-alt-fill"></i>
                    </span>
                    <span class="form-control border-0 shadow-sm">{{ auth()->user()->branch->name ?? 'N/A' }}</span>
                </div>
            @elseif (auth()->user()->cinema_id)
                @php $cinema = $cinemas->firstWhere('id', auth()->user()->cinema_id); @endphp
                <input type="hidden" name="branch_id" value="{{ $cinema->branch_id ?? '' }}">
            @endif

            <!-- Bộ lọc rạp -->
            @if (auth()->user()->hasRole('System Admin') || auth()->user()->branch_id)
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light text-muted border-0">
                        <i class="bi bi-camera-reels"></i>
                    </span>
                    <select name="cinema_id" class="form-select border-0 shadow-sm">
                        <option value="" {{ !$cinemaId ? 'selected' : '' }}>Tất cả rạp</option>
                        @foreach ($branchesRelation[$branchId] ?? [] as $id => $name)
                            <option value="{{ $id }}" {{ $cinemaId == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @elseif (auth()->user()->cinema_id)
                @php $cinema = $cinemas->firstWhere('id', auth()->user()->cinema_id); @endphp
                <input type="hidden" name="cinema_id" value="{{ $cinema->id ?? '' }}">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light text-muted border-0">
                        <i class="bi bi-camera-reels"></i>
                    </span>
                    <span class="form-control border-0 shadow-sm">{{ $cinema->name ?? 'Không xác định' }}</span>
                </div>
            @endif

            <!-- Bộ lọc thời gian -->
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light text-muted border-0">
                    <i class="bi bi-calendar"></i>
                </span>
                <input type="date" name="start_date" class="form-control border-0 shadow-sm" value="{{ $startDate->format('Y-m-d') }}">
            </div>
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light text-muted border-0">
                    <i class="bi bi-calendar"></i>
                </span>
                <input type="date" name="end_date" class="form-control border-0 shadow-sm" value="{{ $endDate->format('Y-m-d') }}">
            </div>

            <!-- Nút lọc -->
            <button type="submit" class="btn btn-sm btn-success rounded-circle p-2" title="Lọc dữ liệu" style="width: 36px; height: 36px;">
                <i class="bi bi-funnel fs-5"></i>
            </button>

            <!-- Nút reset -->
            <button type="button" class="btn btn-sm btn-primary rounded-circle p-2" onclick="resetFilters()" title="Reset bộ lọc" style="width: 36px; height: 36px;">
                <i class="bi bi-arrow-counterclockwise fs-5"></i>
            </button>
        </form>

        <!-- Tổng doanh thu -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Tổng Doanh Thu Food</h5>
                        <p class="fs-4">{{ number_format($totalFoodRevenue) }} VND</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Biểu đồ -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-center">Top 5 Món Ăn Bán Chạy</h5>
                        <canvas id="foodChart" style="height: 400px; width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Script Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var ctx = document.getElementById('foodChart').getContext('2d');
                var foodNames = @json($foodNames);
                var foodRevenues = @json($foodRevenues);
                var foodSummaries = @json($foodSummaries);

                if (foodNames.length > 0 && foodRevenues.length > 0) {
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: foodNames,
                            datasets: [{
                                label: 'Doanh thu (VND)',
                                data: foodRevenues,
                                backgroundColor: '#5156be',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: false,
                            maintainAspectRatio: false,
                            scales: {
                                y: { beginAtZero: true, title: { display: true, text: 'Doanh thu (VND)' } },
                                x: { title: { display: true, text: 'Tên Món Ăn' } }
                            },
                            plugins: {
                                legend: { display: true, position: 'top' },
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
                    ctx.font = "20px Arial";
                    ctx.fillText("Không có dữ liệu để hiển thị", 50, 200);
                }
            });

            function resetFilters() {
                window.location.href = "{{ route('admin.food.revenue') }}";
            }

            // JavaScript động cho System Admin
            document.addEventListener('DOMContentLoaded', function() {
                const branchSelect = document.querySelector('select[name="branch_id"]');
                const cinemaSelect = document.querySelector('select[name="cinema_id"]');
                const branchesRelation = @json($branchesRelation);

                @if (auth()->user()->hasRole('System Admin'))
                    if (branchSelect && cinemaSelect) {
                        branchSelect.addEventListener('change', function() {
                            const branchId = this.value;
                            cinemaSelect.innerHTML = '<option value="" ' + (!branchId ? 'selected' : '') + '>Tất cả rạp</option>';
                            if (branchId && branchesRelation[branchId]) {
                                const cinemas = branchesRelation[branchId];
                                for (const [cinemaId, cinemaName] of Object.entries(cinemas)) {
                                    const isSelected = cinemaId == "{{ $cinemaId }}" ? 'selected' : '';
                                    cinemaSelect.innerHTML += `<option value="${cinemaId}" ${isSelected}>${cinemaName}</option>`;
                                }
                            }
                        });
                        if (branchSelect.value) {
                            branchSelect.dispatchEvent(new Event('change'));
                        }
                    }
                @endif
            });
        </script>
    </div>
@endsection
