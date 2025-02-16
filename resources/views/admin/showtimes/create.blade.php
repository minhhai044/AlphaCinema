@extends('admin.layouts.master')

@section('style')
    <!-- Nếu cần thêm CSS tùy chỉnh, bạn có thể thêm ở đây -->
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
                        <li class="breadcrumb-item active">Tạo xuất chiếu</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="seat_structure" id="seat_structure">
        <input type="hidden" name="slug" value="{{ $slug }}">
        <div class="row">
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-body" style="border: none">
                        <div class="row">
                            <!-- Các trường thông tin cơ bản -->
                            <div class="col-lg-6">
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
                            <div class="col-lg-6">
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
                            <div class="col-lg-6">
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
                            <!-- Phim -->
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="movie_id" class="form-label">
                                        Phim <span style="color: red" class="required">*</span>
                                    </label>
                                    <select name="movie_id" class="form-select @error('movie_id') is-invalid @enderror"
                                        required id="movie_id">
                                        <option value="" disabled selected>Chọn phim</option>
                                        @foreach ($movies as $movie)
                                            <option value="{{ $movie['id'] }}">{{ $movie['name'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('movie_id')
                                        <span class="text-danger"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                            <!-- Ngày chiếu -->
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="date" class="form-label">
                                        Ngày chiếu <span style="color: red" class="required">*</span>
                                    </label>
                                    <input type="date" class="form-control" name="date" id="date">
                                    @error('date')
                                        <span class="text-danger"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                            <!-- Ngày -->
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="day_id" class="form-label">
                                        Ngày <span style="color: red" class="required">*</span>
                                    </label>
                                    <select name="day_id" class="form-select @error('day_id') is-invalid @enderror" required
                                        id="day_id">
                                        <option value="" disabled selected>Chọn ngày</option>
                                        @foreach ($days as $id => $day)
                                            <option value="{{ $id }}">{{ $day }}</option>
                                        @endforeach
                                    </select>
                                    @error('day_id')
                                        <span class="text-danger"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                            <!-- Thể loại -->
                            {{-- <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="format" class="form-label">
                                        Thể loại <span style="color: red" class="required">*</span>
                                    </label>
                                    <select name="format" class="form-select @error('format') is-invalid @enderror" required
                                        id="format">
                                        <option value="" disabled selected>Chọn thể loại</option>
                                        @foreach ($versions as $version)
                                        <option value="{{ $version['id'] }}">{{ $version['name'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('format')
                                    <span class="text-danger"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div> --}}

                            <!-- Nút thêm xuất chiếu -->
                            <div class="col-lg-12">
                                <button type="button" id="addTime" class="btn btn-primary float-end">Thêm thời gian</button>
                            </div>
                            <!-- Danh sách xuất chiếu -->
                            <div class="col-lg-12" id="listTime">
                                <!-- Các dòng xuất chiếu sẽ được thêm vào đây -->
                            </div>
                        </div>
                    </div>

                    <div class="m-3">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-danger">Cancel</button>
                    </div>
                </div>
            </div>

            <!-- Cột bên phải hiển thị xuất chiếu đã có trong ngày -->
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <h4>Xuất chiếu hiện có trong ngày</h4>
                        <!-- Bạn có thể hiển thị danh sách xuất chiếu có sẵn ở đây -->
                    </div>
                </div>
            </div>
        </div>
    </form>
    <input type="hidden" id="duration">
    <input type="hidden" id="movie_id">
    @php
        $appUrl = env('APP_URL');
    @endphp
@endsection

@section('script')
    <script>
        // ----------------------
        // Xử lý lựa chọn chi nhánh, rạp, phòng, phim...
        // ----------------------
        const data = @json($branchsRelation);
        const roomsRelation = @json($roomsRelation);
        $('#cinema_id').prop('disabled', true);

        $('#branch_id').change(function () {
            let id = $(this).val();
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

        });

        $('#room_id').prop('disabled', true);


        $('#cinema_id').change(function () {
            let id = $(this).val();

            let filteredDataRoom = "";
            Object.entries(roomsRelation).forEach(([key, value]) => {
                if (id === key) {
                    filteredDataRoom = value;
                }
            });
            Object.entries(roomsRelation).forEach(([key, value]) => {
                if (id === key) {
                    filteredDataRoom = value;
                }
            });
            $('#room_id').prop('disabled', false).empty();
            Object.entries(filteredDataRoom).forEach(([key, value]) => {
                $('#room_id').append(`<option value="${key}">${value}</option>`);
            });


        })

        const rooms = @json($rooms);
        $('#room_id').change(function () {
            let id = $(this).val();
            let dataSeatStructure = "";
            rooms.forEach(item => {
                if (item.id == id) {
                    dataSeatStructure = item.seat_structure;
                }
            });
            $('#seat_structure').val(dataSeatStructure);
        });

        // ----------------------
        // Xử lý thêm/xóa dòng thời gian xuất chiếu
        // ----------------------
        let idRowCounter = 1;
        function addTimeRow() {
            const id = idRowCounter++;
            const html = `
                                        <div class="row align-items-center" id="row_${id}">
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

        // Khởi tạo dòng xuất chiếu mặc định
        addTimeRow();

        // Thêm dòng xuất chiếu mới
        $('#addTime').click(function () {
            let durationMovie = $('#duration').val();
            addTimeRow();
            if (!durationMovie) {
                $('input[name="start_time[]"]').prop('disabled', true);
            }
        });

        // Xóa dòng xuất chiếu
        $('#listTime').on('click', '.removeItem', function () {
            const id = $(this).data('id');
            $(`#row_${id}`).remove();
        });

        // ----------------------
        // Xử lý phần phim: Lấy duration và cập nhật end_time cho các dòng xuất chiếu đã có
        // ----------------------
        const movies = @json($movies);

        
        $('input[name="start_time[]"]').prop('disabled', true);
        $('#date').prop('disabled', true);

        $('#movie_id').change(function () {
            $('#date').prop('disabled', false);
            $('input[name="start_time[]"]').prop('disabled', false);
            let movieId = $(this).val();
            let durationVal = "";
            movies.forEach(item => {
                if (item.id == movieId) {
                    durationVal = item.duration; // Giả sử duration được lưu dưới dạng số phút
                }
            });
            $('#duration').val(durationVal);
            $('#movie_id').val(movieId);

            // Cập nhật lại end_time cho tất cả các dòng đã có
            $('input[name="start_time[]"]').each(function () {
                let currentStartTime = $(this).val();
                if (currentStartTime) {
                    let id = $(this).data('id');
                    let durationMovie = +$('#duration').val();
                    const newStartMinutes = convertTimeToMinutes(currentStartTime);
                    const newEndMinutes = newStartMinutes + durationMovie;
                    $(`#end_time_${id}`).val(convertMinutesToTime(newEndMinutes));
                }
            });
        });
        $('#date').change(function(){
            let dataDate = $(this).val();
            let idMovie = $('#movie_id').val();
            
          
            
        });

        // ----------------------
        // Các hàm trợ giúp chuyển đổi thời gian
        // ----------------------
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

        // ----------------------
        // Xử lý khi thay đổi thời gian bắt đầu của xuất chiếu
        // Cho phép chèn xuất chiếu vào giữa: kiểm tra khoảng cách với xuất chiếu liền trước và liền sau.
        // ----------------------
        $('#listTime').on('change', 'input[name="start_time[]"]', function () {
            const currentId = $(this).data('id');
            const currentStart = $(this).val();
            const durationMovie = +$('#duration').val(); // duration tính theo số phút

            if (!currentStart) return;
            const currentStartMinutes = convertTimeToMinutes(currentStart);
            const currentEndMinutes = currentStartMinutes + durationMovie;

            // Thu thập tất cả các dòng xuất chiếu có giá trị start time
            let screenings = [];
            $('#listTime > .row').each(function () {
                let rid = $(this).attr('id').replace('row_', '');
                let st = $(`#start_time_${rid}`).val();
                if (st) {
                    screenings.push({ id: rid, start: st, startMinutes: convertTimeToMinutes(st) });
                }
            });

            // Sắp xếp theo thời gian bắt đầu tăng dần
            screenings.sort((a, b) => a.startMinutes - b.startMinutes);

            // Tìm vị trí của xuất chiếu đang thay đổi trong mảng đã sắp xếp
            const index = screenings.findIndex(item => item.id == currentId);

            // Kiểm tra suất chiếu liền trước (nếu có)
            if (index > 0) {
                const prevId = screenings[index - 1].id;
                const prevStart = $(`#start_time_${prevId}`).val();
                const prevStartMinutes = convertTimeToMinutes(prevStart);
                const prevEndMinutes = prevStartMinutes + durationMovie; // Giả sử duration giống nhau cho các suất
                if (currentStartMinutes < prevEndMinutes + 10) {
                    toastr.error('Xuất chiếu mới phải cách suất chiếu trước ít nhất 10 phút!');
                    $(this).val("");
                    $(`#end_time_${currentId}`).val("");
                    return;
                }
            }

            // Kiểm tra suất chiếu liền sau (nếu có)
            if (index < screenings.length - 1) {
                const nextId = screenings[index + 1].id;
                const nextStart = $(`#start_time_${nextId}`).val();
                const nextStartMinutes = convertTimeToMinutes(nextStart);
                if (currentEndMinutes > nextStartMinutes - 10) {
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
@endsection