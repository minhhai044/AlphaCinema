@extends('admin.layouts.master')
@section('content')
    <!-- start page title -->
    <div class="row">

        <h1 class="card-title">Sửa phim: {{ $day->name }}</h1>
        <div class="col-12">
            <form action="{{ route('admin.days.update', $day->id) }}" method="POST" enctype="multipart/form-data"
                class="custom-validation">
                @csrf
                @method('PUT')
                <div class="row">
                    <!-- Khối 9/12 -->
                    <div class="col-lg-9">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Loại ngày</label>
                                            <input type="text" name="name" value="{{ old('name', $day->name) }}"
                                                class="form-control">
                                            @error('name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Phụ phí</label>
                                            <input type="number" name="day_surcharge" value="{{ old('day_surcharge', $day->day_surcharge) }}"
                                                class="form-control">
                                            @error('day_surcharge')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">
                                Submit
                            </button>
                        </a>
                        <a href="{{ route('admin.days.index') }}" class="btn btn-secondary mb-3">Quay lại</a>
                    </div>
                </div>

            </form>
        </div>
    </div>
    <!-- end select2 -->

    </div>
@endsection
