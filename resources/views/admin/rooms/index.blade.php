@extends('admin.layouts.master')
@section('style')
    <link href="{{ asset('theme/admin/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('theme/admin/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/admin/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <style>
        .table {
            vertical-align: middle !important;
        }
    </style>
@endsection
@section('content')


    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-20 fw-semibold">Quản lý phòng chiếu</h4>

                <div class="page-title-right">


                    <button type="button" class="btn btn-primary float-end mb-2 me-3" data-bs-toggle="modal"
                        data-bs-target="#exampleModal">
                        Thêm phòng chiếu
                    </button>
                </div>

            </div>
        </div>
    </div>
    <!-- Modal -->
    <form action="{{ route('admin.rooms.store') }}" method="post" class="formCreate">
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
                            <input type="hidden" name="matrix_colume" id="matrix_colume">
                            {{-- Để tạm is_publish = 1 chưa cần lắm --}}
                            <input type="hidden" name="is_publish" value="1">
                            <div class="col-lg-12 mb-3">
                                <label for="nameInput" class="form-label">Tên phòng chiếu<span
                                        style="color: red">*</span></label>
                                <input type="text" name="name" required
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Nhập tên phòng chiếu" value="{{ old('name') }}">
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
                                <textarea class="form-control" name="description" rows="3" placeholder="Nhập mô tả..."></textarea>
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


    <table id="datatable" class="table table-bordered text-center">
        <thead>
            <tr>
                <th class="fw-semibold">STT</th>
                <th class="fw-semibold">Tên phòng</th>
                <th class="fw-semibold">Chi nhánh</th>
                <th class="fw-semibold">Rạp phim</th>
                <th class="fw-semibold">Loại phòng</th>
                <th class="fw-semibold">Hoạt động</th>
                <th class="fw-semibold">Chức năng</th>
            </tr>
        </thead>
        <tbody>
            @if (!empty($rooms))
                @foreach ($rooms as $room)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="fw-semibold">{{ $room->name }}</td>
                        <td>{{ $room->branch->name ?? 'Không có chi nhánh' }}</td>
                        <td>{{ $room->cinema->name ?? 'Không có rạp' }}</td>
                        <td>{{ $room->type_room->name ?? 'Không có loại phòng' }}</td>

                        <td>
                        <div class="d-flex justify-content-center align-items-center">
                            <div class="form-check form-switch form-switch-success">
                                <input @checked($room->is_active) data-publish="{{ $room->is_publish }}"
                                    class="form-check-input switch-is-active" id="is_active{{ $room->id }}"
                                    type="checkbox">
                            </div>
                        </div>
                        
                        </td>
                        <td>
                            <button class="btn btn-success btn-sm"><a class="dropdown-item"
                                    href="{{ route('admin.rooms.show', $room) }}"><i class="bx bx-show"></i></a></button>
                            <button class="btn btn-warning btn-sm"><a class="dropdown-item edit-room" href="#"
                                    data-id="{{ $room->id }}" data-name="{{ $room->name }}"
                                    data-branch="{{ $room->branch_id }}" data-cinema="{{ $room->cinema_id }}"
                                    data-typeroom="{{ $room->type_room_id }}"
                                    data-seattemplate="{{ $room->seat_template_id }}"
                                    data-description="{{ $room->description }}" data-publish="{{ $room->is_publish }}"
                                    data-bs-toggle="modal" data-bs-target="#exampleModalEdit">
                                    <i class=" bx bx-edit"></i>
                                </a></button>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>

    </table>
    {{-- {{$rooms->links()}} --}}
    <div class="modal fade" id="exampleModalEdit" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="post" class="submitRoomFormUpdate">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Sửa phòng</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="seat_structure" id="seat_structure_edit">
                            <input type="hidden" name="matrix_colume" id="matrix_colume_edit">

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
                                <label for="branchEdit" class="form-label">Chi nhánh <span
                                        style="color: red">*</span></label>
                                <select name="branch_id" class="form-select @error('branch_id') is-invalid @enderror"
                                    required id="branchEdit">
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
                                <label for="cinemasEdit" class="form-label">Rạp phim <span
                                        style="color: red">*</span></label>

                                <select name="cinema_id" class="form-select @error('cinema_id') is-invalid @enderror"
                                    required id="cinemasEdit">
                                    <option value="" disabled selected>Chọn rạp phim</option>
                                </select>
                                @error('cinema_id')
                                    <small class="text-danger fst-italic">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="seat_templates_edit" class="form-label">Mẫu ghế <span
                                        style="color: red">*</span></label>
                                <select name="seat_template_id"
                                    class="form-select @error('seat_template_id') is-invalid @enderror" required
                                    id="seat_templates_edit">
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

                                <select name="type_room_id"
                                    class="form-select @error('type_room_id') is-invalid @enderror" required
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
                                <textarea class="form-control" name="description" id="description" rows="3" placeholder="Nhập mô tả..."></textarea>
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
    <!-- Required datatable js -->
    <script src="{{ asset('theme/admin/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Buttons examples -->
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>

    <!-- Responsive examples -->
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}">
    </script>
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}">
    </script>

    <!-- Datatable init js -->
    <script src="{{ asset('theme/admin/assets/js/pages/datatables.init.js') }}"></script>
    <script>
        $(document).ready(function() {
            @if ($errors->any())
                $('#exampleModal').modal('show'); // Giữ modal mở nếu có lỗi
            @endif
        });
        const data = @json($branchsRelation);

        $('#cinemas').prop('disabled', true);
        $('#branchSelect').change(function() {
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

        $('#seat_templates').change(function() {

            let id = $(this).val();

            let dataTemplate = "";
            let matrix_colume = "";


            seat_templates.forEach((item) => {
                if (id == item.id) {
                    dataTemplate = item.seat_structure;
                    matrix_colume = +item.row_regular + +item.row_vip + +item.row_double;
                    return;
                }
            })

            $('#seat_structure').val(dataTemplate);
            $('#matrix_colume').val(matrix_colume);

        });


        let Url = @json($appUrl);
        $('input[id^="is_active"]').change(function() {
            let id = this.id.replace('is_active', ''); // Lấy ID động
            let is_active = this.checked ? 1 : 0; // Kiểm tra trạng thái
            let publish = $(this).data('publish');


            if (publish) {
                if (confirm("Bạn có chắc chắn muốn thay đổi trạng thái ?")) {
                    $.ajax({
                        url: `${Url}/api/v1/${id}/active-room`,
                        method: "PUT",
                        data: {
                            is_active
                        },
                        success: function(response) {
                            toastr.success('Thao tác thành công !!!');
                        },
                        error: function(error) {
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
        $('.edit-room').click(function() {
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
            let matrix_colume = "";

            seat_templates.forEach((item) => {
                if (seat_template_id == item.id) {
                    dataTemplate = item.seat_structure;
                    matrix_colume = +item.row_regular + +item.row_vip + +item.row_double;
                    return;
                }
            })

            $('#seat_structure_edit').val(dataTemplate);
            $('#matrix_colume_edit').val(matrix_colume);

            $('.submitRoomFormUpdate').attr('action', `rooms/${id}/update`);



        })
        $('#branchEdit').change(function() {
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

        $('#seat_templates_edit').change(function() {

            let id = $(this).val();


            let dataTemplate = "";
            let matrix_colume = "";


            seat_templates.forEach((item) => {
                if (id == item.id) {
                    dataTemplate = item.seat_structure;
                    matrix_colume = +item.row_regular + +item.row_vip + +item.row_double;
                    return;
                }
            })


            $('#seat_structure_edit').val(dataTemplate);
            $('#matrix_colume_edit').val(matrix_colume);


        });


        function handleSubmit(formClass) {
            let form = $(`.${formClass}`);

            if (form[0].checkValidity()) {
                $(form).submit();
            } else {
                form[0].reportValidity();
            }
        }

        $('#submitCreate').click(function() {
            handleSubmit('formCreate');
        });

        $('#submitUpdate').click(function() {
            handleSubmit('submitRoomFormUpdate');
        });
    </script>
@endsection
