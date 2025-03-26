@extends('admin.layouts.master')

@section('title', 'Thống kê Combo')

@section('content')
    <div class="container-fluid mt-4">
        <h3 class="mb-4">Thống kê Combo</h3>

        {{-- Form lọc --}}
        <form method="GET" action="" class="d-flex align-items-center gap-2" id="filterForm">
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
            @elseif (auth()->user()->branch_id && !auth()->user()->cinema_id)
                <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id }}">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light text-muted border-0">
                        <i class="bi bi-geo-alt-fill"></i>
                    </span>
                    <span class="form-control border-0 shadow-sm">{{ auth()->user()->branch->name ?? 'N/A' }}</span>
                </div>
            @elseif (auth()->user()->cinema_id)
                @php
                    $cinema = $cinemas->firstWhere('id', auth()->user()->cinema_id);
                @endphp
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
                        @if ($branchId && isset($branchesRelation[$branchId]))
                            @foreach ($branchesRelation[$branchId] as $id => $name)
                                <option value="{{ $id }}" {{ $cinemaId == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        @elseif (isset($branchesRelation[auth()->user()->branch_id]))
                            @foreach ($branchesRelation[auth()->user()->branch_id] as $id => $name)
                                <option value="{{ $id }}" {{ $cinemaId == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        @else 
                            <option value="" disabled>Không có rạp nào</option>
                        @endif 
                    </select>
                </div>
            @elseif (auth()->user()->cinema_id)
                @php
                    $cinema = $cinemas->firstWhere('id', auth()->user()->cinema_id);
                @endphp
                <input type="hidden" name="cinema_id" value="{{ $cinema->id ?? '' }}">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light text-muted border-0">
                        <i class="bi bi-camera-reels"></i>
                    </span>
                    <span class="form-control border-0 shadow-sm">{{ $cinema->name ?? 'Không xác định' }}</span>
                </div>
            @endif

            <!-- Bộ lọc ngày -->
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light text-muted border-0">
                    <i class="bi bi-calendar"></i>
                </span>
                <input type="date" name="date" class="form-control border-0 shadow-sm" value="{{ $date }}">
            </div>

            <!-- Bộ lọc phim -->
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light text-muted border-0">
                    <i class="bi bi-film"></i>
                </span>
                <select name="movie_id" class="form-select border-0 shadow-sm">
                    <option value="" {{ !$movieId ? 'selected' : '' }}>Tất cả phim</option>
                    @foreach ($movies as $movie)
                        <option value="{{ $movie->id }}" {{ $movieId == $movie->id ? 'selected' : '' }}>
                            {{ $movie->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Bộ lọc tháng -->
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light text-muted border-0">
                    <i class="bi bi-calendar-month"></i>
                </span>
                <select name="month" class="form-select border-0 shadow-sm">
                    <option value="" {{ !$selectedMonth ? 'selected' : '' }}>Chưa chọn</option>
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ $selectedMonth == $i ? 'selected' : '' }}>Tháng {{ $i }}</option>
                    @endfor
                </select>
            </div>

            <!-- Bộ lọc năm -->
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light text-muted border-0">
                    <i class="bi bi-calendar4"></i>
                </span>
                <select name="year" class="form-select border-0 shadow-sm">
                    @for ($i = 2020; $i <= Carbon\Carbon::now()->year; $i++)
                        <option value="{{ $i }}" {{ $selectedYear == $i ? 'selected' : '' }}>Năm {{ $i }}</option>
                    @endfor
                </select>
            </div>

            <!-- Nút lọc -->
            <button type="submit" class="btn btn-sm btn-success rounded-circle p-2 d-flex align-items-center justify-content-center" title="Lọc dữ liệu" style="width: 36px; height: 36px;">
                <i class="bi bi-funnel fs-5"></i>
            </button>

            <!-- Nút reset -->
            <button type="button" class="btn btn-sm btn-primary rounded-circle p-2 d-flex align-items-center justify-content-center" onclick="resetFilters()" title="Reset bộ lọc" style="width: 36px; height: 36px;">
                <i class="bi bi-arrow-counterclockwise fs-5"></i>
            </button>
        </form>

        <!-- Hàng 1: Tổng doanh thu và Tỷ lệ combo -->
        <div class="row g-4 mb-4 mt-2">
            <!-- Tổng doanh thu Combo -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card card-h-100">
                        <div class="card-body">
                            <h5 class="card-title text-center">Tổng doanh thu Combo</h5>
                            <canvas id="comboChart" style="height: 400px; width: 100%;"></canvas>
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
                                @forelse ($comboStatistics as $index => $combo)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $combo->combo_name }}</td>
                                        <td>{{ $combo->total_quantity }}</td>
                                        <td>{{ number_format($combo->total_price, 0) }} VND</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Không có dữ liệu để hiển thị</td>
                                    </tr>
                                @endforelse
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
            // 1. Tổng doanh thu Combo
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
                            backgroundColor: '#5156be',
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
                ctx.font = "20px Arial";
                ctx.fillText("Không có dữ liệu để hiển thị", 50, 200);
            }

            // 2. Tỷ lệ đơn hàng có Combo
            var usageCtx = document.getElementById('comboUsageChart').getContext('2d');
            var comboUsage = @json($comboUsage);
            new Chart(usageCtx, {
                type: 'pie',
                data: {
                    labels: ['Có Combo', 'Không có Combo'],
                    datasets: [{
                        data: [comboUsage, 100 - comboUsage],
                        backgroundColor: ['#5156be', '#d3d3d3'],
                        borderColor: ['#5156be', '#d3d3d3'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
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

            // 3. Doanh thu Combo theo khung giờ
            var ctxStacked = document.getElementById('stackedBarChart').getContext('2d');
            var trendDates = @json($trendDates);
            var trendRevenues = @json($trendRevenues);

            if (trendDates.length > 0 && trendRevenues.length > 0) {
                new Chart(ctxStacked, {
                    type: 'bar',
                    data: {
                        labels: trendDates,
                        datasets: [{
                            label: 'Doanh thu Combo',
                            data: trendRevenues,
                            backgroundColor: '#36A2EB'
                        }]
                    },
                    options: {
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Khung giờ suất chiếu'
                                }
                            },
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Doanh thu (VND)'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            }
                        }
                    }
                });
            } else {
                ctxStacked.font = "20px Arial";
                ctxStacked.fillText("Không có dữ liệu để hiển thị", 50, 100);
            }
        });

        function resetFilters() {
            document.getElementById('filterForm').reset();
            window.location.href = "{{ route('admin.index') }}";
        }

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