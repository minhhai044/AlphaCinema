@extends('admin.layouts.master')
@section('content')
    <h5 class="fw-bold">Quản lý hóa đơn</h5>
    <div class="rounded">
        <form action="{{ route('admin.tickets.index') }}" method="get">
            <div class="row align-items-end ">
                <div class="col-lg-2">
                    <label for="branch_id" class="form-label fw-bold">Chi nhánh</label>
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
                <div class="col-lg-2">
                    <label for="cinema_id" class="form-label fw-bold">Rạp phim</label>
                    <select name="cinema_id" class="form-select" required id="cinema_id">
                        <option value="" disabled selected>Chọn rạp phim</option>
                        @foreach ($cinemas as $cinema)
                            <option value="{{ $cinema['id'] }}"
                                {{ request('cinema_id') == $cinema['id'] ? 'selected' : '' }}>
                                {{ $cinema['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2">
                    <label for="date" class="form-label fw-bold">Ngày chiếu</label>
                    <input type="date" name="date" id="date" class="form-control" value="{{ request('date') }}"
                        required>
                </div>
                <div class="col-lg-3">
                    <label for="movie_id" class="form-label fw-bold">Phim</label>
                    <select name="movie_id" class="form-select" required id="movie_id">
                        <option value="" disabled selected>Chọn phim</option>
                        @foreach ($movies as $movie)
                            <option value="{{ $movie['id'] }}" {{ request('movie_id') == $movie['id'] ? 'selected' : '' }}>
                                {{ $movie['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2">
                    <label for="status_id" class="form-label fw-bold">Trạng thái</label>
                    <select name="status_id" class="form-select" required id="status_id">
                        <option value="" disabled selected>Tất cả</option>
                    </select>
                </div>
                <div class="col-lg-1">
                    <button type="submit" class="btn btn-primary"><i class="bx bx-search-alt-2"></i></button>
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
                            {{-- @foreach ($movies as $movie)
                            <a href="{{ route('admin.showtimes.create', $movie->id) }}"><li class="list-group-item movie-item fw-semibold">{{$movie->name}}</li></a>
                        @endforeach --}}
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive mt-3">
            <table id="ticketTable" class="table  w-100 text-center">
                <thead>
                    <tr class="text-center">
                        <th>Mã vé</th>
                        <th>Thông tin người dùng</th>
                        <th></th>
                        <th>Email</th>
                        <th>Giới tính</th>
                        <th>Loại</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
            </table>
        </div>

    </div>

    @php
        $appUrl = env('APP_URL');
    @endphp
@endsection

@section('script')
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Khi người dùng thay đổi chi nhánh
            $('#branch_id').change(function() {
                var branchId = $(this).val(); // Lấy giá trị chi nhánh đã chọn

                // Ẩn tất cả các rạp phim và phim
                $('#cinema_id').empty().append('<option value="" disabled selected>Chọn rạp phim</option>');
                $('#movie_id').empty().append('<option value="" disabled selected>Chọn phim</option>');

                if (branchId) {
                    // Gửi yêu cầu AJAX để lấy danh sách rạp phim theo chi nhánh
                    $.ajax({
                        url: '/api/v1/get-cinemas', // URL bạn sẽ gọi để lấy danh sách rạp theo chi nhánh
                        type: 'GET',
                        data: {
                            branch_id: branchId
                        },
                        success: function(response) {
                            // Thêm các rạp phim vào select
                            $.each(response.cinemas, function(index, cinema) {
                                $('#cinema_id').append('<option value="' + cinema.id +
                                    '">' + cinema.name + '</option>');
                            });
                        }
                    });
                }
            });

            // Khi người dùng thay đổi rạp phim
            $('#cinema_id').change(function() {
                var cinemaId = $(this).val(); // Lấy giá trị rạp phim đã chọn

                // Ẩn tất cả các phim
                $('#movie_id').empty().append('<option value="" disabled selected>Chọn phim</option>');

                if (cinemaId) {
                    // Gửi yêu cầu AJAX để lấy danh sách phim theo rạp
                    $.ajax({
                        url: '/api/v1/get-movies', // URL bạn sẽ gọi để lấy danh sách phim theo rạp phim
                        type: 'GET',
                        data: {
                            cinema_id: cinemaId
                        },
                        success: function(response) {
                            // Thêm các phim vào select
                            $.each(response.movies, function(index, movie) {
                                $('#movie_id').append('<option value="' + movie.id +
                                    '">' + movie.name + '</option>');
                            });
                        }
                    });
                }
            });
        });
    </script>
@endsection
