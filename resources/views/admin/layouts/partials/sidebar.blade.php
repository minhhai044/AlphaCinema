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


            <li>
                <a href="{{ route('admin.index') }}">
                    <i data-feather="home"></i>
                    <span data-key="t-dashboard">Trang chủ</span>
                </a>
            </li>
            @canany(['Danh sách thống kê'])
                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="pie-chart"></i>
                        <span data-key="t-service-systems">Thống kê</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li>
                            <a href="{{ route('admin.statistical.cinemaRevenue') }}">
                                <span data-key="t-vouchers">Thống kê phim</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.statistical.ticketRevenue') }}">
                                <span data-key="t-vouchers">Thống kê vé</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.statistical.comboRevenue') }}">
                                <span data-key="t-vouchers">Thống kê combo</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.statistical.foodRevenue') }}">
                                <span data-key="t-vouchers">Thống kê đồ ăn</span>
                            </a>
                        </li>
                    </ul>

                </li>
            @endcanany

            @canany([
                'Danh sách đồ ăn',
                'Thêm đồ ăn',
                'Sửa đồ ăn',
                'Xóa đồ ăn',
                'Danh sách combo',
                'Thêm combo',
                'Sửa
                combo',
                'Xóa combo',
                'Danh sách vouchers',
                'Thêm vouchers',
                'Sửa vouchers',
                'Xóa vouchers',
                'Áp dụng
                vouchers',
                'Sửa áp dụng vouchers',
                'Xóa áp dụng vouchers',
                ])
                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="gift"></i>

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
                    </ul>
                </li>
            @endcanany

            @canany([
                'Danh sách chi nhánh',
                'Thêm chi nhánh',
                'Sửa chi nhánh',
                'Xóa chi nhánh',
                'Danh sách rạp',
                'Thêm rạp',
                'Sửa rạp',
                'Xóa rạp',
                'Danh sách phòng chiếu',
                'Thêm phòng chiếu',
                'Sửa phòng chiếu',
                'Xóa phòng chiếu',
                'Xem
                chi tiết phòng chiếu',
                ])
                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="map"></i>


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
            @endcanany


            @canany([
                'Danh sách phim',
                'Thêm phim',
                'Sửa phim',
                'Xóa phim',
                'Xem chi tiết phim',
                'Danh sách suất chiếu',
                'Thêm suất chiếu',
                'Sửa suất chiếu',
                'Xóa suất chiếu',
                'Xem chi tiết suất chiếu',
                'Danh sách hóa đơn',
                'Quét hóa đơn',
                'Xem chi tiết hóa đơn',
                // 'Danh sách ngày',
                // 'Sửa ngày',
                ])
                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="film"></i>
                        <span data-key="t-service-systems">Phim & Suất chiếu</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        @canany(['Danh sách phim', 'Thêm phim', 'Sửa phim', 'Xóa phim', 'Xem chi tiết phim'])
                            <li>
                                <a href="{{ route('admin.movies.index') }}">

                                    <span data-key="t-movies">Quản lý Phim</span>
                                </a>
                            </li>
                        @endcanany

                        @canany([
                            'Danh sách suất chiếu',
                            'Thêm suất chiếu',
                            'Sửa suất chiếu',
                            'Xóa suất chiếu',
                            'Xem chi tiết
                            suất chiếu',
                            ])
                            <li>
                                <a href=" {{ route('admin.showtimes.index') }}">
                                    <span>Quản lý suất chiếu</span>
                                </a>
                            </li>
                        @endcanany
                        @canany(['Danh sách hóa đơn', 'Quét hóa đơn', 'Xem chi tiết hóa đơn'])
                            <li>
                                <a href=" {{ route('admin.tickets.index') }}">
                                    <span>Quản lý hóa đơn</span>
                                </a>
                            </li>
                        @endcanany

                        <li>
                            <a href="{{ route('admin.days.index') }}">
                                <span data-key="t-movies">Quản lý ngày</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endcanany

            @canany(['Danh sách tài khoản', 'Thêm tài khoản', 'Sửa tài khoản', 'Xóa tài khoản'])
                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="users"></i>

                        <span data-key="t-service-systems">Quản lý tài khoản</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li>
                            <a href="{{ route('admin.users.index') }}">
                                <span data-key="t-cinemas">Quản lý người dùng</span>
                            </a>
                        </li>
                        @if (auth()->user()->hasRole('System Admin'))
                            <li>
                                <a href="{{ route('admin.roles.index') }}">
                                    <span data-key="t-cinemas">Phân quyền</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endcanany

            @canany(['Danh sách bài viết', 'Thêm bài viết', 'Sửa bài viết', 'Xóa bài viết', 'Xem chi tiết bài viết',
                'Danh sách slideshows', 'Thêm slideshows', 'Sửa slideshows', 'Xóa slideshows'])
                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="settings"></i>
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
            @endcanany
        </ul>
    </div>
    <!-- Sidebar -->
</div>
