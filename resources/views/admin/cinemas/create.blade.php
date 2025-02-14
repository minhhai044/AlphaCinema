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
            <h4 class="mb-sm-0 font-size-18">Cinema Create</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.cinemas.index') }}">Cinemas</a>
                    </li>
                    <li class="breadcrumb-item active">Cinema Create</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->
<form action="{{ route('admin.cinemas.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-lg-9">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div>
                                <div class="mb-3">
                                    <label for="cinema-name" class="form-label">
                                        <span class="required">*</span>
                                        Name
                                    </label>
                                    <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" id="cinema-name" placeholder="Enter cinema name" value="{{ old('name') }}">

                                    @error('name')
                                    <small class="text-danger fst-italic">*{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div>
                                <div class="mb-3">
                                    <label for="cinema-branch" class="form-label">
                                        <span class="required">*</span>
                                        Branch
                                    </label>
                                    <select class="form-control @error('branch_id') is-invalid @enderror" placeholder="Select branch" name="branch_id">
                                        <option disabled selected>Select branch</option>
                                        @foreach ($branchs as $branch)
                                        <option value="{{ $branch->id }}">
                                            {{ $branch->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('branch_id')
                                    <small class="text-danger fst-italic">*{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="cinema-address" class="form-label">
                                    <span class="required">*</span>
                                    Address
                                </label>
                                <input class="form-control @error('address') is-invalid @enderror" type="text" name="address" id="cinema-address" placeholder="Enter cinema address" value="{{ old('address') }}">
                                @error('address')
                                <small class="text-danger fst-italic">*{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="cinema-description" class="form-label">
                                    Description
                                </label>
                                <textarea class="form-control" name="description" rows="6" placeholder="Enter cinema description"></textarea>
                                @error('description')
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

        <div class="col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3 d-flex gap-2">
                                <label for="cinema-description" class="form-label">
                                    Active:
                                </label>
                                <div class="square-switch">
                                    <input type="checkbox" id="square-switch3" switch="bool" value="1" name="is_active">
                                    <label for="square-switch3" data-on-label="Yes" data-off-label="No"></label>
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
