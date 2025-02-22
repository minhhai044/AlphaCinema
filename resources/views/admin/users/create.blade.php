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
                                            Tên
                                            <span class="required">*</span>
                                        </label>
                                        <input class="form-control" type="text" name="name" id="account-name"
                                            placeholder="Nhập tên" value="{{ old('name') }}">

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
                                            Số điện thoại <span class="required">*</span>
                                        </label>
                                        <input class="form-control" type="tel" name="phone" id="account-phone"
                                            placeholder="Nhập số điện thoại" value="{{ old('phone') }}">

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
                                            Ngày sinh
                                            <span class="required">*</span> </label>
                                        <input class="form-control" type="date" name="birthday" id="account-birthday"
                                            value="{{ old('birthday') }}" />

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
                                            Email
                                            <span class="required">*</span> </label>
                                        <input class="form-control" type="email" name="email" id="account-email"
                                            placeholder="Nhập email" value="{{ old('email') }}">

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
                                            Mật khẩu
                                            <span class="required">*</span> </label>
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
                                            Nhập lại mậtkhẩu <span class="required">*</span>
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
                                            Giới tính:
                                            <span class="required">*</span> </label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check form-radio-warning mb-3">
                                                <input class="form-check-input" type="radio" value="0" name="gender"
                                                    id="account-gender" checked
                                                    {{ old('gender') == '0' ? 'checked' : '' }} />
                                                <label class="form-check-label" for="account-gender">
                                                    Nam
                                                </label>
                                            </div>

                                            <div class="form-check form-radio-info mb-3">
                                                <input class="form-check-input" type="radio" name="gender"
                                                    value="1" id="formRadioColor3"
                                                    {{ old('gender') == '1' ? 'checked' : '' }} />
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
                                            Địa chỉ
                                            <span class="required">*</span> </label>
                                        <input class="form-control" type="text" name="address" id="account-address"
                                            placeholder="Nhập địa chỉ" value="{{ old('address') }}">

                                        @error('address')
                                            <span class="text-danger">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="account-cinema" class="form-label">
                                        Cơ sở
                                        <span class="required">*</span> </label>
                                    <select class="form-select" id="simpleSelect" name="cinema"
                                        value="{{ old('cinema') }}">
                                        @foreach ($cinemas as $cinema)
                                            <option value="{{ $cinema->id }}"
                                                {{ old('cinema') == $cinema->id ? 'selected' : '' }}> {{ $cinema->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('cinema')
                                        <div class="text-danger">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <label for="account-gender" class="form-label">
                                    Vai trò
                                    <span class="required">*</span> </label>
                                {{-- <select class="form-select select2" name="role_id[]" id="multiSelect"
                                    multiple="multiple">
                                        @foreach ($roles as $role)
                                            @if ($role->name != 'System Admin')
                                            <option value="{{ $role->name }}"
                                                {{ in_array($role->name, old('role_id', []))  ? 'selected' : '' }}>
                                                {{ $role->name }}</option>
                                        @endif
                                    @endforeach
                                </select> --}}

                                <select class="form-control" name="role_id[]" id="choices-multiple-remove-button"
                                    placeholder="Chọn một hoặc nhiều mục" multiple>
                                    @foreach ($roles as $role)
                                        @if ($role['name'] != 'System Admin')
                                            <option id="{{ $role['name'] }}"
                                                {{ in_array($role->name, old('role_id', [])) ? 'selected' : '' }}>>
                                                {{ $role['name'] }}</option>
                                        @endif
                                    @endforeach
                                </select>


                                <div class="text-danger"> <strong id="errorSelect2"></strong> </div>

                                @error('role')
                                    <span class="text-danger">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div>
                                    <div class="mb-3">
                                        <label for="account-image" class="form-label">
                                            Ảnh
                                            <input class="form-control" type="file" name="avatar" id="account-image"
                                                onchange="previewImage(event)">
                                    </div>
                                    <!-- Display selected image and delete button -->
                                    <div id="image-container" class="d-none position-relative">
                                        <img id="image-preview" src="" alt="Preview" class="img-fluid mb-2"
                                            style="max-width: 100%; max-height: 200px;">
                                        <!-- Icon thùng rác ở góc phải -->
                                        <button type="button" id="delete-image"
                                            class="btn btn-danger position-absolute top-0 end-0 p-1"
                                            onclick="deleteImage()">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 mb-3">

                <div class="card-footer">
                    <button type="submit" id="btnSubmit" class="btn btn-primary">
                        Thêm mới
                        <i class="bx bx-chevron-right ms-1"></i>
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-danger">
                        Hủy
                        <i class="bx bx-chevron-right ms-1"></i>
                    </a>
                </div>
            </div>

        </div>

    </form>


@endsection

@section('script')
    <script src="{{ asset('assets/js/common.js') }}"></script>
    <script>
        $(document).ready(function() {
            new Choices("#choices-multiple-remove-button", {
                removeItemButton: true,
            })
        });
        let flagSubmit = false;
        let btnSubmit = $('#btnSubmit');

        let accountNameId = $('#account-name');
        let accountPhoneId = $('#account-phone');
        let accountBirthdayId = $('#account-birthday');
        let accountEmailId = $('#account-email');
        let accountPasswordId = $('#account-password');
        let accountConfirmPasswordId = $('#confirm-password');
        let accountGenderId = $('#account-gender');
        let accountAddressId = $('#account-address');
        let accountCinemaId = $('#simpleSelect');
        let accountRoleId = $('#multiSelect');


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

        accountNameId.on('blur', function() {
            let errorMessage = accountNameId.closest(".mb-3").find(".text-danger");

            if (accountNameId.val().trim() === '') {
                // Nếu không có thẻ .text-danger, thêm mới
                if (errorMessage.length === 0) {
                    accountNameId.after('<span class="text-danger"><strong>Tên được để trống</strong></span>');
                } else {
                    errorMessage.html("<strong>Tên không được để trống</strong>");
                }
            } else if (accountNameId.val().trim().length >= 255) {
                errorMessage.html("<strong>Tên ngắn hơn 255 ký tự</strong>");
            } else {
                errorMessage.html('');
            }
        });

        accountPhoneId.on('blur', () => {
            let errorMessage = accountPhoneId.closest(".mb-3").find(".text-danger");

            // Xóa lỗi cũ trước khi kiểm tra lại
            if (errorMessage.length) {
                errorMessage.remove();
            }

            let phoneValue = accountPhoneId.val().trim();

            if (phoneValue === "") {
                accountPhoneId.after(
                    '<span class="text-danger"><strong>Số điện thoại không được để trống</strong></span>');
                flagSubmit = false
            } else {
                let phoneRegex = /^[0-9]{10,11}$/; // Định dạng số điện thoại (10-11 chữ số)
                if (!phoneRegex.test(phoneValue)) {
                    accountPhoneId.after(
                        '<span class="text-danger"><strong>Số điện thoại sai định dạng</strong></span>');
                    flagSubmit = false
                } else {
                    flagSubmit = true
                }
            }

        });

        accountEmailId.on('blur', () => {
            let errorMessage = accountEmailId.closest(".mb-3").find(".text-danger");

            if (errorMessage.length) {
                errorMessage.remove();
            }
            let emailValue = accountEmailId.val().trim();

            if (emailValue === "") {
                accountEmailId.after(
                    '<span class="text-danger"><strong>Email không được để trống</strong></span>');
                flagSubmit = false
            } else {
                let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(emailValue)) {
                    accountEmailId.after(
                        '<span class="text-danger"><strong>Email sai định dạng</strong></span>');
                    flagSubmit = false
                } else {
                    flagSubmit = true
                }

            }

        });

        accountPasswordId.on('blur', () => {
            let errorMessage = accountPasswordId.closest(".mb-3").find(".text-danger");

            if (errorMessage.length) {
                errorMessage.remove();
            }

            let passwordValue = accountPasswordId.val().trim();

            if (passwordValue.length < 8) {
                accountPasswordId.after(
                    '<span class="text-danger"><strong>Mật khẩu không được để trống phải lớn hơn 8 ký tự</strong></span>'
                );
                flagSubmit = false
            }



        });

        accountConfirmPasswordId.on('blur', () => {
            let errorMessage = accountConfirmPasswordId.closest(".mb-3").find(".text-danger");

            if (errorMessage.length) {
                errorMessage.remove();
            }

            let passwordValue = accountPasswordId.val().trim();
            let confirmPasswordValue = accountConfirmPasswordId.val().trim();

            if (confirmPasswordValue === '') {
                accountConfirmPasswordId.after(
                    '<span class="text-danger"><strong>Xác nhận mật khẩu không được để trống</strong></span>'
                );
            } else if (confirmPasswordValue !== passwordValue) {
                accountConfirmPasswordId.after(
                    '<span class="text-danger"><strong>Mật khẩu xác nhận không khớp</strong></span>'
                );
            }
        });

        accountAddressId.on('blur', () => {
            let errorMessage = accountAddressId.closest(".mb-3").find(".text-danger");

            if (errorMessage.length) {
                errorMessage.remove();
            }

            let addressValue = accountAddressId.val().trim();

            if (addressValue === '') {
                accountAddressId.after(
                    '<span class="text-danger"><strong>Nhập địa chỉ</strong></span>'
                );
            }

        });


        accountRoleId.on('select2:close', function() {
            let errorMessage = accountRoleId.closest(".mb-3").find(".text-danger");

            if (errorMessage.length) {
                errorMessage.remove();
            }

            let roleValue = accountRoleId.val();

            if (!roleValue || roleValue.length === 0) {
                $('#errorSelect2').text('Chọn role');
            } else {
                $('#errorSelect2').text('');
            }
        });
    </script>

@endsection
