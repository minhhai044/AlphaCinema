<div class="navbar-header">
    @php
        $user = \Auth::user();
    @endphp

    {{-- <span>{{ \Auth::user()->id }}</span>
    <span>{{ \Auth::user()->name }}</span> --}}

    <div class="d-flex">
        <!-- LOGO -->
        <div class="navbar-brand-box">
            <a href="{{ route('admin.index') }}" class="logo logo-dark">
                <span class="logo-sm">
                    <img src="{{ asset('logo/Icon Alpha cinema.svg') }}" alt="" style="width: 120%">
                </span>
                <span class="logo-lg">
                    <img src="{{ asset('logo/Logo Alpha cinema.svg') }}" alt="" style="width: 52%">
                </span>
            </a>

            <a href="{{ route('admin.index') }}" class="logo logo-light">
                <span class="logo-sm">
                    <img src="{{ asset('logo/Icon Alpha cinema.svg') }}" alt="" height="24">
                </span>
                <span class="logo-lg">
                    <img src="{{ asset('logo/Logo Alpha cinema.svg') }}" alt="" height="24">
                </span>
            </a>
        </div>

        <button type="button" class="btn btn-sm px-3 font-size-16 header-item" id="vertical-menu-btn">
            <i class="fa fa-fw fa-bars"></i>
        </button>

        {{-- <!-- App Search-->
        <form class="app-search d-none d-lg-block">
            <div class="position-relative">
                <input type="text" class="form-control" placeholder="Search...">
                <button class="btn btn-primary" type="button"><i class="bx bx-search-alt align-middle"></i></button>
            </div>
        </form> --}}
    </div>

    <div class="d-flex">
        <div class="dropdown d-inline-block d-lg-none ms-2">
            <button type="button" class="btn header-item" id="page-header-search-dropdown" data-bs-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i data-feather="search" class="icon-lg"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                aria-labelledby="page-header-search-dropdown">

                <form class="p-3">
                    <div class="form-group m-0">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search ..."
                                aria-label="Search Result">

                            <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="dropdown d-none d-sm-inline-block">
            <button type="button" class="btn header-item" data-bs-toggle="modal" data-bs-target="#qrModal">
                <i data-feather="maximize"></i>
            </button>
        </div>

        <div class="dropdown d-none d-sm-inline-block">
            <button type="button" class="btn header-item" id="mode-setting-btn">
                <i data-feather="moon" class="icon-lg layout-mode-dark"></i>
                <i data-feather="sun" class="icon-lg layout-mode-light"></i>
            </button>
        </div>



        @if (!empty(auth()->user()->branch_id) || !empty(auth()->user()->cinema_id))


            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon position-relative"
                    id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    <i data-feather="bell" class="icon-lg"></i>

                    @if ($notifications->where('status', 0)->count() > 0)
                        <span class="badge bg-danger rounded-pill">
                            {{ $notifications->where('status', 0)->count() }}
                        </span>
                    @else
                        <span class="badge bg-danger rounded-pill">
                            0
                        </span>
                    @endif

                </button>

                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-notifications-dropdown">
                    <div class="p-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-0">Thông báo chung</h6>
                            </div>
                        </div>
                    </div>

                    {{-- Bọc nội dung vào 1 div để SimpleBar hoạt động đúng --}}
                    <div id="listNotifications" data-simplebar style="max-height: 500px;">
                        <div class="simplebar-content">
                            @forelse($notifications as $noti)
                                <a href="{{ $noti->link ?? 'javascript:void(0)' }}" class="text-reset notification-item"
                                    data-id="{{ $noti->id }}">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 avatar-sm me-3">
                                            <span class="avatar-title bg-info rounded-circle font-size-16">
                                                <i class="bx bx-bell"></i>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-bold">{{ $noti->title }}</h6>
                                            <div class="font-size-13 text-muted">
                                                <p class="mb-1 {{ $noti->status == 0 ? 'fw-semibold' : '' }}">
                                                    {{ $noti->content }}
                                                </p>
                                                <p class="mb-0">
                                                    <i class="mdi mdi-clock-outline"></i>
                                                    <span>{{ $noti->created_at->diffForHumans() }}</span>

                                                    @if ($noti->status == 0)
                                                        <span class="badge bg-secondary text-light ms-2">Chưa xem</span>
                                                    @else
                                                        <span class="badge bg-success text-light ms-2">Đã xem</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <p class="text-center text-muted my-3 no-notification">Không có thông báo nào</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        @endif






        <div class="dropdown d-inline-block">
            <button type="button" class="btn header-item bg-light-subtle border-start border-end"
                id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{-- @if (Auth::check() && Storage::exists($user->avatar) && $user->avatar)
                    <img class="rounded-circle header-profile-user" src="{{ Storage::url($user->avatar) }}"
                        alt="Header Avatar">
                @else
                    <img class="rounded-circle header-profile-user"
                        src="https://graph.facebook.com/4/picture?type=small" alt="Header Avatar">
                @endif --}}

                @if (Auth::check() && !empty(Auth::user()->avatar) && Storage::exists(Auth::user()->avatar))
                    <img class="rounded-circle header-profile-user" src="{{ Storage::url(Auth::user()->avatar) }}"
                        alt="Header Avatar">
                @else
                    <img class="rounded-circle header-profile-user"
                        src="https://graph.facebook.com/4/picture?type=small" alt="Header Avatar">
                @endif



                <span class="d-none d-xl-inline-block ms-1 fw-medium">{{ Auth::check() ? $user->name : '' }}</span>
                <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
                <!-- item-->
                {{-- <a class="dropdown-item" href="apps-contacts-profile.html"><i
                        class="mdi mdi mdi-face-man font-size-16 align-middle me-1"></i> Profile</a>
                <a class="dropdown-item" href="auth-lock-screen.html"><i
                        class="mdi mdi-lock font-size-16 align-middle me-1"></i> Lock Screen</a>
                <div class="dropdown-divider"></div> --}}
                <a class="dropdown-item" id="changepassword-btn" href="{{ route('admin.change-password') }}">
                    <i class="mdi mdi-lock-reset font-size-16 align-middle me-1"></i>Change Password</a>

                <a class="dropdown-item" id="logout-btn">
                    <i class="mdi mdi-logout font-size-16 align-middle me-1"></i>
                    Logout
                </a>

                <!-- Form ẩn để gửi POST yêu cầu logout -->
                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        $("#logout-btn").click(function(e) {
            e.preventDefault();
            $("#logout-form").submit();
        })
    });
</script>
