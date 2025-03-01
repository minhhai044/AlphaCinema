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
                    {{-- @foreach ($branchs as $branch)
                        <option value="{{ $branch['id'] }}" {{ request('branch_id') == $branch['id'] ? 'selected' : '' }}>
                            {{ $branch['name'] }}
                        </option>
                    @endforeach --}}
                </select>
            </div>
            <div class="col-lg-2">
                <label for="cinema_id" class="form-label fw-bold">Rạp phim</label>
                <select name="cinema_id" class="form-select" required id="cinema_id">
                    <option value="" disabled selected>Chọn rạp</option>
                </select>
            </div>
            <div class="col-lg-2">
                <label for="date" class="form-label fw-bold">Ngày chiếu</label>
                <input type="date" name="date" id="date" class="form-control" value="{{ request('date') }}" required>
            </div>
            <div class="col-lg-3">
                <label for="cinema_id" class="form-label fw-bold">Phim</label>
                <select name="cinema_id" class="form-select" required id="cinema_id">
                    <option value="" disabled selected>Chọn phim</option>
                </select>
            </div>
            <div class="col-lg-2">
                <label for="cinema_id" class="form-label fw-bold">Trạng thái</label>
                <select name="cinema_id" class="form-select" required id="cinema_id">
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

</div>

    @php
        $appUrl = env('APP_URL');
    @endphp
@endsection

@section('script')
    {{-- <script>
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
    </script> --}}
@endsection
