@extends('admin.layouts.master')
@section('title', 'Quản lý đồ ăn')
{{-- @section('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
@endsection --}}
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
                <h4 class="mb-sm-0 font-size-20 fw-semibold">Quản lý đồ ăn</h4>

                <div class="page-title-right">


                    <a href="{{ route('admin.foods.create') }}" class="btn btn-primary  me-2">+ Thêm mới</a>
                    <a href="{{ route('admin.export', 'food') }}" class="btn btn-warning waves-effect waves-light">
                        <i class="bx bx-download me-1"></i>
                        Xuất Excel
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <!-- Bảng danh sách -->
        <div class="table-responsive">
            <table id="datatable" class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th class="fw-semibold text-center">STT</th>
                        <th class="fw-semibold text-center">Tên đồ ăn</th>
                        <th class="fw-semibold text-center">Loại đồ ăn</th>
                        <th class="fw-semibold text-center">Hình ảnh</th>
                        <th class="fw-semibold text-center">Giá bán</th>
                        <th class="fw-semibold text-center">Hoạt động</th>
                        <th class="fw-semibold text-center">Chức năng</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($foods as $food)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold">{{ limitText($food->name, 20) }}</td>
                            <td>{{ $food->type }}</td>
                            <td>
                                @if ($food->img_thumbnail && Storage::exists($food->img_thumbnail))
                                    <img src="{{ Storage::url($food->img_thumbnail) }}" width="70" height="70"
                                        alt="">
                                @else
                                    <img src="{{ asset('images/foods/foods.png') }}" width="70" height="70"
                                        alt="">
                                @endif
                            </td>
                            <td>{{ number_format($food->price) }} VND</td>
                            <td>
                                <div class="d-flex justify-content-center align-items-center">
                                    <div class=" form-check form-switch form-switch-md" dir="ltr">
                                        <input class="form-check-input switch-is-active changeActive" type="checkbox"
                                            data-food-id="{{ $food->id }}" @checked($food->is_active)>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('admin.foods.edit', $food) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @php
        $appUrl = env('APP_URL');
    @endphp
@endsection

@section('script')
    <script>
        var appURL = @json($appUrl);
    </script>
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
            let foodId = $checkbox.data("food-id");
            let is_active = $checkbox.is(":checked") ? 1 : 0;

            confirmChange('Bạn có chắc chắn muốn thay đổi trạng thái món ăn?').then((confirmed) => {
                if (confirmed) {
                    $.ajax({
                        url: "{{ route('food.change-active') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: foodId,
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
