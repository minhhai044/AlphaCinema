@extends('admin.layouts.master')
@section('title', 'Quản lý rạp')

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
            <h4 class="mb-sm-0 font-size-18">Rank Create</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.ranks.index') }}">Ranks</a>
                    </li>
                    <li class="breadcrumb-item active">Rank Create</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->
<form action="{{ route('admin.ranks.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="cinema-name" class="form-label">
                                    <span class="required">*</span>
                                    Tên cấp bậc
                                </label>
                                <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" id="cinema-name" placeholder="Tên cấp bậc" value="{{ old('name') }}">

                                @error('name')
                                <small class="text-danger fst-italic">*{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="cinema-name" class="form-label">
                                    <span class="required">*</span>
                                    Phần trăm vé
                                </label>
                                <input class="form-control @error('ticket_percentage') is-invalid @enderror" type="number" name="ticket_percentage" id="cinema-name" placeholder="Phần trăm vé" value="{{ old('ticket_percentage') }}">

                                @error('ticket_percentage')
                                <small class="text-danger fst-italic">*{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="cinema-name" class="form-label">
                                    <span class="required">*</span>
                                    Tổng chi tiêu
                                </label>
                                <input class="form-control @error('total_spent') is-invalid @enderror" type="number" name="total_spent" id="cinema-name" placeholder="Tổng chi tiêu" value="{{ old('total_spent') }}">

                                @error('total_spent')
                                <small class="text-danger fst-italic">*{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="cinema-name" class="form-label">
                                    <span class="required">*</span>
                                    Phần trăm combo
                                </label>
                                <input class="form-control @error('combo_percentage') is-invalid @enderror" type="number" name="combo_percentage" id="cinema-name" placeholder="Phần trăm combo" value="{{ old('combo_percentage') }}">

                                @error('combo_percentage')
                                <small class="text-danger fst-italic">*{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button class="btn btn-primary">
                        Submit
                        <i class="bx bx-chevron-right ms-1"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection
