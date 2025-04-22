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
                    <div class="auth-full-page-content d-flex p-sm-5 p-4 align-items-center">
                        <div class="w-100 d-flex justify-content-center">
                            <div class="d-flex flex-column w-25">
                                <div class="mb-1 mb-md-5 text-center">
                                    <a href="/" class="d-block auth-logo">
                                        <img src="{{ asset('logo/Logo Alpha cinema.svg') }}" alt="">
                                    </a>
                                </div>
                                <div>
                                    <div class="text-center">
                                        <p class="fw-bold fs-5">Gửi mail quên mật khẩu</p>
                                    </div>

                                    <form class="mt-4" action="{{ route('sendMailPassword') }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="text" class="form-control" id="email" name="email"
                                                placeholder="Enter email">

                                            @error('email')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3 mt-4">
                                            <button class="btn btn-primary w-100 waves-effect waves-light"
                                                type="submit">Reset</button>
                                        </div>
                                    </form>

                                    {{-- <div class="mt-5 text-center">
                                        <p class="text-muted mb-0">Don't have an account ? <a href="auth-register.html"
                                                class="text-primary fw-semibold"> Signup now </a> </p>
                                    </div> --}}
                                </div>

                                <div class="mt-5 text-center">
                                    <a href="{{ route('login') }}" class="text-primary fw-semibold"> Đăng nhập </a>
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

</body>

</html>
