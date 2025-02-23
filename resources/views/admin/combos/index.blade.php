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
                        <div class="col-md-6">
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
                            <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#searchForm">
                                <i class="fas fa-filter"></i> Bộ lọc
                            </button>
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
                                            <input type="number" name="price_min" class="form-control me-2" placeholder="Từ">
                                            <input type="number" name="price_max" class="form-control" placeholder="Đến">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Lọc theo món ăn</label>
                                        <select name="food_id[]" id="foodSelect" class="form-select select2">
                                            <!-- Nội dung sẽ được đổ từ API -->
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

@section('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            var table = $('#comboTable').DataTable({
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
                    { data: 'price_sale', name: 'price_sale' },
                    { data: 'description', name: 'description' },
                    {
                        data: 'is_active',
                        render: function (data) {
                            return data
                                ? '<span class="badge bg-success">Active</span>'
                                : '<span class="badge bg-danger">Inactive</span>';
                        }
                    },
                    {
                        data: 'id',
                        render: function (data) {
                            return `
                                                <div class="dropdown text-center">
                                                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                        ...
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a href="/admin/combos/${data}/edit" class="dropdown-item text-warning">
                                                                <i class="fas fa-edit"></i> Sửa
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <form action="/admin/combos/${data}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger"
                                                                    onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                                                    <i class="fas fa-trash-alt"></i> Xóa
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            `;
                        }
                    }
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



            fetch('http://alphacinema.test/api/combos')
                .then(response => response.json())
                .then(data => {
                    // Lấy mảng món ăn từ key "food"
                    let foods = data.food;
                    let select = document.getElementById('foodSelect');

                    // Xóa nội dung cũ (nếu có)
                    select.innerHTML = "";

                    // Lặp qua từng món ăn và tạo option
                    foods.forEach(food => {
                        let option = document.createElement('option');
                        option.value = food.id;
                        option.textContent = food.name;
                        select.appendChild(option);
                    });

                    $(select).trigger('change');
                })
                .catch(error => console.error('Lỗi load dữ liệu món ăn:', error));

    </script>
@endsection
