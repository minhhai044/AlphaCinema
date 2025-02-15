@extends('admin.layouts.master')
@section('title', 'Quản lý tài khoản')

@section('style')
    <style>
        .required {
            color: red;
            font-style: italic !important;
        }
    </style>
@endsection

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Tạo tài khoản</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.users.index') }}">Tài khoản</a>
                        </li>
                        <li class="breadcrumb-item active">Tạo tài khoản</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->
    <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div>
                                    <div class="mb-3">
                                        <label for="account-name" class="form-label">
                                            <span class="required">*</span>
                                            Tên
                                        </label>
                                        <input class="form-control" type="text" name="name" id="account-name"
                                            placeholder="Nhập tên">

                                        @error('name')
                                            <span class="text-danger">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div>
                                    <div class="mb-3">
                                        <label for="account-phone" class="form-label">
                                            <span class="required">*</span>
                                            Số điện thoại
                                        </label>
                                        <input class="form-control" type="tel" name="phone" id="account-phone"
                                            placeholder="Nhập số điện thoại">

                                        @error('phone')
                                            <span class="text-danger">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <div class="mb-3">
                                        <label for="account-birthday" class="form-label">
                                            <span class="required">*</span>
                                            Ngày sinh
                                        </label>
                                        <input class="form-control" type="date" name="birthday"
                                            id="example-date-input" />

                                        @error('birthday')
                                            <span class="text-danger">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div>
                                    <div class="mb-3">
                                        <label for="account-email" class="form-label">
                                            <span class="required">*</span>
                                            Email
                                        </label>
                                        <input class="form-control" type="email" name="email" id="account-email"
                                            placeholder="Nhập email">

                                        @error('email')
                                            <span class="text-danger">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <div class="mb-3">
                                        <label for="account-password" class="form-label">
                                            <span class="required">*</span>
                                            Mật khẩu
                                        </label>
                                        <input class="form-control" type="password" name="password" id="account-password"
                                            placeholder="Nhập mật khẩu">

                                        @error('password')
                                            <span class="text-danger">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <div class="mb-3">
                                        <label for="account-name" class="form-label">
                                            <span class="required">*</span>
                                            Nhập lại mật khẩu
                                        </label>
                                        <input class="form-control" type="password" name="password_confirmation"
                                            id="confirm-password" placeholder="Nhập lại mật khẩu">

                                        @error('password_confirmation')
                                            <span class="text-danger">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div>
                                    <div class="mb-3">
                                        <label for="account-gender" class="form-label">
                                            <span class="required">*</span>
                                            Giới tính:
                                        </label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check form-radio-warning mb-3">
                                                <input class="form-check-input" type="radio" value="0" name="gender"
                                                    id="formRadioColor4" />
                                                <label class="form-check-label" for="formRadioColor4">
                                                    Nam
                                                </label>
                                            </div>

                                            <div class="form-check form-radio-info mb-3">
                                                <input class="form-check-input" type="radio" name="gender"
                                                    value="1" id="formRadioColor3" />
                                                <label class="form-check-label" for="formRadioColor3">
                                                    Nữ
                                                </label>
                                            </div>
                                            @error('gender')
                                                <span class="text-danger">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <div class="mb-3">
                                        <label for="account-gender" class="form-label">
                                            <span class="required">*</span>
                                            Địa chỉ
                                        </label>
                                        <input class="form-control" type="text" name="address"
                                            placeholder="Nhập địa chỉ">

                                        @error('address')
                                            <span class="text-danger">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            Submit
                            <i class="bx bx-chevron-right ms-1"></i>
                        </button>
                        <button class="btn btn-danger" type="button">
                            Cancel
                            <i class="bx bx-chevron-right ms-1"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3 d-flex gap-2">
                                    <label for="account-description" class="form-label">
                                        <span class="required">*</span>
                                        Admin
                                    </label>
                                    <div class="square-switch">
                                        <input type="checkbox" id="square-switch3" switch="bool" value="1"
                                            name="type_user">
                                        <label for="square-switch3" data-on-label="Yes" data-off-label="No"></label>
                                    </div>

                                    @error('type_user')
                                        <span class="text-danger">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>


                                <div>
                                    <div class="mb-3">
                                        <label for="account-image" class="form-label">
                                            <span class="required">*</span>
                                            Ảnh
                                        </label>
                                        <input class="form-control" type="file" name="avatar" id="account-image"
                                            onchange="previewImage(event)">
                                    </div>
                                    <!-- Display selected image and delete button -->
                                    <div id="image-container" class="d-none position-relative">
                                        <img id="image-preview" src="" alt="Preview" class="img-fluid mb-2" style="max-width: 100px; max-height: 100px;">
                                        <!-- Icon thùng rác ở góc phải -->
                                        <button type="button" id="delete-image" class="btn btn-danger position-absolute top-0 end-0 p-1" onclick="deleteImage()">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>

    <script>
        // Function to display selected image
        function previewImage(event) {
            const file = event.target.files[0];
            const reader = new FileReader();

            reader.onload = function() {
                $('#image-preview').attr('src', reader.result);
                $('#image-container').removeClass('d-none');
            }

            if (file) {
                reader.readAsDataURL(file);
            }
        }

        // Function to delete the selected image
        function deleteImage() {
            $('#delete-image').click(() => {
            $('#image-container').addClass('d-none');
            $('#account-image').val('');
            $('#image-preview').attr('src', '');
        });
        }
    </script>

@endsection
