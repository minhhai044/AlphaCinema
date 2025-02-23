@extends('admin.layouts.master')
@section('content')
    <h5 class="fw-semibold">Danh sách mẫu ghế</h5>
    <button type="button" class="btn btn-primary mb-3 float-end" data-bs-toggle="modal" data-bs-target="#exampleModal">
        Thêm mẫu ghế
    </button>

    {{-- <form action="{{route('admin.index.seat_templates')}}" method="get">
        <input type="text" name="name" value="{{ request('name') }}">
        <button type="submit">Search</button>
    </form> --}}

    <!-- Button trigger modal -->


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
                                <textarea class="form-control" name="description" rows="3" placeholder="Nhập mô tả..."></textarea>
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

    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th class="fw-semibold">STT</th>
                <th class="fw-semibold">Tên mẫu ghế</th>
                <th class="fw-semibold">Mô tả</th>
                <th class="fw-semibold">Ma trận</th>
                <th class="fw-semibold">Trạng thái</th>
                <th class="fw-semibold">Hoạt động</th>
                <th class="fw-semibold">Chức năng</th>
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
                    <td class="fw-semibold">{{ $data->name }}</td>
                    <td>{{ $data->description }}</td>
                    <td>{{ $dataMatrix['name'] }}</td>
                    <td>
                        @if ($data->is_publish)
                            <span class="badge bg-success">Đã cấu tạo</span>
                        @else
                            <span class="badge bg-warning">Chưa cấu tạo</span>
                        @endif
                    </td>
                    <td>
                        <input type="checkbox" id="is_active{{ $data->id }}" data-publish="{{ $data->is_publish }}"
                            switch="success" @checked($data->is_active) />
                        <label for="is_active{{ $data->id }}"></label>
                    </td>
                    <td>

                        <div class="dropdown">
                            <span data-bs-toggle="dropdown" aria-expanded="false" class="cursor-pointer">
                                <i class=" bx bx-dots-vertical-rounded"></i>
                            </span>
                            <ul class="dropdown-menu">
                                <li> <a class="dropdown-item" href="{{ route('admin.edit.seat_templates', $data) }}"><i
                                            class="mdi mdi-plus-circle-outline"></i> Cấu
                                        tạo
                                        ghế</a></li>
                                <li>
                                    <a class="dropdown-item edit-seat-template" href="#"
                                        data-id="{{ $data->id }}" data-name="{{ $data->name }}"
                                        data-matrix="{{ $data->matrix }}" data-regular="{{ $data->row_regular }}"
                                        data-vip="{{ $data->row_vip }}" data-double="{{ $data->row_double }}"
                                        data-description="{{ $data->description }}"
                                        data-publish="{{ $data->is_publish }}" data-bs-toggle="modal"
                                        data-bs-target="#exampleModalEdit">
                                        <i class="mdi mdi-playlist-edit"></i> Chỉnh sửa
                                    </a>
                                </li>
                            </ul>
                        </div>

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $dataAll->links() }}
    @php
        $appUrl = env('APP_URL');
    @endphp
    <div class="modal fade" id="exampleModalEdit" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
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
                                <label for="vipSeat" class="form-label">Ghế Vip <span
                                        style="color: red">*</span></label>
                                <input required type="text" name="row_vip"
                                    class="form-control @error('row_vip') is-invalid @enderror" id="vipSeatEdit"
                                    placeholder="Nhập số lượng ghế Vip">
                                @error('row_vip')
                                    <small class="text-danger fst-italic">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label for="doubleSeat" class="form-label">Ghế đôi <span
                                        style="color: red">*</span></label>
                                <input required type="text" name="row_double"
                                    class="form-control @error('row_double') is-invalid @enderror" id="doubleSeatEdit"
                                    placeholder="Nhập số lượng ghế đôi">
                                @error('row_double')
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
                        <button id="submitSeatTemplateUpdate" type="button" class="btn btn-primary">Cập nhật mẫu
                            ghế</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            @if ($errors->any())
                $('#exampleModal').modal('show'); // Giữ modal mở nếu có lỗi
            @endif
        });
        $(document).ready(function() {
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

            $('#matrixSelectCreate').change(function() {
                handleMatrixChange('#matrixSelectCreate', '#regularSeatCreate', '#vipSeatCreate',
                    '#doubleSeatCreate');
            });

            $('#matrixSelectEdit').change(function() {
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

            $('#submitSeatTemplate').click(function() {
                handleSubmit('submitSeatTemplateForm', '#matrixSelectCreate', '#regularSeatCreate',
                    '#vipSeatCreate', '#doubleSeatCreate');
            });

            $('#submitSeatTemplateUpdate').click(function() {
                handleSubmit('submitSeatTemplateFormUpdate', '#matrixSelectEdit', '#regularSeatEdit',
                    '#vipSeatEdit', '#doubleSeatEdit');
            });
        });
        // Phần active
        $(document).ready(function() {
            let Url = @json($appUrl);
            console.log(Url);

            $('input[id^="is_active"]').change(function() {
                let id = this.id.replace('is_active', ''); // Lấy ID động
                let is_active = this.checked ? 1 : 0; // Kiểm tra trạng thái
                let publish = $(this).data('publish');


                if (publish) {
                    if (confirm("Bạn có chắc chắn muốn thay đổi trạng thái ?")) {
                        $.ajax({

                            url: `${Url}/api/${id}/active-seat-template`,
                            method: "PUT",
                            data: {
                                is_active
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                toastr.success('Thao tác thành công !!!');
                            },
                            error: function(error) {
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
        // Phần edit
        $('.edit-seat-template').click(function() {
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
