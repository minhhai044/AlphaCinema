@extends('admin.layouts.master')

@section('title')
    Danh sách loại ghế
@endsection

@section('style')
    <link href="{{ asset('theme/admin/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/admin/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/admin/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('theme/admin/assets/css/preloader.min.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('theme/admin/assets/css/preloader.min.css') }}" type="text/css" />

    <link href="{{ asset('theme/admin/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('theme/admin/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
@endsection
@section('style-libs')
    <!-- Datatable CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
@endsection

@section('content')
    <!-- Start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-20">Quản lý loại ghế </h4>

            </div>
        </div>
    </div>
    <!-- End page title -->

    <div class="row">
        <div class="col-lg-12">

                    <table id="datatable" class="table table-bordered  text-center"
                        style="width:100%">
                        <thead  >
                            <tr>
                                <th class="text-center">STT</th>
                                <th class="text-center">Tên loại ghế</th>
                                <th class="text-center">Phụ phí</th>
                                <th class="text-center">Chức năng</th>
                            </tr>
                        </thead>
                        <tbody  >
                            @foreach ($typeSeats as $item)
                                <tr>
                                    <td>{{ $loop->iteration  }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ number_format($item->price) }} VNĐ</td>
                                    <td>
                                        <a data-bs-toggle="modal" class="edit_type_seat btn waves-effect waves-light"
                                            data-bs-target="#exampleModal" data-id="{{ $item->id }}"
                                            data-name="{{ $item->name }}" data-price="{{ $item->price }}">
                                            <button title="sửa" class="btn btn-warning btn-sm" type="button">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
        </div><!--end col-->
    </div><!--end row-->


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Sửa loại ghế </h1>
                    {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                </div>
                <div class="modal-body">
                    <form method="post" id="formupdate">
                        @csrf
                        @method('PUT')


                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">

                                    <div class="card-body">
                                        <div class="live-preview">
                                            <div class="row gy-4">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label for="name" class="form-label "> <span
                                                                    class="text-danger">*</span>Tên
                                                                loại ghế
                                                            </label>
                                                            <input type="text" class="form-control" id="name"
                                                                name="name" disabled>
                                                        </div>

                                                        <div class="col-md-6 mb-3">
                                                            <label for="price" class="form-label ">
                                                                <span class="text-danger">*</span>
                                                                Phụ phí
                                                            </label>
                                                            <input type="number" name="price" id="price"
                                                                class="form-control {{ $errors->has('price') ? 'is-invalid' : (old('price') ? 'is-valid' : '') }}">
                                                            <div id="price-error" class="invalid-feedback">
                                                                @if ($errors->has('price'))
                                                                    {{ $errors->first('price') }}
                                                                @endif
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>


                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-close-modal"
                                data-bs-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-primary">Sửa ghế </button>
                        </div>
                    </form>
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

        <script>
            // <!-- Required datatable js -->

            $('.edit_type_seat').click(function(e) {
                let id = $(this).data('id');
                let name = $(this).data('name').trim();
                let price = $(this).data('price');
                console.log(name);

                $('#name').val(name);
                $('#price').val(price);

                $('#formupdate').attr('action', `type_seats/${id}`);
            })
            $('#formupdate').submit(function(e) {
                e.preventDefault(); // Ngăn form submit ngay lập tức

                let price = $.trim($('#price').val()) ;
                // let price = parseFloat($.trim($('#price').val()));

                let errorMessage = '';

                // Lấy danh sách Phụ phí từ dữ liệu đang hiển thị trên trang
                let priceNormal = {{ $typeSeats->where('name', 'Ghế thường')->first()->price ?? 0 }};
                let priceVip = {{ $typeSeats->where('name', 'Ghế VIP')->first()->price ?? 0 }};
                let priceCouple = {{ $typeSeats->where('name', 'Ghế đôi')->first()->price ?? 0 }};

                if (price < 0 || price == '') {
                    errorMessage = 'Phụ phí phải lớn hơn 0!';
                } else if ($('#name').val().trim() === 'Ghế thường' && price >= priceVip) {
                    errorMessage = 'Phụ phí ghế thường phải nhỏ hơn Phụ phí ghế VIP!';
                } else if ($('#name').val().trim() === 'Ghế VIP' && (price <= priceNormal || price >= priceCouple)) {
                    errorMessage = 'Phụ phí ghế VIP phải lớn hơn ghế thường và nhỏ hơn ghế đôi!';
                } else if ($('#name').val().trim() === 'Ghế đôi' && price <= priceVip) {
                    errorMessage = 'Phụ phí ghế đôi phải lớn hơn Phụ phí ghế VIP!';
                }

                if (errorMessage) {
                    $('#price-error').text(errorMessage).show(); // Hiển thị lỗi dưới input
                    $('#price').addClass('is-invalid'); // Thêm class Bootstrap
                } else {
                    $('#price-error').text('').hide(); // Ẩn lỗi nếu không có
                    $('#price').removeClass('is-invalid'); // Xóa class lỗi
                    this.submit(); // Nếu hợp lệ, gửi form
                }

                // Reset lỗi khi đóng modal
                $('.btn-close-modal').click(function() {
                    resetFormErrors();
                });

                function resetFormErrors() {
                    $('#price-error').text('').hide();
                    $('#price').removeClass('is-invalid');
                }
            });
            $(document).ready(function() {
        $('#typeSeatsTable').DataTable({
            paging: false, // Tắt phân trang
            searching: true, // Bật tìm kiếm
            ordering: true, // Bật sắp xếp
            info: false, // Ẩn "Showing X to Y of Z entries"
            responsive: true, // Hỗ trợ responsive
            language: {
                "sSearch": "Tìm kiếm:",
                "zeroRecords": "Không tìm thấy kết quả nào",
                "infoEmpty": "Không có dữ liệu",
                "lengthMenu": "Hiển thị _MENU_ dòng",
                "sProcessing": "Đang xử lý..."
            }
        });
    });

        </script>
    @endsection
