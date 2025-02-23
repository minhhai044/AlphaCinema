@extends('admin.layouts.master')
@section('title', 'Xem chi tiết Combo')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Quản lí Combo</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.combos.index') }}">Combo</a>
                        </li>
                        <li class="breadcrumb-item active">{{ $data->name }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-lg-12">
            <h1>Chi tiết Combo: <span class="fst-italic text-danger">{{ $data->name }}</span></h1>
        </div>

        <div class="row">
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-body">
                        <div class="row">

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Tên Combo: </label>
                                    <input type="text" name="name" id="name" class="form-control "
                                        value="{{ $data->name }}" placeholder="Nhập tên món ăn">
                                </div>

                                <div class="mb-3">
                                    <label for="img_thumbnail" class="form-label">Hình ảnh: </label>

                                    @if ($data->img_thumbnail)
                                        <div class="mb-3">
                                            <img src="{{ Storage::url($data->img_thumbnail) }}" alt="Thumbnail"
                                                class="img-fluid" width="400">
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <div class="mb-3">
                                        <label class="form-label" for="price_sale">Giá Sale:</label>
                                        <input type="text" class="form-control" name="price_sale" id="price_sale"
                                            placeholder="Nhập giá tiền"
                                            value="{{ number_format($data->price_sale) . ' VNĐ' }}">
                                    </div>
                                </div>
                                <div>
                                    <div class="mb-3">
                                        <label for="price" class="form-label">
                                            <span class="required text-danger">*</span> Giá tiền:
                                        </label>
                                        <input type="text" class="form-control" name="price" id="price"
                                            placeholder="Nhập giá tiền"
                                            value="{{ number_format($data->price) . ' VNĐ' }}">
                                    </div>
                                </div>


                                <div class="mb-3">
                                    <label for="description" class="form-label">
                                        <span class="required text-danger">*</span> Mô tả:
                                    </label>
                                    <textarea class="form-control " name="description" rows="6" placeholder="Nhập mô tả">{{ $data->description }}</textarea>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer col-lg-6">
                        <form action="{{ route('admin.foods.forceDestroy', $data->id) }}" method="POST" class="mb-3">
                            @method('DELETE')
                            @csrf
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Có chắc chắn xóa không ?')">
                                Xóa
                            </button>
                        </form>
                        <a href="{{ route('admin.combos.edit', $data->id) }}" class="btn btn-warning mb-3">
                            Cập nhật
                            <i class="bx bx-chevron-right ms-1"></i>
                        </a>

                        <a href="{{ route('admin.combos.index') }}" class="btn btn-danger mb-3">
                            Quay lại
                            <i class="bx bx-chevron-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3 d-flex gap-2">
                                    <label for="food-description" class="form-label">
                                        <span class="required">*</span>
                                        Hoạt động:
                                    </label>
                                    <div class="square-switch">
                                        <div
                                            class="badge font-size-12 {{ $data->is_active ? 'badge-soft-success' : 'badge-soft-danger' }}">
                                            {{ $data->is_active ? 'Hoạt động' : 'Dừng hoạt động' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="created_at" class="form-label">Thời gian thêm mới: </label>
                                    <input type="text" name="created_at" id="created_at" class="form-control "
                                        value="{{ $data->created_at }}" placeholder="Nhập tên món ăn">
                                </div>
                                <div class="mb-3">
                                    <label for="updated_at" class="form-label">Thời gian cập nhật: </label>
                                    <input type="text" name="updated_at" id="updated_at" class="form-control "
                                        value="{{ $data->updated_at }}"
                                        placeholder="Nhập tên món ăn">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
