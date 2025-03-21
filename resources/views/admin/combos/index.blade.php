@extends('admin.layouts.master')
@section('title', 'Quản lý Combo')

@section('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <style>
        .card {
            box-shadow: 0px 1px 10px 3px #dedede;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Quản lý Combo</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Combo</a></li>
                        <li class="breadcrumb-item active">Danh sách Combo</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center mb-3">
                        <!-- Cột bên trái: Hiển thị -->
                        <div class="col-md-6 text-end">
                            <div class="d-flex align-items-center">
                                <span class="me-2">Hiển thị:</span>
                                <select id="pageLength" class="form-select w-auto">
                                    <option value="5" selected>5</option>
                                    <option value="10">10</option>
                                    <option value="15">15</option>
                                    <option value="20">20</option>
                                    <option value="1000">Tất cả</option>
                                </select>
                            </div>
                        </div>
                        <!-- Cột bên phải: Thêm mới và Bộ lọc -->
                        <div class="col-md-6 text-end">
                            <a href="{{ route('admin.combos.create') }}" class="btn btn-primary me-2">+ Thêm mới</a>
                            <button class="btn btn-outline-secondary me-2" type="button" data-bs-toggle="collapse"
                                data-bs-target="#searchForm">
                                <i class="fas fa-filter"></i> Bộ lọc
                            </button>
                            <a href="{{ route('admin.export', 'combos') }}" class="btn btn-warning waves-effect waves-light">
                                <i class="bx bx-download me-1"></i>
                                Xuất Excel
                            </a>
                        </div>
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



                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="comboTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tên Combo</th>
                                    <th>Hình ảnh</th>
                                    <th>Thông tin Combo</th>
                                    <th>Giá bán</th>
                                    <th>Mô tả</th>
                                    <th>Hoạt động</th>
                                    <th>Chức năng</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@php
    $appUrl = env('APP_URL');
@endphp
@section('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        var appURL = @json($appUrl);
        var table;
        $(document).ready(function () {
            table = $('#comboTable').DataTable({
                processing: false,
                serverSide: true,
                responsive: true,
                autoWidth: false,
                ajax: {
                    url: "{{ route('api.combos.index') }}",
                    type: "GET",
                    data: function (d) {
                        d.id = $('input[name="id"]').val();
                        d.name = $('input[name="name"]').val();
                        d.price_min = $('input[name="price_min"]').val();
                        d.price_max = $('input[name="price_max"]').val();
                        d.food_name = $('#foodSelect').val(); // gửi tên món ăn từ select
                    }
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    {
                        data: 'img_thumbnail',
                        render: function (data) {
                            return `<img src="/storage/${data}" style="max-width: 100px; height: auto; display: block; margin: 0 auto;">`;
                        }
                    },
                    {
                        data: 'info',
                        name: 'info',
                        orderable: false,
                        render: function (data) {
                            return data ? data : "Không có thông tin";
                        }
                    },
                    {
                        data: 'price_sale',
                        name: 'price_sale',
                        render: function (data, type, row) {
                            let priceToShow = row.price_sale > 0 ? row.price_sale : row.price;
                            return `<strong class="text-red-500">${new Intl.NumberFormat('en-US').format(priceToShow)} VNĐ</strong>`;
                        }
                    },
                    { data: 'description', name: 'description' },
                    {
                        data: 'is_active',
                        render: function (data, type, row) {
                            return `<div class="form-check form-switch form-switch-success">
                                        <input class="form-check-input switch-is-active changeActive" 
                                            type="checkbox"
                                            onclick="return confirm('Bạn có chắc muốn thay đổi?')"
                                            data-combo-id="${row.id}" 
                                            ${data ? 'checked' : ''}>
                                     </div>`;
                        },
                        orderable: false
                    },
                    {
                        data: 'id',
                        render: function (data, type, row) {
                            let deleteButton = "";

                            // Kiểm tra nếu combo chưa active (is_active == 0) thì cho phép xóa
                            if (row.is_active == 0) {
                                deleteButton = `
                                          <form method="POST" action="combos/${data}/destroy" class="d-inline-block" id="delete-combo-${data}">
                                              @csrf
                                              @method('DELETE')
                                                  <button type="button" class="btn btn-danger btn-sm" onclick="handleDelete(${data})">
                                                  <i class="bi bi-trash"></i>
                                              </button>
                                          </form> `;
                            }

                            return `
                                        <div class="d-flex align-items-center gap-2">
                                            <a href="/admin/combos/${data}/edit" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i> Sửa
                                             </a>
                                            ${deleteButton}
                                        </div>`;
                        },
                        orderable: false
                    },
                ],
                pageLength: 5,
                lengthChange: false,
                language: {
                    search: "Tìm kiếm:",
                    paginate: {
                        next: ">",
                        previous: "<"
                    },
                    lengthMenu: "Hiển thị _MENU_ mục",
                    info: "Hiển thị từ _START_ đến _END_ trong tổng số _TOTAL_ mục",
                    emptyTable: "Không có dữ liệu để hiển thị",
                    zeroRecords: "Không tìm thấy kết quả phù hợp"
                }
            });
            $('#pageLength').on('change', function () {
                table.page.len($(this).val()).draw();
            });
            $('#filterForm').on('submit', function (e) {
                e.preventDefault();
                table.ajax.reload();
            });
            $('#resetFilter').on('click', function () {
                setTimeout(function () {
                    table.ajax.reload();
                }, 50);
            });
        });



        fetch(`/api/combos`)
            .then(response => response.json())
            .then(data => {
                // Lấy mảng món ăn từ key "food"
                let foods = data.food;
                let select = document.getElementById('foodSelect');

                // Xóa nội dung cũ và thêm option mặc định
                select.innerHTML = "<option value=''>--Chọn món ăn--</option>";

                // Lặp qua từng món ăn và tạo option
                foods.forEach(food => {
                    let option = document.createElement('option');
                    option.value = food.name; // dùng tên món làm giá trị
                    option.textContent = food.name;
                    select.appendChild(option);
                });

                $(select).trigger('change');
            })
            .catch(error => console.error('Lỗi load dữ liệu món ăn:', error));


        // Xử lý xóa
        window.handleDelete = function (comboId) {
            Swal.fire({
                title: 'Bạn có chắc chắn?',
                text: "Hành động này không thể hoàn tác!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    $(`#delete-combo-${comboId}`).submit();
                }
            });
        };

        // sự kiện change-active
        $(document).on("change", ".changeActive", function () {
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
                success: function (response) {
                    if (response.success) {
                        // Hiển thị thông báo thành công
                        Swal.fire({
                            icon: "success",
                            title: "Thành công!",
                            text: "Trạng thái hoạt động đã được cập nhật.",
                            timer: 3000,
                            timerProgressBar: true,
                        });

                        // Cập nhật lại bảng DataTable để thay đổi trạng thái nút "Xóa"
                        let row = table.row($(`[data-combo-id="${comboId}"]`).closest("tr"));
                        let rowData = row.data();
                        rowData.is_active = is_active; // Cập nhật giá trị mới
                        row.data(rowData).draw(false); // Vẽ lại hàng mà không cần load lại toàn bộ

                        // Cập nhật lại nút Xóa (chỉ hiển thị nếu is_active == 0)
                        let deleteButton = is_active == 0
                            ? `<form method="POST" action="foods/${comboId}/destroy" class="d-inline-block" id="delete-food-${comboId}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-sm" onclick="handleDelete(${comboId})">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>`
                            : "";

                        // Cập nhật lại nội dung cột hành động
                        let actionCell = row.node().querySelector(".d-flex");
                        if (actionCell) {
                            actionCell.innerHTML = `
                                                <a href="/admin/combos/${comboId}/edit" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i> Sửa
                                                </a>
                                                ${deleteButton}`;
                        }
                    } else {
                        // Nếu có lỗi, hiển thị thông báo và hoàn nguyên checkbox
                        Swal.fire({
                            icon: "error",
                            title: "Lỗi!",
                            text: response.message || "Có lỗi xảy ra.",
                        });
                        $(`[data-combo-id="${comboId}"]`).prop("checked", !is_active);
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: "error",
                        title: "Lỗi!",
                        text: "Có lỗi xảy ra khi cập nhật trạng thái.",
                    });
                    $(`[data-combo-id="${comboId}"]`).prop("checked", !is_active);
                }
            });

            console.log("Đã thay đổi trạng thái active của combo");
        });

    </script>
@endsection