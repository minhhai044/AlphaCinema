@extends('admin.layouts.master')
@section('title', 'Thống kê')

@section('content')
    <div class="container-fluid mt-4">
        <h3 class="mb-4">Thống kê phim</h3>
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
        <form method="GET" action="{{ route('admin.statistical.cinemaRevenue') }}"
            class="d-flex align-items-center gap-2 mb-4" id="filterForm">
            <!-- Chi nhánh -->
            @if (auth()->user()->hasRole('System Admin'))
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light text-muted border-0">
                        <i class="bi bi-geo-alt-fill"></i>
                    </span>
                    <select name="branch_id" class="form-select border-0 shadow-sm" id="branch_id">
                        <option value="" {{ !$branchId ? 'selected' : '' }}>Chọn chi nhánh</option>
                        @if ($branches && $branches->isNotEmpty())
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" {{ $branchId == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
            @else
                @if (auth()->user()->branch_id)
                    <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id }}">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light text-muted border-0">
                            <i class="bi bi-geo-alt-fill"></i>
                        </span>
                        <span class="form-control border-0 shadow-sm">
                            {{ auth()->user()->branch->name ?? 'N/A' }}
                        </span>
                    </div>
                @endif
            @endif

            <!-- Rạp -->
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light text-muted border-0">
                    <i class="bi bi-camera-reels"></i>
                </span>
                <select name="cinema_id" class="form-select border-0 shadow-sm" id="cinema_id">
                    <option value="" {{ !$cinemaId ? 'selected' : '' }}>Chọn rạp</option>
                    @if (auth()->user()->hasRole('System Admin') && $branchId && isset($branchesRelation[$branchId]))
                        @foreach ($branchesRelation[$branchId] as $id => $name)
                            <option value="{{ $id }}" {{ $cinemaId == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    @elseif(auth()->user()->branch_id && isset($branchesRelation[auth()->user()->branch_id]))
                        @foreach ($branchesRelation[auth()->user()->branch_id] as $id => $name)
                            <option value="{{ $id }}" {{ $cinemaId == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    @elseif(auth()->user()->cinema_id)
                        <option value="{{ auth()->user()->cinema_id }}" selected>
                            {{ auth()->user()->cinema->name ?? 'N/A' }}
                        </option>
                    @endif
                </select>
            </div>

            <!-- Ngày -->
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light text-muted border-0">
                    <i class="bi bi-calendar"></i>
                </span>
                <input type="date" name="date" id="date" class="form-control border-0 shadow-sm"
                    value="{{ $date ?? $today }}">
            </div>

            <!-- Tháng -->
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light text-muted border-0">
                    <i class="bi bi-calendar-month"></i>
                </span>
                <select name="month" class="form-select border-0 shadow-sm" id="month">
                    <option value="" {{ !$selectedMonth ? 'selected' : '' }}>Chọn tháng</option>
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ $selectedMonth == $i ? 'selected' : '' }}>
                            Tháng {{ $i }}
                        </option>
                    @endfor
                </select>
            </div>

            <!-- Năm -->
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light text-muted border-0">
                    <i class="bi bi-calendar4"></i>
                </span>
                <select name="year" class="form-select border-0 shadow-sm" id="year">
                    <option value="" {{ !$selectedYear ? 'selected' : '' }}>Chọn năm</option>
                    @for ($i = 2020; $i <= Carbon\Carbon::now()->year; $i++)
                        <option value="{{ $i }}" {{ $selectedYear == $i ? 'selected' : '' }}>
                            Năm {{ $i }}
                        </option>
                    @endfor
                </select>
            </div>

            <!-- Nút lọc -->
            <button type="submit"
                class="btn btn-sm btn-success rounded-circle p-2 d-flex align-items-center justify-content-center"
                title="Lọc dữ liệu" style="width: 36px; height: 36px;">
                <i class="bi bi-funnel fs-5"></i>
            </button>

            <!-- Nút reset -->
            <button type="button"
                class="btn btn-sm btn-primary rounded-circle p-2 d-flex align-items-center justify-content-center"
                onclick="window.location.href='{{ route('admin.statistical.cinemaRevenue') }}'" title="Reset bộ lọc"
                style="width: 36px; height: 36px;">
                <i class="bi bi-arrow-counterclockwise fs-5"></i>
            </button>
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

       <div class="row">
    <div class="col-12">
        <div class="container mt-5">
            <h3 class="text-center mb-4" style="font-weight: bold; color: #5156be;">Top 6 Phim Doanh Thu Cao Nhất</h3>
            <div class="row justify-content-center">
                @forelse ($top6Movies as $index => $movie)
                    <div class="col-md-4 col-lg-3 mb-4">
                        <div class="card top6-card shadow-sm h-100" style="border: none; border-radius: 10px; overflow: hidden;">
                            <div class="position-relative">
                                <img src="{{ Storage::url($movie->img_thumbnail) }}" class="card-img-top" style="height: 150px; object-fit: cover;" alt="{{ $movie->movie_name }}">
                                <span class="top6-rank" style="position: absolute; top: 10px; left: 10px; background: #5156be; color: white; padding: 5px 10px; border-radius: 50%; font-size: 14px; font-weight: bold;">
                                    #{{ $index + 1 }}
                                </span>
                            </div>
                            <div class="card-body text-center" style="padding: 15px;">
                                <h5 class="card-title" style="font-size: 18px; color: #333; margin-bottom: 10px;">
                                    {{ $movie->movie_name }}
                                </h5>
                                <p class="mb-2" style="font-size: 14px; color: #6c757d;">
                                    <strong>Doanh thu:</strong> <span style="color: #5156be;">{{ number_format($movie->revenue) }} đ</span>
                                </p>
                                <p class="mb-0" style="font-size: 14px; color: #6c757d;">
                                    <strong>Số vé bán:</strong> <span style="color: #5156be;">{{ $movie->ticket_count }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-4">
                        <p style="font-size: 16px; color: #6c757d;">Không có dữ liệu phim để hiển thị.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if (auth()->user()->hasRole('System Admin'))
                const branchSelect = document.getElementById('branch_id');
                const cinemaSelect = document.getElementById('cinema_id');

                if (branchSelect && cinemaSelect) {
                    // Lưu giá trị cinema_id hiện tại từ request
                    const selectedCinemaId = '{{ $cinemaId }}';

                    if (branchSelect.value) {
                        updateCinemas(branchSelect.value, selectedCinemaId);
                    }

                    branchSelect.addEventListener('change', function() {
                        const branchId = this.value;
                        updateCinemas(branchId);
                    });

                    function updateCinemas(branchId, preselectedCinemaId = '') {
                        cinemaSelect.innerHTML = '<option value="">Chọn rạp</option>';
                        if (branchId) {
                            fetch('{{ route('admin.statistical.cinemas') }}?branch_id=' + branchId, {
                                    method: 'GET',
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    }
                                })
                                .then(response => {
                                    if (!response.ok) {
                                        return response.json().then(err => {
                                            throw new Error(err.error || 'Network response was not ok');
                                        });
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    if (data.length > 0) {
                                        data.forEach(cinema => {
                                            const option = document.createElement('option');
                                            option.value = cinema.id;
                                            option.text = cinema.name;
                                            // Giữ giá trị đã chọn sau khi submit
                                            if (preselectedCinemaId && cinema.id ==
                                                preselectedCinemaId) {
                                                option.selected = true;
                                            }
                                            cinemaSelect.appendChild(option);
                                        });
                                    } else {
                                        const option = document.createElement('option');
                                        option.value = '';
                                        option.text = 'Không có rạp nào';
                                        option.disabled = true;
                                        cinemaSelect.appendChild(option);
                                    }
                                })
                                .catch(error => {
                                    console.error('Error fetching cinemas:', error);
                                    const option = document.createElement('option');
                                    option.value = '';
                                    option.text = 'Lỗi: ' + error.message;
                                    option.disabled = true;
                                    cinemaSelect.appendChild(option);
                                });
                        }
                    }
                } else {
                    console.error('branch_id or cinema_id not found in DOM');
                }
            @endif

            @if (!$message)
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
                            datasets: [{
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
                                    title: {
                                        display: true,
                                        text: 'Doanh thu (VNĐ)',
                                        font: {
                                            size: 14
                                        }
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
                                        font: {
                                            size: 14
                                        }
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
                                        font: {
                                            size: 12
                                        },
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
                                    font: {
                                        size: 18,
                                        weight: 'bold'
                                    },
                                    padding: 20,
                                    color: '#333'
                                },
                                legend: {
                                    position: 'top',
                                    labels: {
                                        font: {
                                            size: 14
                                        },
                                        padding: 10,
                                        usePointStyle: true
                                    }
                                },
                                tooltip: {
                                    mode: 'index',
                                    intersect: false,
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    titleFont: {
                                        size: 14
                                    },
                                    bodyFont: {
                                        size: 12
                                    },
                                    padding: 10
                                }
                            },
                            interaction: {
                                mode: 'index',
                                intersect: false
                            }
                        }
                    });
                }

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
                                    title: {
                                        display: true,
                                        text: 'Số lượng suất chiếu',
                                        font: {
                                            size: 14
                                        }
                                    },
                                    ticks: {
                                        color: '#333'
                                    },
                                    grid: {
                                        color: 'rgba(0, 0, 0, 0.1)'
                                    }
                                },
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Phim',
                                        font: {
                                            size: 14
                                        }
                                    },
                                    ticks: {
                                        autoSkip: false,
                                        maxRotation: 45,
                                        minRotation: 45,
                                        font: {
                                            size: 12
                                        },
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
                                    text: 'Số Lượng Suất Chiếu Theo Phim',
                                    font: {
                                        size: 18,
                                        weight: 'bold'
                                    },
                                    padding: 20,
                                    color: '#333'
                                },
                                legend: {
                                    position: 'top',
                                    labels: {
                                        font: {
                                            size: 14
                                        },
                                        padding: 10,
                                        usePointStyle: true
                                    }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    titleFont: {
                                        size: 14
                                    },
                                    bodyFont: {
                                        size: 12
                                    },
                                    padding: 10
                                }
                            }
                        }
                    });
                }
            @endif
        });
    </script>

     <style>
        .top6-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .top6-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15) !important;
        }
        .card-img-top {
            border-bottom: 2px solid #5156be;
        }
        .top6-rank {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .card {
            border-radius: 10px;
            overflow: hidden;
        }
        .card-title {
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }
        @media (max-width: 768px) {
            .card-body { padding: 1rem; }
            canvas { height: 150px !important; }
        }
    </style>
@endsection
