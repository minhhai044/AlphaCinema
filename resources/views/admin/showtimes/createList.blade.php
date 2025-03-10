@extends('admin.layouts.master')

@section('style')
<style>
    .bootstrap-select .dropdown-toggle {
       background: white;
    }
    .bootstrap-select .dropdown-menu {
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .dropdown-item.active, .dropdown-item:active {
        background-color: #556ee6;
        color: #fff;
    }
</style>
    <link href="{{ asset('theme/admin/assets/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('theme/admin/assets/css/icons.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('theme/admin/assets/css/app.min.css') }}" rel="stylesheet" />
    <link href="{{asset('theme/admin/assets/libs/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet">
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-sm-flex align-items-center justify-content-between text-center row">
            <div class="text-center col-lg-9">
                <h5 class="mb-2 text-secondary fw-semibold">
                    <i class="bi bi-film"></i> Phim :
                    <span class="text-light-emphasis">{{ $movie->name }}</span>
                </h5>
                <p class="text-muted fs-6">
                    <i class="bi bi-calendar-event"></i> Ngày tạo:
                    <span class="text-warning fw-semibold">
                        {{ \Carbon\Carbon::parse($movie->created_at)->format('d/m/Y') }}
                    </span>
                    | <i class="bi bi-calendar-check"></i> Ngày phát hành:
                    <span class="text-success fw-semibold">
                        {{ \Carbon\Carbon::parse($movie->release_date)->format('d/m/Y') }}
                    </span>
                    | <i class="bi bi-calendar-x"></i> Ngày kết thúc:
                    <span class="text-danger fw-semibold">
                        {{ \Carbon\Carbon::parse($movie->end_date)->format('d/m/Y') }}
                    </span>
                </p>
            </div>

            <div class="page-title-right col-lg-3">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.showtimes.index') }}">Quản lý suất chiếu</a>
                    </li>
                    <li class="breadcrumb-item active">Tạo suất chiếu</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="twitter-bs-wizard">
            <ul class="nav nav-pills nav-justified mb-4">
                <li class="nav-item">
                    <a href="#branchs" class="nav-link active" data-bs-toggle="tab">
                        <i class="bi bi-building"></i><br>Chi nhánh
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#cinemas" class="nav-link" data-bs-toggle="tab">
                        <i class="bi bi-camera-reels"></i><br>Rạp phim
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#rooms" class="nav-link" data-bs-toggle="tab">
                        <i class="bi bi-door-open"></i><br>Phòng chiếu
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#dates" class="nav-link" data-bs-toggle="tab">
                        <i class="bi bi-calendar-date"></i><br>Ngày chiếu
                    </a>
                </li>
            </ul>

            <form action="" method="post">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="branchs">
                        <h5 class="text-center">Chọn chi nhánh</h5>
                        <select class="selectpicker form-control" multiple data-live-search="true" data-actions-box="true" name="branches[]">
                            
                            @foreach($branchs as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="tab-pane fade" id="cinemas">
                        <h5 class="text-center">Chọn rạp phim</h5>
                        
                    </div>
                    <div class="tab-pane fade" id="rooms">
                        <h5 class="text-center">Chọn phòng chiếu</h5>
                        
                    </div>
                    <div class="tab-pane fade" id="dates">
                        <h5 class="text-center">Chọn ngày</h5>
                        
                    </div>
                </div>
            </form>

            <div class="mt-4 d-flex justify-content-between">
                <button class="btn btn-secondary previous"><i class="bi bi-chevron-left"></i> Previous</button>
                <button class="btn btn-primary next">Next <i class="bi bi-chevron-right"></i></button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('theme/admin/assets/libs/twitter-bootstrap-wizard/jquery.bootstrap.wizard.min.js') }}"></script>
<script src="{{ asset('theme/admin/assets/libs/twitter-bootstrap-wizard/prettify.js') }}"></script>
<script src="{{ asset('theme/admin/assets/js/plugin.js') }}"></script>
<script src="{{asset('theme/admin/assets/libs/bootstrap-select/bootstrap-select.min.js')}}"></script>
<script>
    $('.selectpicker').selectpicker();
</script>
<script>
  
</script>
@endsection
