@extends('admin.layouts.master')
@section('title', 'Quản lý loại phòngphòng')

@section('style')
    <style>
        .required {
            color: red;
            font-style: italic !important;
        }
    </style>
@endsection
@section('content')
<div class="container">
    <!-- Modal Thêm Mới -->
    <div class="modal fade show" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true" style="display: block;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Thêm mới loại phòng</h4>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.typerooms.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-9">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div>
                                                    <div class="mb-3">
                                                        <label for="name" class="form-label">
                                                            <span class="required">*</span>
                                                            Tên loại phòng
                                                        </label>
                                                        <input type="text" name="name" id="typeroom-name"
                                                            class="form-control {{ $errors->has('name') ? 'is-invalid' : (old('name') ? 'is-valid' : '') }}"
                                                            value="{{ old('name') }}" placeholder="Nhập tên loại phòng ">
                
                                                        <div class="{{ $errors->has('name') ? 'invalid-feedback' : 'valid-feedback' }}">
                                                            @if ($errors->has('name'))
                                                                {{ $errors->first('name') }}
                                                            @endif
                                                        </div>
                
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div>
                                                        <div class="mb-3">
                                                            <label for="name" class="form-label">
                                                                <span class="required">*</span>
                                                                Nhập Giá 
                                                            </label>
                                                            <input type="number" name="surcharge" id="surcharge" min="0" value="0"
                                                                class="form-control {{ $errors->has('surcharge') ? 'is-invalid' : (old('surcharge') ? 'is-valid' : '') }}"
                                                                value="{{ old('surcharge') }}" placeholder="Nhập Giá Phòng  ">
                    
                                                            <div class="{{ $errors->has('surcharge') ? 'invalid-feedback' : 'valid-feedback' }}">
                                                                @if ($errors->has('surcharge'))
                                                                    {{ $errors->first('surcharge') }}
                                                                @endif
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                
                                        <div class="card-footer">
                                            <button class="btn btn-primary">
                                                Submit
                                                <i class="bx bx-chevron-right ms-1"></i>
                                            </button>
                                            <a class="btn btn-danger"  href="{{ route('admin.typerooms.index') }} ">
                                                Cancel
                                                <i class="bx bx-chevron-right ms-1"></i>
                                            </a>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script để tự động mở modal khi vào trang -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    var myModal = new bootstrap.Modal(document.getElementById('addModal'));
    myModal.show();
});
</script>
@endsection
