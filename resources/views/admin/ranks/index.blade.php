@extends('admin.layouts.master')
@section('title', 'Quản lý rạp')

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Rank Managers</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <a href="javascript: void(0);">Ranks</a>
                    </li>
                    <li class="breadcrumb-item active">Rank List</li>
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
                            <h6 class="mb-sm-0 font-size-16">Rank List</h6>
                        </div>
                    </div>
                    <div class="col-sm-auto">
                        <div class="mb-4">
                            <a href="{{ route('admin.ranks.create') }}" class="btn btn-light waves-effect waves-light">
                                <i class="bx bx-plus me-1"></i>
                                Add Rank
                            </a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <div class="al-table-length">
                            <label>
                                Show
                                <select name="example_length" aria-controls="example"
                                    class="form-select form-select-sm al-table-select">
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
                                <input type="search" class="form-control form-control-sm al-table-input"
                                    placeholder="Search đê">
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive min-vh-100">
                            <table class="table align-middle table-nowrap dt-responsive nowrap w-100"
                                id="customerList-table">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Cấp bậc</th>
                                        <th>Tổng chi tiêu</th>
                                        <th>% vé</th>
                                        <th>% combo</th>
                                        <th>Ngày tạo</th>
                                        <th>Ngày cập nhật</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ranks as $rank)
                                        <td class="sorting_1 dtr-control">
                                            <div class="d-none">{{ $rank->id }}
                                            </div>
                                            <div class="form-check font-size-{{ $rank->id }}">
                                                <input class="form-check-input" type="checkbox"
                                                    id="customerlistcheck-{{ $rank->id }}">
                                                <label class="form-check-label"
                                                    for="customerlistcheck-{{ $rank->id }}"></label>
                                            </div>
                                        </td>
                                        <td>
                                            {{ $rank->name }}

                                            @if($rank->is_default)
                                                <span class="text-black-50 small">(Mặc định)</span>
                                            @endif

                                        </td>
                                        <td>
                                            {{ formatPrice($rank->total_spent) }}đ
                                        </td>
                                        <td>
                                            {{ $rank->ticket_percentage }}%
                                        </td>
                                        <td>
                                            {{ $rank->combo_percentage }}%
                                        </td>
                                        <td>
                                            {{ $rank->created_at->format('d/m/Y') }}
                                            <br>
                                            {{ $rank->created_at->format('H:i:s') }}
                                        </td>
                                        <td>
                                            {{ $rank->updated_at->format('d/m/Y') }}
                                            <br>
                                            {{ $rank->updated_at->format('H:i:s') }}
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <i class="mdi mdi-dots-horizontal font-size-18"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end" style="">
                                                    <li>
                                                        <a class="dropdown-item edit-list cursor-pointer openUpdateRankModal"
                                                            data-rank-id="{{ $rank->id }}"
                                                            data-rank-name="{{ $rank->name }}"
                                                            data-rank-total-spent="{{ $rank->total_spent }}"
                                                            data-rank-ticket-percentage="{{ $rank->ticket_percentage }}"
                                                            data-rank-combo-percentage={{ $rank->combo_percentage }}
                                                            data-rank-default="{{ $rank->is_default }}">
                                                            <i class="mdi mdi-pencil font-size-16 text-success me-1"></i>
                                                            Edit
                                                        </a>
                                                    </li>

                                                    @if(!$rank->is_default)
                                                        <li>
                                                            <form id="delete-rank-{{ $rank->id }}"
                                                                action="{{ route('admin.ranks.destroy', $rank) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="button" class="dropdown-item remove-list"
                                                                    onclick="handleDelete({{ $rank->id }})">
                                                                    <i
                                                                        class="mdi mdi-trash-can font-size-16 text-danger me-1"></i>
                                                                    Delete
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif
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

                {{-- <div class="row">
                    {{ $cinemas->onEachSide(1)->links('admin.layouts.components.pagination') }}
                </div> --}}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="updateRankModal" tabindex="-1" aria-labelledby="updateRankModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateRankModalLabel">Cập nhật cấp bậc <span
                        class="badge bg-primary-subtle text-primary fs-11" id="spanDefault"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateRankForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <input type="hidden" id="updateRankId" name="rank_id">
                        <div class="col-md-12 mb-3">
                            <label for="updateName" class="form-label"><span class="text-danger">*</span> Tên
                                cấp bậc:</label>
                            <input type="text" class="form-control" id="updateName" name="name" required
                                placeholder="Nhập tên cấp bậc">
                            <span class="text-danger mt-3" id="updateNameError"></span>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="updateTotalSpent" class="form-label"><span class="text-danger">*</span> Tổng
                                chi tiêu<span class="text-muted small">(VNĐ)</span>:</label>
                            <input type="text" class="form-control" id="updateTotalSpent" name="total_spent" required
                                placeholder="Nhập tổng chi tiêu">
                            <span class="text-danger mt-3" id="updateTotalSpentError"></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="updateTicketPercentage" class="form-label"><span class="text-danger">*</span>
                                Phần trăm vé<span class="text-muted small">(%)</span>:</label>
                            <input type="text" class="form-control" id="updateTicketPercentage" name="ticket_percentage"
                                required placeholder="Nhập phần trăm vé">
                            <span class="text-danger mt-3" id="updateTicketPercentageError"></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="updateComboPercentage" class="form-label"><span class="text-danger">*</span>
                                Phần trăm combo<span class="text-muted small">(%)</span>:</label>
                            <input type="text" class="form-control" id="updateComboPercentage" name="combo_percentage"
                                required placeholder="Nhập phần trăm combo">
                            <span class="text-danger mt-3" id="updateComboPercentageError"></span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" id="updateRankBtn">Cập nhật</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/js/rank/index.js') }}"></script>
@endsection