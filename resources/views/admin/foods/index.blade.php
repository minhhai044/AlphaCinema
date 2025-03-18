@extends('admin.layouts.master')
@section('title', 'Quản lý đồ ăn')
@section('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
@endsection
@section('content')
    <!-- Start page title -->
    <div class="row">
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
    </div>
    <!-- End page title -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center mb-3">
                        <!-- Cột bên trái: Hiển thị -->
                        <div class="col-md-6 text-end">
                            <div class="d-flex align-items-center">
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
                            <a class="btn btn-primary openCreateFoodModal me-2">+ Thêm mới</a>
                            <button class="btn btn-outline-secondary me-2 " type="button" data-bs-toggle="collapse"
                                data-bs-target="#searchForm">
                                <i class="fas fa-filter"></i> Bộ lọc
                            </button>
                            <a href="{{ route('admin.export', 'food') }}" class="btn btn-warning waves-effect waves-light">
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

    <!-- Modal tạo mới -->
    <div class="modal fade" id="createFoodModal" tabindex="-1" aria-labelledby="createFoodModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createFoodModalLabel">Thêm mới đồ ăn</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createFoodForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <input type="checkbox" hidden name="is_active" value="1" checked>
                            <div class="col-md-6 mb-3">
                                <label for="createName" class="form-label"><span class="text-danger">*</span> Tên món
                                    ăn:</label>
                                <input type="text" class="form-control" id="createName" name="name" required
                                    placeholder="Nhập tên món ăn">
                                <span class="text-danger mt-3" id="createNameError" class="errorName"></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="createType" class="form-label"><span class="text-danger">*</span> Loại đồ
                                    ăn:</label>
                                <select name="type" id="createType" class="form-select" required>
                                    <option disabled selected>Chọn loại đồ ăn</option>
                                    <option value="Đồ ăn">Đồ ăn</option>
                                    <option value="Đồ uống">Đồ uống</option>
                                    <option value="Khác">Khác</option>
                                </select>
                                <span class="text-danger mt-3" id="createTypeError"></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="createPrice" class="form-label"><span class="text-danger">*</span> Giá
                                    tiền:</label>
                                <input type="tel" class="form-control" id="createPrice" name="price" required
                                    placeholder="Nhập giá tiền">
                                <span class="text-danger mt-3" id="createPriceError"></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="createImgThumbnail" class="form-label"><span class="text-danger">*</span>
                                    Hình
                                    ảnh:</label>
                                <input type="file" name="img_thumbnail" id="createImgThumbnail" class="form-control"
                                    required>
                                <span class="text-danger mt-3" id="createImgThumbnailError"></span>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="createDescription" class="form-label"><span class="text-danger">*</span> Mô
                                    tả:</label>

                                <textarea id="createDescription" class="form-control" name="description" rows="6" placeholder="Nhập mô tả"
                                    required></textarea>

                                <span class="text-danger mt-3" id="createDescriptionError"></span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" id="createFoodBtn">Thêm mới</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal cập nhật -->
    <div class="modal fade" id="updateFoodModal" tabindex="-1" aria-labelledby="updateFoodModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateFoodModalLabel">Cập nhật đồ ăn</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateFoodForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="updateFoodId" name="food_id">
                        <div class="row">
                            <input type="checkbox" hidden name="is_active" value="1" checked>
                            <div class="col-md-6 mb-3">
                                <label for="updateName" class="form-label"><span class="text-danger">*</span> Tên món
                                    ăn:</label>
                                <input type="text" class="form-control" id="updateName" name="name" required
                                    placeholder="Nhập tên món ăn">
                                <span class="text-danger mt-3" id="updateNameError"></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="updateType" class="form-label"><span class="text-danger">*</span> Loại đồ
                                    ăn:</label>
                                <select name="type" id="updateType" class="form-select" required>
                                    <option disabled selected>Chọn loại đồ ăn</option>
                                    <option value="Đồ ăn">Đồ ăn</option>
                                    <option value="Đồ uống">Đồ uống</option>
                                    <option value="Khác">Khác</option>
                                </select>
                                <span class="text-danger mt-3" id="updateTypeError"></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="updatePrice" class="form-label"><span class="text-danger">*</span> Giá
                                    tiền:</label>
                                <input type="tel" class="form-control" id="updatePrice" name="price" required
                                    placeholder="Nhập giá tiền">
                                <span class="text-danger mt-3" id="updatePriceError"></span>
                            </div>
                            <div class="col-md-6 mb-3">

                                <label for="updateImgThumbnail" class="form-label"><span class="text-danger">*</span>
                                    Hình
                                    ảnh:</label>

                                <input type="file" name="img_thumbnail" id="updateImgThumbnail" class="form-control">
                                <img id="previewImgThumbnail" src="" alt="Ảnh xem trước" class="mt-2"
                                    style="max-width: 150px; display: none;">
                                <span class="text-danger mt-3" id="updateImgThumbnailError"></span>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="updateDescription" class="form-label"><span class="text-danger">*</span> Mô
                                    tả:</label>

                                <textarea id="updateDescription" class="form-control" name="description" rows="6" placeholder="Nhập mô tả"
                                    required></textarea>

                                <span class="text-danger mt-3" id="updateDescriptionError"></span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" id="updateFoodBtn">Cập nhật</button>
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
            // Format giá tiền
            const formatPriceInput = (input) => {
                input.addEventListener("input", function(e) {
                    let value = e.target.value.replace(/\D/g, "");
                    input.value = value ? Number(value).toLocaleString("en-US") : "";
                });
                input.addEventListener("blur", function(e) {
                    if (!e.target.value) {
                        e.target.value = "0";
                    }
                });
            };
            formatPriceInput(document.getElementById("createPrice"));
            formatPriceInput(document.getElementById("updatePrice"));

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
                            return `<div class="form-check form-switch form-switch-success">
                                        <input class="form-check-input switch-is-active changeActive" type="checkbox" data-food-id="${row.id}" ${data ? 'checked' : ''} onclick="return confirm('Bạn có chắc muốn thay đổi?')">
                                    </div>`;
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
                                    <a class="btn btn-warning btn-sm openUpdateFoodModal" data-food-id="${data}" data-food-name="${row.name}" data-food-type="${row.type}" data-food-price="${row.price}" data-food-img_thumbnail="${row.img_thumbnail}" data-food-description="${row.description}">
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
                lengthMenu: [5,10, 20, 50, 100],
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
                            Swal.fire({
                                icon: "success",
                                title: "Thành công!",
                                text: "Trạng thái hoạt động đã được cập nhật.",
                                timer: 3000,
                                timerProgressBar: true,
                            });
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Lỗi!",
                                text: response.message || "Có lỗi xảy ra.",
                            });
                            $(`[data-food-id="${foodId}"]`).prop("checked", !is_active);
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: "error",
                            title: "Lỗi!",
                            text: "Có lỗi xảy ra khi cập nhật trạng thái.",
                        });
                        $(`[data-food-id="${foodId}"]`).prop("checked", !is_active);
                    }
                });
            });

            // Mở modal tạo mới và đồng bộ aria-hidden
            $('.openCreateFoodModal').click(function() {
                $('#createFoodForm')[0].reset();
                $(".text-danger").text("");
                $(".form-control").removeClass("is-invalid");
                $('#createFoodModal').modal('show');
            });

            $('#createFoodModal').on('shown.bs.modal', function() {
                $(this).attr('aria-hidden', 'false');
                $('#createName').focus();
            }).on('hidden.bs.modal', function() {
                $(this).attr('aria-hidden', 'true');
            });

            // Mở modal cập nhật và đồng bộ aria-hidden
            $(document).on('click', '.openUpdateFoodModal', function() {
                let foodId = $(this).data('food-id');
                $('#updateFoodId').val(foodId);
                $('#updateName').val($(this).data('food-name'));
                $('#updateType').val($(this).data('food-type'));
                $('#updatePrice').val($(this).data('food-price'));
                $('#updateDescription').val($(this).data('food-description'));
                let imgThumbnail = $(this).data('food-img_thumbnail');
                if (imgThumbnail) {
                    $('#previewImgThumbnail').attr('src', `/storage/${imgThumbnail}`).show();
                } else {
                    $('#previewImgThumbnail').hide();
                }
                $(".text-danger").text("");
                $('#updateFoodModal').modal('show');
            });

            $('#updateFoodModal').on('shown.bs.modal', function() {
                $(this).attr('aria-hidden', 'false');
                $('#updateName').focus();
            }).on('hidden.bs.modal', function() {
                $(this).attr('aria-hidden', 'true');
            });

            // Submit form tạo mới
            $('#createFoodBtn').click(function() {
                let formData = new FormData($("#createFoodForm")[0]);
                $.ajax({
                    url: "{{ route('admin.foods.store') }}",
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status === true) {
                            Swal.fire('Thành công!', 'Đồ ăn đã được thêm mới.', 'success');
                            $('#createFoodModal').modal('hide');
                            table.ajax.reload();
                        }
                    },
                    error: function(xhr) {
                        handleAjaxErrors(xhr, "create");
                    }
                });
            });

            // Submit form cập nhật
            $('#updateFoodBtn').click(function() {
                let formData = new FormData($('#updateFoodForm')[0]);
                let foodId = $('#updateFoodId').val();
                let price = $('#updatePrice').val().replace(/[^\d]/g, '');
                formData.set('price', price);
                if (!price || parseFloat(price) <= 0) {
                    $("#updatePriceError").text("Giá phải là số và lớn hơn 0.");
                    return;
                }
                $.ajax({
                    url: `foods/${foodId}`,
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status === true) {
                            Swal.fire('Thành công!', 'Đồ ăn đã được cập nhật.', 'success');
                            $('#updateFoodModal').modal('hide');
                            table.ajax.reload();
                        }
                    },
                    error: function(xhr) {
                        handleAjaxErrors(xhr, "update");
                    }
                });
            });

            // Hàm xử lý lỗi AJAX
            function handleAjaxErrors(xhr, prefix) {
                let errors = xhr.responseJSON?.errors || {};
                console.log('Lỗi từ server:', xhr.responseText); // Log để debug
                $(".text-danger").text("");
                for (let field in errors) {
                    $(`#${prefix}${field.charAt(0).toUpperCase() + field.slice(1)}Error`).text(errors[field][0]);
                }
                if (!Object.keys(errors).length) {
                    Swal.fire('Lỗi!', 'Không thể xử lý yêu cầu. Kiểm tra console để biết thêm chi tiết.', 'error');
                }
            }

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
