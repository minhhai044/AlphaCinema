@extends('admin.layouts.master')

@section('title')
    Danh sách loại ghế
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
                <h4 class="mb-sm-0">Loại ghế</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
                        <li class="breadcrumb-item active">Loại ghế</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- End page title -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                {{-- <div class="card-header d-flex justify-content-between">
                    <h5 class="card-title mb-0">Danh sách loại ghế</h5>
                    <a href="{{ route('admin.type_seats.create') }}" class="btn btn-primary mb-3">Thêm mới</a>
                </div> --}}
                <div class="card-body">
                    <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tên loại ghế</th>
                                <th>Giá</th>
                                <th>Chức năng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($typeSeats as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ number_format($item->price) }} VNĐ</td>

                                    <td>
                                        <div class="dropdown">
                                            <a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <i class="mdi mdi-dots-horizontal font-size-18"></i>
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-end" style="">
                                                <li>
                                                    <a data-bs-toggle="modal"
                                                        class="edit_type_seat  btn waves-effect waves-light"
                                                        data-bs-target="#exampleModal" data-id={{ $item->id }}
                                                        data-name="{{ $item->name }}" data-price={{ $item->price }}>
                                                        <i class="mdi mdi-pencil font-size-16 text-success me-1"></i>
                                                        Sửa 
                                                    </a>
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
                                                                Giá
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
                                data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Sửa ghế </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection


    @section('script')
        <script>
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

                let price = parseFloat($('#price').val());
                let errorMessage = '';

                // Lấy danh sách giá từ dữ liệu đang hiển thị trên trang
                let priceNormal = {{ $typeSeats->where('name', 'Ghế thường')->first()->price ?? 0 }};
                let priceVip = {{ $typeSeats->where('name', 'Ghế VIP')->first()->price ?? 0 }};
                let priceCouple = {{ $typeSeats->where('name', 'Ghế đôi')->first()->price ?? 0 }};

                if (price <= 0) {
                    errorMessage = 'Giá phải lớn hơn 0!';
                } else if ($('#name').val().trim() === 'Ghế thường' && price >= priceVip) {
                    errorMessage = 'Giá ghế thường phải nhỏ hơn giá ghế VIP!';
                } else if ($('#name').val().trim() === 'Ghế VIP' && (price <= priceNormal || price >= priceCouple)) {
                    errorMessage = 'Giá ghế VIP phải lớn hơn ghế thường và nhỏ hơn ghế đôi!';
                } else if ($('#name').val().trim() === 'Ghế đôi' && price <= priceVip) {
                    errorMessage = 'Giá ghế đôi phải lớn hơn giá ghế VIP!';
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
        </script>
    @endsection
