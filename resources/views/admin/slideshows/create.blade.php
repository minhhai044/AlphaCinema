@extends('admin.layouts.master')

@section('title', 'Thêm mới Silde Show')


@section('content')
    <form action="{{ route('admin.slideshows.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Thêm slide show</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.slideshows.index') }}">Danh sách</a></li>
                            <li class="breadcrumb-item active">Thêm mới</li>
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
                            <div class="col-md-12 d-flex justify-content-between">
                                <label for="" class="form-label"></label>
                                <button type="button" class="btn btn-primary" onclick="addSlide()">Thêm ảnh</button>
                            </div>

                            <div id="slide_list" class="col-md-12">
                                <!-- Các phần tử slide sẽ được thêm vào đây -->
                            </div>

                        </div>
                        <!-- end card body -->
                    </div>
                    
                    <!-- end card -->
                </div> <!-- end col -->
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
                        <button type="submit" class="btn btn-primary mx-1">Thêm mới</button>
                    </div>
                </div>
            </div>
            <!--end col-->
        </div>

    </form>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            let slideCount = 0;
            const minSlideItems = 3;
            const maxSlideItems = 10;

            const slideList = document.getElementById('slide_list');

            for (let i = 0; i < minSlideItems; i++) {
                addSlide(i);
            }


            function addSlide(index) {
                if (slideCount >= maxSlideItems) {
                    showAlert('warning', 'Chỉ được thêm tối đa 8 ảnh', 'AlphaCinema thông báo');
                    return;
                }


                const id = 'gen_' + Math.random().toString(36).substring(2, 15).toLowerCase();


                const html = `
                    <div class="col-md-12 mb-3" id="${id}_item">
                        <div class="d-flex">
                            <div class="mt-2" style="width: 100%;">
                                <div class="border rounded">
                                    <div class="d-flex p-2">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="bg-light">
                                                <img id="preview_${index}" src="" style="width: 105px; height: 45px">
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="pt-1" style="width: 80%;">
                                                <input type="file" id="img_thumbnail_${index}" name="img_thumbnail[]" required
                                                        class="form-control" onchange="previewImg(this, ${index})">
                                            </div>
                                        </div>
                                        <div class="mt-1">
                                            <button type="button" class="btn btn-danger remove-btn">
                                                <span class="bx bx-trash"></span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;


                slideList.insertAdjacentHTML('beforeend', html);

                // Gán sự kiện cho nút xóa và select box
                slideList.querySelector(`#${id}_item .remove-btn`).addEventListener('click', function() {
                    removeSlide(`${id}_item`);
                });

                slideCount++;
                console.log();

            }

            // Hàm xem trước hình ảnh
            window.previewImg = function (input, id) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const preview = document.getElementById(`preview_${id}`);
                        preview.src = e.target.result;
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            };

            function removeSlide(id) {
                if (slideCount > minSlideItems) {
                    if (confirm('Bạn có chắc muốn xóa không?')) {

                    const element = document.getElementById(id);
                    element.style.transition = 'opacity 0.5s ease';
                    element.style.opacity = '0';

                    setTimeout(() => {
                        element.remove();
                        slideCount--;
                    }, 350);
                }
                } else {
                    showAlert('warning', 'Phải có ít nhất 3 ảnh', 'AlphaCinema thông báo');
                }
            }

            document.querySelector('button[onclick="addSlide()"]').addEventListener('click', function() {
                addSlide(slideCount);
            });
            
        });
    </script>
@endsection
