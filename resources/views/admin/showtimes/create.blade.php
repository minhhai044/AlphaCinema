@extends('admin.layouts.master')

@section('style')
    <!-- Nếu cần thêm CSS tùy chỉnh, bạn có thể thêm ở đây -->
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class=" d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Thêm suất chiếu cho phim
                <span class="fw-semibold fs-4 text-info">{{ $movie->name }}</span>
                <p class="text-success fs-5 mt-2">

                    {{ \Carbon\Carbon::parse($movie->created_at)->format('d/m/Y') }}
                    -
                    {{ \Carbon\Carbon::parse($movie->release_date)->format('d/m/Y') }}
                    - {{\Carbon\Carbon::parse($movie->end_date)->format('d/m/Y') }}
                </p>
            </h4>

            <div class="page-title-right">
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
<!-- end page title -->

<form class="FormCreate" action="{{route('admin.showtimes.store')}}" method="POST">
    @csrf
    <input type="hidden" name="seat_structure" id="seat_structure">
    <input type="hidden" name="slug" value="{{ $slug }}">
    <input type="hidden" name="movie_id" value="{{$movie->id }}">
    <input type="hidden" name="day_id">
    <input type="hidden" name="status_special">

    <div class="row">
        <div class="col-lg-9">
            <div class="card">
                <div class="card-body" style="border: none">
                    <div class="row">
                        <!-- Các trường thông tin cơ bản -->
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="branch_id" class="form-label">
                                    Chi nhánh <span style="color: red" class="required">*</span>
                                </label>
                                <select name="branch_id" class="form-select @error('branch_id') is-invalid @enderror"
                                    required id="branch_id">
                                    <option value="" disabled selected>Chọn chi nhánh</option>
                                    @foreach ($branchs as $branch)
                                        <option value="{{ $branch['id'] }}">{{ $branch['name'] }}</option>
                                    @endforeach
                                </select>
                                @error('branch_id')
                                    <span class="text-danger"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        <!-- Rạp phim -->
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="cinema_id" class="form-label">
                                    Rạp phim <span style="color: red" class="required">*</span>
                                </label>
                                <select name="cinema_id" class="form-select @error('cinema_id') is-invalid @enderror"
                                    required id="cinema_id">
                                    <option value="" disabled selected>Chọn rạp</option>
                                </select>
                                @error('cinema_id')
                                    <span class="text-danger"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        <!-- Phòng -->
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="room_id" class="form-label">
                                    Phòng <span style="color: red" class="required">*</span>
                                </label>
                                <select name="room_id" class="form-select @error('room_id') is-invalid @enderror"
                                    required id="room_id">
                                    <option value="" disabled selected>Chọn phòng</option>
                                    {{-- @foreach ($rooms as $room)
                                    <option value="{{ $room['id'] }}">{{ $room['name'] }}</option>
                                    @endforeach --}}
                                </select>
                                @error('room_id')
                                    <span class="text-danger"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        <!-- Ngày chiếu -->
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="date" class="form-label">
                                    Ngày <span style="color: red" class="required">*</span>
                                </label>
                                <input type="date" class="form-control" name="date" id="date" required>
                                @error('date')
                                    <span class="text-danger"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        <!-- Ngày -->
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="day_id" class="form-label">
                                    Loại ngày <span style="color: red" class="required">*</span>
                                </label>
                                <select class="form-select @error('day_id') is-invalid @enderror" required id="day_id">
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
                                <label for="status_special" class="form-label">
                                    Loại suất chiếu <span style="color: red" class="required">*</span>
                                </label>
                                <select class="form-select @error('status_special') is-invalid @enderror" required
                                    id="status_special">
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
                                <label for="price_special" class="form-label">
                                    Phụ phí <span class="text-secondary">(Nếu là suất đặc biệt !!!)</span>
                                </label>
                                <input type="text" name="price_special"
                                    class="@error('price_special') is-invalid @enderror form-control" id="price_special"
                                    placeholder="Chỉ có suất chiếu đặc biệt mới có phụ phí !!!">
                                @error('price_special')
                                    <span class="text-danger"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        <!-- Nút thêm xuất chiếu -->
                        <div class="col-lg-12">
                            <button type="button" id="addTime" class="btn btn-primary float-end ms-3">Thêm thời
                                gian</button>
                            <button type="button" id="autoGenerate" class="btn btn-success float-end">Tự động</button>
                        </div>
                        <!-- Danh sách xuất chiếu -->
                        <div class="col-lg-12" id="listTime">

                        </div>
                    </div>
                </div>

                <div class="m-3">
                    <button id="submitForm" type="button" onclick="return confirm('Bạn có chắc chắn không !!!')" class="btn btn-primary">Thêm</button>
                    <a href="{{ route('admin.showtimes.index') }}"><button type="button" class="btn btn-danger">Quay lại</button></a>
                </div>
            </div>
        </div>

        <!-- Cột bên phải hiển thị xuất chiếu đã có trong ngày -->
        <div class="col-lg-3">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body">
                    <h4 class="mb-3 text-center text-warning">Suất chiếu hiện có trong ngày</h4>

                    <div class="list-group" id="listShowtime">

                    </div>
                </div>
            </div>
        </div>



    </div>
</form>
<input type="hidden" id="duration" value="{{$movie->duration}}">
<input type="hidden" id="surchargeMovie" value="{{$movie->surcharge}}">
<input type="hidden" id="surchargeBranch">
<input type="hidden" id="surchargeTypeRoom">
<input type="hidden" id="surchargeDay">

@php
    $appUrl = env('APP_URL');
@endphp
@endsection

@section('script')
    <script>

        const appUrl = @json($appUrl);
        const branchs = @json($branchs);
        const data = @json($branchsRelation);
        const roomsRelation = @json($roomsRelation);
        const rooms = @json($rooms);
        const movie = @json($movie);
        const specialshowtimes = @json($specialshowtimes);
        const type_rooms = @json($type_rooms);
        const days = @json($days);
        let type_seats = @json($type_seats);

        let dataShowtimeCheck = [];
        let idRowCounter = 1;

        // Tự động tạo suất chiếu
        const operatingStart = "08:00";
        const operatingEnd = "23:59";
        const gapTime = 30;

        const getAllShowtime = (room_id, dataDate) => {
            $.ajax({
                type: "GET",
                async: false,
                url: `${appUrl}/api/${room_id}/showtime?date=${dataDate}`,
                success: function (response) {

                    const showtimes = response.data;


                    $('#listShowtime').empty();
                    dataShowtimeCheck = [];
                    if (showtimes && showtimes.length > 0) {
                        showtimes.forEach((item) => {
                            dataShowtimeCheck.push(item);
                            $('#listShowtime').append(`
                                                                                                        <div class="list-group-item d-flex align-items-center justify-content-between py-3">
                                                                                                            <div>
                                                                                                                <strong class="text-dark">${item.start_time}</strong> - <strong class="text-dark">${item.end_time}</strong><br>
                                                                                                                <small>
                                                                                                                    <form action="{{route('admin.showtimes.delete') }}" method="POST">
                                                                                                                        @csrf
                                                                                                                        <input type="hidden" name="showtime_id" value="${item.id}">
                                                                                                                        <button type="submit" onclick="return confirm('Bạn có chắc chắn không !!!')" class="btn btn-sm text-danger fw-bold">Xóa</button>
                                                                                                                    </form>
                                                                                                                </small>
                                                                                                            </div>
                                                                                                            <div class="d-flex align-items-center">
                                                                                                                <span class="badge bg-success px-3 py-2">${item.room.type_room.name}</span>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    `);
                        });
                    } else {
                        dataShowtimeCheck = [];
                        $('#listShowtime').append(`
                                                                                                    <div class="list-group-item d-flex align-items-center text-danger fw-bold justify-content-between py-3">
                                                                                                        Hiện tại chưa có suất chiếu nào 
                                                                                                    </div>
                                                                                                `);
                    }

                }
            });
        }

        const showtimeEmpty = () => {
            dataShowtimeCheck = [];
            $('#listShowtime').empty();
            $('#listShowtime').append(`
                                                                                                    <div class="list-group-item d-flex align-items-center text-danger fw-bold justify-content-between py-3">
                                                                                                        Hiện tại chưa có suất chiếu nào 
                                                                                                    </div>
                                                                                                `);
        }


        function addTimeRow() {
            const id = idRowCounter++;
            const html = `
                                                                                                            <div class="row align-items-center" id="row_${id}">
                                                                                                                <div class="col-lg-5">
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
                                                                                                                <div class="col-lg-2 ps-5 d-flex align-items-center">
                                                                                                                    <button type="button" class="btn btn-danger text-white mt-3 removeItem" data-id="${id}">
                                                                                                                        <i class="bi bi-trash"></i>
                                                                                                                    </button>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        `;
            $('#listTime').append(html);
        }

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

        // Khởi tạo mặc định
        addTimeRow();
        showtimeEmpty();

        $('#cinema_id').prop('disabled', true);
        $('#room_id').prop('disabled', true);
        $('#autoGenerate').prop('disabled', true);
        $('#price_special').prop('disabled', true);
        $('input[name="start_time[]"]').prop('disabled', true);
        // branch_id
        $('#branch_id').change(function () {
            // Khi chọn branch sẽ sinh ra các cinema của branch đó
            let id = $(this).val();
            let surchargeBranch = 0;

            branchs.forEach((item) => {
                if (id == item.id) {
                    surchargeBranch = item.surcharge;
                }
            });
            $('#surchargeBranch').val(surchargeBranch);

            let filteredData = "";
            Object.entries(data).forEach(([key, value]) => {
                if (id === key) {
                    filteredData = value;
                }
            });
            $('#cinema_id').prop('disabled', false).empty();
            Object.entries(filteredData).forEach(([key, value]) => {
                $('#cinema_id').append(`<option value="${key}">${value}</option>`);
            });


            // Và lấy các room của cinema đó
            let idCinema = $('#cinema_id').val();
            let filteredDataRoom = "";
            Object.entries(roomsRelation).forEach(([key, value]) => {
                if (idCinema === key) {
                    filteredDataRoom = value;
                }
            });
            $('#room_id').prop('disabled', false).empty();
            Object.entries(filteredDataRoom).forEach(([key, value]) => {
                $('#room_id').append(`<option value="${key}">${value}</option>`);
            });
            // Set giá trị cho seat_structure khi chọn rooms
            let dataSeatStructure = "";
            let surchargeTypeRoom = "";
            let room_id = $('#room_id').val();
            let dataDate = $('#date').val();
            rooms.forEach(item => {
                if (item.id == room_id) {
                    dataSeatStructure = item.seat_structure;
                    surchargeTypeRoom = item.type_room.surcharge;
                }
            });
            showtimeEmpty();
            if (room_id && dataDate) {
                getAllShowtime(room_id, dataDate);

            }
            $('#seat_structure').val(JSON.stringify(dataSeatStructure));

            $('#surchargeTypeRoom').val(surchargeTypeRoom);
            $('#listTime').empty();
            addTimeRow();
            if (!dataDate) {
                $('input[name="start_time[]"]').prop('disabled', true);
            }


        });
        // cinema_id
        $('#cinema_id').change(function () {
            let id = $(this).val();
            // Khi cinema thay đổi thì set giá trị cho các room của cinema đó
            let filteredDataRoom = "";
            Object.entries(roomsRelation).forEach(([key, value]) => {
                if (id === key) {
                    filteredDataRoom = value;
                }
            });
            $('#room_id').prop('disabled', false).empty();
            Object.entries(filteredDataRoom).forEach(([key, value]) => {
                $('#room_id').append(`<option value="${key}">${value}</option>`);
            });
            // Set giá trị cho seat_structure khi chọn rooms
            let dataSeatStructure = "";
            let surchargeTypeRoom = "";
            let room_id = $('#room_id').val();
            let dataDate = $('#date').val();

            rooms.forEach(item => {
                if (item.id == room_id) {
                    dataSeatStructure = item.seat_structure;
                    surchargeTypeRoom = item.type_room.surcharge;
                }
            });
            showtimeEmpty();
            if (dataDate && room_id) {
                getAllShowtime(room_id, dataDate);
            }
            $('#surchargeTypeRoom').val(surchargeTypeRoom);
            $('#seat_structure').val(JSON.stringify(dataSeatStructure));
            $('#listTime').empty();
            addTimeRow();
            if (!dataDate) {
                $('input[name="start_time[]"]').prop('disabled', true);
            }

        })
        // room_id
        $('#room_id').change(function () {
            // Khi room thay đổi thì set seat_structure 
            let id = $(this).val();
            let dataDate = $('#date').val();

            let dataSeatStructure = "";
            let surchargeTypeRoom = "";
            rooms.forEach(item => {
                if (item.id == id) {
                    dataSeatStructure = item.seat_structure;
                    surchargeTypeRoom = item.type_room.surcharge;
                }
            });
            showtimeEmpty();
            if (dataDate && id) {
                getAllShowtime(id, dataDate);
            }

            $('#listTime').empty();
            addTimeRow();
            if (!dataDate) {
                $('input[name="start_time[]"]').prop('disabled', true);
            }
            $('#surchargeTypeRoom').val(surchargeTypeRoom);
            $('#seat_structure').val(JSON.stringify(dataSeatStructure));
        });

        // Thêm time
        $('#addTime').click(function () {
            let date = $('#date').val();
            addTimeRow();
            if (!date) {
                $('input[name="start_time[]"]').prop('disabled', true);
            }
        });

        // Xóa time 
        $('#listTime').on('click', '.removeItem', function () {
            const id = $(this).data('id');
            $(`#row_${id}`).remove();
        });
        // Chọn date
        $('#date').change(function () {
            $('#autoGenerate').prop('disabled', false);
            $('input[name="start_time[]"]').prop('disabled', false);
            // Lấy các giá trị của thời gian
            let dataDate = $(this).val();
            let date_create = movie.created_at.split("T")[0];
            let release_date = movie.release_date;
            let end_date = movie.end_date;
            // Nếu nằm trong ngoài khoảng thì sẽ thực thi
            if (dataDate < date_create || dataDate > end_date) {
                toastr.error('Vui lòng chọn ngày phù hợp !!!');
                $(this).val("");
                $('#day_id').val("");
                $('[name="day_id"]').val("");
                $('[name="status_special"]').val("");
                $('#status_special').val("");
                $('#listTime').empty();
                $('#autoGenerate').prop('disabled', true);
                $('#price_special').prop('disabled', true).val('');
                addTimeRow();
                $('input[name="start_time[]"]').prop('disabled', true);
                return;
            }
            // Thêm . khi nhập số 
            $('#price_special').on('input', function (e) {
                e.preventDefault();
                let valueDiscount = $(this).val().replace(/\D/g, '');
                let setValueDiscount = new Intl.NumberFormat('vi-VN').format(valueDiscount);
                $(this).val(setValueDiscount);
            });
            // Nếu Trước thời gian phát hành thì sẽ là suất chiếu đặc biệt
            if (dataDate >= date_create && dataDate <= release_date) {
                $('#price_special').prop('disabled', false);
                $('[name="status_special"]').val(2);
                $('#status_special').val(2);
            }
            // Nếu đang trong thời gian phát hành thì sẽ là suất chiếu thường
            if (dataDate >= release_date && dataDate <= end_date) {
                $('#price_special').prop('disabled', true).val('');
                $('[name="status_special"]').val(1);
                $('#status_special').val(1);
            }

            // Trong bảng day mặc định 1 : Ngày thường , 2 : Cuối Tuần , 3 Đặt biệt
            // Kiểm tra T7 và CN : 0 là CN , 6  là T7
            const date = new Date(dataDate);
            const day = date.getDay();
            if (day == 0 || day == 6) {
                $('#day_id').val(2);
                $('[name="day_id"]').val(2);
                let surchargeDay = 0;


                days.forEach((item) => {
                    if (item.id == 2) {
                        surchargeDay = item.day_surcharge;
                    }
                })

                $('#surchargeDay').val(surchargeDay);
            } else {
                let surchargeDay = 0;
                days.forEach((item) => {
                    if (item.id == 1) {
                        surchargeDay = item.day_surcharge;
                    }
                })
                $('#surchargeDay').val(surchargeDay);
                $('#day_id').val(1)
                $('[name="day_id"]').val(1);
            }
            let room_id = $('#room_id').val();

            showtimeEmpty();
            if (room_id && dataDate) {
                getAllShowtime(room_id, dataDate);
            }
            $('#listTime').empty();
            addTimeRow();
            if (!dataDate) {
                $('input[name="start_time[]"]').prop('disabled', true);
            }



        });
        // Gán dữ liệu cho surchargeDay
        $('#day_id').change(function () {
            let day_id = $(this).val();
            let surchargeDay = 0;


            days.forEach((item) => {
                if (item.id == day_id) {
                    surchargeDay = item.day_surcharge;
                }
            })
            $('[name="day_id"]').val(day_id);
            $('#surchargeDay').val(surchargeDay);

        })
        // Khi start_time thay đổi 
        $('#listTime').on('change', 'input[name="start_time[]"]', function () {
            const currentId = $(this).data('id'); // id của div đó
            const currentStart = $(this).val(); // Thời gian của start_time
            const durationMovie = +$('#duration').val(); // thời lượng phim 

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
            let screeningsChecks = [];

            if (dataShowtimeCheck) {
                dataShowtimeCheck.forEach((item) => {
                    screeningsChecks.push({
                        id: item.id,
                        start: item.start_time,
                        startMinutes: convertTimeToMinutes(item.start_time),
                        end: item.end_time, // nếu có
                        endMinutes: convertTimeToMinutes(item.end_time) // nếu có
                    });
                });

                screeningsChecks = [...screeningsChecks, ...screenings];

                // Sắp xếp lại theo thời gian bắt đầu
                screeningsChecks.sort((a, b) => a.startMinutes - b.startMinutes);

                const index = screeningsChecks.findIndex(index => index.id == currentId);

                if (index > 0) {
                    const prevScreening = screeningsChecks[index - 1];
                    // Nếu có endMinutes từ dữ liệu cũ, sử dụng nó, nếu không thì tính lại:
                    const prevEndMinutes = prevScreening.endMinutes || (prevScreening.startMinutes + durationMovie);
                    if (currentStartMinutes < prevEndMinutes + 10) {
                        toastr.error('Xuất chiếu mới phải cách suất chiếu trước ít nhất 10 phút!');
                        $(`#end_time_${currentId}`).val("");
                        $(this).val("");

                        return;
                    }
                }

                if (index < screeningsChecks.length - 1) {
                    const nextScreening = screeningsChecks[index + 1];
                    // Nếu có startMinutes từ dữ liệu cũ, sử dụng nó:
                    const nextStartMinutes = nextScreening.startMinutes;
                    if (currentEndMinutes > nextStartMinutes - 10) {
                        toastr.error('Xuất chiếu mới phải kết thúc ít nhất 10 phút trước khi suất chiếu sau bắt đầu!');
                        $(`#end_time_${currentId}`).val("");
                        $(this).val("");

                        return;
                    }
                }

            }


            // Nếu thỏa mãn các điều kiện, cập nhật thời gian kết thúc của xuất chiếu hiện tại
            $(`#end_time_${currentId}`).val(convertMinutesToTime(currentEndMinutes));
        });
        // Auto gen time
        $('#autoGenerate').click(function () {
            const durationMovie = +$('#duration').val();
            let dataDate = $('#date').val();
            if (!durationMovie) {
                toastr.error('Thời lượng phim không hợp lệ!');
                return;
            }

            const startOperatingMinutes = convertTimeToMinutes(operatingStart); // 8:00
            const endOperatingMinutes = convertTimeToMinutes(operatingEnd); // 23:00

            $('#listTime').empty();
            idRowCounter = 1;

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
                        (startTime >= existing.startMinutes && startTime < existing.endMinutes) || // Bắt đầu trong khoảng cũ
                        (endTime > existing.startMinutes && endTime <= existing.endMinutes) || // Kết thúc trong khoảng cũ
                        (startTime <= existing.startMinutes && endTime >= existing.endMinutes) // Bao trùm suất cũ
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
                                <div class="col-lg-5">
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
                                <div class="col-lg-2 ps-5 d-flex align-items-center">
                                    <button type="button" class="btn btn-danger text-white mt-3 removeItem" data-id="${idRowCounter}">
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

            if ($('#listTime').children().length === 0) {
                addTimeRow();
                if (!dataDate) {
                    $('input[name="start_time[]"]').prop('disabled', true);
                }
                toastr.error('Không đủ thời gian để tạo suất chiếu theo cấu hình hiện tại!');
            }
        });
        // submit
        $('#submitForm').click(function (e) {



            let form = $('.FormCreate');
            if (form[0].checkValidity()) {
                let surchargeMovie = +$('#surchargeMovie').val();
                let surchargeBranch = +$('#surchargeBranch').val();
                let surchargeTypeRoom = +$('#surchargeTypeRoom').val();
                let surchargeDay = +$('#surchargeDay').val();
                let price_special = +$('#price_special').val().replace(/\./g, '');
                let seat_structure = JSON.parse($('#seat_structure').val());

                if (typeof seat_structure === 'string') {
                    seat_structure = JSON.parse(seat_structure);
                }


                let data_seat_structure = [];
                seat_structure.forEach((item) => {
                    data_seat_structure.push({
                        ...item,
                        price: +type_seats.filter((element) => element.id == item.type_seat_id)[0].price + surchargeMovie + surchargeBranch + surchargeTypeRoom + surchargeDay + price_special,
                    })
                });

                $('#seat_structure').val(JSON.stringify(data_seat_structure));

                $(form).submit();
            } else {

                form[0].reportValidity();
            }
        })
    </script>
@endsection