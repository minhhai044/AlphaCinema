@php
const menus = [
[
'label' => 'Dashboard',
'key' => 't-dashboard',
'name' => 'admin.index',
'icon' => ''
]
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
                    <span data-key="t-dashboard">Dashboard</span>
                </a>
            </li>

            <li>
                <a href="javascript: void(0);" class="has-arrow">
                    <i data-feather="grid"></i>
                    <span data-key="t-cinema-systems">Cinema Systems</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li>
                        <a href="{{ route('admin.cinemas.index') }}">
                            <span data-key="t-cinemas">Cinemas</span>
                        </a>
                    </li>

                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            <span data-key="t-email">Email</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="apps-email-inbox.html" data-key="t-inbox">Inbox</a></li>
                            <li><a href="apps-email-read.html" data-key="t-read-email">Read Email</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            <span data-key="t-invoices">Invoices</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="apps-invoices-list.html" data-key="t-invoice-list">Invoice List</a>
                            </li>
                            <li><a href="apps-invoices-detail.html" data-key="t-invoice-detail">Invoice
                                    Detail</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript: void(0);" class="has-arrow">
                    <i data-feather="grid"></i>
                    <span data-key="t-cinema-systems">Quản lí loại phòng</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li>
                        <a href="{{ route('admin.typerooms.index') }}">
                            <span data-key="t-cinemas">Danh sách loại phòng</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{ route('admin.type_seats.index') }}" class="has-arrow">
                    <i data-feather="grid"></i>
                    <span data-key="t-cinema-systems">Quản lí loại ghế </span>
                </a>
                {{-- <ul class="sub-menu" aria-expanded="false">
                    <li>
                        <a href="{{ route('admin.typerooms.index') }}">
                            <span data-key="t-cinemas">Danh sách loại phòng</span>
                        </a>
                    </li>
                </ul> --}}
            </li>
            <li>
                <a href="#">
                    <i data-feather="layout"></i>
                    <span data-key="t-accounts">Accounts</span>
                </a>
            </li>

            <li>
                <a href="#">
                    <i data-feather="layout"></i>
                    <span data-key="t-movies">Movies</span>
                </a>
            </li>

            <li>
                <a href="#">
                    <i data-feather="layout"></i>
                    <span data-key="t-rooms">Rooms</span>
                </a>
            </li>

            <li class="menu-title mt-2" data-key="t-components">Elements</li>

            <li>
                <a href="javascript: void(0);">
                    <i data-feather="box"></i>
                    <span class="badge rounded-pill badge-soft-danger  text-danger float-end">7</span>
                    <span data-key="t-forms">Forms</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li><a href="form-elements.html" data-key="t-form-elements">Basic Elements</a></li>
                    <li><a href="form-validation.html" data-key="t-form-validation">Validation</a></li>
                    <li><a href="form-advanced.html" data-key="t-form-advanced">Advanced Plugins</a></li>
                    <li><a href="form-editors.html" data-key="t-form-editors">Editors</a></li>
                    <li><a href="form-uploads.html" data-key="t-form-upload">File Upload</a></li>
                    <li><a href="form-wizard.html" data-key="t-form-wizard">Wizard</a></li>
                    <li><a href="form-mask.html" data-key="t-form-mask">Mask</a></li>
                </ul>
            </li>
        </ul>
    </div>
    <!-- Sidebar -->
</div>
