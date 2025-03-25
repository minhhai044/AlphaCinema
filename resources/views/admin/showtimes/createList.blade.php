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
        .bootstrap-select .dropdown-toggle {
            background: white;
        }

        .bootstrap-select .dropdown-menu {
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .dropdown-item.active,
        .dropdown-item:active {
            background-color: #556ee6;
            color: #fff;
        }

        /* .nav-tabs.nav-fill .nav-link.active {
                                                                                                                                                                                                        background-color: #48e7cf !important;
                                                                                                                                                                                                        color: #fff !important;
                                                                                                                                                                                                        border-color: #48e7cf;
                                                                                                                                                                                                    } */
    </style>
    {{-- <link href="{{ asset('theme/admin/assets/css/bootstrap.min.css') }}" rel="stylesheet" /> --}}
    {{-- <link href="{{ asset('theme/admin/assets/css/icons.min.css') }}" rel="stylesheet" /> --}}
    {{-- <link href="{{ asset('theme/admin/assets/css/app.min.css') }}" rel="stylesheet" /> --}}
    <link href="{{ asset('theme/admin/assets/libs/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    {{-- Phần Header --}}
    <div class="row">
        <div class="col-12">
            <div class="d-sm-flex align-items-center justify-content-between text-center row">
                <div class="text-center col-lg-9">
                    <h5 class="mb-2 text-secondary fw-semibold">
                        <i class="bi bi-film"></i> Phim :
                        <span class="text-light-emphasis">{{ $movie->name }}</span>
                    </h5>
                    <p class="text-muted fs-6">
                        <i class="bi bi-calendar-event"></i> Ngày tạo:
                        <span class="text-warning fw-semibold">
                            {{ \Carbon\Carbon::parse($movie->created_at)->format('d/m/Y') }}
                        </span>
                        | <i class="bi bi-calendar-check"></i> Ngày phát hành:
                        <span class="text-success fw-semibold">
                            {{ \Carbon\Carbon::parse($movie->release_date)->format('d/m/Y') }}
                        </span>
                        | <i class="bi bi-calendar-x"></i> Ngày kết thúc:
                        <span class="text-danger fw-semibold">
                            {{ \Carbon\Carbon::parse($movie->end_date)->format('d/m/Y') }}
                        </span>
                    </p>
                </div>

                <div class="page-title-right col-lg-3">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.showtimes.index') }}">Quản lý suất chiếu</a>
                        </li>
                        <li class="breadcrumb-item active">Tạo suất chiếu</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    {{-- Phần Tab chọn Nhiều --}}
    @if (!session('dataFull'))
        <div class="card">
            <div class="card-body">
                <div class="twitter-bs-wizard">
                    <ul class="nav nav-pills nav-justified mb-4">
                        <li class="nav-item">
                            <a href="#branchs" class="nav-link active" data-bs-toggle="tab">
                                <i class="bi bi-building"></i><br>Chi nhánh
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#cinemas" class="nav-link" data-bs-toggle="tab">
                                <i class="bi bi-camera-reels"></i><br>Rạp phim
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#rooms" class="nav-link" data-bs-toggle="tab">
                                <i class="bi bi-door-open"></i><br>Phòng chiếu
                            </a>
                        </li>

                    </ul>

                    <form action="{{ route('admin.showtimes.multipleSelect', $movie->id) }}" method="post">
                        @csrf
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="branchs">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label for="data_branchs">Chọn chi nhánh <span class="text-danger">*</span></label>
                                        <select style="border: 1px solid #ded9d9 !important;" id="data_branchs"
                                            class="selectpicker form-control " multiple data-live-search="true"
                                            data-actions-box="true" name="branches[]">

                                            @foreach ($branchs as $branch)
                                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="datepicker-multiple">Chọn ngày <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control shadow-sm" name="dates"
                                            id="datepicker-multiple" placeholder="Chọn ngày...">
                                    </div>
                                    <div class="col-lg-12 mt-3">
                                        <button type="button" id="addTime"
                                            class="btn btn-primary btn-sm float-end ms-3"><i
                                                class=" bx bx-plus"></i></button>
                                        <button type="button" id="autoGenerate" class="btn btn-success btn-sm float-end"><i
                                                class="bx bxs-copy"></i></button>
                                    </div>
                                    <div class="col-lg-12" id="listTime">

                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="cinemas">

                                <div id="listCinemas" class="row">

                                </div>
                            </div>
                            <div class="tab-pane fade" id="rooms">

                                <div id="listRooms" class="row mt-3"></div>

                            </div>
                        </div>


                        <div class="mt-4 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Thêm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @else
        <form class="formSubmit" action="{{ route('admin.showtimes.storePremium', $movie) }}" method="post">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="twitter-bs-wizard">
                        <ul class="nav nav-pills nav-justified mb-4">
                            @foreach (session('dataFull') as $date => $branches)
                                <li class="nav-item">
                                    <a href="#date_{{ $loop->index }}_{{ Str::slug($date) }}"
                                        class="nav-link {{ $loop->first ? 'active' : '' }}" data-bs-toggle="tab">
                                        <i
                                            class="bx bxs-calendar"></i><br>{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>

                        <div class="tab-content mt-3">
                            @foreach (session('dataFull') as $date => $branches)
                                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                    id="date_{{ $loop->index }}_{{ Str::slug($date) }}">
                                    <ul class="nav nav-tabs nav-fill" role="tablist">
                                        @foreach ($branches as $nameBranch => $cinemas)
                                            <li class="nav-item">
                                                <a class="nav-link {{ $loop->first ? 'active' : '' }}"
                                                    data-bs-toggle="tab"
                                                    href="#branch_{{ Str::slug($nameBranch) }}_{{ $loop->parent->index }}">
                                                    <i class="bi bi-building"></i> {{ $nameBranch }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>

                                    <div class="tab-content mt-3">
                                        @foreach ($branches as $nameBranch => $cinemas)
                                            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                                id="branch_{{ Str::slug($nameBranch) }}_{{ $loop->parent->index }}">
                                                <div class="accordion"
                                                    id="cinemasAccordionBranch_{{ Str::slug($nameBranch) }}_{{ $loop->parent->index }}">
                                                    @foreach ($cinemas as $nameCinema => $rooms)
                                                        <div class="accordion-item">
                                                            <h2 class="accordion-header">
                                                                <button class="accordion-button collapsed fw-semibold"
                                                                    type="button" data-bs-toggle="collapse"
                                                                    data-bs-target="#cinema_{{ Str::slug($nameCinema) }}_{{ Str::slug($date) }}">
                                                                    <i class="bi bi-camera-reels me-2"></i>
                                                                    {{ $nameCinema }}
                                                                </button>
                                                            </h2>
                                                            <div id="cinema_{{ Str::slug($nameCinema) }}_{{ Str::slug($date) }}"
                                                                class="accordion-collapse collapse">
                                                                <div class="accordion-body">
                                                                    @foreach ($rooms as $room)
                                                                        <input type="hidden"
                                                                            name="dates[{{ $room['room']['id'] }}_{{ Str::slug($date) }}][]"
                                                                            id="date_{{ $room['room']['id'] }}_{{ Str::slug($date) }}"
                                                                            value="{{ $date }}">
                                                                        <input type="hidden"
                                                                            name="days[{{ $room['room']['id'] }}_{{ Str::slug($date) }}][]"
                                                                            id="day_{{ $room['room']['id'] }}_{{ Str::slug($date) }}">
                                                                        <input type="hidden"
                                                                            name="branches[{{ $room['room']['id'] }}_{{ Str::slug($date) }}][]"
                                                                            id="branch_{{ $room['room']['id'] }}_{{ Str::slug($date) }}"
                                                                            value="{{ $room['branch']['id'] }}">
                                                                        <input type="hidden"
                                                                            name="cinemas[{{ $room['room']['id'] }}_{{ Str::slug($date) }}][]"
                                                                            id="cinema_{{ $room['room']['id'] }}_{{ Str::slug($date) }}"
                                                                            value="{{ $room['cinema']['id'] }}">
                                                                        <input type="hidden"
                                                                            name="rooms[{{ $room['room']['id'] }}_{{ Str::slug($date) }}][]"
                                                                            id="room_{{ $room['room']['id'] }}_{{ Str::slug($date) }}"
                                                                            value="{{ $room['room']['id'] }}">
                                                                        <input type="hidden"
                                                                            name="seat_structure[{{ $room['room']['id'] }}_{{ Str::slug($date) }}][]"
                                                                            id="seat_structure_{{ $room['room']['id'] }}_{{ Str::slug($date) }}"
                                                                            value="{{ $room['room']['seat_structure'] }}">

                                                                        <input type="hidden"
                                                                            id="day_surcharge_{{ $room['room']['id'] }}_{{ Str::slug($date) }}">

                                                                        <input type="hidden"
                                                                            id="branch_surcharge_{{ $room['room']['id'] }}_{{ Str::slug($date) }}"
                                                                            value="{{ $room['branch']['surcharge'] }}">

                                                                        <input type="hidden"
                                                                            id="type_room_{{ $room['room']['id'] }}_{{ Str::slug($date) }}"
                                                                            value="{{ $room['room']['type_room']['surcharge'] }}">

                                                                        <div class="row">
                                                                            <div class="col-lg-12">
                                                                                <div class="card">
                                                                                    <div class="card-body"
                                                                                        style="border: none">
                                                                                        <div
                                                                                            class="row p-3 bg-white rounded shadow-sm">
                                                                                            <!-- Tên phòng -->
                                                                                            <div class="col-lg-12 mb-4">
                                                                                                <h4
                                                                                                    class="border-bottom pb-2 fw-bold">
                                                                                                    <i
                                                                                                        class="bx bx-building-house text-primary"></i>
                                                                                                    Phòng: <span
                                                                                                        class="text-success">{{ $room['room']['name'] }}</span>
                                                                                                </h4>
                                                                                            </div>

                                                                                            <!-- Loại ngày -->
                                                                                            <div class="col-lg-4 mb-3">
                                                                                                <label
                                                                                                    for="day_id_{{ $room['room']['id'] }}_{{ Str::slug($date) }}"
                                                                                                    class="form-label fw-semibold">
                                                                                                    Loại ngày <span
                                                                                                        class="text-danger">*</span>
                                                                                                </label>
                                                                                                <select
                                                                                                    class="form-select shadow-sm"
                                                                                                    required
                                                                                                    id="day_id_{{ $room['room']['id'] }}_{{ Str::slug($date) }}">
                                                                                                    <option disabled
                                                                                                        selected>
                                                                                                        Chọn
                                                                                                        loại ngày</option>
                                                                                                    @foreach ($days as $day)
                                                                                                        <option
                                                                                                            @disabled($day['id'] == 1 || $day['id'] == 2)
                                                                                                            value="{{ $day['id'] }}">
                                                                                                            {{ $day['name'] }}
                                                                                                        </option>
                                                                                                    @endforeach
                                                                                                </select>
                                                                                            </div>

                                                                                            <!-- Loại suất chiếu -->
                                                                                            <div class="col-lg-4 mb-3">
                                                                                                <label
                                                                                                    for="status_special_{{ $room['room']['id'] }}_{{ Str::slug($date) }}"
                                                                                                    class="form-label fw-semibold">
                                                                                                    Loại suất chiếu <span
                                                                                                        class="text-danger">*</span>
                                                                                                </label>
                                                                                                <select
                                                                                                    class="form-select shadow-sm"
                                                                                                    required
                                                                                                    id="status_special_{{ $room['room']['id'] }}_{{ Str::slug($date) }}">
                                                                                                    <option value=""
                                                                                                        disabled selected>
                                                                                                        Chọn
                                                                                                        loại suất chiếu
                                                                                                    </option>
                                                                                                    @foreach ($specialshowtimes as $specialshowtime)
                                                                                                        <option
                                                                                                            @disabled($specialshowtime['id'] == 1 || $specialshowtime['id'] == 2)
                                                                                                            value="{{ $specialshowtime['id'] }}">
                                                                                                            {{ $specialshowtime['name'] }}
                                                                                                        </option>
                                                                                                    @endforeach
                                                                                                </select>
                                                                                            </div>

                                                                                            <!-- Phụ phí -->
                                                                                            <div class="col-lg-4 mb-3">
                                                                                                <label
                                                                                                    for="price_special_{{ $room['room']['id'] }}_{{ Str::slug($date) }}"
                                                                                                    class="form-label fw-semibold">
                                                                                                    Phụ phí <small
                                                                                                        class="text-muted">(Nếu
                                                                                                        là suất đặc
                                                                                                        biệt)</small>
                                                                                                </label>
                                                                                                <input type="text"
                                                                                                    name="price_specials[{{ $room['room']['id'] }}_{{ Str::slug($date) }}][]"
                                                                                                    class="form-control shadow-sm"
                                                                                                    id="price_special_{{ $room['room']['id'] }}_{{ Str::slug($date) }}"
                                                                                                    placeholder="Nhập phụ phí nếu có">
                                                                                            </div>

                                                                                            <!-- Nút thêm và tự động -->
                                                                                            <div
                                                                                                class="col-lg-12 text-end mt-2">
                                                                                                <button type="button"
                                                                                                    id="autoGenerate_{{ $room['room']['id'] }}_{{ Str::slug($date) }}"
                                                                                                    class="btn btn-success btn-sm shadow-sm me-2">
                                                                                                    <i
                                                                                                        class="bx bxs-copy"></i>
                                                                                                </button>
                                                                                                <button type="button"
                                                                                                    id="addTime_{{ $room['room']['id'] }}_{{ Str::slug($date) }}"
                                                                                                    class="btn btn-primary btn-sm shadow-sm">
                                                                                                    <i
                                                                                                        class="bx bx-plus"></i>
                                                                                                </button>
                                                                                            </div>

                                                                                            <!-- Danh sách xuất chiếu -->
                                                                                            <div class="col-lg-12 mt-4 overflow-auto"
                                                                                                style="height: 300px;"
                                                                                                id="listTime_{{ $room['room']['id'] }}_{{ Str::slug($date) }}">
                                                                                                <!-- Danh sách sẽ được render tại đây -->
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4 d-flex justify-content-end">
                            <button type="button" id="clickForm" class="btn btn-primary">Thêm</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @endif
    @php
        $dataFull = session('dataFull') ?? [];
        $showtimes = session('showtimes') ?? [];
    @endphp
@endsection

@section('script')
    {{-- Start Logic sử lý phần Tab --}}
    <script src="{{ asset('theme/admin/assets/libs/twitter-bootstrap-wizard/jquery.bootstrap.wizard.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/twitter-bootstrap-wizard/prettify.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/@simonwep/pickr/pickr.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/@simonwep/pickr/pickr.es5.min.js') }}"></script>

    <script src="{{ asset('theme/admin/assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/js/pages/form-advanced.init.js') }}"></script>
    <script>
        $('.selectpicker').selectpicker();

        $(document).ready(function() {
            $('#checkAll').change(function() {
                $('.cinema-checkbox').prop('checked', $(this).is(':checked'));
            });
        });
    </script>
    <script>
        let branchs = @json($branchs);
        let movie = @json($movie);


        // Chuyển đổi thời gian về số nguyên
        function convertTimeToMinutes(timeStr) {
            if (!timeStr) return 0;
            const [hours, minutes] = timeStr.split(':').map(Number);
            return hours * 60 + minutes;
        }
        // Chuyển đổi số nguyên về lại thời gian
        function convertMinutesToTime(totalMinutes) {
            const hours = Math.floor(totalMinutes / 60) % 24;
            const minutes = totalMinutes % 60;
            return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;
        }
        // Chọn chi nhánh → load rạp phim
        $('#data_branchs').change(function() {
            let data_branchs = $(this).val();
            $('#listCinemas').empty();
            $('#listRooms').empty();

            data_branchs.forEach(branchId => {
                let branch = branchs.find(b => b.id == branchId);

                let cinemaItems = '';
                branch.cinemas.forEach(cinema => {
                    cinemaItems += `
                <li class="list-group-item bg-body-tertiary">
                    <div class="form-check text-center">
                        <input class="form-check-input cinema-checkbox" name="cinemas[${branch.id}][]" type="checkbox" id="cinema_${cinema.id}" value="${cinema.id}">
                        <label class="form-check-label fw-semibold" for="cinema_${cinema.id}">${cinema.name}</label>
                    </div>
                </li>
            `;
                });

                $('#listCinemas').append(`
            <div class="listCinemas col-sm-3">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <span class="fw-bold"><span class="text-white"><i class="bi bi-building"></i></span> ${branch.name}</span>
                    <div class="form-check m-0">
                        <input class="form-check-input check-all" type="checkbox" value="${branch.id}">
                        <label class="form-check-label fw-semibold"></label>
                    </div>
                </div>
                <ul class="list-group list-group-flush p-2 bg-body-tertiary">
                    ${cinemaItems}
                </ul>
            </div>
        `);
            });
        });

        //  rạp trong chi nhánh
        $('#listCinemas').on('change', '.check-all', function() {
            $(this).closest('.listCinemas').find('.cinema-checkbox')
                .prop('checked', $(this).is(':checked'))
                .trigger('change');
        });


        $(document).on('change', '.cinema-checkbox', function() {
            $('#listRooms').empty();
            $('.cinema-checkbox:checked').each(function() {
                let cinemaId = $(this).val();
                let branch = branchs.find(b => b.cinemas.some(c => c.id == cinemaId));
                let cinema = branch.cinemas.find(c => c.id == cinemaId);

                let roomItems = '';
                cinema.rooms.forEach(room => {
                    roomItems += `
                <li class="list-group-item bg-body-tertiary ">
                    <div class="form-check  text-center">
                        <input class="form-check-input room-checkbox " name="rooms[${cinema.id}][]" type="checkbox" id="room_${room.id}" value="${room.id}">
                        <label class="form-check-label" for="room_${room.id}">${room.name}</label>
                    </div>
                </li>
            `;
                });

                $('#listRooms').append(`
                <div class="listRooms col-sm-3">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="bi bi-camera-reels"></i> ${cinema.name}</span>
                        <div class="form-check m-0">
                            <input class="form-check-input check-all-rooms" type="checkbox" value="${cinema.id}">
                            <label class="form-check-label fw-semibold"></label>
                        </div>
                    </div>
                    <ul class="list-group list-group-flush p-2 bg-body-tertiary">
                        ${roomItems}
                    </ul>
                </div>
        `);
            });
        });

        //  phòng trong từng rạp
        $('#listRooms').on('change', '.check-all-rooms', function() {
            $(this).closest('.listRooms').find('.room-checkbox')
                .prop('checked', $(this).is(':checked'));
        });

        function addTimeRow() {
            const id = Date.now();
            const html = `
                <div class="row align-items-center" id="row_${id}">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="start_time_${id}" class="form-label">
                                Thời gian bắt đầu <span class="text-danger">*</span>
                            </label>
                            <input type="time" required class="form-control" name="start_time[]" data-id="${id}" id="start_time_${id}" aria-label="Chọn thời gian">
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="mb-3">
                            <label for="end_time_${id}" class="form-label">
                                Thời gian kết thúc <span class="text-danger">*</span>
                            </label>
                            <input type="time" required class="form-control" name="end_time[]" id="end_time_${id}" readonly>
                        </div>
                    </div>
                    <div class="col-lg-1 d-flex align-items-center">
                        <button type="button" class="btn btn-danger btn-sm text-white mt-3 removeItem" data-id="${id}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            $('#listTime').append(html);
        }

        addTimeRow();
        $('#addTime').click(function() {
            addTimeRow();
        });
        $('#listTime').on('click', '.removeItem', function() {
            const id = $(this).data('id');
            let count = $('input[name="start_time[]"]').length;
            if (count == 1) {
                toastr.warning('Phải có ít nhất 1 khoảng thời gian !!!');
                return;
            }

            $(`#row_${id}`).remove();
        });
        const operatingStart = "08:00";
        const operatingEnd = "23:59";
        $('#autoGenerate').click(function() {
            const durationMovie = +movie.duration;

            if (!durationMovie) {
                toastr.error('Thời lượng phim không hợp lệ!');
                return;
            }

            const startOperatingMinutes = convertTimeToMinutes(operatingStart); // 8:00
            const endOperatingMinutes = convertTimeToMinutes(operatingEnd); // 23:00

            $('#listTime').empty();
            idRowCounter = Date.now();

            const fixedShowTimes = [
                convertTimeToMinutes("08:00"),
                convertTimeToMinutes("10:00"),
                convertTimeToMinutes("12:00"),
                convertTimeToMinutes("15:00"),
                convertTimeToMinutes("17:00"),
                convertTimeToMinutes("19:00"),
                convertTimeToMinutes("21:00"),
                convertTimeToMinutes("23:00"),
            ];

            let screeningsChecks = [];


            let lastEndTime = startOperatingMinutes; // Lưu lại thời gian kết thúc của suất chiếu trước đó

            fixedShowTimes.forEach(startTime => {
                // Nếu suất chiếu trước đó kết thúc gần sát suất hiện tại -> bỏ qua
                if (lastEndTime !== startOperatingMinutes && lastEndTime + 10 > startTime) {
                    return;
                }

                const endTime = startTime + durationMovie;
                if (endTime > endOperatingMinutes) return;

                // Kiểm tra có chồng lấn suất chiếu cũ không
                let isOverlapping = screeningsChecks.some(existing => {
                    return (
                        (startTime >= existing.startMinutes && startTime < existing
                            .endMinutes) || // Bắt đầu trong khoảng cũ
                        (endTime > existing.startMinutes && endTime <= existing.endMinutes) ||
                        // Kết thúc trong khoảng cũ
                        (startTime <= existing.startMinutes && endTime >= existing
                            .endMinutes) // Bao trùm suất cũ
                    );
                });

                // Kiểm tra nếu có khoảng trống giữa các suất chiếu cũ để chèn vào
                let canFitBetween = screeningsChecks.every(existing => {
                    return (
                        endTime <= existing.startMinutes || startTime >= existing.endMinutes
                    );
                });

                if (!isOverlapping || canFitBetween) {
                    const showStart = convertMinutesToTime(startTime);
                    const showEnd = convertMinutesToTime(endTime);

                    const row = `
                            <div class="row align-items-center" id="row_${idRowCounter}">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="start_time_${idRowCounter}" class="form-label">
                                            Thời gian bắt đầu <span class="text-danger">*</span>
                                        </label>
                                        <input type="time" required class="form-control" name="start_time[]" data-id="${idRowCounter}" id="start_time_${idRowCounter}" value="${showStart}">
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="mb-3">
                                        <label for="end_time_${idRowCounter}" class="form-label">
                                            Thời gian kết thúc <span class="text-danger">*</span>
                                        </label>
                                        <input type="time" required class="form-control" name="end_time[]" id="end_time_${idRowCounter}" value="${showEnd}" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-1 d-flex align-items-center">
                                    <button type="button" class="btn btn-danger btn-sm text-white mt-3 removeItem" data-id="${idRowCounter}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        `;

                    $('#listTime').append(row);
                    idRowCounter++;

                    lastEndTime = endTime; // Cập nhật thời gian kết thúc của suất chiếu hiện tại
                }
            });
        });
        $('#listTime').on('change', 'input[name="start_time[]"]', function() {
            const currentId = $(this).data('id'); // id của div đó
            const currentStart = $(this).val(); // Thời gian của start_time
            const durationMovie = +movie.duration; // thời lượng phim

            if (!currentStart) return;
            const currentStartMinutes = convertTimeToMinutes(currentStart);
            const currentEndMinutes = currentStartMinutes + durationMovie;

            // Thu thập tất cả các dòng xuất chiếu có giá trị start time
            let screenings = [];
            $('#listTime > .row').each(function() {
                let rid = $(this).attr('id').replace('row_', ''); // lấy id của row đó
                let st = $(`#start_time_${rid}`).val(); // lấy giá trị của start_time_id
                if (st) {
                    screenings.push({
                        id: +rid,
                        start: st,
                        startMinutes: convertTimeToMinutes(st)
                    }); // Thêm vào mảng
                }
            });


            // Sắp xếp start_time tăng dần
            screenings.sort((a, b) => a.startMinutes - b.startMinutes);
            // Lấy vị trí của suất chiếu trong mảng
            const index = screenings.findIndex(item => item.id == currentId);

            // Kiểm tra suất chiếu liền trước (nếu có)
            if (index > 0) {
                const prevId = screenings[index - 1].id; // lấy id item trước đó
                const prevStart = $(`#start_time_${prevId}`).val(); // lấy value item trước đó
                const prevStartMinutes = convertTimeToMinutes(prevStart); // Chuyển đổi value đó thành số nguyên
                const prevEndMinutes = prevStartMinutes + durationMovie; // Lấy giá trị đó + thời lượng phim
                if (currentStartMinutes < prevEndMinutes +
                    10) { // Nếu như start time vừa nhập mà nhỏ hơn giá trị đó + thời lượng phim + 10P
                    toastr.error('Xuất chiếu mới phải cách suất chiếu trước ít nhất 10 phút!');
                    $(this).val("");
                    $(`#end_time_${currentId}`).val("");
                    return;
                }
            }

            // Kiểm tra suất chiếu liền sau (nếu có)
            if (index < screenings.length - 1) {
                const nextId = screenings[index + 1].id; // lấy id item sau đó
                const nextStart = $(`#start_time_${nextId}`).val(); // lấy value item sau đó
                const nextStartMinutes = convertTimeToMinutes(nextStart);
                if (currentEndMinutes > nextStartMinutes -
                    10) { // Nếu như end time vừa nhập mà lớn hơn giá trị sau đó + thời lượng phim - 10P
                    toastr.error('Xuất chiếu mới phải kết thúc ít nhất 10 phút trước khi suất chiếu sau bắt đầu!');
                    $(this).val("");
                    $(`#end_time_${currentId}`).val("");
                    return;
                }
            }
            // Nếu thỏa mãn các điều kiện, cập nhật thời gian kết thúc của xuất chiếu hiện tại
            $(`#end_time_${currentId}`).val(convertMinutesToTime(currentEndMinutes));
        });
    </script>
    {{-- End Logic sử lý phần Tab --}}
    {{-- Start Logic sử lý phần chỉnh sửa showtime --}}

    <script>
        let dataFull = @json($dataFull);
        let days = @json($days);
        let date_create = movie.created_at.split("T")[0];
        let release_date = movie.release_date;
        let end_date = movie.end_date;
        let dataShowtimeCheck = [];
        let type_seats = @json($type_seats);
        for (let date in dataFull) {
            for (let branch in dataFull[date]) {
                for (const cinema in dataFull[date][branch]) {
                    let arrayAll = dataFull[date][branch][cinema];
                    arrayAll.forEach(item => {
                        const dt = new Date(date);
                        const day = dt.getDay();
                        if (day == 0 || day == 6) {
                            $(`#day_id_${item.room.id}_${date.trim()}`).val(2);

                            let surchargeDay = 0;


                            days.forEach((itemday) => {
                                if (itemday.id == 2) {
                                    surchargeDay = itemday.day_surcharge;
                                }
                            })

                            $(`#day_${item.room.id}_${date.trim()}`).val(2);

                            $(`#day_surcharge_${item.room.id}_${date.trim()}`).val(surchargeDay);
                        } else {
                            let surchargeDay = 0;
                            days.forEach((itemday) => {
                                if (itemday.id == 1) {
                                    surchargeDay = itemday.day_surcharge;
                                }
                            })
                            $(`#day_${item.room.id}_${date.trim()}`).val(1);

                            $(`#day_id_${item.room.id}_${date.trim()}`).val(1);
                            $(`#day_surcharge_${item.room.id}_${date.trim()}`).val(surchargeDay);
                        }
                        $(`#day_id_${item.room.id}_${date.trim()}`).change(function() {

                            let value = $(this).val();

                            let surchargeDay = 0;
                            days.forEach((itemday) => {
                                if (itemday.id == value) {
                                    surchargeDay = itemday.day_surcharge;
                                }
                            })
                            $(`#day_${item.room.id}_${date.trim()}`).val(value);
                            $(`#day_id_${item.room.id}_${date.trim()}`).val(value);
                            $(`#day_surcharge_${item.room.id}_${date.trim()}`).val(surchargeDay);
                            setSeatStructure();
                        })

                        if (date.trim() >= date_create && date.trim() < release_date) {
                            $(`#price_special_${item.room.id}_${date.trim()}`).prop('readonly', false).val('');

                            $(`#status_special_${item.room.id}_${date.trim()}`).val(2);
                        }

                        if (date.trim() >= release_date && date.trim() <= end_date) {
                            $(`#price_special_${item.room.id}_${date.trim()}`).prop('readonly', true).val('');

                            $(`#status_special_${item.room.id}_${date.trim()}`).val(1);
                        }

                        $(`#price_special_${item.room.id}_${date.trim()}`).on('input', function(e) {
                            e.preventDefault();
                            let valueDiscount = $(this).val().replace(/\D/g, '');
                            let setValueDiscount = new Intl.NumberFormat('vi-VN').format(valueDiscount);
                            $(this).val(setValueDiscount);
                        });
                        $(`#price_special_${item.room.id}_${date.trim()}`).change(function() {
                            setSeatStructure();
                        })
                        let listTimeContainer = $(`#listTime_${item.room.id}_${date.trim()}`);
                        listTimeContainer.empty();

                        item.showtimes.forEach((value, i) => {
                            dataShowtimeCheck.push({
                                id: `${item.room.id}_${date.trim()}_${i}`,
                                room_id: item.room.id,
                                date: date.trim(), // Lưu ngày chiếu
                                index: i,
                                start_time: value.start_time,
                                end_time: value.end_time
                            });
                            listTimeContainer.append(`
                            <div class="row align-items-center mt-2"  id="row_${item.room.id}_${date.trim()}_${i}">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="start_time_${item.room.id}_${date.trim()}_${i}" class="form-label">
                                            Thời gian bắt đầu <span class="text-danger">*</span>
                                        </label>
                                        <input type="time" required data-id="${i}" class="form-control" name="start_time[${item.room.id}_${date.trim()}][]" id="start_time_${item.room.id}_${date.trim()}_${i}" value="${value.start_time}">
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="mb-3">
                                        <label for="end_time_${item.room.id}_${date.trim()}_${i}" class="form-label">
                                            Thời gian kết thúc <span class="text-danger">*</span>
                                        </label>
                                        <input type="time" required class="form-control" name="end_time[${item.room.id}_${date.trim()}][]" id="end_time_${item.room.id}_${date.trim()}_${i}" readonly value="${value.end_time}">
                                    </div>
                                </div>
                                <div class="col-lg-1 d-flex align-items-center">
                                    <button type="button" class="btn btn-danger btn-sm text-white removeShowtime" data-row="row_${item.room.id}_${date.trim()}_${i}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        `);
                        });
                        // console.log(dataShowtimeCheck);

                        function addTimeRowMutiple() {
                            const code_slug = Date.now();
                            const html =
                                `
                            <div class="row align-items-center" id="row_${item.room.id}_${date.trim()}_${code_slug}">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="start_time_${item.room.id}_${date.trim()}_${code_slug}" class="form-label">
                                            Thời gian bắt đầu <span class="text-danger">*</span>
                                        </label>
                                        <input type="time" required class="form-control" name="start_time[${item.room.id}_${date.trim()}][]" data-id="${code_slug}" id="start_time_${item.room.id}_${date.trim()}_${code_slug}" aria-label="Chọn thời gian">
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="mb-3">
                                        <label for="end_time_${item.room.id}_${date.trim()}_${code_slug}" class="form-label">
                                            Thời gian kết thúc <span class="text-danger">*</span>
                                        </label>
                                        <input type="time" required class="form-control" name="end_time[${item.room.id}_${date.trim()}][]" id="end_time_${item.room.id}_${date.trim()}_${code_slug}" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-1 d-flex align-items-center">
                                    <button type="button" class="btn btn-danger btn-sm text-white mt-3 removeShowtime" data-row="row_${item.room.id}_${date.trim()}_${code_slug}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                                                                                                                                                                                                `;
                            listTimeContainer.append(html);
                        }
                        $(`#addTime_${item.room.id}_${date.trim()}`).click(function() {
                            addTimeRowMutiple();
                        });
                        // console.log(dataShowtimeCheck);
                        $(`#autoGenerate_${item.room.id}_${date.trim()}`).click(function() {
                            const durationMovie = +movie.duration;

                            if (!durationMovie) {
                                toastr.error('Thời lượng phim không hợp lệ!');
                                return;
                            }

                            const startOperatingMinutes = convertTimeToMinutes(operatingStart); // 8:00
                            const endOperatingMinutes = convertTimeToMinutes(operatingEnd); // 23:00

                            $(`#listTime_${item.room.id}_${date.trim()}`).empty();
                           

                            const fixedShowTimes = [
                                convertTimeToMinutes("08:00"),
                                convertTimeToMinutes("10:00"),
                                convertTimeToMinutes("12:00"),
                                convertTimeToMinutes("15:00"),
                                convertTimeToMinutes("17:00"),
                                convertTimeToMinutes("19:00"),
                                convertTimeToMinutes("21:00"),
                                convertTimeToMinutes("23:00"),
                            ];

                            let screeningsChecks = [];


                            let lastEndTime =
                            startOperatingMinutes; // Lưu lại thời gian kết thúc của suất chiếu trước đó

                            fixedShowTimes.forEach(startTime => {
                                // Nếu suất chiếu trước đó kết thúc gần sát suất hiện tại -> bỏ qua
                                if (lastEndTime !== startOperatingMinutes && lastEndTime + 10 > startTime) {
                                    return;
                                }

                                const endTime = startTime + durationMovie;
                                if (endTime > endOperatingMinutes) return;

                                // Kiểm tra có chồng lấn suất chiếu cũ không
                                let isOverlapping = screeningsChecks.some(existing => {
                                    return (
                                        (startTime >= existing.startMinutes &&
                                            startTime < existing
                                            .endMinutes) || // Bắt đầu trong khoảng cũ
                                        (endTime > existing.startMinutes && endTime <=
                                            existing.endMinutes) ||
                                        // Kết thúc trong khoảng cũ
                                        (startTime <= existing.startMinutes &&
                                            endTime >= existing
                                            .endMinutes) // Bao trùm suất cũ
                                    );
                                });

                                // Kiểm tra nếu có khoảng trống giữa các suất chiếu cũ để chèn vào
                                let canFitBetween = screeningsChecks.every(existing => {
                                    return (
                                        endTime <= existing.startMinutes || startTime >=
                                        existing.endMinutes
                                    );
                                });

                                if (!isOverlapping || canFitBetween) {
                                    const showStart = convertMinutesToTime(startTime);
                                    const showEnd = convertMinutesToTime(endTime);
                                    idRowCounter = Date.now();
                                    const row = `
                                        <div class="row align-items-center" id="row_${item.room.id}_${date.trim()}_${idRowCounter}">
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="start_time_${item.room.id}_${date.trim()}_${idRowCounter}" class="form-label">
                                                        Thời gian bắt đầu <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="time" required class="form-control" name="start_time[${item.room.id}_${date.trim()}][]" data-id="${idRowCounter}" id="start_time_${item.room.id}_${date.trim()}_${idRowCounter}" aria-label="Chọn thời gian" value="${showStart}">
                                                </div>
                                            </div>
                                            <div class="col-lg-5">
                                                <div class="mb-3">
                                                    <label for="end_time_${item.room.id}_${date.trim()}_${idRowCounter}" class="form-label">
                                                        Thời gian kết thúc <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="time" required class="form-control" name="end_time[${item.room.id}_${date.trim()}][]" id="end_time_${item.room.id}_${date.trim()}_${idRowCounter}" value="${showEnd}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-lg-1 d-flex align-items-center">
                                                <button type="button" class="btn btn-danger btn-sm text-white mt-3 removeShowtime" data-row="row_${item.room.id}_${date.trim()}_${idRowCounter}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    `;

                                    $(`#listTime_${item.room.id}_${date.trim()}`).append(row);
                                    

                                    lastEndTime = endTime; // Cập nhật thời gian kết thúc của suất chiếu hiện tại
                                }
                            });
                        });
                        listTimeContainer.on('change', 'input[name^="start_time"]', function() {


                            const currentIdFilter = $(this).data('id'); // Lấy id khi click
                            const currentStartFilter = $(this).val(); // Lấy giá trị của phần time thay đổi
                            const durationMovie = +movie.duration; // Lấy thời lượng phim

                            if (!currentStartFilter) return;
                            const currentStartMinutes = convertTimeToMinutes(
                                currentStartFilter); // Chuyển về số
                            const currentEndMinutes = currentStartMinutes +
                                durationMovie; // Thời gian bắt đầu + Thời lượng phim
                            let screenings = [];

                            // Chọn tất cả các hàng trong danh sách thời gian
                            $(`#listTime_${item.room.id}_${date.trim()} .row`).each(function() {
                                let rid = $(this).attr('id').replace(
                                    `row_${item.room.id}_${date.trim()}_`, ''); // Lấy ID chính xác
                                let st = $(`#start_time_${item.room.id}_${date.trim()}_${rid}`)
                                    .val(); // Lấy giá trị thời gian bắt đầu

                                if (st) {
                                    screenings.push({
                                        id: rid,
                                        start: st,
                                        startMinutes: convertTimeToMinutes(st)
                                    });
                                }
                            });


                            screenings.sort((a, b) => a.startMinutes - b.startMinutes);

                            const indexArray = screenings.findIndex(itemIndex => Number(itemIndex.id) ===
                                Number(currentIdFilter));
                            console.log(indexArray, 'indexArray');
                            if (indexArray > 0) {
                                const prevScreening = screenings[indexArray - 1]; // Lấy đối tượng trước đó
                                const prevId = prevScreening.id; // Lấy id của nó
                                const prevStart = $(`#start_time_${item.room.id}_${date.trim()}_${prevId}`)
                                    .val(); // Lấy thời gian bắt đầu
                                const prevStartMinutes = convertTimeToMinutes(
                                    prevStart); // Chuyển đổi về số
                                const prevEndMinutes = prevStartMinutes +
                                    durationMovie; // Công thêm thời lượng phim để biết giờ gian kết thúc

                                if (currentStartMinutes < prevEndMinutes +
                                    10
                                ) { // Nếu thời gian đang chọn nhỏ hơn thời gian kết thúc của phim trước đó thì
                                    toastr.error(
                                        'Suất chiếu mới phải cách suất chiếu trước ít nhất 10 phút!');
                                    $(this).val(""); // Reset thời gian đang trọn
                                    $(`#end_time_${item.room.id}_${date.trim()}_${currentIdFilter}`).val(
                                        ""); // Chuyển thời gian kết thúc của thời gian đang chọn về rỗng
                                    return;
                                }
                            }

                            if (indexArray < screenings.length - 1) {
                                const nextId = screenings[indexArray + 1].id; // lấy id item sau đó
                                const nextStart = $(`#start_time_${item.room.id}_${date.trim()}_${nextId}`)
                                    .val(); // lấy value item sau đó
                                const nextStartMinutes = convertTimeToMinutes(
                                    nextStart); // Chuyển thời gian đó về số
                                if (currentEndMinutes > nextStartMinutes -
                                    10
                                ) { // Nếu như end time vừa nhập mà lớn hơn giá trị sau đó + thời lượng phim - 10P
                                    toastr.error(
                                        'Suất chiếu mới phải kết thúc ít nhất 10 phút trước khi suất chiếu sau bắt đầu!'
                                    );
                                    $(this).val(""); // Rest giá trị của thời gian vừa chọn
                                    $(`#end_time_${item.room.id}_${date.trim()}_${currentIdFilter}`).val(
                                        ""); // Chuyển thời gian kết thúc của thời gian đang chọn về rỗng
                                    return;
                                }
                            }


                            $(`#end_time_${item.room.id}_${date.trim()}_${currentIdFilter}`).val(
                                convertMinutesToTime(currentEndMinutes));

                        });
                        $(document).on("click", ".removeShowtime", function() {
                            let row = $(this).closest(".row");
                            let listTimeContainer = row.closest(".card-body").find("[id^='listTime']");
                            let count = listTimeContainer.find(".row[id^='row_']").length;

                            if (count === 1) {
                                toastr.error('Phải có ít nhất 1 khoảng thời gian trong ngày này !!!');
                                return;
                            }
                            row.remove();
                        });
                        const setSeatStructure = () => {
                            let seat_structure = JSON.parse($(`#seat_structure_${item.room.id}_${date.trim()}`)
                                .val());
                            let surchargeMovie = +movie.surcharge;
                            let surchargeBranch = +$(`#branch_surcharge_${item.room.id}_${date.trim()}`).val();
                            let surchargeTypeRoom = +$(`#type_room_${item.room.id}_${date.trim()}`).val();
                            let surchargeDay = +$(`#day_surcharge_${item.room.id}_${date.trim()}`).val();
                            let surchargeSpecial = $(`#price_special_${item.room.id}_${date.trim()}`).val() ??
                                0;
                            let dataSeatStructure = [];
                            seat_structure.forEach((itemSeatStructure) => {
                                dataSeatStructure.push({
                                    ...itemSeatStructure,
                                    price: +type_seats.filter((element) => element.id ==
                                            itemSeatStructure.type_seat_id)[0].price +
                                        surchargeMovie + surchargeBranch + surchargeTypeRoom +
                                        +surchargeDay + Number(surchargeSpecial.replace(/\./g,
                                            "")),
                                });

                            });
                            $(`#seat_structure_${item.room.id}_${date.trim()}`).val(JSON.stringify(
                                dataSeatStructure));
                        }
                        setSeatStructure();

                    });

                }
            }
        }
        $('#clickForm').click(function() {
            let form = $('.formSubmit');

            if ($('.formSubmit input[name^="start_time"]').length === 0) {
                toastr.error("Bạn phải thêm ít nhất một suất chiếu!");
                return;
            }

            if (form[0].checkValidity()) {
                form.submit();
            } else {
                form[0].reportValidity();
            }

        });
    </script>
@endsection
