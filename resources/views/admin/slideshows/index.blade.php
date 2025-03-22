@extends('admin.layouts.master')

@section('title', 'Slide Shows')


@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Slideshows</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Danh sách</a></li>
                        <li class="breadcrumb-item active">Slideshows</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="card-title mb-0"> Danh sách Slideshow </h5>
                    <a href="{{ route('admin.slideshows.create') }}" class="btn btn-primary">Thêm mới</a>
                </div>
                <div class="card-body">
                    <table id="example" class="table table-bordered dt-responsive nowrap align-middle w-100">
                        <thead class='table-light'>
                            <tr>
                                <th style="width: 40px;">#</th>
                                <th>Hình ảnh</th>
                                <th>Mô tả ngắn</th>
                                <th style="width: 100px;">Hoạt động</th>
                                <th style="width: 155px;">Chức năng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($slideshows as $slideshow)
                                <tr>
                                    <td>{{ $slideshow->id }}</td>
                                    <td class="text-center" style="width: 400px;">
                                        <div class="overflow-x-auto">
                                            <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 10px;">
                                                @if (is_array($slideshow->img_thumbnail))
                                                    @foreach ($slideshow->img_thumbnail as $image)
                                                        @if (filter_var($image, FILTER_VALIDATE_URL))
                                                            <img src="{{ $image }}" width="100px"
                                                                alt="Slideshow image" class="rounded-2">
                                                        @else
                                                            <img src="{{ Storage::url($image) }}" width="100px"
                                                                alt="Slideshow image" class="rounded-2">
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <p>No image !</p>
                                                @endif
                                            </div>

                                        </div>

                                    </td>
                                    <td>{{ $slideshow->description }}</td>
                                    <td>
                                        <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
                                            <input class="form-check-input switch-is-active changeActive" name="is_active"
                                                type="checkbox" role="switch" data-slideshow-id="{{ $slideshow->id }}"
                                                @checked($slideshow->is_active) onclick="return checkActive(this)">
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.slideshows.edit', $slideshow) }}">
                                            <button title="Cập nhật" class="btn btn-warning btn-sm " type="button"><i
                                                    class="fas fa-edit"></i></button>
                                        </a>
                                        @if (!$slideshow->is_active)
                                            <form action="{{ route('admin.slideshows.destroy', $slideshow) }}"
                                                method="POST" id="delete-food-{{ $slideshow->id }}" class="d-inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Bạn có muốn xóa không')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div><!--end col-->
    </div><!--end row-->
@endsection

@php
    $appUrl = env('APP_URL');
@endphp

@section('script')

    <script>
        var appURL = @json($appUrl);
        $(document).ready(function() {
            // Set về trạng thái mặc định khi load trang
            $('.changeActive').each(function() {
                let isActive = $(this).is(':checked') ? 1 : 0;
                $(this).data('current-active', isActive);
            });

            // Xử lý sự kiện thay đổi trạng thái
            $('.changeActive').on('change', function() {
                let currentCheckbox = $(this);
                let slideshowId = currentCheckbox.data('slideshow-id');
                let is_active = currentCheckbox.is(':checked') ? 1 : 0;

                // Kiểm tra nếu checkbox đang bật mà bị tắt chính nó
                if (!is_active && currentCheckbox.data('current-active') === 1) {
                    alert('Bạn không thể tắt slideshow đang hoạt động!');
                    currentCheckbox.prop('checked', true); // Hoàn tác thay đổi
                    return;
                }

                // Xác nhận nếu người dùng muốn thay đổi
                if (!confirm('Bạn có chắc muốn thay đổi?')) {
                    currentCheckbox.prop('checked', !currentCheckbox.is(':checked')); // Hoàn tác thay đổi
                    return;
                }

                // Tắt tất cả các checkbox khác nếu bật checkbox hiện tại
                if (is_active === 1) {
                    $('.changeActive').not(currentCheckbox).prop('checked', false);
                }

                // Gửi yêu cầu AJAX để cập nhật trạng thái
                $.ajax({
                    url: "{{ route('slideshows.change-active') }}",
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: slideshowId,
                        is_active: is_active
                    },
                    success: function(response) {
                        if (response.success) {
                            // Cập nhật trạng thái hiện tại
                            $('.changeActive').data('current-active',
                                0); // Reset trạng thái cho tất cả
                            if (is_active === 1) {
                                currentCheckbox.data('current-active',
                                    1); // Đặt trạng thái mới cho checkbox đang bật
                            }
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công!',
                                text: 'Trạng thái hoạt động đã được cập nhật.',
                                confirmButtonText: 'Đóng',
                                timer: 3000,
                                timerProgressBar: true,
                            }).then(() => location.reload());
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi!',
                                text: 'Có lỗi xảy ra, vui lòng thử lại.',
                                confirmButtonText: 'Đóng',
                                timer: 3000,
                                timerProgressBar: true,
                            });
                            console.error(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi!',
                            text: 'Lỗi kết nối hoặc server không phản hồi.',
                            confirmButtonText: 'Đóng',
                            timer: 3000,
                            timerProgressBar: true,
                        });
                        console.error(error);

                        // Hoàn tác thay đổi nếu server phản hồi lỗi
                        currentCheckbox.prop('checked', !currentCheckbox.is(':checked'));
                    }
                });
            });
        });
    </script>
@endsection
