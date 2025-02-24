@extends('admin.layouts.master')
@section('title', 'Quản lý đồ ăn')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Quản lý đồ ăn</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Đồ ăn</a>
                        </li>
                        <li class="breadcrumb-item active">Danh sách đồ ăn</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">

                <div class="card-body">
                    <div class="row">
                        <div class="col-sm">
                            <div class="mb-4">
                                <h6 class="mb-sm-0 font-size-16">Danh sách đồ ăn</h6>
                            </div>
                        </div>
                        <div class="col-sm-auto">
                            <div class="mb-4">
                                <a class="btn btn-light waves-effect waves-light openCreateFoodModal">
                                    <i class="bx bx-plus me-1"></i>
                                    Thêm mới đồ ăn
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- Bộ lọc số lượng hiển thị -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="d-flex align-items-center flex-wrap gap-3">
                                <div class="al-table-length">
                                    <label>
                                        Hiển thị
                                        <select name="example_length" aria-controls="example"
                                            class="form-select form-select-sm al-table-select">
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select>
                                        mục
                                    </label>
                                </div>

                                <!-- Ô tìm kiếm -->
                                <div class="al-table-length ms-auto">
                                    <label>
                                        Tìm kiếm:
                                        <input type="search" class="form-control form-control-sm al-table-input"
                                            placeholder="Search đê">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bảng danh sách-->
                    <div class="card-body">
                        <table id="example" class="table table-bordered dt-responsive nowrap align-middle w-100">
                            <thead class='table-light'>
                                <tr>
                                    <th>#</th>
                                    <th>Tên đồ ăn</th>
                                    <th>Loại đồ ăn</th>
                                    <th>Hình ảnh</th>
                                    <th>Giá bán</th>
                                    <th>Hoạt động</th>
                                    <th>Chức năng</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($data as $food)
                                    <tr>
                                        <td class="sorting_1 dtr-control">
                                            <div class="d-none">{{ $food->id }}</div>
                                            <div class="form-check font-size-{{ $food->id }}">
                                                <input class="form-check-input" type="checkbox"
                                                    id="customerlistcheck-{{ $food->id }}">
                                                <label class="form-check-label"
                                                    for="customerlistcheck-{{ $food->id }}"></label>
                                            </div>
                                        </td>
                                        <td>
                                            {{ $food->name }}
                                        </td>
                                        <td>
                                            {{ $food->type }}
                                        </td>
                                        <td class="text-center">
                                            @if ($food->img_thumbnail)
                                                <img class="img-thumbnail" alt="" width="100px" height="60px"
                                                    src="{{ Storage::url($food->img_thumbnail) }}">
                                            @endif
                                        </td>
                                        <td>
                                            <strong class="text-red-500">{{ number_format($food->price) }}
                                                VNĐ</strong>
                                        </td>

                                        {{-- <td>
                                                    <input type="checkbox" id="is_active{{$food->id}}"
                                                        data-publish="{{ $food->is_publish }}" switch="success"
                                                        @checked($food->is_active) />
                                                    <label for="is_active{{$food->id}}"></label>
                                                </td> --}}
                                        <td>
                                            <div class="form-check form-switch form-switch-success">
                                                <input class="form-check-input switch-is-active changeActive"
                                                    name="is_active" type="checkbox" role="switch"
                                                    data-food-id="{{ $food->id }}"
                                                    {{ $food->is_active ? 'checked' : '' }}
                                                    onclick="return confirm('Bạn có chắc muốn thay đổi ?')">

                                            </div>
                                        </td>


                                        <td>
                                            <div class="dropdown">
                                                <a href="#" class="dropdown-toggle card-drop"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="mdi mdi-dots-horizontal font-size-18"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end" style="">
                                                    <li>
                                                        <a class="dropdown-item edit-list cursor-pointer openUpdateFoodModal"
                                                            data-food-id="{{ $food->id }}"
                                                            data-food-name="{{ $food->name }}"
                                                            data-food-description="{{ $food->description }}"
                                                            data-food-img_thumbnail="{{ $food->img_thumbnail }}"
                                                            data-food-type="{{ $food->type }}"
                                                            data-food-price="{{ formatPrice($food->price) }}">
                                                            <i class="mdi mdi-pencil font-size-16 text-warning me-1"></i>
                                                            Cập nhật
                                                        </a>
                                                    </li>
                                                    <li>
                                                        @if ($food->combos()->count() == 0)
                                                            <form action="{{ route('admin.foods.destroy', $food) }}"
                                                                method="POST" id="delete-food-{{ $food->id }}">
                                                                @method('DELETE')
                                                                @csrf

                                                                <button type="button" class="dropdown-item remove-list"
                                                                    onclick="handleDelete({{ $food->id }})">
                                                                    <i
                                                                        class="mdi mdi-trash-can font-size-16 text-danger me-1"></i>
                                                                    Xóa
                                                                </button>
                                                            </form>
                                                        @endif
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
            </div>
            <div class="row">
                {{ $data->onEachSide(1)->links('admin.layouts.components.pagination') }}
            </div>
        </div>
    </div>
    </div>
    </div>
    {{-- model create --}}
    <div class="modal fade" id="createFoodModal" tabindex="-1" aria-labelledby="createFoodModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createFoodModalLabel">
                        Thêm mới đồ ăn
                        <span class="badge bg-primary-subtle text-primary fs-11" id="spanDefault"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createFoodForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <input type="checkbox" hidden class="form-control" name="is_active" value="1" checked>
                            <div class="col-md-6 mb-3">
                                <label for="createName" class="form-label">
                                    <span class="text-danger">*</span>
                                    Tên món ăn:
                                </label>
                                <input type="text" class="form-control" id="createName" name="name" required
                                    placeholder="Nhập tên món ăn">
                                <span class="text-danger mt-3" id="createNameError"></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="createType" class="form-label">
                                    <span class="text-danger">*</span>
                                    Loại đồ ăn:
                                </label>
                                <select name="type" id="createType" class="form-select">
                                    <option disabled selected>Chọn loại đồ ăn</option>
                                    <option value="Đồ ăn">
                                        Đồ ăn
                                    </option>
                                    <option value="Đồ uống">
                                        Đồ uống
                                    </option>
                                    <option value="Khác">
                                        Khác
                                    </option>
                                </select>
                                <span class="text-danger mt-3" id="createTypeError"></span>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="createPrice" class="form-label">
                                    <span class="text-danger">*</span>
                                    Giá tiền:
                                </label>
                                <input type="tel" class="form-control" id="createPrice" name="price" required
                                    placeholder="Nhập giá tiền">
                                <input type="hidden" name="price" id="price_hidden" value="{{ old('price') }}">
                                <span class="text-danger mt-3" id="createPriceError"></span>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="createImgThumbnail" class="form-label">
                                    <span class="text-danger">*</span>
                                    Hình ảnh:
                                </label>
                                <input type="file" name="img_thumbnail" id="createImgThumbnail"
                                    class="form-control ">
                                <span class="text-danger mt-3" id="createImgThumbnailError"></span>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="createDescription" class="form-label">
                                    <span class="text-danger">*</span>
                                    Mô tả:
                                </label>
                                <textarea id="createDescription" class="form-control " name="description" rows="6"
                                    placeholder="Nhập mô tả"></textarea>
                                <span class="text-danger mt-3" id="createDescriptionError"></span>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" id="createFoodBtn">Thêm mới</button>
                </div>
            </div>
        </div>
    </div>
    {{-- modal update --}}
    <div class="modal fade" id="updateFoodModal" tabindex="-1" aria-labelledby="updateFoodModalLabel"
        aria-hidden="true">
        <input type="hidden" id="updateFoodId" name="food_id">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateFoodModalLabel">
                        Cập nhật đồ ăn
                        <span class="badge bg-primary-subtle text-primary fs-11" id="spanDefault"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateFoodForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <input type="checkbox" hidden class="form-control" name="is_active" value="1" checked>
                            <div class="col-md-6 mb-3">
                                <label for="updateName" class="form-label">
                                    <span class="text-danger">*</span>
                                    Tên món ăn:
                                </label>
                                <input type="text" class="form-control" id="updateName" name="name" required
                                    placeholder="Nhập tên món ăn">
                                <span class="text-danger mt-3" id="updateNameError"></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="updateType" class="form-label">
                                    <span class="text-danger">*</span>
                                    Loại đồ ăn:
                                </label>
                                <select name="type" id="updateType" class="form-select">
                                    <option disabled selected>Chọn loại đồ ăn</option>
                                    <option value="Đồ ăn">
                                        Đồ ăn
                                    </option>
                                    <option value="Đồ uống">
                                        Đồ uống
                                    </option>
                                    <option value="Khác">
                                        Khác
                                    </option>
                                </select>
                                <span class="text-danger mt-3" id="updateTypeError"></span>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="updatePrice" class="form-label">
                                    <span class="text-danger">*</span>
                                    Giá tiền:
                                </label>
                                <input type="tel" class="form-control" id="updatePrice" name="price"
                                    placeholder="Nhập giá tiền">
                                <span class="text-danger mt-3" id="updatePriceError"></span>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="updateImgThumbnail" class="form-label">
                                    <span class="text-danger">*</span> Hình ảnh:
                                </label>
                                <input type="file" name="img_thumbnail" id="updateImgThumbnail" class="form-control">
                                <img id="previewImgThumbnail" src="" alt="Ảnh xem trước" class="mt-2"
                                    style="max-width: 150px; display: none;">
                                <span class="text-danger mt-3" id="updateImgThumbnailError"></span>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="updateDescription" class="form-label">
                                    <span class="text-danger">*</span>
                                    Mô tả:
                                </label>
                                <textarea id="updateDescription" class="form-control " name="description" rows="6"
                                    placeholder="Nhập mô tả"></textarea>
                                <span class="text-danger mt-3" id="updateDescriptionError"></span>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" id="updateFoodBtn">Cập nhật</button>
                </div>
            </div>
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
    <script src="{{ asset('assets/js/food/index.js') }}">
        // console.log($appURL);
    </script>
    <script>
        $(document).ready(function() {
            // Xử lý sự kiện change cho checkbox .changeActive
            $(document).on("change", ".changeActive", function() {
                let foodId = $(this).data("food-id");
                let is_active = $(this).is(":checked") ? 1 : 0;

                // Swal.fire({
                //     title: "Đang xử lý...",
                //     text: "Vui lòng chờ trong giây lát.",
                //     allowOutsideClick: false,
                //     didOpen: () => {
                //         Swal.showLoading();
                //     },
                // });

                // Gửi AJAX cập nhật trạng thái
                $.ajax({
                    url: "{{ route('food.change-active') }}", // route api
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: foodId,
                        is_active: is_active,
                    },
                    success: function(response) {
                        if (response.success) {
                            let checkbox = $(`[data-food-id="${foodId}"]`);
                            checkbox.prop("checked", parseInt(response.data.is_active));

                            Swal.fire({
                                icon: "success",
                                title: "Thành công!",
                                text: "Trạng thái hoạt động đã được cập nhật.",
                                confirmButtonText: "Đóng",
                                timer: 3000,
                                timerProgressBar: true,
                            });
                            // location.reload();
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Lỗi!",
                                text: response.message || "Có lỗi xảy ra.",
                                confirmButtonText: "Đóng",
                            });
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText); // Debug lỗi nếu có
                        Swal.fire({
                            icon: "error",
                            title: "Lỗi!",
                            text: "Có lỗi xảy ra khi cập nhật trạng thái.",
                            confirmButtonText: "Đóng",
                        });

                        let checkbox = $(`[data-food-id="${foodId}"]`);
                        checkbox.prop("checked", !is_active);
                    },
                });

                console.log("Đã gửi yêu cầu thay đổi trạng thái.");
            });
        });
    </script>

@endsection
