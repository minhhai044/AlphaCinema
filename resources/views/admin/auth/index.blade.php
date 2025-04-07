<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Đăng nhập</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('theme/admin/assets/images/favicon.ico') }}">

    <!-- preloader css -->
    <link rel="stylesheet" href="{{ asset('theme/admin/assets/css/preloader.min.css') }}" type="text/css" />

    <!-- Bootstrap Css -->
    <link href="{{ asset('theme/admin/assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet"
        type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('theme/admin/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('theme/admin/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />

</head>

<body>
    <!-- <body data-layout="horizontal"> -->
    <div class="auth-page">
        <div class="container-fluid p-0">
            <div class="row g-0">
                <div class="col-xxl-12 col-lg-12 col-md-12">
                    <div class="auth-full-page-content d-flex p-sm-5 p-4">
                        <div class="w-100 d-flex justify-content-center">
                            <div class="d-flex flex-column w-25">
                                <div class="mb-1 mb-md-5 text-center">
                                    <a href="index.html" class="d-block auth-logo">
                                        <img src="{{ asset('logo/Logo Alpha cinema.svg') }}" alt="">
                                    </a>
                                </div>
                                <div>
                                    <div class="text-center">
                                        <p class="fw-bold fs-5">Đăng nhập vào hệ thống</p>
                                    </div>
                                    <form class="mt-4 pt-2" action="{{ route('login') }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="text" class="form-control" name="email" id="email"
                                                placeholder="Enter username">
                                            <div class="text-danger mt-1 fw-medium" id="email-error"> </div>

                                        </div>
                                        <div class="mb-3">
                                            <div class="d-flex align-items-start">
                                                <div class="flex-grow-1">
                                                    <label class="form-label">Mật khẩu</label>
                                                </div>
                                                {{-- <div class="flex-shrink-0">
                                                    <div class="">
                                                        <a href="auth-recoverpw.html" class="text-muted">Forgot
                                                            password?</a>
                                                    </div>
                                                </div> --}}
                                            </div>

                                            <div class="input-group auth-pass-inputgroup">
                                                <input type="password" class="form-control" name="password"
                                                    id="password" placeholder="Enter password" aria-label="Password"
                                                    aria-describedby="password-addon">
                                                <button class="btn btn-light shadow-none ms-0" type="button"
                                                    id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
                                            </div>
                                            <div class="text-danger mt-1 fw-medium" id="password-error"></div>
                                        </div>
                                        {{-- <div class="row mb-4">
                                            <div class="col">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="remember-check">
                                                    <label class="form-check-label" for="remember-check">
                                                        Remember me
                                                    </label>
                                                </div>
                                            </div>

                                        </div> --}}
                                        <div class="mb-3">
                                            <button class="btn btn-primary w-100 waves-effect waves-light"
                                                type="submit">Đăng nhập</button>
                                        </div>
                                    </form>

                                    {{-- <div class="mt-5 text-center">
                                        <p class="text-muted mb-0">Don't have an account ? <a href="auth-register.html"
                                                class="text-primary fw-semibold"> Signup now </a> </p>
                                    </div> --}}
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end auth full page content -->
                </div>
                <!-- end col -->

                <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container fluid -->
    </div>


    <!-- JAVASCRIPT -->
    <script src="{{ asset('theme/admin/assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/feather-icons/feather.min.js') }}"></script>
    <!-- pace js -->
    <script src="{{ asset('theme/admin/assets/libs/pace-js/pace.min.js') }}"></script>
    <!-- password addon init -->
    <script src="{{ asset('theme/admin/assets/js/pages/pass-addon.init.js') }}"></script>

    <script>
        // document.querySelector('#password-addon').addEventListener('click', function() {
        //     var passwordField = document.querySelector('#password');
        //     var icon = this.querySelector('i');

        //     if (passwordField.type === 'password') {
        //         passwordField.type = 'text';
        //         icon.classList.remove('mdi-eye-outline');
        //         icon.classList.add('mdi-eye-off-outline');
        //     } else {
        //         passwordField.type = 'password';
        //         icon.classList.remove('mdi-eye-off-outline');
        //         icon.classList.add('mdi-eye-outline');
        //     }
        // });

        $(document).ready(function() {
            $("form").submit(function(event) {
                var email = $("#email").val();
                var password = $("#password").val();
                var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                var isValid = true;

                // Xóa thông báo lỗi trước khi kiểm tra
                $("#email-error").text("");
                $("#password-error").text("");

                // Kiểm tra email hợp lệ
                if (!emailPattern.test(email)) {
                    $("#email-error").text("Email không hợp lệ và phải đúng định dạng.");
                    isValid = false;
                }

                // Kiểm tra mật khẩu không để trống
                if (password.trim() === "") {
                    $("#password-error").text("Mật khẩu không được để trống.");
                    isValid = false;
                }

                // Nếu có lỗi, ngừng submit form
                if (!isValid) {
                    event.preventDefault();
                }
            });
        });
    </script>


</body>

</html>
