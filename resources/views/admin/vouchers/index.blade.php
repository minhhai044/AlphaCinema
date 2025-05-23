@extends('admin.layouts.master')
@section('title', 'Danh sách mã giảm giá')

@section('style')
    <link href="{{ asset('theme/admin/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('theme/admin/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/admin/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('theme/admin/assets/css/preloader.min.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('theme/admin/assets/css/preloader.min.css') }}" type="text/css" />

    <link href="{{ asset('theme/admin/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('theme/admin/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />

    <style>
        #datatable_length select {
            width: 60px;
        }

        #datatable thead th {
            text-align: center;
            vertical-align: middle;
        }
    </style>
@endsection
@section('style-libs')
    <!-- Datatable CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm">
                            <div class="mb-4">
                                <h6 class="mb-sm-0 font-size-16">Danh sách mã giảm giá</h6>
                            </div>
                        </div>
                        <div class="col-sm-auto">
                            <div class="mb-4">
                                <a href="{{ route('admin.vouchers.create') }}" class="btn btn-primary me-2">+ Thêm mới</a>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="datatable" class="table table-bordered w-100 text-center">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Mã giảm giá</th>
                                    <th>Giảm giá (VNĐ)</th>
                                    <th>Ngày bắt đầu</th>
                                    <th>Ngày kết thúc</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($vouchers as $voucher)
                                    <tr>
                                        <td>{{ $voucher->id }}</td>
                                        <td>{{ $voucher->code }}</td>
                                        <td>{{ number_format($voucher->discount, 0, ',', '.') }} VNĐ</td>
                                        <td>{{ \Carbon\Carbon::parse($voucher->start_date_time)->format('d/m/Y H:i:s') }}
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($voucher->end_date_time)->format('d/m/Y H:i:s') }}
                                        </td>


                                      

                                        <td>
                                            <div class="d-flex justify-content-center align-items-center">
                                                <div class=" form-check form-switch form-switch-md" dir="ltr">
                                                    <input class="form-check-input switch-is-active changeActive"
                                                        @checked($voucher->is_active) type="checkbox"
                                                        data-voucher-id="{{ $voucher->id }}">
                                                </div>
                                            </div>
                                        </td>

                                        <td class="text-center">
                                            <a href="{{ route('admin.vouchers.show', $voucher) }}">
                                                <button title="xem" class="btn btn-success btn-sm " type="button"><i
                                                        class="bi bi-eye"></i></button>
                                            </a>

                                            <a href="{{ route('admin.vouchers.edit', $voucher) }}">
                                                <button title="xem" class="btn btn-warning btn-sm " type="button"><i
                                                        class="fas fa-edit"></i></button>
                                            </a>

                                            {{-- <form method="POST" action="{{ route('admin.vouchers.destroy', $voucher) }}"
                                                class="d-inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Bạn có muốn xóa không')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form> --}}
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
@endsection

@section('script')
    <!-- Required datatable js -->
    <script src="{{ asset('theme/admin/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Buttons examples -->
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>
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

    <script src="{{ asset('theme/admin/assets/js/app.js') }}"></script>

    <script src="{{ asset('assets/js/common.js') }}"></script>
    <script src="{{ asset('assets/js/cinema/index.js') }}"></script>
    <script>
        // Hàm xác nhận trước khi thay đổi
        function confirmChange(text = 'Bạn có chắc chắn muốn thay đổi trạng thái Đồ ăn?', title =
            'AlphaCinema thông báo') {
            return Swal.fire({
                icon: 'warning',
                title: title,
                text: text,
                showCancelButton: true,
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Hủy',
            }).then(result => result.isConfirmed);
        }

        // Sự kiện thay đổi trạng thái của đồ ăn (food)
        $(document).on("change", ".changeActive", function(e) {
            e.preventDefault();

            let $checkbox = $(this);
            let voucherID = $checkbox.data("voucher-id");
            let is_active = $checkbox.is(":checked") ? 1 : 0;

            confirmChange('Bạn có chắc chắn muốn thay đổi trạng thái món ăn?').then((confirmed) => {
                if (confirmed) {
                    $.ajax({
                        url: "{{ route('voucher.change-active') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: voucherID,
                            is_active: is_active
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success('Trạng thái hoạt động đã được cập nhật.');
                            } else {
                                toastr.error(response.message || 'Có lỗi xảy ra.');
                                $checkbox.prop("checked", !is_active);
                            }
                        },
                        error: function() {
                            toastr.error('Có lỗi xảy ra khi cập nhật trạng thái.');
                            $checkbox.prop("checked", !is_active);
                        }
                    });
                } else {
                    $checkbox.prop("checked", !is_active); // Nếu hủy thì hoàn tác checkbox
                }
            });
        });
    </script>

@endsection
