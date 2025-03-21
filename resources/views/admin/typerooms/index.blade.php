@extends('admin.layouts.master')
@section('title', 'Quản lý loại phòng ')

@section('style')
    <link href="{{ asset('theme/admin/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/admin/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/admin/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('theme/admin/assets/css/preloader.min.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('theme/admin/assets/css/preloader.min.css') }}" type="text/css" />

    <link href="{{ asset('theme/admin/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('theme/admin/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
@endsection
@section('style-libs')
    <!-- Datatable CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
@endsection
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Quản lí danh sách phòng </h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Menu</a>
                        </li>
                        <li class="breadcrumb-item active">Danh sách phòng </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm">
                            <div class="mb-4">
                                
                            </div>
                        </div>
                        <div class="col-sm-auto">

                        </div>
                        <div class="col-sm-auto">
                            <div class="mb-4">
                                <a data-bs-toggle="modal" class="add_type_room btn btn-primary waves-effect waves-light"
                                    data-bs-target="#addModal">
                                    <i class="mdi mdi-plus"></i>
                                    Thêm mới
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table class="table align-middle table-nowrap dt-responsive nowrap w-100"
                                    id="datatable">
                                    <thead class="table-light">
                                        <tr >
                                            <th>#</th>
                                            <th>Loại phòng</th>
                                            <th>Phụ Phí</th>
                                            <th>Thời gian tạo</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody class="roomList">
                                        @foreach ($type_rooms as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td> <!-- Hiển thị số thứ tự thay vì ID -->
                                                <td>{{ $item->name }}</td>
                                                <td>{{ number_format($item->surcharge) }} VND</td>
                                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i:s') }}</td>

                                                <td>
                                                    <!-- Nút sửa -->
                                                    <button type="button" data-bs-toggle="modal" title="sửa"
                                                        class="edit_type_room btn btn-warning btn-sm edit-list"
                                                        data-bs-target="#exampleModal" data-id="{{ $item->id }}"
                                                        data-name="{{ $item->name }}"
                                                        data-surcharge="{{ $item->surcharge }}">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <!-- Nút xóa -->
                                                    {{-- <form method="POST"
                                                        action="{{ route('admin.typerooms.destroy', $item) }}"
                                                        class="d-inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="edit-list btn btn-danger btn-sm"
                                                            onclick="return confirm('Bạn có muốn xóa không')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form> --}}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>





                </div>
            </div>
        </div>
    </div>
    {{--  end index --}}

    {{-- modal thêm mới --}}

    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addModalLabel">Thêm mới phòng </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="{{ route('admin.typerooms.store') }}" id="formadd">
                    <div class="modal-body">
                        @csrf

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">

                                    <div class="card-body">
                                        <div class="live-preview">
                                            <div class="row gy-4">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label for="name">Tên loại phòng:</label>
                                                            <input type="text" name="name" id="name"
                                                                class="form-control" value="{{ old('name') }}">
                                                            <small class="text-danger error" id="nameError">
                                                                @error('name')
                                                                    {{ $message }}
                                                                @enderror
                                                            </small>
                                                        </div>

                                                        <div class="col-md-6 mb-3">
                                                            <label for="name">Giá phòng:</label>
                                                            <input type="number" name="surcharge"
                                                                class="form-control {{ $errors->has('surcharge') ? 'is-invalid' : (old('surcharge') ? 'is-valid' : '') }}"
                                                                value="{{ old('surcharge') }}">

                                                            <div class="invalid-feedback">
                                                                @if ($errors->has('surcharge'))
                                                                    {{ $errors->first('surcharge') }}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-submit"> Thêm mới</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- modal sửa  --}}

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Chỉnh sửa phòng </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" id="formupdate">
                    <div class="modal-body">
                        @csrf
                        @method('PUT')


                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">

                                    <div class="card-body">
                                        <div class="live-preview">
                                            <div class="row gy-4">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label for="name" class="form-label "> <span
                                                                    class="text-danger">*</span>Tên
                                                                loại phòng
                                                            </label>
                                                            <input type="text" class="form-control bg-light"
                                                                id="nameedit" name="name" readonly>
                                                        </div>

                                                        <div class="col-md-6 mb-3">
                                                            <label for="price" class="form-label ">
                                                                <span class="text-danger">*</span>
                                                                Giá
                                                            </label>
                                                            <input type="number" name="surcharge" id="surcharge"
                                                                class="form-control {{ $errors->has('surcharge') ? 'is-invalid' : (old('surcharge') ? 'is-valid' : '') }}">

                                                            <div
                                                                class="{{ $errors->has('surcharge') ? 'invalid-feedback' : 'valid-feedback' }}">
                                                                @if ($errors->has('surcharge'))
                                                                    {{ $errors->first('surcharge') }}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Sửa loại phòng</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('js/validate-type-room.js') }}"></script>
        <!-- Required datatable js -->
        <script src="{{ asset('theme/admin/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('theme/admin/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
        <!-- Buttons examples -->
        <script src="{{ asset('theme/admin/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('theme/admin/assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('theme/admin/assets/libs/jszip/jszip.min.js') }}"></script>
        <script src="{{ asset('theme/admin/assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
        <script src="{{ asset('theme/admin/assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>
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
    
        <script src="{{ asset('theme/admin/assets/js/app.js') }}"></script>
    
    <script>
        // Sửa 
        $('.edit_type_room').click(function(e) {
            let id = $(this).data('id');
            let name = $(this).data('name');
            let surcharge = $(this).data('surcharge');
            $('#nameedit').val(name);
            $('#surcharge').val(surcharge);

            $('#formupdate').attr('action', `typerooms/${id}/update`);
        });
    </script>
@endsection
