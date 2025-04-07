@extends('admin.layouts.master')
@section('title', 'Chi tiết tài khoản')

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
                <h4 class="mb-sm-0 font-size-18">Chi tiết tài khoản</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.users.index') }}">Tài khoản</a>
                        </li>
                        <li class="breadcrumb-item active">Chi tiết tài khoản</li>
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

                                        Tên
                                    </label>
                                    <input class="form-control" disabled type="text" name="name" id="account-name"
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

                                        Số điện thoại
                                    </label>
                                    <input class="form-control" disabled type="tel" name="phone" id="account-phone"
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
                                    <input class="form-control" disabled type="date" name="birthday"
                                        value="{{ $user->birthday }}" id="account-birthday" />

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

                                        Email
                                    </label>
                                    <input class="form-control" disabled type="email" name="email" id="account-email"
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
                                    <input class="form-control" disabled type="text" name="address"
                                        value="{{ old('address', $user->address) }}" placeholder="Nhập địa chỉ">

                                    @error('address')
                                        <span class="text-danger">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-12">
                                @if ($user->type_user == 0)
                                    <!-- Chỉ hiển thị khi type_user là 0 (người dùng là Member) -->
                                    <h5 class="mt-4">Lịch sử điểm của người dùng</h5>
                                    @if ($pointHistories->isEmpty())
                                        <p>Không có lịch sử điểm.</p>
                                    @else
                                        <table class="table table-striped table-bordered text-center">
                                            <thead>
                                                <tr>
                                                    <th>Loại điểm</th>
                                                    <th>Số điểm</th>
                                                    <th>Mô tả</th>
                                                    <th>Ngày hết hạn</th>
                                                    <th>Trạng thái xử lý</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($pointHistories as $history)
                                                    <tr>
                                                        <td>{{ $history->type }}</td>
                                                        <td>{{ number_format($history->point) }}</td>
                                                        <td>{{ $history->description }}</td>
                                                        <td>{{ $history->expiry_date ? \Carbon\Carbon::parse($history->expiry_date)->format('d/m/Y') : 'Không có' }}</td>

                                                        </td>
                                                        <td>
                                                            <span class="badge rounded-pill {{ $history->processed ? 'bg-success' : 'bg-warning' }}">
                                                                {{ $history->processed ? 'Đã xử lý' : 'Chưa xử lý' }}
                                                            </span>
                                                        </td>
                                                        
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endif

                                    <!-- Hiển thị thông tin Rank của người dùng -->
                                    <h5 class="mt-4">Thông tin Rank của người dùng</h5>
                                    @if ($userRank)
                                        <div class="mb-3  gap-2">
                                            <label for="account-description" class="form-label">
                                                Rank:
                                            </label>
                                            <span
                                                class="badge rounded-pill {{ $userRank ? 'bg-primary' : 'bg-secondary' }}">
                                                {{ $userRank ? $userRank->name : 'Không có rank' }}
                                            </span>
                                        </div>
                                        <p><strong>Tổng tiền đã tiêu: </strong>{{ number_format($user->total_amount) }}</p>
                                        <p><strong>Tổng tiền yêu cầu đạt được rank:
                                            </strong>{{ number_format($userRank->total_spent) }}</p>
                                    @else
                                        <p>Không có rank phù hợp với số tiền đã tiêu của người dùng.</p>
                                    @endif
                                @else
                                    <!-- Nếu không phải người dùng (Admin), không hiển thị lịch sử điểm và rank -->
                                    <p>Lịch sử điểm và Rank chỉ hiển thị cho người dùng.</p>
                                @endif
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
                                <div class="mb-3  gap-2">
                                    <label for="account-description" class="form-label">
                                        Loại:
                                    </label>
                                    <span
                                        class="badge rounded-pill {{ $user->type_user === 1 ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $user->type_user === 1 ? 'Admin' : 'Người dùng' }}
                                    </span>
                                </div>

                                <div class="mt-5">
                                    <div class="mb-3">
                                        <label for="account-image" class="form-label">
                                            Avatar
                                        </label>
                                        @if ($user->avatar && Storage::url($user->avatar))
                                            <img src="{{ Storage::url($user->avatar) }}" class="img-thumbnail"
                                                alt="Avatar của người dùng" width="100px">
                                        @else
                                            <img src="https://graph.facebook.com/4/picture?type=large"
                                                class="img-thumbnail" alt="Avatar của người dùng" width="100px">
                                        @endif

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
