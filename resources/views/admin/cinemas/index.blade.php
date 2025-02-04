@extends('admin.layouts.master')
@section('title', 'Quản lý rạp')

@section('style')
<style>
    .card {
        box-shadow: 0px 1px 10px 3px #dedede;
    }

    .al-table-length label {
        font-weight: normal;
        text-align: left;
        white-space: nowrap;
    }

    .al-table-length .al-table-select {
        width: auto;
        display: inline-block;
    }

    .al-table-length .al-table-input {
        margin-left: .5em;
        display: inline-block;
        width: auto;
    }

    .al-table-info {
        padding-top: .85em;
    }

    .al-table-paginate {
        margin: 0;
        white-space: nowrap;
        text-align: right;
    }

    .al-table-paginate .pagination {
        margin: 2px 0;
        white-space: nowrap;
        justify-content: flex-end;
    }

</style>
@endsection

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Cinemas Managers</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <a href="javascript: void(0);">Cinemas</a>
                    </li>
                    <li class="breadcrumb-item active">Cinema List</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm">
                        <div class="mb-4">
                            <h6 class="mb-sm-0 font-size-16">Cinema List</h6>
                        </div>
                    </div>
                    <div class="col-sm-auto">
                        <div class="mb-4">
                            <a href="{{ route('admin.cinemas.create') }}" class="btn btn-light waves-effect waves-light">
                                <i class="bx bx-plus me-1"></i>
                                Add Cinema
                            </a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <div class="al-table-length">
                            <label>
                                Show
                                <select name="example_length" aria-controls="example" class="form-select form-select-sm al-table-select">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                                entries
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div class="al-table-length text-end">
                            <label>
                                Search:
                                <input type="search" class="form-control form-control-sm al-table-input" placeholder="Search đê">
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table align-middle table-nowrap dt-responsive nowrap w-100" id="customerList-table">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Branch</th>
                                        <th>Address</th>
                                        <th>Active</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cinemas as $cinema)
                                    <td class="sorting_1 dtr-control">
                                        <div class="d-none">{{ $cinema->id }}</div>
                                        <div class="form-check font-size-{{ $cinema->id }}">
                                            <input class="form-check-input" type="checkbox" id="customerlistcheck-{{ $cinema->id }}">
                                            <label class="form-check-label" for="customerlistcheck-{{ $cinema->id }}"></label>
                                        </div>
                                    </td>
                                    <td>
                                        {{ $cinema->name }}
                                    </td>
                                    <td>
                                        {{ $cinema->branch->name }}
                                    </td>
                                    <td>
                                        {{ $cinema->address }}
                                    </td>
                                    <td>
                                        <div class="badge font-size-12 {{ $cinema->is_active ? 'badge-soft-success' : 'badge-soft-danger' }}">
                                            {{ $cinema->is_active ? 'Active' : 'No Active' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="mdi mdi-dots-horizontal font-size-18"></i>
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-end" style="">
                                                <li>
                                                    <a href="{{ route('admin.cinemas.edit', $cinema) }}" class="dropdown-item edit-list" data-edit-id="{{ $cinema->id }}">
                                                        <i class="mdi mdi-pencil font-size-16 text-success me-1"></i>
                                                        Edit
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('admin.cinemas.show', $cinema) }}" class="dropdown-item edit-list" data-edit-id="{{ $cinema->id }}">
                                                        <i class="mdi mdi-pencil font-size-16 text-warning me-1"></i>
                                                        Show
                                                    </a>
                                                </li>
                                                <li>
                                                    <button class="dropdown-item remove-list" data-remove-id="{{ $cinema->id }}">
                                                        <i class="mdi mdi-trash-can font-size-16 text-danger me-1"></i>
                                                        Delete
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <div class="al-table-info" id="customerList-table_info" role="status" aria-live="polite">
                            Showing 1 to 10 of 12 entries
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <div class="al-table-paginate paging_simple_numbers pagination-rounded" id="customerList-table_paginate">
                            <ul class="pagination">
                                <li class="paginate_button page-item previous disabled" id="customerList-table_previous"><a aria-controls="customerList-table" aria-disabled="true" role="link" data-dt-idx="previous" tabindex="-1" class="page-link"><i class="mdi mdi-chevron-left"></i></a></li>
                                <li class="paginate_button page-item active"><a href="#" aria-controls="customerList-table" role="link" aria-current="page" data-dt-idx="0" tabindex="0" class="page-link">1</a></li>
                                <li class="paginate_button page-item "><a href="#" aria-controls="customerList-table" role="link" data-dt-idx="1" tabindex="0" class="page-link">2</a></li>
                                <li class="paginate_button page-item next" id="customerList-table_next"><a href="#" aria-controls="customerList-table" role="link" data-dt-idx="next" tabindex="0" class="page-link"><i class="mdi mdi-chevron-right"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
