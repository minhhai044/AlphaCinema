@extends('admin.layouts.master')
@section('title', 'Cập nhật tài khoản')

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
                <h4 class="mb-sm-0 font-size-18">Cập nhật tài khoản</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.users.index') }}">Tài khoản</a>
                        </li>
                        <li class="breadcrumb-item active">Cập nhật tài khoản</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <form id="userForm" action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="account-name" class="form-label">
                                        <span class="required">*</span>
                                        Tên
                                    </label>
                                    <input class="form-control" type="text" name="name" id="account-name"
                                        value="{{ old('name', $user->name) }}" placeholder="Nhập tên">

                                    @error('name')
                                        <span class="text-danger fw-medium">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>



                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="account-phone" class="form-label">
                                        <span class="required">*</span>
                                        Số điện thoại
                                    </label>
                                    <input class="form-control" type="tel" name="phone" id="account-phone"
                                        value="{{ old('phone', $user->phone) }}" placeholder="Nhập số điện thoại">

                                    @error('phone')
                                        <span class="text-danger fw-medium">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="account-birthday" class="form-label">
                                        Ngày sinh
                                    </label>
                                    <input class="form-control" type="date" name="birthday" value="{{ $user->birthday }}"
                                        id="account-birthday" />

                                    @error('birthday')
                                        <span class="text-danger fw-medium">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="account-email" class="form-label">
                                        <span class="required">*</span>
                                        Email
                                    </label>
                                    <input class="form-control" type="email" name="email" id="account-email"
                                        value="{{ old('email', $user->email) }}" placeholder="Nhập email">

                                    @error('email')
                                        <span class="text-danger fw-medium">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="account-password" class="form-label">
                                        Mật khẩu
                                    </label>
                                    <input class="form-control" type="password" name="password" id="account-password"
                                        placeholder="Nhập mật khẩu">

                                    @error('password')
                                        <span class="text-danger fw-medium">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="confirm-password" class="form-label">
                                        Nhập lại mật khẩu
                                    </label>
                                    <input class="form-control" type="password" name="password_confirmation"
                                        id="confirm-password" placeholder="Nhập lại mật khẩu">

                                    @error('password_confirmation')
                                        <span class="text-danger fw-medium">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="account-gender" class="form-label">
                                        Giới tính:
                                    </label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check form-radio-warning mb-3">
                                            <input class="form-check-input" type="radio" value="0"
                                                {{ old('gender', $user->gender) == 0 ? 'checked' : '' }} name="gender"
                                                id="formRadioColor4" />
                                            <label class="form-check-label" for="formRadioColor4">
                                                Nam
                                            </label>
                                        </div>

                                        <div class="form-check form-radio-info mb-3">
                                            <input class="form-check-input" type="radio" value="1"
                                                {{ old('gender', $user->gender) == 1 ? 'checked' : '' }} name="gender"
                                                id="formRadioColor3" />
                                            <label class="form-check-label" for="formRadioColor3">
                                                Nữ
                                            </label>
                                        </div>
                                        @error('gender')
                                            <span class="text-danger fw-medium">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="account-address" class="form-label">
                                        Địa chỉ
                                    </label>
                                    <input class="form-control" type="text" name="address"
                                        value="{{ old('address', $user->address) }}" placeholder="Nhập địa chỉ">

                                    @error('address')
                                        <span class="text-danger fw-medium">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <label for="account-role" class="form-label">
                                    Vai trò
                                    <span class="required">*</span> </label>
                                <select class="form-control" name="role_id" id="choices-multiple-remove-button">
                                    @foreach ($roles as $role)
                                        @if ($role->name != 'System Admin')
                                            @if (auth()->user()->hasRole('System Admin'))
                                                <!-- Admin can select all roles except 'System Admin' -->
                                                <option value="{{ $role->id }}"
                                                    {{ $user->roles->contains($role) ? 'selected' : '' }}>
                                                    {{ $role->name }}
                                                </option>
                                            @elseif (auth()->user()->hasRole('Quản lý chi nhánh'))
                                                <!-- Branch Manager can select 'Quản lý chi nhánh' and 'Nhân viên' -->
                                                @if ($role->name == 'Quản lý rạp' || $role->name == 'Nhân viên')
                                                    <option value="{{ $role->id }}"
                                                        {{ $user->roles->contains($role) ? 'selected' : '' }}>
                                                        {{ $role->name }}
                                                    </option>
                                                @endif
                                            @elseif (auth()->user()->hasRole('Quản lý rạp'))
                                                <!-- Facility Manager can only select 'Nhân viên' -->
                                                @if ($role->name == 'Nhân viên')
                                                    <option value="{{ $role->id }}"
                                                        {{ $user->roles->contains($role) ? 'selected' : '' }}>
                                                        {{ $role->name }}
                                                    </option>
                                                @endif
                                            @endif
                                        @endif
                                    @endforeach
                                </select>

                                <span id="errorRole" class="text-danger fw-medium"></span>
                            </div>

                            <div id="box-branch"
                                class="col-lg-6 {{ auth()->user()->hasRole(['Quản lý chi nhánh', 'Quản lý rạp']) && $user->hasRole(['Quản lý rạp', 'Nhân viên'])? 'd-none': '' }}">
                                <div class="mb-3">
                                    <label for="account-branch" class="form-label">Chi nhánh <span
                                            class="required">*</span></label>
                                    <select class="form-select disabled" id="branch_select" name="branch_id">
                                        <option value="">Chọn chi nhánh</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}"
                                                {{ old('branch_id', $user->branch_id) == $branch->id ? 'selected' : '' }}
                                                {{ old('branch_id', $user->cinema ? $user->cinema->branch_id : 0) == $branch->id ? 'selected' : '' }}>
                                                {{ $branch->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('branch_id')
                                        <div class="text-danger fw-medium">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-6 {{ $user->hasRole('Quản lý chi nhánh') ? 'd-none' : '' }}"
                                id="box-cinema">
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
                                                {{ $user->cinema_id == $cinema->id ? 'selected' : '' }}>
                                                {{ $cinema->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('cinema_id')
                                        <div class="text-danger fw-medium">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
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
                                <div id="image-container" class="position-relative "
                                    style="width: 100%; height: 60%px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                    <!-- Preview ảnh -->

                                    <img id="image-preview"
                                        src="{{ $user->avatar && Storage::exists($user->avatar) ? Storage::url($user->avatar) : 'https://graph.facebook.com/4/picture?type=large' }}"
                                        class="img-fluid rounded avatar-xl"
                                        style="width: 100%; height: 60%; object-fit: cover;">

                                    <!-- Nút xóa ảnh -->
                                    <button type="button" id="delete-image"
                                        class="btn btn-danger position-absolute top-0 end-0 p-1">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>

                                <!-- Input file để chọn ảnh -->
                                <div class="mt-3">
                                    <input type="file" id="account-image" name="avatar" accept="image/*"
                                        class="d-none">
                                    <button type="button" id="change-image" class="btn btn-primary">Chọn ảnh</button>
                                </div>
                            </div>

                            @error('avatar')
                                <div class="text-danger fw-medium mt-3">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-lg-12 mb-3">
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    Cập nhật
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
        // Function to display selected image
        // Hàm xem trước ảnh khi chọn
        // $(document).ready(function() {
        //     new Choices("#choices-multiple-remove-button", {
        //         removeItemButton: true, // Enable remove item button for each selected option
        //         searchEnabled: true, // Enable search in the dropdown
        //         placeholderValue: 'Select roles', // Set placeholder text
        //     });

        // });
        const roleAdmin = @json($roleAdmin);

        $("#account-phone").on("keypress", function(event) {
            if (!/^[0-9]$/.test(String.fromCharCode(event.which || event.keyCode))) {
                event.preventDefault();
            }
        });

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

            // Nếu có lỗi thì ngăn không cho submit
            if (!isValid) {
                e.preventDefault();
            }
        });

        if (roleAdmin == true) {
            $("#choices-multiple-remove-button").on("change", function() {
                const value = $(this).val(); // Lấy giá trị của select
                console.log(value);

                // if (Array.isArray(value) && value.includes("2")) {
                if (value == 2) {
                    $("#box-cinema").addClass('d-none'); // Ẩn box-cinema
                    $("#box-branch").removeClass('d-none'); // Hiển thị box-branch
                    // } else if (Array.isArray(value) && value.includes("4") || value.includes("3")) {
                } else if (value == 4 || value == 3) {
                    $("#box-cinema").removeClass('d-none'); // Hiển thị box-cinema
                    const branchId = $('#branch_select').val();
                    if (branchId) {
                        getCinemasByBranch(branchId);
                    }
                } else {
                    $("#box-cinema").removeClass('d-none'); // Ẩn box-cinema
                    $("#box-branch").removeClass('d-none'); // Ẩn box-branch
                }
            });
        }

        $("#cinema_select").on("change", function(e) {
            console.log($(this).val());
            if ($(this).val()) {
                $('#error-cinema').remove(); // Xóa thông báo lỗi nếu đã chọn
            }
        });

        $('#change-image').click(function() {
            $('#account-image').click();
        });

        // Khi người dùng chọn ảnh mới
        $('#account-image').change(function(event) {
            let file = event.target.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#image-preview').attr('src', e.target.result).removeClass('d-none');
                    $('#image-container').removeClass('d-none');
                };
                reader.readAsDataURL(file);
            }
        });

        // Khi nhấn nút "Xóa ảnh"
        $('#delete-image').click(function() {
            $('#image-preview').attr('src', '').addClass('d-none');
            $('#image-container').addClass('d-none');
            $('#account-image').val('');
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
