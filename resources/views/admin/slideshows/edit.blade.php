@extends('admin.layouts.master')

@section('title', 'Cập nhật SildeShow')


@section('content')
    <form action="{{ route('admin.slideshows.update', $slide->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Cập nhật SlideShow</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.slideshows.index') }}">Danh sách</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <!-- end page title -->

            <div class="row">
                <div class="col-lg-7">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Chọn ảnh</h4>
                        </div><!-- end card header -->

                        <div class="card-body">
                            <div class="d-flex mb-1 justify-content-between">
                                <p class="text-muted">Vui lòng chọn một hoặc nhiều hình ảnh để chỉnh sửa.</p>
                                <p class="btn btn-sm btn-primary fw-bold" id="add-row" style="cursor: pointer">Thêm ảnh</p>
                            </div>

                            <div class="my-3">
                                <table style="width: 100%;">
                                    <tbody id="img-table">
                                        @php
                                            $images = is_array($slide->img_thumbnail) ? $slide->img_thumbnail : json_decode($slide->img_thumbnail, true);
                                        @endphp

                                        @if (!empty($images))
                                            @foreach ($images as $key => $imgPath)
                                                <tr>
                                                    <td class="d-flex align-items-center justify-content-around">
                                                        <div style="width: 100%;">
                                                            <div class="border rounded">
                                                                <div class="d-flex p-2">
                                                                    <div class="flex-shrink-0 me-3">
                                                                        <div class="avatar-sm bg-light rounded">
                                                                            <img id="preview_{{ $key }}"
                                                                                src="{{ Storage::url($imgPath) }}"
                                                                                style="width: 45px; height: 45px; object-fit: cover;">
                                                                        </div>
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <div class="pt-1" style="width: 73%;">
                                                                            <input type="file" id="img_thumbnail_{{ $key }}"
                                                                                name="img_thumbnail[{{ $key }}]"
                                                                                class="form-control @error('img_thumbnail.' . $key) is-invalid @enderror"
                                                                                onchange="previewImg(this, {{ $key }})">
                                                                            <input type="hidden" name="existing_images[{{ $key }}]"
                                                                                value="{{ $imgPath }}">
                                                                            @error('img_thumbnail.' . $key)
                                                                                <div class="invalid-feedback">
                                                                                    {{ $message }}
                                                                                </div>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                    <div class="flex-shrink-0 ms-3">
                                                                        <button class="btn btn-sm btn-danger" type="button"
                                                                            onclick="removeRow(this)">Xóa</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- end card -->
                    </div>
                </div> 
                <!-- end col -->
                <div class="col-lg-5">
                    <div class="card">
                        <div class="card-body">
                            <label for="createDescription" class="form-label">
                                <span class="text-danger">*</span>
                                Mô tả:
                            </label>
                            <textarea id="createDescription" class="form-control " name="description"
                                placeholder="Nhập mô tả"></textarea>
                            <span class="text-danger mt-3" id="createDescriptionError"></span>
                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <!-- end card body -->

                        <input type="hidden" name="is_active" value="0">
                    </div>

                </div> <!-- end col -->
            </div>
            <!-- end row -->

        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <button type="submit" class="btn btn-primary mx-1">Lưu thay đổi</button>
                    </div>
                </div>
            </div>
            <!--end col-->
        </div>

    </form>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let rowCount = document.querySelectorAll('#img-table tr').length;

            document.getElementById('add-row').addEventListener('click', function () {
                const tableBody = document.getElementById('img-table');

                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                        <td class="d-flex align-items-center justify-content-around">
                            <div class="mt-2" style="width: 100%;">
                                <div class="border rounded">
                                    <div class="d-flex p-2">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar-sm bg-light rounded">
                                                <img id="preview_${rowCount}" src="" style="width: 45px; height: 45px; object-fit: cover;">
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="pt-1" style="width: 73%;">
                                                <input type="file" id="img_thumbnail_${rowCount}" name="img_thumbnail[new_${rowCount}]"
                                                       class="form-control" onchange="previewImg(this, ${rowCount})">
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0 ms-3">
                                            <button class="btn btn-sm btn-danger" type="button" onclick="removeRow(this)">Xóa</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>`;
                tableBody.appendChild(newRow);
                rowCount++;
            });
            window.previewImg = function (input, index) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        document.getElementById(`preview_${index}`).src = e.target.result;
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            };

            window.removeRow = function (item) {
                const row = item.closest('tr');
                row.remove();
            };
        });
    </script>
@endsection