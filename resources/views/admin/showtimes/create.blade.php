@extends('admin.layouts.master')
@section('style')
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Thêm mới xuất chiếu</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.showtimes.index') }}">Quản lý xuất chiếu</a>
                        </li>
                        <li class="breadcrumb-item active">Tạo tài khoản</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="seat_structure" id="seat_structure">
        <input type="hidden" name="slug" value="{{$slug}}">
        <div class="row">
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-body " style="border: none">
                        <div class="row">
                            <!-- Cột trái -->
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="branch_id" class="form-label">
                                        Chi nhánh <span style="color: red" class="required">*</span>
                                    </label>


                                    <select name="branch_id" class="form-select @error('branch_id') is-invalid @enderror"
                                        required id="branch_id">
                                        <option value="" disabled selected>Chọn chi nhánh</option>
                                        @foreach ($branchs as $branch)
                                            <option value="{{ $branch['id'] }}">{{ $branch['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('branch_id')
                                        <span class="text-danger">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Cột phải -->
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="cinema_id" class="form-label">
                                        Rạp phim <span style="color: red" class="required">*</span>
                                    </label>
                                    <select name="cinema_id" class="form-select @error('branch_id') is-invalid @enderror"
                                        required id="cinema_id">
                                        <option value="" disabled selected>Chọn rạp</option>

                                    </select>
                                    @error('cinema_id')
                                        <span class="text-danger">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="room_id" class="form-label">
                                        Phòng <span style="color: red" class="required">*</span>
                                    </label>
                                    <select name="room_id" class="form-select @error('branch_id') is-invalid @enderror"
                                        required id="room_id">
                                        <option value="" disabled selected>Chọn phòng</option>
                                        @foreach ($rooms as $room)
                                            <option value="{{ $room['id'] }}">{{ $room['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('room_id')
                                        <span class="text-danger">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="movie_id" class="form-label">
                                        Phim <span style="color: red" class="required">*</span>
                                    </label>
                                    <select name="movie_id" class="form-select @error('movie_id') is-invalid @enderror"
                                        required id="movie_id">
                                        <option value="" disabled selected>Chọn phim</option>
                                        @foreach ($movies as $movie)
                                            <option value="{{ $movie['id'] }}">{{ $movie['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('movie_id')
                                        <span class="text-danger">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="day_id" class="form-label">
                                        Ngày <span style="color: red" class="required">*</span>
                                    </label>
                                    <select name="day_id" class="form-select @error('day_id') is-invalid @enderror" required
                                        id="day_id">
                                        <option value="" disabled selected>Chọn ngày</option>
                                        @foreach ($days as $id => $day)
                                            <option value="{{ $id }}">{{ $day }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('day_id')
                                        <span class="text-danger">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="format" class="form-label">
                                        Thể loại <span style="color: red" class="required">*</span>
                                    </label>
                                    <select name="format" class="form-select @error('format') is-invalid @enderror" required
                                        id="format">
                                        <option value="" disabled selected>Chọn ngày</option>
                                        @foreach ($versions as $version)
                                            <option value="{{ $version['id'] }}">{{ $version['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('format')
                                        <span class="text-danger">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="date" class="form-label">
                                        Ngày chiếu <span style="color: red" class="required">*</span>
                                    </label>
                                    <input type="date" class="form-control" name="date" id="date">
                                    @error('date')
                                        <span class="text-danger">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <button type="button" id="addTime" class="btn btn-primary float-end">Thêm thời gian</button>
                            </div>
                            <div class="col-lg-12" id="listTime">


                            </div>
                        </div>
                    </div>

                    <div class="m-3">
                        <button type="submit" class="btn btn-primary">
                            Submit
                        </button>
                        <button type="button" class="btn btn-danger">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>

            <!-- Cột bên phải -->
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <h4>Xuất chiếu hiện có trong ngày</h4>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <input type="hidden" id="duration">
    @php
        $appUrl = env('APP_URL');
    @endphp
@endsection

@section('script')

    <script>
        const data = @json($branchsRelation);

        
        $('#cinema_id').prop('disabled', true);

        $('#branch_id').change(function () {
            let id = $(this).val();
            let filteredData = "";
            Object.entries(data).forEach(([key, value]) => {
                if (id === key) {
                    filteredData = value;
                }
            });
            $('#cinema_id').prop('disabled', false);
            $('#cinema_id').empty();
            Object.entries(filteredData).forEach(([key, value]) => {
                $('#cinema_id').append(`<option value="${key}">${value}</option>`);
            });
        });
        const rooms = @json($rooms);
        $('#room_id').change(function () {
            let id = $(this).val();
            let dataSeatStructure = "";
            rooms.forEach((item) => {
                if (item.id == id) {
                    dataSeatStructure = item.seat_structure;
                }
            })
            $('#seat_structure').val(dataSeatStructure);

        })
        let idRowCounter = 1;
        function addTimeRow() {
            const id = idRowCounter++;
            
            const html = `
        <div class="row align-items-center" id="${id}">
            <div class="col-lg-5">
                <div class="mb-3">
                    <label for="start_time_${id}" class="form-label">
                        Thời gian bắt đầu <span class="text-danger">*</span>
                    </label>
                    <input type="time" class="form-control" name="start_time[]" data-id="${id}" id="start_time_${id}" aria-label="Chọn thời gian">
                </div>
            </div>
            <div class="col-lg-5">
                <div class="mb-3">
                    <label for="end_time_${id}" class="form-label">
                        Thời gian kết thúc <span class="text-danger">*</span>
                    </label>
                    <input type="time" class="form-control" name="end_time[]" id="end_time_${id}" readonly>
                </div>
            </div>
            <div class="col-lg-2 d-flex align-items-center">
                <button type="button" class="btn btn-danger text-white mt-3 removeItem" data-id="${id}">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
    `;
            $('#listTime').append(html);

        }

        addTimeRow();
        // Thêm Item
        $('#addTime').click(function () {
            let durationMovie = $('#duration').val();
            addTimeRow();
            if (!durationMovie) {
                $('input[name="start_time[]"]').prop('disabled', true);
            }
        });
        // Xóa Item
        $('#listTime').on('click', '.removeItem', function () {
            const id = $(this).data('id');
            $('#' + id).remove();
        });
        //Sử lý phần movie
        const movies = @json($movies);
        $('input[name="start_time[]"]').prop('disabled', true);

        $('#movie_id').change(function () {
            $('input[name="start_time[]"]').prop('disabled', false);

            let id = $(this).val();
            let filteredData = "";
            movies.forEach(item => {
                if (item.id == id) {
                    filteredData = item.duration;
                }
            });
            $('#duration').val(filteredData);

            // Cập nhật lại end_time cho tất cả các start_time khi thay đổi movie
            $('input[name="start_time[]"]').each(function () {
                let currentStartTime = $(this).val();
                if (currentStartTime) {
                    let id = $(this).data('id');
                    let durationMovie = $('#duration').val();

                    let [startHour, startMinute] = currentStartTime.split(':').map(Number);
                    let totalMinutes = startHour * 60 + startMinute;
                    totalMinutes += +durationMovie;
                    let newHour = Math.floor(totalMinutes / 60) % 24;
                    let newMinute = totalMinutes % 60;
                    newHour = newHour.toString().padStart(2, '0');
                    newMinute = newMinute.toString().padStart(2, '0');
                    let newTime = `${newHour}:${newMinute}`;

                    $(`#end_time_${id}`).val(newTime);
                }
            });
        });

        // Set time cho end_time
        $('#listTime').on('change', 'input[name="start_time[]"]', function () {
            let id = $(this).data('id');
            let timeStart = $(this).val();
            let durationMovie = $('#duration').val();

            let end_time_before =  $(`#end_time_${id - 1}`).val(); // Thời gian của end_time trước đó
            console.log(end_time_before);
            
            let [startHour, startMinute] = timeStart.split(':').map(Number);

            let totalMinutes = startHour * 60 + startMinute;

            totalMinutes += +durationMovie;

            let newHour = Math.floor(totalMinutes / 60) % 24;
            let newMinute = totalMinutes % 60;

            newHour = newHour.toString().padStart(2, '0');
            newMinute = newMinute.toString().padStart(2, '0');

            let newTime = `${newHour}:${newMinute}`;

            $(`#end_time_${id}`).val(newTime);

        });

    </script>

@endsection