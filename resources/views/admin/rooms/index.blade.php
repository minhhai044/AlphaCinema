@extends('admin.layouts.master')
@section('content')
    <h1>Quản lý phòng chiếu</h1>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary float-end mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
        Tạo phòng chiếu
    </button>
    <!-- Modal -->
    <form action="{{route('admin.rooms.store')}}" method="post" class="formCreate">
        @csrf
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Thêm mới phòng chiếu</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="seat_structure" id="seat_structure">
                            {{-- Để tạm is_publish = 1 chưa cần lắm --}}
                            <input type="hidden" name="is_publish" value="1">
                            <div class="col-lg-12 mb-3">
                                <label for="nameInput" class="form-label">Tên phòng chiếu<span
                                        style="color: red">*</span></label>
                                <input type="text" name="name" required
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Nhập tên phòng chiếu">
                                @error('name')
                                    <small class="text-danger fst-italic">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="branchSelect" class="form-label">Chi nhánh <span
                                        style="color: red">*</span></label>
                                <select name="branch_id" class="form-select @error('branch_id') is-invalid @enderror"
                                    required id="branchSelect">
                                    <option value="" disabled selected>Chọn chi nhánh</option>

                                    @foreach ($branchs as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>

                                    @endforeach
                                </select>
                                @error('branch_id')
                                    <small class="text-danger fst-italic">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="cinemas" class="form-label">Rạp phim <span style="color: red">*</span></label>

                                <select name="cinema_id" class="form-select @error('cinema_id') is-invalid @enderror"
                                    required id="cinemas">
                                    <option value="" disabled selected>Chọn rạp phim</option>

                                </select>
                                @error('cinema_id')
                                    <small class="text-danger fst-italic">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="seat_templates" class="form-label">Mẫu ghế <span
                                        style="color: red">*</span></label>
                                <select name="seat_template_id"
                                    class="form-select @error('seat_template_id') is-invalid @enderror" required
                                    id="seat_templates">
                                    <option value="" disabled selected>Chọn mẫu ghế</option>

                                    @foreach ($seat_templates as $seat_template)
                                        <option value="{{ $seat_template->id }}">{{ $seat_template->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('seat_template_id')
                                    <small class="text-danger fst-italic">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="type_room_id" class="form-label">Loại phòng <span
                                        style="color: red">*</span></label>

                                <select name="type_room_id" class="form-select @error('type_room_id') is-invalid @enderror"
                                    required id="type_room_id">
                                    <option value="" disabled selected>Chọn loại phòng</option>
                                    @foreach ($type_rooms as  $id => $type_room)
                                    <option value="{{ $id }}">{{ $type_room }}
                                    </option>

                                    @endforeach
                                </select>
                                @error('type_room_id')
                                    <small class="text-danger fst-italic">{{ $message }}</small>
                                @enderror
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
                        <button id="submitCreate" type="button" class="btn btn-primary">Thêm mới</button>
                    </div>
                </div>
            </div>
        </div>
    </form>


    <table class="table table-bordered">
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên phòng</th>
                <th>Chi nhánh</th>
                <th>Rạp phim</th>
                <th>Loại phòng</th>
                <th>Hoạt động</th>
                <th>Chức năng</th>
            </tr>
        </thead>
        <tbody>
            @if (!empty($rooms))
                @foreach ($rooms as $room)
                    <tr>
                        <td>{{ $room->id}}</td>
                        <td>{{ $room->name}}</td>
                        <td>{{ $room->branch->name ?? 'Không có chi nhánh' }}</td>
                        <td>{{ $room->cinema->name ?? 'Không có rạp' }}</td>
                        <td>{{ $room->type_room->name ?? 'Không có loại phòng' }}</td>

                        <td>
                            <input type="checkbox" id="is_active{{$room->id}}" data-publish="{{ $room->is_publish }}"
                                switch="success" @checked($room->is_active) />
                            <label for="is_active{{$room->id}}"></label>
                        </td>
                        <td>
                            <div class="dropdown">
                                <span data-bs-toggle="dropdown" aria-expanded="false" class="cursor-pointer">
                                    <i class=" bx bx-dots-vertical-rounded"></i>
                                </span>
                                <ul class="dropdown-menu">
                                    <li> <a class="dropdown-item" href="{{ route('admin.rooms.show', $room) }}"><i
                                                class="mdi mdi-plus-circle-outline"></i> Xem</a></li>
                                    <li>
                                        <a class="dropdown-item edit-room" href="#" data-id="{{ $room->id }}"
                                            data-name="{{ $room->name }}" data-branch="{{ $room->branch_id }}"
                                            data-cinema="{{ $room->cinema_id }}" data-typeroom="{{ $room->type_room_id }}"
                                            data-seattemplate="{{ $room->seat_template_id }}"
                                            data-description="{{ $room->description }}" data-publish="{{ $room->is_publish }}"
                                            data-bs-toggle="modal" data-bs-target="#exampleModalEdit">
                                            <i class="mdi mdi-playlist-edit"></i> Sửa
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    <div class="modal fade" id="exampleModalEdit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="post" class="submitRoomFormUpdate">
                    @csrf
                    @method("PUT")
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Sửa phòng</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="seat_structure" id="seat_structure_edit">
                            {{-- Để tạm is_publish = 1 chưa cần lắm --}}
                            <input type="hidden" name="is_publish" value="1">
                            <div class="col-lg-12 mb-3">
                                <label for="name" class="form-label">Tên phòng chiếu<span
                                        style="color: red">*</span></label>
                                <input type="text" name="name" required
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Nhập tên phòng chiếu" id="name">
                                @error('name')
                                    <small class="text-danger fst-italic">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="branchEdit" class="form-label">Chi nhánh <span style="color: red">*</span></label>
                                <select name="branch_id" class="form-select @error('branch_id') is-invalid @enderror" required
                                    id="branchEdit">
                                    <option value="" disabled selected>Chọn chi nhánh</option>
                                    @foreach ($branchs as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                                @error('branch_id')
                                    <small class="text-danger fst-italic">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="cinemasEdit" class="form-label">Rạp phim <span style="color: red">*</span></label>

                                <select name="cinema_id" class="form-select @error('cinema_id') is-invalid @enderror" required
                                    id="cinemasEdit">
                                    <option value="" disabled selected>Chọn rạp phim</option>
                                </select>
                                @error('cinema_id')
                                    <small class="text-danger fst-italic">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="seat_templates_edit" class="form-label">Mẫu ghế <span
                                        style="color: red">*</span></label>
                                <select name="seat_template_id" class="form-select @error('seat_template_id') is-invalid @enderror"
                                    required id="seat_templates_edit">
                                    <option value="" disabled selected>Chọn mẫu ghế</option>
                                    @foreach ($seat_templates as $seat_template)
                                        <option value="{{ $seat_template->id }}">{{ $seat_template->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('seat_template_id')
                                    <small class="text-danger fst-italic">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="type_room" class="form-label">Loại phòng <span
                                        style="color: red">*</span></label>

                                <select name="type_room_id" class="form-select @error('type_room_id') is-invalid @enderror" required
                                    id="type_room">
                                    <option value="" disabled selected>Chọn loại phòng</option>
                                    @foreach ($type_rooms as $id => $type_room)
                                        <option value="{{ $id }}">{{ $type_room }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('type_room_id')
                                    <small class="text-danger fst-italic">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-lg-12 mb-3">
                                <label for="description" class="form-label">Mô tả</label>
                                <textarea class="form-control" name="description" id="description" rows="3"
                                    placeholder="Nhập mô tả..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Quay lại</button>
                        <button id="submitUpdate" type="button" class="btn btn-primary">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @php
        $appUrl = env('APP_URL');
    @endphp
@endsection
@section('script')
    <script>
        const data = @json($branchsRelation);

        $('#cinemas').prop('disabled', true);
        $('#branchSelect').change(function () {
            let id = $(this).val();
            let filteredData = "";
            Object.entries(data).forEach(([key, value]) => {
                if (id === key) {
                    filteredData = value;
                }
            });
            $('#cinemas').prop('disabled', false);
            $('#cinemas').empty();
            Object.entries(filteredData).forEach(([key, value]) => {
                $('#cinemas').append(`<option value="${key}">${value}</option>`);
            });
        });

        const seat_templates = @json($seat_templates);

        $('#seat_templates').change(function () {

            let id = $(this).val();

            let dataTemplate = "";


            seat_templates.forEach((item) => {
                if (id == item.id) {
                    dataTemplate = item.seat_structure;
                    return;
                }
            })

            $('#seat_structure').val(dataTemplate);

        });


        let Url = @json($appUrl);
        $('input[id^="is_active"]').change(function () {
            let id = this.id.replace('is_active', ''); // Lấy ID động
            let is_active = this.checked ? 1 : 0; // Kiểm tra trạng thái
            let publish = $(this).data('publish');


            if (publish) {
                if (confirm("Bạn có chắc chắn muốn thay đổi trạng thái ?")) {
                    $.ajax({
                        url: `${Url}/api/${id}/active-room`,
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
            } else {
                $(this).prop('checked', !is_active);
                toastr.error('Thao tác thất bại , Vui lòng xuất bản phòng !!!');
            }
        });
        $('.edit-room').click(function () {
            let id = $(this).data('id');
            let name = $(this).data('name');
            let branch_id = $(this).data('branch');
            let cinema_id = $(this).data('cinema');
            let typeroom_id = $(this).data('typeroom');
            let seat_template_id = $(this).data('seattemplate');
            let description = $(this).data('description');
            let publish = $(this).data('publish');

            let filteredData = "";
            Object.entries(data).forEach(([key, value]) => {

                if (branch_id == key) {

                    filteredData = value;

                }
            });

            $('#cinemasEdit').empty();
            Object.entries(filteredData).forEach(([key, value]) => {
                $('#cinemasEdit').append(`<option value="${key}">${value}</option>`);
            });

            $('#name').val(name);
            $('#branchEdit').val(branch_id);
            $('#cinemasEdit').val(cinema_id);
            $('#seat_templates_edit').val(seat_template_id);
            $('#type_room').val(typeroom_id);
            $('#description').val(description);

            let dataTemplate = "";


            seat_templates.forEach((item) => {
                if (seat_template_id == item.id) {
                    dataTemplate = item.seat_structure;
                    return;
                }
            })

            $('#seat_structure_edit').val(dataTemplate);

            $('.submitRoomFormUpdate').attr('action', `rooms/${id}/update`);



        })
        $('#branchEdit').change(function () {
            let id = $(this).val();
            let filteredData = "";
            Object.entries(data).forEach(([key, value]) => {
                if (id === key) {
                    filteredData = value;
                }
            });
            $('#cinemasEdit').empty();
            Object.entries(filteredData).forEach(([key, value]) => {
                $('#cinemasEdit').append(`<option value="${key}">${value}</option>`);
            });
        });

        $('#seat_templates_edit').change(function () {

            let id = $(this).val();


            let dataTemplate = "";


            seat_templates.forEach((item) => {
                if (id == item.id) {
                    dataTemplate = item.seat_structure;
                    return;
                }
            })


            $('#seat_structure_edit').val(dataTemplate);

        });


        function handleSubmit(formClass) {
            let form = $(`.${formClass}`);

            if (form[0].checkValidity()) {
                $(form).submit();
            } else {
                form[0].reportValidity();
            }
        }

        $('#submitCreate').click(function () {
            handleSubmit('formCreate');
        });

        $('#submitUpdate').click(function () {
            handleSubmit('submitRoomFormUpdate');
        });

    </script>

@endsection