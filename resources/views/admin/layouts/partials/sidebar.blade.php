@php
    const menus = [
        [
            'label' => 'Dashboard',
            'key' => 't-dashboard',
            'name' => 'admin.index',
            'icon' => '',
        ],
    ];
@endphp


<div data-simplebar class="h-100">
    <!--- Sidemenu -->
    <div id="sidebar-menu">
        <!-- Left Menu Start -->
        <ul class="metismenu list-unstyled" id="side-menu">
            <li class="menu-title" data-key="t-menu">Menu</li>

            <li>
                <a href="{{ route('admin.index') }}">
                    <i data-feather="home"></i>
                    <span data-key="t-dashboard">Trang chủ</span>
                </a>
            </li>
            <li>
                <a href="javascript: void(0);" class="has-arrow">
                    <i data-feather="grid"></i>
                    <span data-key="t-service-systems">Thống kê</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li>
                        <a href="{{ route('admin.statistical.cinemaRevenue') }}">

                            <span data-key="t-vouchers">Thống kê phim</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.statistical.combAndFoodRevenue') }}">

                            <span data-key="t-vouchers">Thống Combo và Đồ ăn</span>
                        </a>
                    </li>
                </ul>

            </li>

            <li>
                <a href="javascript: void(0);" class="has-arrow">
                    <i data-feather="grid"></i>
                    <span data-key="t-service-systems">Dịch vụ và ưu đãi</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li>
                        <a href="{{ route('admin.vouchers.index') }}">

                            <span data-key="t-vouchers">Quản lý mã giảm giá</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.user-vouchers.index') }}">
                            <span data-key="t-vouchers">Áp mã giảm giá</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.foods.index') }}">
                            <span data-key="t-accounts">Quản lý đồ ăn</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.combos.index') }}">
                            <span data-key="t-accounts">Quản lý combo</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.ranks.index') }}">
                            <span data-key="t-ranks">Quản lý hạn mức</span>
                        </a>
                    </li>


                        </a>
                    </li>

                </ul>

            </li>

            <li>
                <a href="javascript: void(0);" class="has-arrow">
                    <i data-feather="grid"></i>
                    <span data-key="t-service-systems">Hệ thống Rạp</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li>
                        <a href="{{ route('admin.branches.index') }}">
                            <span>Quản lý chi nhánh</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.cinemas.index') }}">
                            <span>Quản lý Rạp</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.rooms.index') }}">
                            <span>Quản lý phòng</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.index.seat_templates') }}">
                            <span>Quản lý mẫu sơ đồ ghế</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.type_seats.index') }}">
                            <span>Quản lý loại ghế</span>
                        </a>
                    </li>
                    <li>
                        <a href=" {{ route('admin.typerooms.index') }}">
                            <span>Quản lý loại phòng</span>
                        </a>
                    </li>

                </ul>
            </li>
            <li>
                <a href="javascript: void(0);" class="has-arrow">
                    <i data-feather="grid"></i>
                    <span data-key="t-service-systems">Phim & Suất chiếu</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li>
                        <a href="{{ route('admin.movies.index') }}">

                            <span data-key="t-movies">Quản lý Phim</span>
                        </a>
                    </li>
                    <li>
                        <a href=" {{ route('admin.showtimes.index') }}">
                            <span>Quản lý suất chiếu</span>
                        </a>
                    </li>
                    <li>
                        <a href=" {{ route('admin.tickets.index') }}">
                            <span>Quản lý hóa đơn</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.days.index') }}">
                            <span data-key="t-movies">Quản lý ngày</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript: void(0);" class="has-arrow">
                    <i data-feather="grid"></i>
                    <span data-key="t-service-systems">Quản lý tài khoản</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li>
                        <a href="{{ route('admin.users.index') }}">
                            <span data-key="t-cinemas">Quản lý người dùng</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.roles.index') }}">
                            <span data-key="t-cinemas">Phân quyền</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript: void(0);" class="has-arrow">
                    <i data-feather="grid"></i>
                    <span data-key="t-service-systems">Cấu hình</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li>
                        <a href="{{ route('admin.settings.index') }}">
                            <span data-key="t-cinemas">Cấu hình cài đặt</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.slideshows.index') }}">
                            <span data-key="t-cinemas">SlideShow</span>
                        </a>
                    </li>
                </ul>
            </li>

        </ul>
    </div>
    <!-- Sidebar -->
</div>
