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
                        <img src="{{ Auth::user()->avatar ? Storage::url(Auth::user()->avatar) : 'https://graph.facebook.com/4/picture?type=small' }}"
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
                    
                </ul>
                <div class="tab-content">
                    <div class="tab-pane show active" id="chat">
                        <div class="chat-message-list" data-simplebar>
                            <div class="pt-3">
                                <div class="px-3">
                                    <h5 class="font-size-14 mb-3">Danh sách phòng chat</h5>
                                </div>
                                <ul class="list-unstyled chat-list">

                                    @foreach ($RoomChats as $room)
                                        <li class="{{ $room->id === $RoomChat->id ? 'unread' : '' }}">
                                            <a href="{{ route('admin.roomchats.show', $room->id) }}">
                                                <div class="d-flex align-items-start">
                                                    <div class="flex-shrink-0 user-img online align-self-center me-3">
                                                        <img src="{{ $room->image ? Storage::url($room->image) : asset('logo/Icon Alpha cinema.svg') }}"
                                                            class="rounded-circle avatar-sm" alt="">
                                                        <span class="user-status"></span>
                                                    </div>

                                                    <div class="flex-grow-1 overflow-hidden">
                                                        <h5 class="text-truncate font-size-14 mb-1">{{ $room->name }}
                                                        </h5>
                                                        <p class="text-truncate mb-0">Chào mừng bạn tham gia phòng chat của
                                                            chúng tôi !!!
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
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="groups">
                        <div class="chat-message-list" data-simplebar>
                            <div class="pt-3">
                                <div class="px-3">
                                    <h5 class="font-size-14 mb-3">Danh sách hoạt động</h5>
                                </div>
                                <ul class="list-unstyled chat-list" id="listOnline">

                                </ul>
                            </div>
                        </div>
                    </div>


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

                        @foreach ($RoomChat->messenges->sortBy('created_at') as $mess)
                            @php
                                $isMine = $mess->user_id == Auth::id();
                                $avatar = $mess->user->avatar
                                    ? Storage::url($mess->user->avatar)
                                    : 'https://graph.facebook.com/4/picture?type=small';
                            @endphp

                            <li class="{{ $isMine ? 'right' : '' }}">
                                <div class="conversation-list d-flex {{ $isMine ? 'justify-content-end' : '' }}">
                                    @if (!$isMine)
                                        <div class="flex-shrink-0 me-2">
                                            <img src="{{ $avatar }}" alt="Avatar"
                                                class="rounded-circle avatar-xs" style="height: 2rem;
    width: 2rem;">
                                        </div>
                                    @endif

                                    <div class="ctext-wrap">
                                        <div class="ctext-wrap-content">
                                            <h5 class="conversation-name">
                                                <a href="#"
                                                    class="user-name">{{ $isMine ? 'Bạn' : $mess->user->name ?? 'Ẩn danh' }}</a>
                                                <span class="time">{{ $mess->created_at->format('H:i') }}</span>
                                            </h5>
                                            <p class="mb-0">{{ $mess->messenge }}</p>
                                        </div>
                                        <div class="dropdown align-self-start">
                                            <a class="dropdown-toggle" href="#" role="button"
                                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </a>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="#">Copy</a>
                                                <a class="dropdown-item" href="#">Xóa</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach

                    </ul>
                </div>

                <div class="p-3 border-top">
                    <div class="row">
                        <div class="col">
                            <div class="position-relative">
                                <input type="text" id="messenger" class="form-control border bg-light-subtle"
                                    placeholder="Nhập tin nhắn ...">
                            </div>
                        </div>
                        <div class="col-auto">
                            <button type="button" id="sendMessenger"
                                class="btn btn-primary chat-send w-md waves-effect waves-light"><span
                                    class="d-none d-sm-inline-block me-2">Gửi</span> <i
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
        let UserId = "{{ Auth::user()->id }}";
        let routeMessenger = "{{ route('admin.roomchats.messenger', $RoomChat->id) }}"
    </script>
    @vite('resources/js/roomChat.js')


@endsection

@section('script')

@endsection
