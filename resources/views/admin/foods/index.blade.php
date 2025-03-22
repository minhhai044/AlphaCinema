@extends('admin.layouts.master')
@section('title', 'Quản lý đồ ăn')
@section('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
@endsection
@section('content')
    <!-- Start page title -->
    {{-- <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Quản lý đồ ăn</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Đồ ăn</a></li>
                        <li class="breadcrumb-item active">Danh sách đồ ăn</li>
                    </ol>
                </div>
            </div>
        </div>
    </div> --}}
    <!-- End page title -->

        <div class="col-lg-12">
                    <div class="row align-items mb-3">
                        <h4 class="mb-sm-0 font-size-30">Quản lý đồ ăn</h4>
                        <!-- Cột bên trái: Hiển thị -->
                        <div class="col-md-6 text-end">
                            <div class="d-flex align-items">
                                {{-- <span class="me-2">Hiển thị:</span>
                                <select id="pageLength" class="form-select w-auto">
                                    <option value="5" selected>5</option>
                                    <option value="10">10</option>
                                    <option value="15">15</option>
                                    <option value="20">20</option>
                                    <option value="1000">Tất cả</option>
                                </select> --}}
                            </div>
                        </div>
                        <!-- Cột bên phải: Thêm mới và Bộ lọc -->
                        <div class="col-md-6 text-end">
                            <a href="{{ route('admin.foods.create') }}" class="btn btn-primary  me-2">+ Thêm mới</a>
                            <a href="{{ route('admin.export', 'food') }}" class="btn btn-warning waves-effect waves-light">
                                <i class="bx bx-download me-1"></i>
                                Xuất Excel
                            </a>
                        </div>

                        <div class="collapse" id="searchForm">
                            <div class="card card-body mb-3">
                                <form id="filterForm">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="form-label">Tìm nhanh theo ID</label>
                                            <input type="text" name="id" class="form-control" placeholder="Nhập ID">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Khoảng giá</label>
                                            <div class="d-flex">
                                                <input type="number" name="price_min" class="form-control me-2"
                                                    placeholder="Từ">
                                                <input type="number" name="price_max" class="form-control" placeholder="Đến">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Lọc theo món ăn</label>
                                            <select name="food_name" id="foodSelect" class="form-select select2">
                                                <option value="">--Chọn món ăn--</option>
                                            </select>
                                        </div>
                                    </div>
    
                                    <div class="mt-3">
                                        <button type="submit" class="btn btn-primary">Lọc</button>
                                        <button type="reset" class="btn btn-secondary" id="resetFilter">Reset</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Bảng danh sách -->
                    <div class="table-responsive">
                        <table id="foodTable" class="table table-bordered dt-responsive nowrap align-middle w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Tên đồ ăn</th>
                                    <th>Loại đồ ăn</th>
                                    <th>Hình ảnh</th>
                                    <th>Giá bán</th>
                                    <th>Hoạt động</th>
                                    <th>Chức năng</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $appUrl = env('APP_URL');
        // dd($appUrl);
    @endphp
@endsection

@section('script')
    <script>
        var appURL = @json($appUrl);
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Khởi tạo DataTable
            var table = $('#foodTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,
                ajax: {
                    url: "{{ route('api.foods.index') }}",
                    type: "GET",
                    error: function(xhr, status, error) {
                        console.error("Lỗi API:", xhr.responseText);
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id',
                        render: function(data) {
                            return `<div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="foodcheck-${data}">
                                        <label class="form-check-label" for="foodcheck-${data}">${data}</label>
                                    </div>`;
                        }
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'img_thumbnail',
                        render: function(data) {

                            return data ?
                                `<img src="/storage/${data}" style="max-width: 100px; height: auto; display: block; margin: 0 auto;">` :
                                'Không có ảnh';

                        },
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'price',
                        render: function(data) {
                            return `<strong class="text-red-500">${new Intl.NumberFormat('en-US').format(data)} VNĐ</strong>`;
                        }
                    },
                    {
                        data: 'is_active',
                        render: function(data, type, row) {

                            return `<div class="form-check form-switch form-switch-md mb-3" dir="ltr">
                                        <input class="form-check-input switch-is-active changeActive" type="checkbox" data-food-id="${row.id}" ${data ? 'checked' : ''} onclick="return confirm('Bạn có chắc muốn thay đổi?')">
                                    </div>
                                    `;

                        },
                        orderable: false
                    },
                    {
                        data: 'id',
                        render: function(data, type, row) {

                            let deleteButton = row.combos_count == 0 ? `
                                <form method="POST" action="foods/${data}/destroy" class="d-inline-block" id="delete-food-${data}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm" onclick="handleDelete(${data})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>` : '';
                            return `
                                <div class="d-flex align-items-center gap-2">
                                    <a href="${appURL}/admin/foods/${row.id}/edit" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    ${deleteButton}
                                </div>`;
                        },
                        orderable: false,
                        searchable: false
                    }
                ],
                pageLength: 5,
                lengthChange: true,
                lengthMenu: [5, 10, 20, 50, 100],
                language: {
                    search: "Tìm kiếm:",
                    paginate: {
                        next: ">",
                        previous: "<"
                    },
                    info: "Hiển thị từ _START_ đến _END_ trong tổng số _TOTAL_ mục",
                    emptyTable: "Không có dữ liệu để hiển thị",
                    zeroRecords: "Không tìm thấy kết quả phù hợp",
                    "lengthMenu": "Hiển thị _MENU_ mục"
                }
            });

            // Thay đổi số dòng hiển thị
            $('#pageLength').on('change', function() {
                table.page.len($(this).val()).draw();
            });

            // Tìm kiếm theo tên
            $('#searchInput').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Xử lý sự kiện thay đổi trạng thái is_active
            $(document).on("change", ".changeActive", function() {
                let foodId = $(this).data("food-id");
                let is_active = $(this).is(":checked") ? 1 : 0;
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
                            $(`[data-food-id="${foodId}"]`).prop("checked", !is_active);
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Có lỗi xảy ra khi cập nhật trạng thái.');
                        $(`[data-food-id="${foodId}"]`).prop("checked", !is_active);
                    }
                });
            });

            // Xử lý xóa
            window.handleDelete = function(foodId) {
                Swal.fire({
                    title: 'Bạn có chắc chắn?',
                    text: "Hành động này không thể hoàn tác!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Xóa',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(`#delete-food-${foodId}`).submit();
                    }
                });
            };

        });
    </script>
@endsection
