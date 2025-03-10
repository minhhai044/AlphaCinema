@extends('admin.layouts.master')



@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-sm-flex align-items-center justify-content-between text-center row">
            <div class="text-center col-lg-9">
                <h4 class="mb-2 text-primary fw-bold">
                    <i class="bi bi-film"></i> Nhân bản suất chiếu cho phim :
                    <span class="text-info">{{ $movie->name }}</span>
                </h4>
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
                    <li class="breadcrumb-item active">Nhân bản</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<form class="FormCreate" action="{{route('admin.showtimes.storeCopies')}}" method="POST">
    @csrf
    <input type="hidden" name="seat_structure" id="seat_structure">
    <input type="hidden" name="branch_id" value="{{$showtime[0]['branch']['id']}}">
    <input type="hidden" name="movie_id" value="{{$showtime[0]['movie']['id']}}">
    <input type="hidden" name="cinema_id" value="{{$showtime[0]['cinema']['id']}}">
    <input type="hidden" name="room_id" value="{{$showtime[0]['room']['id']}}">
    <input type="hidden" name="day_id" id="day_id">

    <div class="row">
        <div class="col-lg-9">
            @foreach ($date as $key => $day)
            <div class="card shadow-lg border-0 rounded-1">
                <div class="card-body" style="border: none">
                    <div class="row">
                        <!-- Ngày chiếu -->
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="date{{$key}}" class="form-label">
                                    Ngày <span style="color: red" class="required">*</span>
                                </label>

                                <input type="date" class="form-control" readonly name="date[{{$key}}]" id="date{{$key}}"
                                    value="{{$day}}" required>
                                @error('date')
                                    <span class="text-danger"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        <!-- Ngày -->
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="day_id{{$key}}" class="form-label">
                                    Loại ngày <span style="color: red" class="required">*</span>
                                </label>
                                <select name="day_id{{$key}}" class="form-select @error('day_id') is-invalid @enderror"
                                    required id="day_id{{$key}}">
                                    <option value="" disabled selected>Loại ngày</option>
                                    @foreach ($days as $day)
                                        <option @disabled($day['id'] == 1 || $day['id'] == 2) value="{{ $day['id'] }}">
                                            {{ $day['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('day_id')
                                    <span class="text-danger"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        {{-- Loại xuất chiếu --}}
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="status_special{{$key}}" class="form-label">
                                    Loại suất chiếu <span style="color: red" class="required">*</span>
                                </label>
                                <select class="form-select @error('status_special') is-invalid @enderror" required
                                    id="status_special{{$key}}">
                                    <option value="" disabled selected>Loại suất chiếu</option>
                                    @foreach ($specialshowtimes as $specialshowtime)
                                        <option @disabled($specialshowtime['id'] == 1 || $specialshowtime['id'] == 2)
                                            value="{{ $specialshowtime['id'] }}">{{ $specialshowtime['name'] }}</option>
                                    @endforeach
                                </select>
                                @error('day_id')
                                    <span class="text-danger"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="price_special{{$key}}" class="form-label">
                                    Phụ phí <span class="text-secondary">(Nếu là suất đặc biệt !!!)</span>
                                </label>
                                <input type="text" name="price_special[{{$key}}]"
                                    class="@error('price_special') is-invalid @enderror form-control"
                                    id="price_special{{$key}}"
                                    placeholder="Chỉ có suất chiếu đặc biệt mới có phụ phí !!!">
                                @error('price_special')
                                    <span class="text-danger"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        <!-- Nút thêm xuất chiếu -->
                        <div class="col-lg-12">
                            <button type="button" id="addTime{{$key}}"
                                class="btn btn-primary btn-sm float-end ms-3">Thêm thời
                                gian</button>
                            <button type="button" id="autoGenerate{{$key}}" class="btn btn-success btn-sm float-end">Tự
                                động</button>
                        </div>
                        <!-- Danh sách xuất chiếu -->
                        <div class="col-lg-12 overflow-auto" style="height: 300px;" id="listTime{{$key}}">

                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>


        <div class="col-lg-3">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-body p-4">
                    <h4 class="mb-3 text-center text-warning fw-bold">
                        <i class="fas fa-info-circle me-2"></i> Thông tin chi tiết
                    </h4>

                    @if (!empty($showtime))
                        <div class="p-3 rounded bg-white shadow-sm border">
                            <p class="mb-3"><i class="fas fa-map-marker-alt text-danger me-2"></i>
                                <strong>Chi nhánh :</strong> {{$showtime[0]['branch']['name']}}
                            </p>
                            <p class="mb-3"><i class="fas fa-film text-primary me-2"></i>
                                <strong>Rạp :</strong> {{$showtime[0]['cinema']['name']}}
                            </p>
                            <p class="mb-3"><i class="fas fa-chair text-success me-2"></i>
                                <strong>Phòng :</strong> {{$showtime[0]['room']['name']}}
                            </p>
                        </div>
                    @else
                        <div class="alert alert-warning text-center mt-3">
                            <i class="fas fa-exclamation-circle"></i> Không có thông tin
                        </div>
                    @endif

                    <!-- Group nút bấm -->
                    <div class="d-flex justify-content-between mt-4">
                        <button id="submitForm" type="button" onclick="return confirm('Bạn có chắc chắn không !!!')"
                            class="btn btn-primary btn-sm px-3">
                            <i class="fas fa-plus-circle me-1"></i> Thêm
                        </button>
                        <a href="{{ route('admin.showtimes.index') }}">
                            <button type="button" class="btn btn-danger btn-sm px-3">
                                <i class="fas fa-arrow-left me-1"></i> Quay lại
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>
<input type="hidden" id="surchargeDay">
<input type="hidden" id="surchargeSpecial">
@php
    $appUrl = env('APP_URL');
@endphp
@endsection
@section('script')
    <script>
        const appUrl = @json($appUrl);
        const specialshowtimes = @json($specialshowtimes);
        const days = @json($days);
        const dates = @json($date);
        const movie = @json($movie);
        const showtime = @json($showtime);
        let type_seats = @json($type_seats);


        let dataShowtimeCheck = [];


        function convertTimeToMinutes(timeStr) {
            if (!timeStr) return 0;
            const [hours, minutes] = timeStr.split(':').map(Number);
            return hours * 60 + minutes;
        }

        function convertMinutesToTime(totalMinutes) {
            const hours = Math.floor(totalMinutes / 60) % 24;
            const minutes = totalMinutes % 60;
            return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;
        }

        dates.forEach((item, index) => {
            // Set day
            const date = new Date(item);
            const day = date.getDay();

            if ($("#day_id" + index).length) {
                if (day === 0 || day === 6) {
                    $("#day_id" + index).val(2);


                    let surchargeDayValue = $('#surchargeDay').val();
                    let DayIdValue = $('#day_id').val();

                    let surchargeArray = surchargeDayValue ? JSON.parse(surchargeDayValue) : [];
                    let DayIdArray = DayIdValue ? JSON.parse(DayIdValue) : [];
                    days.forEach((item) => {
                        if (item.id == 2) {
                            let newSurcharge = {
                                id: index,
                                day_surcharge: item.day_surcharge
                            };
                            let newDay = {
                                id: index,
                                day_id: item.id
                            };
                            surchargeArray.push(newSurcharge);
                            DayIdArray.push(newDay);
                        }
                    })
                    $('#surchargeDay').val(JSON.stringify(surchargeArray));
                    $('#day_id').val(JSON.stringify(DayIdArray));
                } else {
                    $("#day_id" + index).val(1);
                    let surchargeDayValue = $('#surchargeDay').val();
                    let DayIdValue = $('#day_id').val();
                    let DayIdArray = DayIdValue ? JSON.parse(DayIdValue) : [];
                    let surchargeArray = surchargeDayValue ? JSON.parse(surchargeDayValue) : [];
                    days.forEach((item) => {
                        if (item.id == 1) {
                            let newSurcharge = {
                                id: index,
                                day_surcharge: item.day_surcharge
                            };
                            let newDay = {
                                id: index,
                                day_id: item.id
                            };
                            DayIdArray.push(newDay);
                            surchargeArray.push(newSurcharge);
                        }
                    })
                    $('#surchargeDay').val(JSON.stringify(surchargeArray));
                    $('#day_id').val(JSON.stringify(DayIdArray));
                }
            }

            $('#day_id' + index).change(function () {
                let value = $(this).val();
                let surchargeArray = $('#surchargeDay').val() ? JSON.parse($('#surchargeDay').val()) : [];
                let dayIdArray = $('#day_id').val() ? JSON.parse($('#day_id').val()) : [];

                days.forEach(item => {
                    if (item.id == value) {
                        let updatedSurcharge = surchargeArray.some(element => {
                            if (element.id == index) {
                                element.day_surcharge = item.day_surcharge;
                                return true;
                            }
                            return false;
                        });

                        if (!updatedSurcharge) {
                            surchargeArray.push({
                                id: index,
                                day_surcharge: item.day_surcharge
                            });
                        }

                        let updatedDayId = dayIdArray.some(element => {
                            if (element.id == index) {
                                element.day_id = item.id;
                                return true;
                            }
                            return false;
                        });

                        if (!updatedDayId) {
                            dayIdArray.push({
                                id: index,
                                day_id: item.id
                            });
                        }
                    }
                });

                $('#surchargeDay').val(JSON.stringify(surchargeArray));
                $('#day_id').val(JSON.stringify(dayIdArray));
            });



            // Set loại suất chiếu
            let date_create = movie.created_at.split("T")[0];
            let release_date = movie.release_date;
            let end_date = movie.end_date;


            // Thêm . khi nhập số 
            $('#price_special' + index).on('input', function (e) {
                e.preventDefault();
                let valueDiscount = $(this).val().replace(/\D/g, '');
                let setValueDiscount = new Intl.NumberFormat('vi-VN').format(valueDiscount);
                $(this).val(setValueDiscount);
            });

            // Nếu Trước thời gian phát hành thì sẽ là suất chiếu đặc biệt
            if (item >= date_create && item < release_date) {
                $('#price_special' + index).prop('disabled', false);

                $('#status_special' + index).val(2);

                let surchargeSpecialValue = $('#surchargeSpecial').val();

                let surchargeArray = surchargeSpecialValue ? JSON.parse(surchargeSpecialValue) : [];
                specialshowtimes.forEach((item) => {
                    if (item.id == 2) {
                        let newSurcharge = {
                            id: index,
                            special_surcharge: 0
                        };
                        surchargeArray.push(newSurcharge);
                    }
                })
                $('#surchargeSpecial').val(JSON.stringify(surchargeArray));
            }
            // Nếu đang trong thời gian phát hành thì sẽ là suất chiếu thường
            if (item >= release_date && item <= end_date) {
                $('#price_special' + index).prop('disabled', true).val('');

                $('#status_special' + index).val(1);
                let surchargeSpecialValue = $('#surchargeSpecial').val();
                let surchargeArray = surchargeSpecialValue ? JSON.parse(surchargeSpecialValue) : [];
                specialshowtimes.forEach((item) => {
                    if (item.id == 1) {
                        let newSurcharge = {
                            id: index,
                            special_surcharge: 0
                        };
                        surchargeArray.push(newSurcharge);
                    }
                })
                $('#surchargeSpecial').val(JSON.stringify(surchargeArray));
            }

            $('#price_special' + index).change(function () {
                let value = $(this).val();
                let surchargeSpecialValue = $('#surchargeSpecial').val();
                let surchargeArray = surchargeSpecialValue ? JSON.parse(surchargeSpecialValue) : [];

                surchargeArray.forEach(element => {
                    if (element.id == index) {

                        element.special_surcharge = value;
                    }
                });

                $('#surchargeSpecial').val(JSON.stringify(surchargeArray));
                console.log($('#surchargeSpecial').val());

            });
            let listTimeContainer = $("#listTime" + index);
            listTimeContainer.empty();

            if (showtime.length > 0) {
                showtime.forEach((value, i) => {
                    listTimeContainer.append(`
                        <div class="row align-items-center mt-2" id="row_${index}_${i}">
                            <div class="col-lg-5">
                                <div class="mb-3">
                                    <label for="start_time_${index}_${i}" class="form-label">
                                        Thời gian bắt đầu <span class="text-danger">*</span>
                                    </label>
                                    <input type="time" required class="form-control" name="start_time[${index}][]" id="start_time_${index}_${i}" value="${value.start_time}">
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="mb-3">
                                    <label for="end_time_${index}_${i}" class="form-label">
                                        Thời gian kết thúc <span class="text-danger">*</span>
                                    </label>
                                    <input type="time" required class="form-control" name="end_time[${index}][]" id="end_time_${index}_${i}" readonly value="${value.end_time}">
                                </div>
                            </div>
                            <div class="col-lg-2 d-flex align-items-center">
                                <button type="button" class="btn btn-danger btn-sm text-white removeShowtime" data-row="row_${index}_${i}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                        `);
                });
            } else {
                listTimeContainer.append('<p class="text-center text-muted">Chưa có suất chiếu nào.</p>');
            }

            function addTimeRow() {
                const id = Date.now();
                const html = `
                        <div class="row align-items-center" id="row_${index}_${id}">
                            <div class="col-lg-5">
                                <div class="mb-3">
                                    <label for="start_time_${index}_${id}" class="form-label">
                                        Thời gian bắt đầu <span class="text-danger">*</span>
                                    </label>
                                    <input type="time" required class="form-control" name="start_time[${index}][]" data-id="${id}" id="start_time_${index}_${id}" aria-label="Chọn thời gian">
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="mb-3">
                                    <label for="end_time_${index}_${id}" class="form-label">
                                        Thời gian kết thúc <span class="text-danger">*</span>
                                    </label>
                                    <input type="time" required class="form-control" name="end_time[${index}][]" id="end_time_${index}_${id}" readonly>
                                </div>
                            </div>
                            <div class="col-lg-2 d-flex align-items-center">
                                <button type="button" class="btn btn-danger btn-sm text-white mt-3 removeShowtime" data-row="row_${index}_${id}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                                                                                                                                                                                                `;
                $('#listTime' + index).append(html);
            }

            $('#addTime' + index).click(function () {
                addTimeRow();
            });


            $('#listTime' + index).on('change', 'input[name^="start_time"]', function () {


                const currentId = $(this).data('id');
                const currentStart = $(this).val();
                const durationMovie = +movie.duration;


                if (!currentStart) return;
                const currentStartMinutes = convertTimeToMinutes(currentStart);
                const currentEndMinutes = currentStartMinutes + durationMovie;

                let screenings = [];

                $(`#listTime${index} > .row`).each(function () {
                    let rid = $(this).attr('id').replace(`row_${index}_`, '');
                    let st = $(`#start_time_${index}_${rid}`).val();



                    if (st) {
                        screenings.push({ id: +rid, start: st, startMinutes: convertTimeToMinutes(st) });
                    }
                });
                screenings.sort((a, b) => a.startMinutes - b.startMinutes);

                const indexArray = screenings.findIndex(item => Number(item.id) === Number(currentId));

                if (indexArray > 0) {
                    const prevScreening = screenings[indexArray - 1]; // Lấy đối tượng trước đó
                    if (!prevScreening || !prevScreening.id) {
                        console.error("Lỗi: Không tìm thấy suất chiếu trước đó.");
                        return;
                    }

                    const prevId = prevScreening.id;
                    const prevStart = $(`#start_time_${index}_${prevId}`).val();

                    if (!prevStart) {
                        console.error("Lỗi: Không tìm thấy thời gian bắt đầu của suất chiếu trước.");
                        return;
                    }

                    const prevStartMinutes = convertTimeToMinutes(prevStart);
                    const prevEndMinutes = prevStartMinutes + durationMovie;

                    if (currentStartMinutes < prevEndMinutes + 10) {
                        toastr.error('Suất chiếu mới phải cách suất chiếu trước ít nhất 10 phút!');
                        $(this).val("");
                        $(`#end_time_${index}_${currentId}`).val("");
                        return;
                    }
                }

                if (indexArray < screenings.length - 1) {
                    const nextId = screenings[indexArray + 1].id; // lấy id item sau đó
                    const nextStart = $(`#start_time_${index}_${nextId}`).val(); // lấy value item sau đó
                    const nextStartMinutes = convertTimeToMinutes(nextStart);
                    if (currentEndMinutes > nextStartMinutes - 10) { // Nếu như end time vừa nhập mà lớn hơn giá trị sau đó + thời lượng phim - 10P
                        toastr.error('Xuất chiếu mới phải kết thúc ít nhất 10 phút trước khi suất chiếu sau bắt đầu!');
                        $(this).val("");
                        $(`#end_time_${index}_${currentId}`).val("");
                        return;
                    }
                }
                $(`#end_time_${index}_${currentId}`).val(convertMinutesToTime(currentEndMinutes));

            });

            $('#autoGenerate' + index).click(function () {
                const durationMovie = +movie.duration;
                let dataDate = $('#date' + index).val();
                if (!durationMovie) {
                    toastr.error('Thời lượng phim không hợp lệ!');
                    return;
                }

                const startOperatingMinutes = convertTimeToMinutes("08:00"); // Giờ mở cửa
                const endOperatingMinutes = convertTimeToMinutes("23:00"); // Giờ đóng cửa

                $('#listTime' + index).empty();
                let idRowCounter = Date.now();

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
                if (dataShowtimeCheck) {
                    dataShowtimeCheck.forEach((item) => {
                        screeningsChecks.push({
                            id: item.id,
                            startMinutes: convertTimeToMinutes(item.start_time),
                            endMinutes: convertTimeToMinutes(item.end_time)
                        });
                    });
                }

                let lastEndTime = startOperatingMinutes;

                fixedShowTimes.forEach(startTime => {
                    if (lastEndTime !== startOperatingMinutes && lastEndTime + 10 > startTime) {
                        return;
                    }

                    const endTime = startTime + durationMovie;
                    if (endTime > endOperatingMinutes) return;

                    let isOverlapping = screeningsChecks.some(existing => {
                        return (
                            (startTime >= existing.startMinutes && startTime < existing.endMinutes) ||
                            (endTime > existing.startMinutes && endTime <= existing.endMinutes) ||
                            (startTime <= existing.startMinutes && endTime >= existing.endMinutes)
                        );
                    });

                    let canFitBetween = screeningsChecks.every(existing => {
                        return (
                            endTime <= existing.startMinutes || startTime >= existing.endMinutes
                        );
                    });

                    if (!isOverlapping || canFitBetween) {
                        const showStart = convertMinutesToTime(startTime);
                        const showEnd = convertMinutesToTime(endTime);

                        const row = `
                            <div class="row align-items-center" id="row_${index}_${idRowCounter}">
                                <div class="col-lg-5">
                                    <div class="mb-3">
                                        <label for="start_time_${index}_${idRowCounter}" class="form-label">
                                            Thời gian bắt đầu <span class="text-danger">*</span>
                                        </label>
                                        <input type="time" required class="form-control" name="start_time[${index}][]" data-id="${idRowCounter}" id="start_time_${index}_${idRowCounter}" value="${showStart}">
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="mb-3">
                                        <label for="end_time_${index}_${idRowCounter}" class="form-label">
                                            Thời gian kết thúc <span class="text-danger">*</span>
                                        </label>
                                        <input type="time" required class="form-control" name="end_time[${index}][]" id="end_time_${index}_${idRowCounter}" value="${showEnd}" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-2 d-flex align-items-center">
                                    <button type="button" class="btn btn-danger btn-sm text-white removeShowtime" data-row="row_${index}_${idRowCounter}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        `;

                        $('#listTime' + index).append(row);
                        idRowCounter++;
                        lastEndTime = endTime;
                    }
                });
            });
        });

        // xoá suất chiếu
        $(document).on("click", ".removeShowtime", function () {
            let row = $(this).closest(".row");
            let listTimeContainer = row.closest(".card-body").find("[id^='listTime']");
            let count = listTimeContainer.find(".row[id^='row_']").length;

            if (count === 1) {
                toastr.error('Phải có ít nhất 1 khoảng thời gian trong ngày này !!!');
                return;
            }

            row.fadeOut(300, function () {
                $(this).remove();
            });
        });



        $('#submitForm').click(function (e) {
            let form = $('.FormCreate');
            if (form[0].checkValidity()) {

                let surchargeMovie = +movie.surcharge;
                let surchargeBranch = +showtime[0]['branch']['surcharge'];
                let surchargeTypeRoom = +showtime[0]['room'].type_room.surcharge;
                let surchargeDay = JSON.parse($('#surchargeDay').val());
                let surchargeSpecial = JSON.parse($('#surchargeSpecial').val());
                let seat_structure = JSON.parse(showtime[0]['room']['seat_structure']);

                let new_seat_structure = [];

                surchargeDay.forEach((day, day_id) => {
                    surchargeSpecial.forEach((special, special_id) => {
                        let specialSurcharge = special && special.special_surcharge ? String(special.special_surcharge) : "0";

                        if (day_id == special_id) {
                            let temp_structure = []; // Tạo mảng riêng cho mỗi lần lặp
                            seat_structure.forEach((item) => {
                                temp_structure.push({
                                    ...item,
                                    price: +type_seats.filter((element) => element.id == item.type_seat_id)[0].price +
                                        surchargeMovie + surchargeBranch + surchargeTypeRoom +
                                        +day.day_surcharge + Number(specialSurcharge.replace(/\./g, "")),
                                });
                            });

                            new_seat_structure.push(temp_structure);
                        }
                    });
                });


                $('#seat_structure').val(JSON.stringify(new_seat_structure));
                $(form).submit();
            } else {
                form[0].reportValidity();
            }
        });



    </script>
@endsection