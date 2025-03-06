@extends('admin.layouts.master')
@section('title', 'Quản lý loại phòng ')

@section('style')
    <style>
        .card {
            box-shadow: 0px 1px 10px 3px #dedede;
        }

        .al-table-length label {
            font-weight: normal;
            text-align: left;
            white-space: nowrap;
        }

        .al-table-length .al-table-select {
            width: auto;
            display: inline-block;
        }

        .al-table-length .al-table-input {
            margin-left: .5em;
            display: inline-block;
            width: auto;
        }

        .al-table-info {
            padding-top: .85em;
        }

        .al-table-paginate {
            margin: 0;
            white-space: nowrap;
            text-align: right;
        }

        .al-table-paginate .pagination {
            margin: 2px 0;
            white-space: nowrap;
            justify-content: flex-end;
        }
    </style>
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
                            <a href="javascript: void(0);">Danh sách phòng</a>
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
                                <h6 class="mb-sm-0 font-size-16">Danh sách phòng </h6>
                            </div>
                        </div>
                        <div class="col-sm-auto">
                            {{-- <div class="mb-4">
                                <a class="btn btn-light waves-effect waves-light">
                                    <i class="mdi mdi-arrow-down"></i>
                                    Xuất file 
                                </a>
                            </div> --}}
                        </div>
                        <div class="col-sm-auto">
                            <div class="mb-4">
                                <a data-bs-toggle="modal" class="add_type_room btn btn-light waves-effect waves-light"
                                    data-bs-target="#addModal">
                                    <i class="mdi mdi-plus"></i>
                                    Thêm mới
                                </a>
                            </div>
                        </div>
                        
                    </div>

                    {{-- <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="al-table-length">
                                <label>
                                    Show
                                    <select name="example_length" aria-controls="example"
                                        class="form-select form-select-sm al-table-select">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                    entries
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="al-table-length text-end">
                                <label>
                                    Search:
                                    <input type="search" class="form-control form-control-sm al-table-input"
                                        placeholder="Search đê">
                                </label>
                            </div>
                        </div>
                    </div> --}}

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table class="table align-middle table-nowrap dt-responsive nowrap w-100"
                                    id="customerList-table">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Loại phòng </th>
                                            <th>Phụ Phí </th>
                                            <th>Thời gian tạo </th>
                                            <th>Hành động </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <tbody class="roomList">
                                        @foreach ($type_rooms as $item)
                                            <td class="sorting_1 dtr-control">
                                                <div class="d-none">{{ $item->id }}</div>
                                                <div class="form-check font-size-{{ $item->id }}">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="customerlistcheck-{{ $item->id }}">
                                                    <label class="form-check-label"
                                                        for="customerlistcheck-{{ $item->id }}"></label>
                                                </div>
                                            </td>
                                            <td>
                                                {{ $item->name }}
                                            </td>
                                            <td>
                                                <div>{{ number_format($item->surcharge) }} VND</div>

                                            </td>
                                            <td>
                                                <div>{{ $item->created_at }}</div>

                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <a href="#" class="dropdown-toggle card-drop"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="mdi mdi-dots-horizontal font-size-18"></i>
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-menu-end" style="">
                                                        {{-- Button edit --}}
                                                        <li>
                                                            <button type="button" data-bs-toggle="modal"
                                                                class="edit_type_room dropdown-item edit-list"
                                                                data-bs-target="#exampleModal" data-id={{ $item->id }}
                                                                data-name="{{ $item->name }}"
                                                                data-surcharge={{ $item->surcharge }}>
                                                                <i
                                                                    class="mdi mdi-pencil font-size-16 text-success me-1"></i>
                                                                Sửa 
                                                            </button>
                                                        </li>
                                                        {{-- xoas --}}
                                                        <li>
                                                            <form method="POST"
                                                                action="{{ route('admin.typerooms.destroy', $item) }}"
                                                                class="d-inline-block">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item edit-list"
                                                                    onclick="return confirm('Bạn có muốn xóa không')">
                                                                    <i class="mdi mdi-pencil font-size-16 text-danger me-1"></i>
                                                                    Xóa
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 col-md-5">
                            <div class="al-table-info" id="customerList-table_info" role="status" aria-live="polite">
                                Showing 1 to 10 of 12 entries
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-7">
                            <div class="al-table-paginate paging_simple_numbers pagination-rounded"
                                id="customerList-table_paginate">
                                <ul class="pagination">
                                    <li class="paginate_button page-item previous disabled"
                                        id="customerList-table_previous"><a aria-controls="customerList-table"
                                            aria-disabled="true" role="link" data-dt-idx="previous" tabindex="-1"
                                            class="page-link"><i class="mdi mdi-chevron-left"></i></a></li>
                                    <li class="paginate_button page-item active"><a href="#"
                                            aria-controls="customerList-table" role="link" aria-current="page"
                                            data-dt-idx="0" tabindex="0" class="page-link">1</a></li>
                                    <li class="paginate_button page-item "><a href="#"
                                            aria-controls="customerList-table" role="link" data-dt-idx="1"
                                            tabindex="0" class="page-link">2</a></li>
                                    <li class="paginate_button page-item next" id="customerList-table_next"><a
                                            href="#" aria-controls="customerList-table" role="link"
                                            data-dt-idx="next" tabindex="0" class="page-link"><i
                                                class="mdi mdi-chevron-right"></i></a></li>
                                </ul>
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
                                                            <input type="text" class="form-control bg-light" id="nameedit"
                                                                name="name" readonly>
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
