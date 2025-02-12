@extends('admin.layouts.master')
@section('content')
    <h1>Quản lý phòng chiếu</h1>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#exampleModal">
        Tạo phòng chiếu
    </button>

    <!-- Modal -->
    <form action="" method="post">
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Thêm mới phòng chiếu</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="seat_structures" id="seat_structures">
                            <div class="col-lg-12 mb-3">
                                <label for="nameInput" class="form-label">Tên phòng chiếu<span
                                        style="color: red">*</span></label>
                                <input type="text" name="name" required
                                    data-pristine-required-message="Vui lòng nhập tên mẫu ghế !!!" class="form-control"
                                    placeholder="Nhập tên phòng chiếu">
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="regularSeat" class="form-label">Chi nhánh <span
                                        style="color: red">*</span></label>
                                <select name="branch_id" class="form-select" required id="matrixSelectCreate">
                                    <option value="" disabled selected>Chọn chi nhánh</option>
                                    @foreach ($branchs as $branch)
                                        <option value="{{ $branch['id'] }}">{{ $branch['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="vipSeat" class="form-label">Rạp phim <span style="color: red">*</span></label>

                                <select name="cinema_id" class="form-select" required id="matrixSelectCreate">
                                    <option value="" disabled selected>Chọn rạp phim</option>
                                    @foreach ($cinemas as $cinema)
                                        <option value="{{ $cinema['id'] }}">{{ $cinema['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="regularSeat" class="form-label">Mẫu ghế <span
                                        style="color: red">*</span></label>
                                <select name="seat_template_id" class="form-select" required id="matrixSelectCreate">
                                    <option value="" disabled selected>Chọn mẫu ghế</option>
                                    @foreach ($seat_templates as $seat_template)
                                        <option value="{{ $seat_template['id'] }}">{{ $seat_template['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="vipSeat" class="form-label">Loại phòng <span style="color: red">*</span></label>

                                <select name="type_room_id" class="form-select" required id="matrixSelectCreate">
                                    <option value="" disabled selected>Chọn loại phòng</option>
                                    @foreach ($type_rooms as $type_room)
                                    <option value="{{ $type_room['id'] }}">{{ $type_room['name'] }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <label for="description" class="form-label">Mô tả</label>
                                <textarea class="form-control" name="description" rows="3"
                                    placeholder="Nhập mô tả..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bỏ</button>
                        <button type="button" class="btn btn-primary">Thêm mới</button>
                    </div>
                </div>
            </div>
        </div>
    </form>






@endsection
@section('script')


@endsection