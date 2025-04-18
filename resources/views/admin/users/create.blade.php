@extends('admin.layouts.master')
@section('title', 'Quản lý tài khoản')

@section('style')
    <style>
        .required {
            color: red;
            font-style: italic !important;
        }

        .choices {
            margin-bottom: 0 !important;
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
    <form action="{{ route('admin.users.store') }}" id="userForm" method="POST" enctype="multipart/form-data">
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
                                            Nhập lại mật khẩu <span class="required">*</span>
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
                                <label for="account-role" class="form-label">
                                    Vai trò
                                    <span class="required">*</span> </label>

                                <select class="form-control" name="role_id" id="choices-multiple-remove-button">
                                    <option value="">Chọn vai trò</option>
                                    @foreach ($roles as $role)
                                        @if ($role['name'] != 'System Admin')
                                            @if (auth()->user()->hasRole('System Admin'))
                                                <!-- Admin can select all roles -->
                                                <option id="{{ $role['name'] }}" {{-- {{ in_array($role->name, old('role_id', [])) ? 'selected' : '' }}> --}}
                                                    {{ old('role_id') == $role['name'] ? 'selected' : '' }}>
                                                    {{ $role['name'] }}
                                                </option>
                                            @elseif (auth()->user()->hasRole('Quản lý chi nhánh'))
                                                <!-- Branch Manager can assign 'Quản lý chi nhánh' and 'Nhân viên' roles -->
                                                @if ($role['name'] == 'Quản lý rạp' || $role['name'] == 'Nhân viên')
                                                    <option id="{{ $role['name'] }}" {{-- {{ in_array($role->name, old('role_id', [])) ? 'selected' : '' }}> --}}
                                                        {{ old('role_id') == $role['name'] ? 'selected' : '' }}>
                                                        {{ $role['name'] }}
                                                    </option>
                                                @endif
                                            @elseif (auth()->user()->hasRole('Quản lý rạp'))
                                                <!-- Facility Manager can only assign 'Nhân viên' role -->
                                                @if ($role['name'] == 'Nhân viên')
                                                    <option id="{{ $role['name'] }}" {{-- {{ in_array($role->name, old('role_id', [])) ? 'selected' : '' }}> --}}
                                                        {{ old('role_id') ? 'selected' : '' }}>
                                                        {{ $role['name'] }}
                                                    </option>
                                                @endif
                                            @endif
                                        @endif
                                    @endforeach
                                </select>

                                <span id="errorRole" class="text-danger fw-medium"></span>
                            </div>

                            <div id="box-branch"
                                class="col-lg-6 {{ auth()->user()->hasRole('Quản lý chi nhánh') || auth()->user()->hasRole('Quản lý rạp') ? 'd-none' : '' }}">
                                <div class="mb-3">
                                    <label for="account-cinema" class="form-label">
                                        Chi nhánh
                                        <span class="required">*</span>
                                    </label>
                                    <select class="form-select" id="branch_select" name="branch_id">
                                        <option value="">Chọn chi nhánh làm việc</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}"
                                                {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                                {{ $branch->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('branch_id')
                                        <div class="text-danger">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-6" id="box-cinema">
                                <div class="mb-3">
                                    <label for="account-cinema" class="form-label">
                                        Cơ sở
                                        <span class="required">*</span>
                                    </label>
                                    <select class="form-select" id="cinema_select" name="cinema_id"
                                        {{ auth()->user()->hasRole('Quản lý rạp') ? 'disabled' : '' }}>
                                        <option value="">Chọn rạp làm việc</option>
                                        @foreach ($cinemas as $cinema)
                                            <option value="{{ $cinema->id }}"
                                                {{ auth()->user()->hasRole('Quản lý rạp') && auth()->user()->cinema_id == $cinema->id ? 'selected' : '' }}
                                                {{ old('cinema_id') == $cinema->id ? 'selected' : '' }}>
                                                {{ $cinema->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('cinema_id')
                                        <div class="text-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{-- <div class="col-lg-3">
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
            </div> --}}

            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-2">
                            <label for="account-image" class="form-label">
                                <span class=" text-danger fw-medium required">*</span> Ảnh
                                <input class="form-control" type="file" name="avatar" id="account-image"
                                    onchange="previewImage(event)">
                        </div>
                        @if (old('avatar'))
                            <img src="{{ Storage::url(old('avatar')) }}" alt="Thumbnail" class="img-fluid"
                                width="200" style="max-width: 70%; max-height: 150px;">
                        @endif
                        @error('avatar')
                            <span class="text-danger fw-medium">
                                {{ $message }}
                            </span>
                        @enderror
                        <!-- Display selected image and delete button -->
                        <div id="image-container" class="d-none position-relative text-center">
                            <img id="image-preview" src="" alt="Preview" class="img-fluid mb-2"
                                style="max-width: 70%; max-height: 100px;">
                            <!-- Icon thùng rác ở góc phải -->
                            <button type="button" id="delete-image"
                                class="btn btn-danger position-absolute top-0 end-0 p-1" onclick="deleteImage()">
                                <i class="fas fa-trash-alt"></i>
                            </button>
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

    @php
        $roleAdmin = auth()->user()->hasRole('System Admin');
    @endphp

@endsection

@section('script')
    <script src="{{ asset('assets/js/common.js') }}"></script>
    <script>
        // $(document).ready(function() {
        //     new Choices("#choices-multiple-remove-button", {
        //         removeItemButton: true,
        //     })
        // });

        const roleAdmin = @json($roleAdmin);

        const selectedValue = $("#choices-multiple-remove-button").val(); // Lấy giá trị của lựa chọn

        if (selectedValue == "Quản lý chi nhánh") {
            $("#box-cinema").addClass("d-none"); // Ẩn box-cinema nếu chọn "Quản lý chi nhánh"
        } else {
            $("#box-cinema").removeClass("d-none"); // Hiển thị lại box-cinema nếu không chọn "Quản lý chi nhánh"
        }

        $("#account-phone").on("keypress", function(event) {
            if (!/^[0-9]$/.test(String.fromCharCode(event.which || event.keyCode))) {
                event.preventDefault();
            }
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
        let accountCinemaId = $('#branch_select');
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

        $('#branch_select').on('change', function() {
            var branchId = $(this).val(); // Lấy giá trị của chi nhánh đã chọn

            // Reset dropdown "Cơ sở" trước khi thêm các rạp mới
            $('#cinema_select').html('<option value="">Chọn rạp làm việc</option>');

            if (branchId) {
                getCinemasByBranch(branchId);
            }
        });

        $('#userForm').on('submit', function(e) {
            let isValid = true;
            const selectedRoles = $('#choices-multiple-remove-button').val();

            $('#error-cinema').remove();
            $('#error-branch').remove();
            $('#errorRole').text('');

            // Kiểm tra dropdown cinema_id (Cơ sở)
            if ($('#cinema_select[name="cinema_id"]').val() === '' && !$("#box-cinema").hasClass('d-none')) {
                isValid = false;

                $('<div id="error-cinema" class="text-danger fw-medium fw-medium mt-1">Vui lòng chọn cơ sở làm việc.</div>')
                    .insertAfter($('#cinema_select[name="cinema_id"]'));
            }
            if ($('#branch_select[name="branch_id"]').val() === '' && !$("#box-branch").hasClass('d-none')) {
                isValid = false;

                $('<div id="error-branch" class="text-danger fw-medium fw-medium mt-1">Vui lòng chọn chi nhánh làm việc.</div>')
                    .insertAfter($('#branch_select[name="branch_id"]'));
            }

            if (!selectedRoles || selectedRoles.length === 0) {
                isValid = false;

                $('#errorRole').text('Vui lòng chọn vai trò');
            }

            if (!isValid) {
                e.preventDefault();
            }
        });

        if (roleAdmin == true) {
            $("#choices-multiple-remove-button").on("change", function() {
                const value = $(this).val(); // Lấy giá trị của select
                console.log(value);

                // Kiểm tra nếu chọn "Quản lý chi nhánh", ẩn cinema và hiển thị box-branch
                // if (Array.isArray(value) && value.includes("Quản lý chi nhánh")) {
                if (value == "Quản lý chi nhánh") {
                    $("#box-cinema").addClass('d-none'); // Ẩn box-cinema
                    $("#box-branch").removeClass('d-none'); // Hiển thị box-branch
                    // } else if (Array.isArray(value) && value.includes("Nhân viên") || value.includes("Quản lý rạp")) {
                } else if (value == "Nhân viên" || value == "Quản lý rạp") {
                    // Kiểm tra nếu chọn "Nhân viên" hoặc "Quản lý rạp", hiển thị cinema và ẩn branch
                    $("#box-cinema").removeClass('d-none'); // Hiển thị box-cinema
                    const branchId = $('#branch_select').val();
                    if (branchId) {
                        getCinemasByBranch(branchId);
                    }
                } else {
                    // Nếu không chọn "Nhân viên" hoặc "Quản lý rạp" hoặc "Quản lý chi nhánh", ẩn cinema và branch
                    $("#box-cinema").removeClass('d-none'); // Ẩn box-cinema
                    $("#box-branch").removeClass('d-none'); // Ẩn box-branch
                }
            });
        }

        $("#cinema_select").on("change", function(e) {
            if ($(this).val()) {
                $('#error-cinema').remove(); // Xóa thông báo lỗi nếu đã chọn
            }
        });
        $("#branch_select").on("change", function(e) {
            if ($(this).val()) {
                $('#error-branch').remove(); // Xóa thông báo lỗi nếu đã chọn
            }
        });

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

        function getCinemasByBranch(branchId) {
            $.ajax({
                url: '/admin/get-cinemas/' + branchId, // Đảm bảo URL này đúng
                method: 'GET',
                success: function(response) {
                    $('#cinema_select').html('<option value="">Chọn rạp làm việc</option>'); // Reset dropdown
                    if (response.cinemas.length > 0) {
                        // Lặp qua các rạp và thêm vào dropdown
                        response.cinemas.forEach(function(cinema) {
                            $('#cinema_select').append(
                                '<option value="' + cinema.id + '">' + cinema.name + '</option>'
                            );
                        });
                    } else {
                        $('#cinema_select').html('<option value="">Không có rạp cho chi nhánh này</option>');
                    }
                },
                error: function(error) {
                    console.log("Có lỗi xảy ra khi lấy danh sách rạp:", error);
                }
            });
        }
    </script>

@endsection
