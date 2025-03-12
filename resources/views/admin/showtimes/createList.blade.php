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
    </style>
    <link href="{{ asset('theme/admin/assets/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('theme/admin/assets/css/icons.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('theme/admin/assets/css/app.min.css') }}" rel="stylesheet" />
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
                                        <label for="data_branchs">Chọn chi nhánh</label>
                                        <select style="border: 1px solid #ded9d9 !important;" id="data_branchs" class="selectpicker form-control " multiple
                                            data-live-search="true" data-actions-box="true" name="branches[]">

                                            @foreach ($branchs as $branch)
                                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="datepicker-multiple">Chọn ngày</label>
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
        <h1>Danh sách suất chiếu</h1>
    @endif

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
        $('#listTime').on('change', 'input[name="start_time[]"]', function () {
            const currentId = $(this).data('id'); // id của div đó
            const currentStart = $(this).val(); // Thời gian của start_time
            const durationMovie = +movie.duration;// thời lượng phim

            if (!currentStart) return;
            const currentStartMinutes = convertTimeToMinutes(currentStart);
            const currentEndMinutes = currentStartMinutes + durationMovie;

            // Thu thập tất cả các dòng xuất chiếu có giá trị start time
            let screenings = [];
            $('#listTime > .row').each(function () {
                let rid = $(this).attr('id').replace('row_', ''); // lấy id của row đó
                let st = $(`#start_time_${rid}`).val(); // lấy giá trị của start_time_id
                if (st) {
                    screenings.push({ id: +rid, start: st, startMinutes: convertTimeToMinutes(st) }); // Thêm vào mảng
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
                if (currentStartMinutes < prevEndMinutes + 10) { // Nếu như start time vừa nhập mà nhỏ hơn giá trị đó + thời lượng phim + 10P
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
                if (currentEndMinutes > nextStartMinutes - 10) { // Nếu như end time vừa nhập mà lớn hơn giá trị sau đó + thời lượng phim - 10P
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

    <script></script>
@endsection
