@extends('admin.layouts.master')
@section('title', 'Quản lý Combo')

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
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Quản lý Combo</h4>
                <div class="page-title-right">
                    <a href="{{ route('admin.combos.create') }}" class="btn btn-primary me-2">+ Thêm mới</a>
                    <a href="{{ route('admin.export', 'combos') }}" class="btn btn-warning waves-effect waves-light">
                        <i class="bx bx-download me-1"></i>
                        Xuất Excel
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">

            <div class="table-responsive">
                <table class="table table-striped table-bordered text-center" id="datatable">
                    <thead>
                        <tr>
                            <th class="fw-semibold text-center">STT</th>
                            <th class="fw-semibold text-center">Tên Combo</th>
                            <th class="fw-semibold text-center">Hình ảnh</th>
                            <th class="fw-semibold text-center">Thông tin Combo</th>
                            <th class="fw-semibold text-center">Giá bán</th>
                            <th class="fw-semibold text-center">Mô tả</th>
                            <th class="fw-semibold text-center">Hoạt động</th>
                            <th class="fw-semibold text-center">Chức năng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="fw-semibold">{{ limitText($item->name, 20) }}</td>
                                <td>
                                    @if ($item->img_thumbnail && Storage::exists($item->img_thumbnail))
                                        <img src="{{ Storage::url($item->img_thumbnail) }}" width="70" height="70"
                                            alt="Không có ảnh !!!">
                                    @else
                                        <img src="{{ asset('images/foods/foods.png') }}" width="70" height="70"
                                            alt="Không có ảnh !!!">
                                    @endif
                                </td>
                                <td>
                                    @if ($item->comboFood)
                                        @foreach ($item->comboFood as $foods)
                                            <p class="fw-bold">{{ $foods->food->name }} <span class="fw-normal">x
                                                    {{ $foods->quantity }} </span></p>
                                        @endforeach
                                    @else
                                        <p class="text-danger">Không có thông tin</p>
                                    @endif
                                </td>
                                <td>
                                    @if ($item->price_sale)
                                        {{ number_format($item->price_sale) }} VNĐ
                                    @else
                                        {{ number_format($item->price) }} VNĐ
                                    @endif
                                </td>
                                <td>
                                    {{  limitText($item->description, 15) }}
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center align-items-center">
                                        <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
                                            <input class="form-check-input switch-is-active changeActive" type="checkbox"
                                                onclick="return confirm('Bạn có chắc muốn thay đổi?')"
                                                data-combo-id="{{ $item->id }}" @checked($item->is_active)>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('admin.combos.edit', $item) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
@php
    $appUrl = env('APP_URL');
@endphp
@section('script')
    <!-- Required datatable js -->
    <script src="{{ asset('theme/admin/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Buttons examples -->
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>

    <!-- Responsive examples -->
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}">
    </script>
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}">
    </script>

    <!-- Datatable init js -->
    <script src="{{ asset('theme/admin/assets/js/pages/datatables.init.js') }}"></script>

    <script>
        $(document).on("change", ".changeActive", function() {
            let comboId = $(this).data("combo-id"); // Lấy ID của combo
            let is_active = $(this).is(":checked") ? 1 : 0;

            // Gửi yêu cầu AJAX để thay đổi trạng thái
            $.ajax({
                url: "{{ route('combos.change-active') }}", // API cập nhật trạng thái combo
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: comboId,
                    is_active: is_active
                },
                success: function(response) {
                    if (response.success) {

                        toastr.success('Trạng thái hoạt động đã được cập nhật.');


                    } else {

                        toastr.error(response.message || 'Có lỗi xảy ra.');
                        $(`[data-combo-id="${comboId}"]`).prop("checked", !is_active);
                    }
                },
                error: function() {
                    $(`[data-combo-id="${comboId}"]`).prop("checked", !is_active);
                }
            });


        })
    </script>
@endsection
