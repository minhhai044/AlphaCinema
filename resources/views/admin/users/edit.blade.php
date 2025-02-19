@extends('admin.layouts.master')
@section('title', 'Cập nhật tài khoản')

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
    <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data">
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
                                        <span class="text-danger">
                                            <strong>{{ $message }}</strong>
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
                                        <span class="text-danger">
                                            <strong>{{ $message }}</strong>
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
                                        <span class="text-danger">
                                            <strong>{{ $message }}</strong>
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
                                        <span class="text-danger">
                                            <strong>{{ $message }}</strong>
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
                                        <span class="text-danger">
                                            <strong>{{ $message }}</strong>
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
                                        <span class="text-danger">
                                            <strong>{{ $message }}</strong>
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
                                            <span class="text-danger">
                                                <strong>{{ $message }}</strong>
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
                                        <span class="text-danger">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="account-cinema" class="form-label">
                                        Cơ sở
                                        <span class="required">*</span> </label>
                                    <select class="form-select" id="simpleSelect">
                                        @foreach ($cinemas as $cinema)
                                            <option value="{{ $cinema->id }}"
                                                {{ $cinema->id == $user->cinema_id ? 'selected' : '' }}>
                                                {{ $cinema->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <label for="account-gender" class="form-label">
                                    Vai trò
                                    <span class="required">*</span> </label>
                                <select class="form-select select2" name="role_id[]" id="multiSelect"
                                    multiple="multiple">
                                    @foreach ($roles as $role)
                                        @if ($role->name != 'System Admin')
                                            <option value="{{ $role->id }}"
                                                {{ $user->roles->contains($role) ? 'selected' : '' }}> {{ $role->name }}
                                            </option>
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
                                <div id="image-container" class="position-relative d-none">
                                    <!-- Preview ảnh -->
                                    <img id="image-preview" alt="Preview" class="align-items-center"
                                        class="img-fluid rounded avatar-xl mb-2"
                                        style="max-width: 100%; max-height: 60%;">

                                    <!-- Nút xóa ảnh -->
                                    <button type="button" id="delete-image"
                                        class="btn btn-danger position-absolute top-0 end-0 p-1">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>

                                <!-- Input file để chọn ảnh -->
                                <div class="mt-3">
                                    <input type="file" id="account-image" accept="image/*" class="d-none">
                                    <button type="button" id="change-image" class="btn btn-primary">Chọn ảnh</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12">

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

@endsection

@section('script')
    <script src="{{ asset('assets/js/common.js') }}"></script>
    <script>
        // Function to display selected image
        // Hàm xem trước ảnh khi chọn
        function previewImage(event) {
            const file = event.target.files[0];
            const reader = new FileReader();

            reader.onload = function() {
                // Hiển thị ảnh và nút xóa
                const imagePreview = document.getElementById('image-preview');
                const imageContainer = document.getElementById('image-container');
                imagePreview.src = reader.result;
                imageContainer.classList.remove('d-none'); // Hiển thị container ảnh và nút xóa
            }

            if (file) {
                reader.readAsDataURL(file);
            }
        }

        // Hàm xóa ảnh đã chọn
        document.addEventListener('DOMContentLoaded', function() {
            const accountImageInput = document.querySelector('#account-image');
            const deleteImageButton = document.querySelector('#delete-image');
            const imageContainer = document.querySelector('#image-container');
            const imagePreview = document.querySelector('#image-preview');

            // Gán sự kiện onchange cho input file
            accountImageInput.addEventListener('change', function(event) {
                previewImage(event);
            });

            // Xóa ảnh khi click vào nút xóa
            deleteImageButton.addEventListener('click', function() {
                imageContainer.classList.add('d-none'); // Ẩn container ảnh
                accountImageInput.value = ''; // Xóa file đã chọn
                imagePreview.src = ''; // Xóa ảnh xem trước
            });

            // Mở file input khi click vào nút "Chọn ảnh mới"
            document.querySelector('#change-image').addEventListener('click', function() {
                accountImageInput.click();
            });
        });
    </script>
@endsection
