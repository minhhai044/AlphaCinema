@extends('admin.layouts.master');

@section('title', 'Cập nhật vai trò')

@section('style')
    <style>
        .card {
            box-shadow: 0px 1px 3px 1px #dedede;
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

    <form action="{{ route('admin.roles.update', $role) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method("PUT")

        <div class="row">


            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Cập nhật vai trò</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.roles.index') }}">Vai trò </a>
                        </li>
                        <li class="breadcrumb-item active">Cập nhật</li>
                    </ol>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-sm-12 d-flex justify-content-end">
                                <div class="al-table-length text-end">
                                    <label>
                                        Search:
                                        <input type="search" class="form-control form-control-sm al-table-input"
                                            placeholder="tìm kiếm" id="search-permission">
                                    </label>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-lg-4">
                                <div class="card">
                                    <div class="card-header fw-semibold">
                                        Thêm thông tin vai trò
                                    </div>
                                    <div class="card-body mb-3">
                                        <label for="account-name" class="form-label">
                                            <span class="text-danger">*</span> Tên vai trò
                                        </label>
                                        <input class="form-control" type="text" name="name" id="account-name" value="{{ $role->name }}"
                                            placeholder="Nhập tên">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-8">
                                <div class="card">
                                    <div class="card-header fw-semibold">
                                        Thêm quyền hạn cho vai trò
                                    </div>
                                    <div data-bs-spy="scroll" data-bs-target="#navbar-example2"
                                        data-bs-root-margin="0px 0px -40%" data-bs-smooth-scroll="true"
                                        class="scrollspy-example rounded-2" tabindex="0"
                                        style="max-height: 400px; overflow-y: auto;">
                                        <div class="card-body box-permission-role">
                                            <div class="row mb-2">
                                                <div class="col-md-6 px-5">
                                                    <div class="row">
                                                        <div class="col-md-12 mt-2">
                                                            <input type="checkbox" name="checkboxAll"
                                                                class="form-check-input checkboxAll" id="checkboxAll">
                                                            <label for="checkboxAll">Chọn tất cả</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 px-5">
                                                    @foreach ($permissions as $permission)
                                                        <div class="container-checkbox mb-2 border py-2 px-5">
                                                            <input type="checkbox" class="form-check-input"
                                                                id="{{ $permission->id }}"  name="permissions[]"
                                                                value="{{ $permission->name }}"
                                                                @if (in_array($permission->name, $rolePermissions)) checked @endif>
                                                            <label
                                                                for="{{ $permission->id }}">{{ $permission->name }}</label>
                                                        </div>
                                                    @endforeach
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

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <a href="{{ route('admin.roles.index') }}" class="btn btn-primary">Quay lại</a>
                            <button type="submit" class="btn btn-success mx-1">Cập nhật</button>
                        </div>
                    </div>
                </div>
                <!--end col-->
            </div>
    </form>





@endsection

@section('script')
    <script>
        $('#checkboxAll').on('click', function() {

            $('input[name="permissions[]"]').prop('checked', this.checked);
        });

        $('input[name="permissions[]"]').on('click', function() {

            $('#checkboxAll').prop('checked', $('input[name="permissions[]"]').length === $(
                'input[name="permissions[]"]:checked').length);
        });

        $('#search-permission').on('input', function() {
            var searchValue = $(this).val().toLowerCase();

            $(".container-checkbox").each(function() {
                var labelText = $(this).find("label").text().toLowerCase();

                if (labelText.includes(searchValue)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            })
        });

    </script>
@endsection
