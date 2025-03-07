@extends('admin.layouts.master')
@section('style')

    <link rel="stylesheet" href="{{asset('theme/admin/assets/libs/@simonwep/pickr/themes/classic.min.css')}}" />
    <!-- 'classic' theme -->
    <link rel="stylesheet" href="{{asset('theme/admin/assets/libs/@simonwep/pickr/themes/monolith.min.css')}}" />
    <!-- 'monolith' theme -->
    <link rel="stylesheet" href="{{asset('theme/admin/assets/libs/@simonwep/pickr/themes/nano.min.css')}}" />
    <!-- 'nano' theme -->
    <link rel="stylesheet" href="{{asset('theme/admin/assets/libs/flatpickr/flatpickr.min.css')}}">

@endsection
@section('content')

    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Quản lý suất chiếu</h4>

                <div class="page-title-right">

                    <button class="btn btn-primary btn-sm float-end mb-2 me-3" data-bs-toggle="modal"
                        data-bs-target="#movieModal">
                        <i class="bi bi-plus-lg"></i> Thêm suất chiếu
                    </button>
                </div>

            </div>
        </div>
    </div>
    <div class="rounded">
        <form action="{{ route('admin.showtimes.index') }}" method="get" class="pb-2 shadow-sm">
            <div class="row g-3 align-items-end">
                <div class="col-lg-3">
                    <select name="branch_id" class="form-select" required id="branch_id">
                        <option value="" disabled selected>Chọn chi nhánh</option>
                        @foreach ($branchs as $branch)
                            <option value="{{ $branch['id'] }}" {{ request('branch_id') == $branch['id'] ? 'selected' : '' }}>
                                {{ $branch['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3">
                    <select name="cinema_id" class="form-select" required id="cinema_id">
                        <option value="" disabled selected>Chọn rạp</option>
                    </select>
                </div>
                <div class="col-lg-3">
                    <input type="date" name="date" id="date" class="form-control" value="{{ request('date') }}" required>
                </div>
                <div class="col-lg-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-info "><i class=" bx bx-search-alt-2"></i></button>
                </div>
            </div>
        </form>

        <div class="modal fade" id="movieModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-light">Danh sách phim đang hoạt động</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <ul class="list-group">
                            @foreach ($movies as $movie)
                                <a href="{{ route('admin.showtimes.create', $movie->id) }}">
                                    <li class="list-group-item movie-item fw-semibold"><i class="bi bi-film me-3"></i>
                                        {{$movie->name}}</li>
                                </a>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <table class="table table-bordered table-hover dt-responsive nowrap w-100 text-center">
            <thead class="table-success text-light text-center rounded-top-2">
                <tr>
                    <th class="fw-semibold">STT</th>
                    <th class="fw-semibold">Phim</th>
                    <th class="fw-semibold">Ảnh</th>
                    <th class="fw-semibold">Thể loại phim</th>
                    <th class="fw-semibold">Thời lượng</th>
                    <th class="fw-semibold">Thao tác</th>
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
                                <img src="{{ Storage::url($showtimes['movie']->img_thumbnail) }}" alt="" width="100px"
                                    class="img-thumbnail">
                            </td>
                            <td>{{ implode(', ', $showtimes['movie']->movie_genres) }}</td>
                            <td>{{ $showtimes['movie']->duration }} phút</td>
                            <td>
                                <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse-{{ $movieId }}" aria-expanded="false"
                                    aria-controls="collapse-{{ $movieId }}">
                                    <i class="mdi mdi-plus-circle-outline"></i>
                                </button>
                                <button class="btn btn-success btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#movieModalXXX{{ $movieId }}"><i class="bx bxs-copy"></i></button>
                                <div class="modal fade" id="movieModalXXX{{ $movieId }}" tabindex="-1"
                                    aria-labelledby="movieModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content shadow-lg border-0">
                                            <!-- Header -->
                                            <div class="modal-header bg-primary text-light">
                                                <h5 class="modal-title fw-bold text-light" id="movieModalLabel">Chọn thời gian nhân
                                                    bản</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>

                                            <!-- Form -->
                                            <form action="{{route('admin.showtimes.copys')}}" method="post">
                                                @csrf
                                                <input type="hidden" name="movie_id" value="{{ $movieId }}">
                                                <input type="hidden" name="showtime"
                                                    value="{{ json_encode($showtimes['showtimes']) }}">
                                                <input type="hidden" name="date_showtime" value="{{ request('date') }}">
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <input type="text" class="form-control border-primary shadow-sm" name="date"
                                                            id="datepicker-multiple" placeholder="Chọn ngày...">
                                                    </div>
                                                </div>

                                                <!-- Footer -->
                                                <div class="modal-footer d-flex justify-content-between">
                                                    <button type="button" class="btn btn-outline-secondary"
                                                        data-bs-dismiss="modal">Hủy</button>
                                                    <button type="submit" class="btn btn-success fw-bold">Nhân bản</button>
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

                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($showtime['start_time'])->format('H:i') }} -
                                                        {{ \Carbon\Carbon::parse($showtime['end_time'])->format('H:i') }}
                                                    </td>

                                                    <td>{{$showtime['room']['name'] }}</td>
                                                    <td>
                                                        <input type="checkbox" id="is_active{{$showtime['id']}}"
                                                            @checked($showtime['is_active']) switch="success" />
                                                        <label for="is_active{{$showtime['id']}}"></label>
                                                    </td>
                                                    <td>
                                                        <form action="{{route('admin.showtimes.delete') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="showtime_id" value="{{$showtime['id']}}">
                                                            <button type="submit" onclick="return confirm('Bạn có chắc chắn không !!!')"
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
    </div>

    @php
        $appUrl = env('APP_URL');
    @endphp
@endsection

@section('script')
    <script src="{{asset('theme/admin/assets/libs/@simonwep/pickr/pickr.min.js')}}"></script>
    <script src="{{asset('theme/admin/assets/libs/@simonwep/pickr/pickr.es5.min.js')}}"></script>

    <script src="{{asset('theme/admin/assets/libs/flatpickr/flatpickr.min.js')}}"></script>
    <script src="{{asset('theme/admin/assets/js/pages/form-advanced.init.js')}}"></script>
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

        $('#branch_id').change(function () {
            loadCinemas($(this).val());
        });

        if (selectedBranchId) {
            loadCinemas(selectedBranchId);
        }


        $('input[id^="is_active"]').change(function () {
            let id = this.id.replace('is_active', ''); // Lấy ID động
            let is_active = this.checked ? 1 : 0; // Kiểm tra trạng thái

            if (confirm("Bạn có chắc chắn muốn thay đổi trạng thái ?")) {
                $.ajax({
                    url: `${Url}/api/v1/${id}/active-showtime`,
                    method: "PUT",
                    data: {
                        is_active
                    },
                    success: function (response) {
                        toastr.success('Thao tác thành công !!!');
                    },
                    error: function (error) {
                        toastr.error('Thao tác thất bại !!!');
                    }
                });
            } else {
                $(this).prop('checked', !is_active);
            }

        });
    </script>
@endsection