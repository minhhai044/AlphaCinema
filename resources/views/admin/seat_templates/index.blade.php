@extends('admin.layouts.master')
@section('style')
    <link href="{{ asset('theme/admin/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/admin/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/admin/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />

@endsection
@section('content')

    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 fw-semibold font-size-20">Danh sách mẫu ghế</h4>

                <div class="page-title-right">

                    <button type="button" id="addSeatTemplate" class="btn btn-primary float-end mb-2 me-3" data-bs-toggle="modal"
                        data-bs-target="#exampleModal">
                        <i class="bi bi-plus-lg"></i> Thêm mẫu ghế
                    </button>
                </div>

            </div>
        </div>
    </div>
    <!-- Modal create -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('admin.store.seat_templates') }}" method="post" class="submitSeatTemplateForm">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Thêm mẫu ghế</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-lg-12 mb-3">
                                <label for="nameInput" class="form-label">Tên mẫu ghế <span
                                        style="color: red">*</span></label>
                                <input type="text" name="name" required
                                    data-pristine-required-message="Vui lòng nhập tên mẫu ghế !!!"
                                    class="form-control @error('name') is-invalid @enderror" placeholder="Nhập tên mẫu ghế"
                                    value="{{ old('name') }}">
                                @error('name')
                                    <small class="text-danger fst-italic">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-lg-12 mb-3">
                                <label for="matrixSelectCreate" class="form-label">Ma trận ghế <span
                                        style="color: red">*</span></label>
                                <select name="matrix" class="form-select @error('matrix') is-invalid @enderror" required
                                    id="matrixSelectCreate">
                                    <option value="" disabled selected>Chọn ma trận ghế</option>
                                    @foreach ($matrixs as $matrix)
                                        <option value="{{ $matrix['id'] }}">{{ $matrix['name'] }}
                                            {{ $matrix['description'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('matrix')
                                    <small class="text-danger fst-italic">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label for="regularSeat" class="form-label">Ghế thường <span
                                        style="color: red">*</span></label>
                                <input required type="text" name="row_regular"
                                    class="form-control @error('row_regular') is-invalid @enderror" id="regularSeatCreate"
                                    placeholder="Nhập số lượng ghế thường">
                                @error('row_regular')
                                    <small class="text-danger fst-italic">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label for="vipSeat" class="form-label">Ghế Vip <span style="color: red">*</span></label>
                                <input required type="text" name="row_vip"
                                    class="form-control @error('row_vip') is-invalid @enderror" id="vipSeatCreate"
                                    placeholder="Nhập số lượng ghế Vip">
                                @error('row_vip')
                                    <small class="text-danger fst-italic">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label for="doubleSeat" class="form-label">Ghế đôi <span style="color: red">*</span></label>
                                <input required type="text" name="row_double"
                                    class="form-control @error('row_double') is-invalid @enderror" id="doubleSeatCreate"
                                    placeholder="Nhập số lượng ghế đôi">
                                @error('row_double')
                                    <small class="text-danger fst-italic">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-lg-12 mb-3">
                                <label for="description" class="form-label">Mô tả</label>
                                <textarea class="form-control" name="description" rows="3"
                                    placeholder="Nhập mô tả...">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Quay lại</button>
                        <button id="submitSeatTemplate" type="button" class="btn btn-primary">Thêm mẫu ghế</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- Modal update --}}

    <table id="datatable" class="table table-bordered text-center">
        <thead >
            <tr>
                <th class="fw-semibold text-center">STT</th>
                <th class="fw-semibold text-center">Tên mẫu ghế</th>
                <th class="fw-semibold text-center">Mô tả</th>
                <th class="fw-semibold text-center">Ma trận</th>
                <th class="fw-semibold text-center">Trạng thái</th>
                <th class="fw-semibold text-center">Hoạt động</th>
                <th class="fw-semibold text-center">Chức năng</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dataAll as $data)
                    @php
                        $dataMatrix = $matrix = \App\Models\Seat_template::getMatrixById($data->matrix);
                    @endphp

                    <tr>
                        <input type="hidden" name="id" id="dataId" value="{{ $data->id }}">
                        <td id="dataId">{{ $loop->iteration }}</td>
                        <td class="fw-semibold">{{ limitText($data->name, 20) }}</td>
                        <td>{{ limitText($data->description, 20)  }}</td>
                        <td>{{ $dataMatrix['name'] }}</td>
                        <td>
                            @if ($data->is_publish)
                                <span class="badge bg-success">Đã cấu tạo</span>
                            @else
                                <span class="badge bg-warning">Chưa cấu tạo</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex justify-content-center align-items-center">
                                <div class="custom-switch">
                                    <input @checked($data->is_active) switch="primary" data-publish="{{ $data->is_publish }}" class="form-check-input switch-is-active" id="is_active{{ $data->id }}" type="checkbox">
                                    <label for="is_active{{ $data->id }}"></label>
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('admin.edit.seat_templates', $data) }}"><button class="btn btn-success btn-sm"><i
                                        class="mdi mdi-plus-circle-outline"></i></button>
                            </a>
                            <a class="edit-seat-template" href="#" data-id="{{ $data->id }}" data-name="{{ $data->name }}"
                                data-matrix="{{ $data->matrix }}" data-regular="{{ $data->row_regular }}"
                                data-vip="{{ $data->row_vip }}" data-double="{{ $data->row_double }}"
                                data-description="{{ $data->description }}" data-publish="{{ $data->is_publish }}"
                                data-bs-toggle="modal" data-bs-target="#exampleModalEdit">
                                <button class="btn btn-warning btn-sm"> <i class="bx bx-edit"></i></button>
                            </a>


                        </td>
                    </tr>
            @endforeach
        </tbody>
    </table>
    {{-- {{ $dataAll->links() }} --}}
    @php
        $appUrl = env('APP_URL');
    @endphp
    <div class="modal fade" id="exampleModalEdit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="post" class="submitSeatTemplateFormUpdate">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Sửa mẫu ghế</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12 mb-3">
                                <label for="nameInput" class="form-label">Tên mẫu ghế <span
                                        style="color: red">*</span></label>
                                <input required type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror" id="nameInput"
                                    placeholder="Nhập tên mẫu ghế">
                                @error('name')
                                    <small class="text-danger fst-italic">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-lg-12 mb-3">
                                <label for="matrixSelectEdit" class="form-label">Ma trận ghế <span
                                        style="color: red">*</span></label>
                                <select name="matrix" id="matrixSelectEdit"
                                    class="form-select @error('matrix') is-invalid @enderror" required>
                                    <option value="" disabled selected>Chọn ma trận ghế</option>
                                    @foreach ($matrixs as $matrix)
                                        <option value="{{ $matrix['id'] }}">{{ $matrix['name'] }}
                                            {{ $matrix['description'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('matrix')
                                    <small class="text-danger fst-italic">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label for="regularSeat" class="form-label">Ghế thường <span
                                        style="color: red">*</span></label>
                                <input required type="text" name="row_regular"
                                    class="form-control @error('row_regular') is-invalid @enderror" id="regularSeatEdit"
                                    placeholder="Nhập số lượng ghế thường">
                                @error('row_regular')
                                    <small class="text-danger fst-italic">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label for="vipSeat" class="form-label">Ghế Vip <span style="color: red">*</span></label>
                                <input required type="text" name="row_vip"
                                    class="form-control @error('row_vip') is-invalid @enderror" id="vipSeatEdit"
                                    placeholder="Nhập số lượng ghế Vip">
                                @error('row_vip')
                                    <small class="text-danger fst-italic">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label for="doubleSeat" class="form-label">Ghế đôi <span style="color: red">*</span></label>
                                <input required type="text" name="row_double"
                                    class="form-control @error('row_double') is-invalid @enderror" id="doubleSeatEdit"
                                    placeholder="Nhập số lượng ghế đôi">
                                @error('row_double')
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
                        <button id="submitSeatTemplateUpdate" type="button" class="btn btn-primary">Cập nhật mẫu
                            ghế</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
            @if (session('error_modal') == 'create')
                $('#exampleModal').modal('show');
            @elseif (session('error_modal') == 'edit' && session('seat_template_id'))

                setTimeout(() => {
                    const editBtn = $(`.edit-seat-template[data-id="{{ session('seat_template_id') }}"]`);
                    editBtn.trigger('click');
                    $('#exampleModalEdit').modal('show');

                    // Gán class is-invalid
                    @foreach ($errors->keys() as $field)
                        $(`[name="{{ $field }}"]`).addClass('is-invalid');
                    @endforeach

                    // Gán thông báo lỗi dưới field
                    @foreach ($errors->messages() as $field => $messages)
                        let input = $(`[name="{{ $field }}"]`);
                        let errorMessage =
                            `<small class="text-danger fst-italic">{{ $messages[0] }}</small>`;
                        if (input.length > 0) {
                            input.closest('.mb-3').append(errorMessage);
                        }
                    @endforeach

                }, 400);

            @endif
        });

        function resetErrors(modalId = '') {
            let modal = modalId ? $(modalId) : $(document);

            modal.find('input, select, textarea').removeClass('is-invalid');

            modal.find('small.text-danger').remove();
        }
        $(document).ready(function () {
            let matrixData = @json($matrixs);

            function handleMatrixChange(selectId, regularId, vipId, doubleId) {
                let matrixId = $(selectId).val();
                let selectedMatrix = matrixData.find(matrix => matrix.id == matrixId);

                if (selectedMatrix) {
                    $(regularId).val(selectedMatrix.row_default['regular']);
                    $(vipId).val(selectedMatrix.row_default['vip']);
                    $(doubleId).val(selectedMatrix.row_default['double']);
                } else {
                    $(regularId).val('');
                    $(vipId).val('');
                    $(doubleId).val('');
                }
            }

            $('#matrixSelectCreate').change(function () {
                handleMatrixChange('#matrixSelectCreate', '#regularSeatCreate', '#vipSeatCreate',
                    '#doubleSeatCreate');
            });

            $('#matrixSelectEdit').change(function () {
                handleMatrixChange('#matrixSelectEdit', '#regularSeatEdit', '#vipSeatEdit',
                    '#doubleSeatEdit');
            });

            function handleSubmit(formClass, matrixSelectId, regularSeatId, vipSeatId, doubleSeatId) {
                let form = $(`.${formClass}`);

                if (form[0].checkValidity()) {
                    let regularSeat = $(regularSeatId).val().trim();
                    let vipSeat = $(vipSeatId).val().trim();
                    let doubleSeat = $(doubleSeatId).val().trim();
                    let matrixId = $(matrixSelectId).val().trim();

                    let totalSeat = parseInt(regularSeat) + parseInt(vipSeat) + parseInt(doubleSeat);
                    let selectedMatrix = matrixData.find(matrix => matrix.id == matrixId);

                    if (matrixId && selectedMatrix) {
                        if (totalSeat === selectedMatrix.max_col) {
                            $(form).submit();
                        } else {
                            toastr.error('Vui lòng kiểm tra lại số lượng ghế !!');
                            handleMatrixChange(matrixSelectId, regularSeatId, vipSeatId, doubleSeatId);
                        }
                    } else {
                        toastr.error('matrixId rỗng hoặc không hợp lệ !!');
                    }
                } else {
                    form[0].reportValidity();
                }
            }

            $('#submitSeatTemplate').click(function () {
                handleSubmit('submitSeatTemplateForm', '#matrixSelectCreate', '#regularSeatCreate',
                    '#vipSeatCreate', '#doubleSeatCreate');
            });

            $('#submitSeatTemplateUpdate').click(function () {
                handleSubmit('submitSeatTemplateFormUpdate', '#matrixSelectEdit', '#regularSeatEdit',
                    '#vipSeatEdit', '#doubleSeatEdit');
            });
        });
        // Phần active
        $(document).ready(function () {
            let Url = @json($appUrl);
            console.log(Url);

            $('input[id^="is_active"]').change(function () {
                let id = this.id.replace('is_active', ''); // Lấy ID động
                let is_active = this.checked ? 1 : 0; // Kiểm tra trạng thái
                let publish = $(this).data('publish');


                if (publish) {
                    if (confirm("Bạn có chắc chắn muốn thay đổi trạng thái ?")) {
                        $.ajax({
                            url: `${Url}/api/v1/${id}/active-seat-template`,
                            method: "PUT",
                            data: {
                                is_active
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                toastr.success('Thao tác thành công !!!');
                            },
                            error: function (error) {
                                console.log(error)

                                toastr.error('Thao tác thất bại !!!');
                            }
                        });
                    } else {
                        $(this).prop('checked', !is_active);
                    }
                } else {
                    $(this).prop('checked', !is_active);
                    toastr.error('Thao tác thất bại , Vui lòng cấu tạo ghế !!!');
                }
            });
        });
        // addClass
        $('#addSeatTemplate').click(function () {
            resetErrors('#exampleModal');
        });
        // Phần edit
        $('.edit-seat-template').click(function () {
            resetErrors('#exampleModalEdit');
            let id = $(this).data('id');
            let name = $(this).data('name');
            let matrix = $(this).data('matrix');
            let regular = $(this).data('regular');
            let vip = $(this).data('vip');
            let double = $(this).data('double');
            let description = $(this).data('description');
            let publish = $(this).data('publish');

            // Điền dữ liệu vào form
            $('#nameInput').val(name);
            $('#matrixSelectEdit').val(matrix);
            $('#regularSeatEdit').val(regular);
            $('#vipSeatEdit').val(vip);
            $('#doubleSeatEdit').val(double);
            $('#description').val(description);

            if (publish) {
                $('#regularSeatEdit, #vipSeatEdit, #doubleSeatEdit').attr('readonly', true);
                $('#matrixSelectEdit').prop('disabled', true);
            } else {
                $('#vipSeatEdit, #doubleSeatEdit').removeAttr('readonly');
                $('#matrixSelectEdit').prop('disabled', false);
            }




            // Thay đổi action của form để cập nhật
            $('.submitSeatTemplateFormUpdate').attr('action', `seat-templates/${id}/update`);
            $('.submitSeatTemplateFormUpdate').append('<input type="hidden" name="_method" value="PUT">');

            // Đổi text của nút submit
            $('#submitSeatTemplateUpdate').text('Cập nhật');
        });
    </script>
@endsection