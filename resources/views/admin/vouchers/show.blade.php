@extends('admin.layouts.master')
@section('title', 'Show Voucher')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Voucher Management</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.vouchers.index') }}">Vouchers</a>
                    </li>
                    <li class="breadcrumb-item active">{{ $voucher->code }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-sm border-light rounded">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Voucher Details: <span class="text-primary">{{ $voucher->code }}</span></h5>
                <a href="{{ route('admin.vouchers.index') }}" class="btn btn-outline-primary btn-sm"><i class="bx bx-arrow-back"></i> Back to List</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Voucher Code:</strong>
                            <p class="text-muted">{{ $voucher->code }}</p>
                        </div>
                        <div class="mb-3">
                            <strong>Title:</strong>
                            <p class="text-muted">{{ $voucher->title }}</p>
                        </div>
                        <div class="mb-3">
                            <strong>Discount:</strong>
                            <p class="text-muted">{{ $voucher->discount }}%</p>
                        </div>
                        <div class="mb-3">
                            <strong>Quantity:</strong>
                            <p class="text-muted">{{ $voucher->quantity }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Start Date:</strong>
                            <p class="text-muted">{{ $voucher->start_date_time }}</p>
                        </div>
                        <div class="mb-3">
                            <strong>End Date:</strong>
                            <p class="text-muted">{{ $voucher->end_date_time }}</p>
                        </div>
                        <div class="mb-3">
                            <strong>Limit Per User:</strong>
                            <p class="text-muted">{{ $voucher->limit_by_user }}</p>
                        </div>
                        <div class="mb-3">
                            <strong>Mô tả:</strong>
                            <p class="text-muted">{{ $voucher->description }}</p>
                        </div>
                        <div class="mb-3">
                            <strong>Status:</strong>
                            <span class="badge font-size-12 {{ $voucher->is_active ? 'badge-soft-success' : 'badge-soft-danger' }}">
                                {{ $voucher->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.vouchers.edit', $voucher) }}" class="btn btn-warning btn-sm me-2">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="{{ route('admin.vouchers.destroy', $voucher) }}" method="POST" class="d-inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this voucher?')">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
