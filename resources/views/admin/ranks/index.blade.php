@extends('admin.layouts.master')
@section('title', 'Quản lý rạp')
@section('style')
    <link href="{{ asset('theme/admin/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('theme/admin/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/admin/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <style>
        .table {
            vertical-align: middle !important;
        }
    </style>
@endsection
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-20 fw-semibold">Quản lý hạn mức</h4>

                <div class="page-title-right">


                </div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">





            <div class="row">
                <div class="col-sm-12">
                    <div class="table-responsive min-vh-100">
                        <table class="table table-bordered text-center" id="datatable">
                            <thead>
                                <tr>
                                    <th class="fw-semibold text-center">STT</th>
                                    <th class="fw-semibold text-center">Cấp bậc</th>
                                    <th class="fw-semibold text-center">Tổng chi tiêu</th>
                                    <th class="fw-semibold text-center">% vé</th>
                                    <th class="fw-semibold text-center">% combo</th>
                                    <th class="fw-semibold text-center">Chức năng</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($ranks as $rank)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            {{ $rank->name }}

                                            @if ($rank->is_default)
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
                                            <a class="dropdown-item edit-list cursor-pointer openUpdateRankModal"
                                                data-rank-id="{{ $rank->id }}" data-rank-name="{{ $rank->name }}"
                                                data-rank-total-spent="{{ $rank->total_spent }}"
                                                data-rank-ticket-percentage="{{ $rank->ticket_percentage }}"
                                                data-rank-combo-percentage={{ $rank->combo_percentage }}
                                                data-rank-default="{{ $rank->is_default }}">
                                                <button class="btn btn-warning btn-sm">
                                                    <i class="bx bx-edit"></i>
                                                </button>

                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="updateRankModal" tabindex="-1" aria-labelledby="updateRankModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title " id="updateRankModalLabel">Cập nhật cấp bậc <span
                            class="badge bg-primary text-white fs-11 ms-3" id="spanDefault"></span></h5>
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
                                <input type="text" class="form-control" id="updateTicketPercentage"
                                    name="ticket_percentage" required placeholder="Nhập phần trăm vé">
                                <span class="text-danger mt-3" id="updateTicketPercentageError"></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="updateComboPercentage" class="form-label"><span class="text-danger">*</span>
                                    Phần trăm combo<span class="text-muted small">(%)</span>:</label>
                                <input type="text" class="form-control" id="updateComboPercentage"
                                    name="combo_percentage" required placeholder="Nhập phần trăm combo">
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
    <script src="{{ asset('theme/admin/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Buttons examples -->
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>

    <script src="{{ asset('theme/admin/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}">
    </script>
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}">
    </script>

    <script src="{{ asset('theme/admin/assets/js/pages/datatables.init.js') }}"></script>
    <script src="{{ asset('assets/js/rank/index.js') }}"></script>
@endsection
