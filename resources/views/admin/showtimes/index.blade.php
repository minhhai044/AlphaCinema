@extends('admin.layouts.master')
@section('style')
    <link rel="stylesheet" href="{{ asset('theme/admin/assets/libs/@simonwep/pickr/themes/classic.min.css') }}" />
    <!-- 'classic' theme -->
    <link rel="stylesheet" href="{{ asset('theme/admin/assets/libs/@simonwep/pickr/themes/monolith.min.css') }}" />
    <!-- 'monolith' theme -->
    <link rel="stylesheet" href="{{ asset('theme/admin/assets/libs/@simonwep/pickr/themes/nano.min.css') }}" />
    <!-- 'nano' theme -->
    <link rel="stylesheet" href="{{ asset('theme/admin/assets/libs/flatpickr/flatpickr.min.css') }}">
    <style>
        .table {
            vertical-align: middle !important;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-20 fw-semibold">Quản lý suất chiếu</h4>

                <div class="d-flex align-items-center">
                    <form id="FormFilter" action="{{ route('admin.showtimes.index') }}" method="get"
                        class="d-flex align-items-center me-5 shadow-sm">
                        <div class="row g-2">
                            <div class="col-auto">
                                <select name="branch_id" class="form-select" required id="branch_id">
                                    <option value="" disabled selected>Chọn chi nhánh</option>
                                   
                                    @foreach ($branchs as $branch)
                                        <option value="{{ $branch['id'] }}"
                                            {{ request('branch_id') == $branch['id'] ? 'selected' : '' }}>
                                            {{ $branch['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-auto">
                                <select name="cinema_id" class="form-select" required id="cinema_id">
                                    <option value="" disabled selected>Chọn rạp</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <input type="date" name="date" id="date" class="form-control"
                                    value="{{ request('date') }}" required>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary"><i class="bx bx-search-alt-2"></i></button>
                                <button id="submitFilter" type="button" class="btn btn-primary"><i
                                        class="bx bx-filter-alt"></i></button>
                            </div>
                        </div>
                    </form>
                    @can('Thêm suất chiếu')
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#movieModal">
                            <i class="bi bi-plus-lg"></i> Thêm suất chiếu
                        </button>
                    @endcan
                </div>

            </div>
        </div>
    </div>


    <div class="rounded">
        <div class="modal fade" id="movieModal" tabindex="-1" aria-labelledby="movieModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title d-flex text-dark fw-semibold align-items-center">
                            <i class="bi bi-film me-2"></i> Danh sách phim đang hoạt động
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Ô tìm kiếm -->
                        <div class="mb-3">
                            <input type="text" id="searchMovie" class="form-control" placeholder="Nhập tên phim...">
                        </div>

                        <!-- Danh sách phim -->
                        <ul class="list-group overflow-auto" style="height: 200px;" id="movieList">
                            {{-- @foreach ($movies as $movie)

                            @endforeach --}}
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @php
            $colors = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'dark', 'muted'];
            $roomColors = [];
        @endphp
        @if ($listShowtimesByDates->isNotEmpty())

            <div class="accordion " id="accordionExample">
                @foreach ($listShowtimesByDates as $key => $listShowtimesByDate)
                    <div class="accordion-item ">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-bold text-dark bg-white" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapseOne{{ $key }}"
                                aria-expanded="true" aria-controls="collapseOne">
                                <span class="text-secondary">Danh sách suất chiếu ngày : </span><span
                                    class="text-dark ms-1">{{ \Carbon\Carbon::parse($key)->format('d/m/Y') }}</span>

                            </button>
                        </h2>
                        <div id="collapseOne{{ $key }}" class="accordion-collapse collapse"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <table class="table table-bordered table-hover dt-responsive nowrap w-100 text-center">
                                    <thead class="table text-light text-center rounded-top-2">
                                        <tr>
                                            <th class="fw-semibold text-center">STT</th>
                                            <th class="fw-semibold text-center">Phim</th>
                                            <th class="fw-semibold text-center">Ảnh</th>
                                            <th class="fw-semibold text-center">Thể loại phim</th>
                                            <th class="fw-semibold text-center">Thời lượng</th>
                                            <th class="fw-semibold text-center">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($listShowtimesByDate['movies'] as $movie)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="fw-semibold fs-5">{{ $movie['movie']->name }}</td>
                                                <td>
                                                    <img src="{{ Storage::url($movie['movie']->img_thumbnail) }}"
                                                        alt="" width="100px" class="img-thumbnail">
                                                </td>
                                                <td>{{ implode(', ', $movie['movie']->movie_genres) }}</td>
                                                <td>{{ $movie['movie']->duration }} phút</td>
                                                <td>
                                                    <button class="btn btn-success btn-sm" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapse-{{ $movie['movie']->id }}{{ $key }}"
                                                        aria-expanded="false"
                                                        aria-controls="collapse-{{ $movie['movie']->id }}{{ $key }}">
                                                        <i class="bx bx-show"></i>
                                                    </button>
                                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#movieModalXXX{{ $movie['movie']->id }}{{ $key }}"><i
                                                            class="bx bxs-copy"></i></button>
                                                    <div class="modal fade"
                                                        id="movieModalXXX{{ $movie['movie']->id }}{{ $key }}"
                                                        tabindex="-1" aria-labelledby="movieModalLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content shadow-lg border-0">
                                                                <!-- Header -->
                                                                <div class="modal-header fw-semibold">
                                                                    <h5 class="modal-title fw-bold text-dark"
                                                                        id="movieModalLabel">Chọn
                                                                        thời gian nhân
                                                                        bản</h5>
                                                                    <button type="button"
                                                                        class="btn-close btn-close-white"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                                </div>

                                                                <!-- Form -->
                                                                <form action="{{ route('admin.showtimes.copys') }}"
                                                                    method="post">
                                                                    @csrf
                                                                    <input type="hidden" name="movie_id"
                                                                        value="{{ $movie['movie']->id }}">
                                                                    <input type="hidden" name="showtime"
                                                                        value="{{ json_encode($movie['showtimes']) }}">
                                                                    <input type="hidden" name="date_showtime"
                                                                        value="{{ request('date') }}">
                                                                    <div class="modal-body">
                                                                        <div class="mb-3">
                                                                            <input type="text"
                                                                                class="form-control border-primary shadow-sm"
                                                                                name="date" id="datepicker-multiple"
                                                                                placeholder="Chọn ngày...">
                                                                        </div>
                                                                    </div>

                                                                    <!-- Footer -->
                                                                    <div
                                                                        class="modal-footer d-flex justify-content-between">
                                                                        <button type="button"
                                                                            class="btn btn-outline-secondary"
                                                                            data-bs-dismiss="modal">Quay lại</button>
                                                                        <button type="submit"
                                                                            class="btn btn-primary fw-bold">Nhân
                                                                            bản</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="6">
                                                    <div class="collapse"
                                                        id="collapse-{{ $movie['movie']->id }}{{ $key }}">

                                                        <table class="table table-bordered text-center">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th class="text-center fw-semibold">Thời gian</th>
                                                                    <th class="text-center fw-semibold">Phòng</th>
                                                                    <th class="text-center fw-semibold">Hoạt động</th>
                                                                    <th class="text-center fw-semibold">Chức năng</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($movie['showtimes'] as $showtime)
                                                                    @php
                                                                        $roomName = $showtime['room']['name'];
                                                                        if (!isset($roomColors[$roomName])) {
                                                                            $roomColors[$roomName] =
                                                                                $colors[array_rand($colors)];
                                                                        }
                                                                        $color = $roomColors[$roomName];
                                                                    @endphp
                                                                    <tr>
                                                                        <td>{{ \Carbon\Carbon::parse($showtime['start_time'])->format('H:i') }}
                                                                            -
                                                                            {{ \Carbon\Carbon::parse($showtime['end_time'])->format('H:i') }}
                                                                        </td>

                                                                        <td class="text-{{ $color }} fw-semibold">
                                                                            {{ $showtime['room']['name'] }}</td>
                                                                        <td>
                                                                            <div
                                                                                class="d-flex justify-content-center align-items-center mt-2">
                                                                                <div class="custom-switch">
                                                                                    <input @checked($showtime['is_active'])
                                                                                        switch="primary"
                                                                                        class="form-check-input switch-is-active"
                                                                                        id="is_active{{ $showtime['id'] }}"
                                                                                        type="checkbox">
                                                                                    <label
                                                                                        for="is_active{{ $showtime['id'] }}"></label>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="d-flex justify-content-center align-items-center gap-1">
                                                                                <a href="{{route('admin.showtimes.show' , $showtime['slug']) }}" class="btn btn-sm btn-warning" title="Xem chi tiết">
                                                                                    <i class="bx bx-show"></i>
                                                                                </a>
                                                                                <form action="{{ route('admin.showtimes.delete') }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn không !!!')">
                                                                                    @csrf
                                                                                    <input type="hidden" name="showtime_id" value="{{ $showtime['id'] }}">
                                                                                    <button type="submit" class="btn btn-sm btn-danger fw-bold" title="Xoá suất chiếu">
                                                                                        <i class="bi bi-trash"></i>
                                                                                    </button>
                                                                                </form>
                                                                        
                                                                                
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>

                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach


                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach


            </div>
        @else
            <table class="table table-bordered table-hover dt-responsive nowrap w-100 text-center">
                <thead class="table text-light text-center rounded-top-2">
                    <tr>
                        <th class="fw-semibold text-center">STT</th>
                        <th class="fw-semibold text-center">Phim</th>
                        <th class="fw-semibold text-center">Ảnh</th>
                        <th class="fw-semibold text-center">Thể loại phim</th>
                        <th class="fw-semibold text-center">Thời lượng</th>
                        <th class="fw-semibold text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($listShowtimes->isEmpty())
                        <tr>
                            <td colspan="6">Không có dữ liệu sẵn , Vui lòng nhập thông tin tìm kiếm 😎😎😎 !!!</td>
                        </tr>
                    @else
                        @foreach ($listShowtimes as $movieId => $showtimes)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="fw-semibold fs-5">{{ $showtimes['movie']->name }}</td>
                                <td>
                                    <img src="{{ Storage::url($showtimes['movie']->img_thumbnail) }}" alt=""
                                        width="100px" class="img-thumbnail">
                                </td>
                                <td>{{ implode(', ', $showtimes['movie']->movie_genres) }}</td>
                                <td>{{ $showtimes['movie']->duration }} phút</td>
                                <td>
                                    <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse-{{ $movieId }}" aria-expanded="false"
                                        aria-controls="collapse-{{ $movieId }}">
                                        <i class="bx bx-show"></i>
                                    </button>
                                    <button class="btn btn-success btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#movieModalXXX{{ $movieId }}"><i
                                            class="bx bxs-copy"></i></button>
                                    <div class="modal fade" id="movieModalXXX{{ $movieId }}" tabindex="-1"
                                        aria-labelledby="movieModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content shadow-lg border-0">
                                                <!-- Header -->
                                                <div class="modal-header bg-primary text-light">
                                                    <h5 class="modal-title fw-bold text-light" id="movieModalLabel">Chọn
                                                        thời gian nhân
                                                        bản</h5>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>

                                                <!-- Form -->
                                                <form action="{{ route('admin.showtimes.copys') }}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="movie_id" value="{{ $movieId }}">
                                                    <input type="hidden" name="showtime"
                                                        value="{{ json_encode($showtimes['showtimes']) }}">
                                                    <input type="hidden" name="date_showtime"
                                                        value="{{ request('date') }}">
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <input type="text"
                                                                class="form-control border-primary shadow-sm"
                                                                name="date" id="datepicker-multiple"
                                                                placeholder="Chọn ngày...">
                                                        </div>
                                                    </div>

                                                    <!-- Footer -->
                                                    <div class="modal-footer d-flex justify-content-between">
                                                        <button type="button" class="btn btn-outline-secondary"
                                                            data-bs-dismiss="modal">Hủy</button>
                                                        <button type="submit" class="btn btn-success fw-bold">Nhân
                                                            bản</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                </td>
                            </tr>
                            <tr>
                                <td colspan="6">
                                    <div class="collapse" id="collapse-{{ $movieId }}">

                                        <table class="table table-bordered text-center">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Thời gian</th>
                                                    <th>Phòng</th>
                                                    <th>Hoạt động</th>
                                                    <th>Chức năng</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($showtimes['showtimes'] as $showtime)
                                                    @php
                                                        $roomName = $showtime['room']['name'];
                                                        if (!isset($roomColors[$roomName])) {
                                                            $roomColors[$roomName] = $colors[array_rand($colors)];
                                                        }
                                                        $color = $roomColors[$roomName];
                                                    @endphp
                                                    <tr>
                                                        <td>{{ \Carbon\Carbon::parse($showtime['start_time'])->format('H:i') }}
                                                            -
                                                            {{ \Carbon\Carbon::parse($showtime['end_time'])->format('H:i') }}
                                                        </td>

                                                        <td class="fw-semibold text-{{ $color }}">
                                                            {{ $showtime['room']['name'] }}</td>
                                                        <td>
                                                            <div class="mt-2">
                                                                <input type="checkbox" @disabled($showtime['tickets'])
                                                                    id="is_active{{ $showtime['id'] }}"
                                                                    @checked($showtime['is_active']) switch="primary" />
                                                                <label for="is_active{{ $showtime['id'] }}"></label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <form action="{{ route('admin.showtimes.delete') }}"
                                                                method="POST">
                                                                @csrf
                                                                <input type="hidden" name="showtime_id"
                                                                    value="{{ $showtime['id'] }}">
                                                                <button @disabled($showtime['tickets']) type="submit"
                                                                    onclick="return confirm('Bạn có chắc chắn không !!!')"
                                                                    class="btn btn-sm btn-danger fw-bold">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif

                </tbody>
            </table>
        @endif

    </div>

    @php
        $appUrl = env('APP_URL');
    @endphp
@endsection

@section('script')
    <script src="{{ asset('theme/admin/assets/libs/@simonwep/pickr/pickr.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/@simonwep/pickr/pickr.es5.min.js') }}"></script>

    <script src="{{ asset('theme/admin/assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/js/pages/form-advanced.init.js') }}"></script>
    <script>
        const branchsRelation = @json($branchsRelation);
        const selectedBranchId = "{{ request('branch_id') }}";
        const selectedCinemaId = "{{ request('cinema_id') }}";
        let Url = @json($appUrl);

        function loadCinemas(branchId) {
            let cinemas = branchsRelation[branchId] || {};
            let $cinemaSelect = $('#cinema_id');

            $cinemaSelect.prop('disabled', !branchId).empty().append(`<option value="" disabled>Chọn rạp</option>`);

            Object.entries(cinemas).forEach(([id, name]) => {
                let selected = (id == selectedCinemaId) ? 'selected' : '';
                $cinemaSelect.append(`<option value="${id}" ${selected}>${name}</option>`);
            });
        }

        $('#branch_id').change(function() {
            loadCinemas($(this).val());
        });

        if (selectedBranchId) {
            loadCinemas(selectedBranchId);
        }


        $('input[id^="is_active"]').change(function() {
            let id = this.id.replace('is_active', ''); // Lấy ID động
            let is_active = this.checked ? 1 : 0; // Kiểm tra trạng thái

            if (confirm("Bạn có chắc chắn muốn thay đổi trạng thái ?")) {
                $.ajax({
                    url: `${Url}/api/v1/${id}/active-showtime`,
                    method: "PUT",
                    data: {
                        is_active
                    },
                    success: function(response) {
                        toastr.success('Thao tác thành công !!!');
                    },
                    error: function(error) {
                        toastr.error('Thao tác thất bại !!!');
                    }
                });
            } else {
                $(this).prop('checked', !is_active);
            }

        });
        let movies = @json($movies);

        function renderMovies(filteredMovies) {
            $('#movieList').empty(); // Xóa danh sách cũ
            filteredMovies.forEach(item => {
                $('#movieList').append(`

                       <li class="list-group-item movie-item fw-semibold d-flex align-items-center justify-content-between border-0 rounded-3 mb-2 p-3 shadow-sm">
                            <a href="/admin/showtimes/${item.id}/create" class="text-dark fw-semibold d-flex align-items-center text-decoration-none flex-grow-1">
                                <i class="bi bi-film me-3"></i>
                                <span class="movie-name">${item.name}</span>
                            </a>
                            <a href="/admin/showtimes/${item.id}/createList">
                                <button class="btn btn-sm btn-primary d-flex align-items-center justify-content-center">
                                    <i class="bx bx-list-plus"></i>
                                </button>
                            </a>
                        </li>


                `);
            });
        }
        renderMovies(movies);

        $('#searchMovie').keyup(function() {
            let searchText = $(this).val().toLowerCase();
            let filteredMovies = movies.filter(movie => movie.name.toLowerCase().includes(searchText));

            renderMovies(filteredMovies);
        });
        $('#submitFilter').click(function() {
            let branch_id = $('#branch_id').val();
            let cinema_id = $('#cinema_id').val();
            $('#date').prop('disabled', true);
            if (!branch_id && !cinema_id) {
                toastr.warning('Vui lòng nhập đầy đủ thông tin !!!');
                $('#date').prop('disabled', false);
                return;
            }
            $('#FormFilter').submit();

        })
    </script>
@endsection
