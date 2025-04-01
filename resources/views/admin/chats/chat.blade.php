@extends('admin.layouts.master')
@section('title', 'Phòng chat')
@section('style')

@endsection
@section('content')

    <div class="d-lg-flex">
        <div class="chat-leftsidebar card">
            <div class="p-3 px-4 border-bottom">
                <div class="d-flex align-items-start ">
                    <div class="flex-shrink-0 me-3 align-self-center">
                        <img src="{{ Auth::user()->avatar ? Storage::url(Auth::user()->avatar) : '	https://graph.facebook.com/4/picture?type=small' }}"
                            class="avatar-sm rounded-circle" alt="">
                    </div>

                    <div class="flex-grow-1">
                        <h5 class="font-size-16 mb-1"><a href="apps-chat.html#" class="text-dark">{{ Auth::user()->name }} <i
                                    class="mdi mdi-circle text-success align-middle font-size-10 ms-1"></i></a>
                        </h5>
                        <p class="text-muted mb-0">Available</p>
                    </div>

                    <div class="flex-shrink-0">
                        <div class="dropdown chat-noti-dropdown">
                            <button class="btn dropdown-toggle p-0" type="button" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="bx bx-dots-horizontal-rounded"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="apps-chat.html#">Profile</a>
                                <a class="dropdown-item" href="apps-chat.html#">Edit</a>
                                <a class="dropdown-item" href="apps-chat.html#">Add Contact</a>
                                <a class="dropdown-item" href="apps-chat.html#">Setting</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-3">
                <div class="search-box position-relative">
                    <input type="text" class="form-control rounded border" placeholder="Search...">
                    <i class="bx bx-search search-icon"></i>
                </div>
            </div>

            <div class="chat-leftsidebar-nav">
                <ul class="nav nav-pills nav-justified bg-light-subtle  p-1">
                    <li class="nav-item">
                        <a href="apps-chat.html#chat" data-bs-toggle="tab" aria-expanded="true" class="nav-link active">
                            <i class="bx bx-chat font-size-20 d-sm-none"></i>
                            <span class="d-none d-sm-block">Phòng</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="apps-chat.html#groups" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                            <i class="bx bx-group font-size-20 d-sm-none"></i>
                            <span class="d-none d-sm-block">Hoạt động</span>
                        </a>
                    </li>
                    {{-- <li class="nav-item">
                        <a href="apps-chat.html#contacts" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                            <i class="bx bx-book-content font-size-20 d-sm-none"></i>
                            <span class="d-none d-sm-block">Contacts</span>
                        </a>
                    </li> --}}
                </ul>
                <div class="tab-content">
                    <div class="tab-pane show active" id="chat">
                        <div class="chat-message-list" data-simplebar>
                            <div class="pt-3">
                                <div class="px-3">
                                    <h5 class="font-size-14 mb-3">Danh sách phòng chat</h5>
                                </div>
                                <ul class="list-unstyled chat-list">
                                    <li class="active">
                                        <a href="apps-chat.html#">
                                            <div class="d-flex align-items-start">

                                                <div class="flex-shrink-0 user-img online align-self-center me-3">
                                                    <img src="assets/images/users/avatar-2.jpg"
                                                        class="rounded-circle avatar-sm" alt="">
                                                    <span class="user-status"></span>
                                                </div>

                                                <div class="flex-grow-1 overflow-hidden">
                                                    <h5 class="text-truncate font-size-14 mb-1">Jennie Sherlock
                                                    </h5>
                                                    <p class="text-truncate mb-0">Hey! there I'm available</p>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <div class="font-size-11">02 min</div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="unread">
                                        <a href="apps-chat.html#">
                                            <div class="d-flex align-items-start">
                                                <div class="flex-shrink-0 user-img online align-self-center me-3">
                                                    <div class="avatar-sm align-self-center">
                                                        <span
                                                            class="avatar-title rounded-circle  bg-primary-subtle text-primary">
                                                            S
                                                        </span>
                                                    </div>
                                                    <span class="user-status"></span>
                                                </div>

                                                <div class="flex-grow-1 overflow-hidden">
                                                    <h5 class="text-truncate font-size-14 mb-1">Stacie Dube
                                                    </h5>
                                                    <p class="text-truncate mb-0">I've finished it! See you so
                                                    </p>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <div class="font-size-11">10 min</div>
                                                </div>
                                                <div class="unread-message">
                                                    <span class="badge bg-danger rounded-pill">1</span>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="apps-chat.html#">
                                            <div class="d-flex align-items-start">
                                                <div class="flex-shrink-0 user-img away align-self-center me-3">
                                                    <img src="assets/images/users/avatar-3.jpg"
                                                        class="rounded-circle avatar-sm" alt="">
                                                    <span class="user-status"></span>
                                                </div>

                                                <div class="flex-grow-1 overflow-hidden">
                                                    <h5 class="text-truncate font-size-14 mb-1">Katie Olson
                                                    </h5>
                                                    <p class="text-truncate mb-0">This theme is awesome!</p>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <div class="font-size-11">22 min</div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="apps-chat.html#">
                                            <div class="d-flex align-items-start">

                                                <div class="flex-shrink-0 user-img align-self-center me-3">
                                                    <img src="assets/images/users/avatar-4.jpg"
                                                        class="rounded-circle avatar-sm" alt="">
                                                    <span class="user-status"></span>
                                                </div>

                                                <div class="flex-grow-1 overflow-hidden">
                                                    <h5 class="text-truncate font-size-14 mb-1">Marshall Wilson
                                                    </h5>
                                                    <p class="text-truncate mb-0">Nice to meet you</p>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <div class="font-size-11">01 Hr</div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="apps-chat.html#">
                                            <div class="d-flex align-items-start">

                                                <div class="flex-shrink-0 user-img online align-self-center me-3">
                                                    <div class="avatar-sm align-self-center">
                                                        <span
                                                            class="avatar-title rounded-circle  bg-primary-subtle text-primary">
                                                            J
                                                        </span>
                                                    </div>
                                                    <span class="user-status"></span>
                                                </div>

                                                <div class="flex-grow-1 overflow-hidden">
                                                    <h5 class="text-truncate font-size-14 mb-1">James Lee</h5>
                                                    <p class="text-truncate mb-0">Wow that's great</p>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <div class="font-size-11">04 Hrs</div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="apps-chat.html#">
                                            <div class="d-flex align-items-start">

                                                <div class="flex-shrink-0 user-img align-self-center me-3">
                                                    <img src="assets/images/users/avatar-5.jpg"
                                                        class="rounded-circle avatar-sm" alt="">
                                                    <span class="user-status"></span>
                                                </div>

                                                <div class="flex-grow-1 overflow-hidden">
                                                    <h5 class="text-truncate font-size-14 mb-1">Ronald Tucker
                                                    </h5>
                                                    <p class="text-truncate mb-0">Nice to meet you</p>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <div class="font-size-11">22/04</div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="apps-chat.html#">
                                            <div class="d-flex align-items-start">

                                                <div class="flex-shrink-0 user-img align-self-center me-3">
                                                    <img src="assets/images/users/avatar-6.jpg"
                                                        class="rounded-circle avatar-sm" alt="">
                                                    <span class="user-status"></span>
                                                </div>

                                                <div class="flex-grow-1 overflow-hidden">
                                                    <h5 class="text-truncate font-size-14 mb-1">Dennis Stewart
                                                    </h5>
                                                    <p class="text-truncate mb-0">Nice to meet you</p>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <div class="font-size-11">22/04</div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="apps-chat.html#">
                                            <div class="d-flex align-items-start">
                                                <div class="flex-shrink-0 user-img away align-self-center me-3">
                                                    <img src="assets/images/users/avatar-3.jpg"
                                                        class="rounded-circle avatar-sm" alt="">
                                                    <span class="user-status"></span>
                                                </div>

                                                <div class="flex-grow-1 overflow-hidden">
                                                    <h5 class="text-truncate font-size-14 mb-1">Katie Olson
                                                    </h5>
                                                    <p class="text-truncate mb-0">This theme is awesome!</p>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <div class="font-size-11">22 min</div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="apps-chat.html#">
                                            <div class="d-flex align-items-start">

                                                <div class="flex-shrink-0 user-img align-self-center me-3">
                                                    <img src="assets/images/users/avatar-4.jpg"
                                                        class="rounded-circle avatar-sm" alt="">
                                                    <span class="user-status"></span>
                                                </div>

                                                <div class="flex-grow-1 overflow-hidden">
                                                    <h5 class="text-truncate font-size-14 mb-1">Marshall Wilson
                                                    </h5>
                                                    <p class="text-truncate mb-0">Nice to meet you</p>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <div class="font-size-11">01 Hr</div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="groups">
                        <div class="chat-message-list" data-simplebar>
                            <div class="pt-3">
                                <div class="px-3">
                                    <h5 class="font-size-14 mb-3">Groups</h5>
                                </div>
                                <ul class="list-unstyled chat-list">
                                    <li>
                                        <a href="apps-chat.html#">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 avatar-sm me-3">
                                                    <span
                                                        class="avatar-title rounded-circle  bg-primary-subtle text-primary">
                                                        G
                                                    </span>
                                                </div>

                                                <div class="flex-grow-1">
                                                    <h5 class="font-size-14 mb-0">General</h5>
                                                </div>
                                            </div>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="apps-chat.html#">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 avatar-sm me-3">
                                                    <span
                                                        class="avatar-title rounded-circle  bg-primary-subtle text-primary">
                                                        R
                                                    </span>
                                                </div>

                                                <div class="flex-grow-1">
                                                    <h5 class="font-size-14 mb-0">Reporting</h5>
                                                </div>
                                            </div>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="apps-chat.html#">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 avatar-sm me-3">
                                                    <span
                                                        class="avatar-title rounded-circle  bg-primary-subtle text-primary">
                                                        M
                                                    </span>
                                                </div>

                                                <div class="flex-grow-1">
                                                    <h5 class="font-size-14 mb-0">Meeting</h5>
                                                </div>
                                            </div>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="apps-chat.html#">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 avatar-sm me-3">
                                                    <span
                                                        class="avatar-title rounded-circle  bg-primary-subtle text-primary">
                                                        A
                                                    </span>
                                                </div>

                                                <div class="flex-grow-1">
                                                    <h5 class="font-size-14 mb-0">Project A</h5>
                                                </div>
                                            </div>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="apps-chat.html#">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 avatar-sm me-3">
                                                    <span
                                                        class="avatar-title rounded-circle  bg-primary-subtle text-primary">
                                                        B
                                                    </span>
                                                </div>

                                                <div class="flex-grow-1">
                                                    <h5 class="font-size-14 mb-0">Project B</h5>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="tab-pane" id="contacts">
                        <div class="chat-message-list" data-simplebar>
                            <div class="pt-3">
                                <div class="px-3">
                                    <h5 class="font-size-14 mb-3">Contacts</h5>
                                </div>

                                <div>
                                    <div>
                                        <div class="px-3 contact-list">A</div>

                                        <ul class="list-unstyled chat-list">
                                            <li>
                                                <a href="apps-chat.html#">
                                                    <h5 class="font-size-14 mb-0">Adam Miller</h5>
                                                </a>
                                            </li>

                                            <li>
                                                <a href="apps-chat.html#">
                                                    <h5 class="font-size-14 mb-0">Alfonso Fisher</h5>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="mt-4">
                                        <div class="px-3 contact-list">B</div>

                                        <ul class="list-unstyled chat-list">
                                            <li>
                                                <a href="apps-chat.html#">
                                                    <h5 class="font-size-14 mb-0">Bonnie Harney</h5>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="mt-4">
                                        <div class="px-3 contact-list">C</div>

                                        <ul class="list-unstyled chat-list">
                                            <li>
                                                <a href="apps-chat.html#">
                                                    <h5 class="font-size-14 mb-0">Charles Brown</h5>
                                                </a>
                                                <a href="apps-chat.html#">
                                                    <h5 class="font-size-14 mb-0">Carmella Jones</h5>
                                                </a>
                                                <a href="apps-chat.html#">
                                                    <h5 class="font-size-14 mb-0">Carrie Williams</h5>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="mt-4">
                                        <div class="px-3 contact-list">D</div>

                                        <ul class="list-unstyled chat-list">
                                            <li>
                                                <a href="apps-chat.html#">
                                                    <h5 class="font-size-14 mb-0">Dolores Minter</h5>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>

        </div>
        <!-- end chat-leftsidebar -->

        <div class="w-100 user-chat mt-4 mt-sm-0 ms-lg-1">
            <div class="card">
                <div class="p-3 px-lg-4 border-bottom">
                    <div class="row">
                        <div class="col-xl-4 col-7">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 avatar-sm me-3 d-sm-block d-none">
                                    <img src="{{ $RoomChat->image ? Storage::url($RoomChat->image) : asset('logo/Icon Alpha cinema.svg') }}"
                                        alt="" class="img-fluid d-block rounded-circle">
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="font-size-14 mb-1 text-truncate"><a href="apps-chat.html#"
                                            class="text-dark">{{ $RoomChat->name }}</a></h5>
                                    <p class="text-muted text-truncate mb-0">Online</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-8 col-5">
                            <ul class="list-inline user-chat-nav text-end mb-0">
                                <li class="list-inline-item">
                                    <div class="dropdown">
                                        <button class="btn nav-btn dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="bx bx-search"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-md p-2">
                                            <form class="px-2">
                                                <div>
                                                    <input type="text" class="form-control border bg-light-subtle"
                                                        placeholder="Search...">
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </li>

                                <li class="list-inline-item">
                                    <div class="dropdown">
                                        <button class="btn nav-btn dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="bx bx-dots-horizontal-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="apps-chat.html#">Profile</a>
                                            <a class="dropdown-item" href="apps-chat.html#">Archive</a>
                                            <a class="dropdown-item" href="apps-chat.html#">Muted</a>
                                            <a class="dropdown-item" href="apps-chat.html#">Delete</a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="chat-conversation p-3 px-2" data-simplebar>
                    <ul class="list-unstyled mb-0">
                        <li class="chat-day-title">
                            <span class="title">Today</span>
                        </li>
                        <li>
                            <div class="conversation-list">
                                <div class="ctext-wrap">
                                    <div class="ctext-wrap-content">
                                        <h5 class="conversation-name"><a href="apps-chat.html#" class="user-name">Jennie
                                                Sherlock</a> <span class="time">10:00</span></h5>
                                        <p class="mb-0">Good morning !</p>
                                    </div>
                                    <div class="dropdown align-self-start">
                                        <a class="dropdown-toggle" href="apps-chat.html#" role="button"
                                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </a>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="apps-chat.html#">Copy</a>
                                            <a class="dropdown-item" href="apps-chat.html#">Save</a>
                                            <a class="dropdown-item" href="apps-chat.html#">Forward</a>
                                            <a class="dropdown-item" href="apps-chat.html#">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </li>

                        <li class="right">
                            <div class="conversation-list">
                                <div class="ctext-wrap">
                                    <div class="ctext-wrap-content">
                                        <h5 class="conversation-name"><a href="apps-chat.html#"
                                                class="user-name">Shawn</a> <span class="time">10:02</span>
                                        </h5>
                                        <p class="mb-0">Good morning</p>
                                    </div>
                                    <div class="dropdown align-self-start">
                                        <a class="dropdown-toggle" href="apps-chat.html#" role="button"
                                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </a>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="apps-chat.html#">Copy</a>
                                            <a class="dropdown-item" href="apps-chat.html#">Save</a>
                                            <a class="dropdown-item" href="apps-chat.html#">Forward</a>
                                            <a class="dropdown-item" href="apps-chat.html#">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </li>

                        <li>
                            <div class="conversation-list">
                                <div class="ctext-wrap">
                                    <div class="ctext-wrap-content">
                                        <h5 class="conversation-name"><a href="apps-chat.html#" class="user-name">Jennie
                                                Sherlock</a> <span class="time">10:04</span></h5>
                                        <p class="mb-0">
                                            Hello!
                                        </p>
                                    </div>
                                    <div class="dropdown align-self-start">
                                        <a class="dropdown-toggle" href="apps-chat.html#" role="button"
                                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </a>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="apps-chat.html#">Copy</a>
                                            <a class="dropdown-item" href="apps-chat.html#">Save</a>
                                            <a class="dropdown-item" href="apps-chat.html#">Forward</a>
                                            <a class="dropdown-item" href="apps-chat.html#">Delete</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="ctext-wrap">
                                    <div class="ctext-wrap-content">
                                        <h5 class="conversation-name"><a href="apps-chat.html#" class="user-name">Jennie
                                                Sherlock</a> <span class="time">10:04</span></h5>
                                        <p class="mb-0">
                                            What about our next meeting?
                                        </p>
                                    </div>
                                    <div class="dropdown align-self-start">
                                        <a class="dropdown-toggle" href="apps-chat.html#" role="button"
                                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </a>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="apps-chat.html#">Copy</a>
                                            <a class="dropdown-item" href="apps-chat.html#">Save</a>
                                            <a class="dropdown-item" href="apps-chat.html#">Forward</a>
                                            <a class="dropdown-item" href="apps-chat.html#">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </li>

                        <li>
                            <div class="conversation-list">
                                <div class="ctext-wrap">
                                    <div class="ctext-wrap-content">
                                        <h5 class="conversation-name"><a href="apps-chat.html#" class="user-name">Jennie
                                                Sherlock</a> <span class="time">10:06</span></h5>
                                        <p class="mb-0">
                                            Next meeting tomorrow 10.00AM
                                        </p>
                                    </div>
                                    <div class="dropdown align-self-start">
                                        <a class="dropdown-toggle" href="apps-chat.html#" role="button"
                                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </a>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="apps-chat.html#">Copy</a>
                                            <a class="dropdown-item" href="apps-chat.html#">Save</a>
                                            <a class="dropdown-item" href="apps-chat.html#">Forward</a>
                                            <a class="dropdown-item" href="apps-chat.html#">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </li>

                        <li class="right">
                            <div class="conversation-list">
                                <div class="ctext-wrap">
                                    <div class="ctext-wrap-content">
                                        <h5 class="conversation-name"><a href="apps-chat.html#"
                                                class="user-name">Shawn</a> <span class="time">10:08</span>
                                        </h5>
                                        <p class="mb-0">
                                            Wow that's great
                                        </p>
                                    </div>
                                    <div class="dropdown align-self-start">
                                        <a class="dropdown-toggle" href="apps-chat.html#" role="button"
                                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </a>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="apps-chat.html#">Copy</a>
                                            <a class="dropdown-item" href="apps-chat.html#">Save</a>
                                            <a class="dropdown-item" href="apps-chat.html#">Forward</a>
                                            <a class="dropdown-item" href="apps-chat.html#">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </li>

                        <li>
                            <div class="conversation-list">
                                <div class="ctext-wrap">
                                    <div class="ctext-wrap-content">
                                        <h5 class="conversation-name"><a href="apps-chat.html#" class="user-name">Jennie
                                                Sherlock</a> <span class="time">10:09</span></h5>
                                        <p class="mb-0">
                                            img-1.jpg & img-2.jpg images for a New Projects
                                        </p>

                                        <ul class="list-inline message-img mt-3  mb-0">
                                            <li class="list-inline-item message-img-list">
                                                <a class="d-inline-block m-1" href="apps-chat.html">
                                                    <img src="assets/images/small/img-1.jpg" alt=""
                                                        class="rounded img-thumbnail">
                                                </a>
                                            </li>

                                            <li class="list-inline-item message-img-list">
                                                <a class="d-inline-block m-1" href="apps-chat.html">
                                                    <img src="assets/images/small/img-2.jpg" alt=""
                                                        class="rounded img-thumbnail">
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="dropdown align-self-start">
                                        <a class="dropdown-toggle" href="apps-chat.html#" role="button"
                                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </a>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="apps-chat.html#">Copy</a>
                                            <a class="dropdown-item" href="apps-chat.html#">Save</a>
                                            <a class="dropdown-item" href="apps-chat.html#">Forward</a>
                                            <a class="dropdown-item" href="apps-chat.html#">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="p-3 border-top">
                    <div class="row">
                        <div class="col">
                            <div class="position-relative">
                                <input type="text" class="form-control border bg-light-subtle"
                                    placeholder="Enter Message...">
                            </div>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary chat-send w-md waves-effect waves-light"><span
                                    class="d-none d-sm-inline-block me-2">Send</span> <i
                                    class="mdi mdi-send float-end"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end user chat -->



        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <script>
                            document.write(new Date().getFullYear())
                        </script> © Minia.
                    </div>
                    <div class="col-sm-6">
                        <div class="text-sm-end d-none d-sm-block">
                            Design & Develop by <a href="apps-chat.html#!"
                                class="text-decoration-underline">Themesbrand</a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <!-- end main content-->
    
        <script>
            let roomId = "{{ $RoomChat->id }}";
        </script>
    @vite('resources/js/roomChat.js')
@endsection

@section('script')


@endsection
