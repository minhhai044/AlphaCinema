@extends('admin.layouts.master')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <h1 class="card-title">Tạo mới phim</h1>
            <a href="{{ route('admin.movies.index') }}" class="btn btn-primary mb-3">Quay lại</a>
            <form action="{{ route('admin.movies.store') }}" method="POST" enctype="multipart/form-data"
                class="custom-validation">
                @csrf
                <div class="row">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0 font-size-18">MOVIES</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Movies</a></li>
                                        <li class="breadcrumb-item active">Table Movies</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- Khối 9/12 -->
                    <div class="col-lg-9">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Tên phim</label>
                                            <input type="text" name="name" class="form-control">
                                            @error('name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Đường dẫn phim</label>
                                            <input type="text" name="slug" class="form-control">
                                            @error('slug')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Danh mục phim</label>
                                            <input type="text" name="category" class="form-control">
                                            @error('category')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Ảnh </label>
                                            <input type="file" name="img_thumbnail" class="form-control">
                                            @error('img_thumbnail')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Phiên bản phim </label>
                                            <input type="text" name="movie_versions" class="form-control">

                                            @error('movie_versions')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Mô tả </label>
                                            <textarea name="description" class="form-control"></textarea>
                                            @error('description')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Tác giả</label>
                                            <input type="text" name="director" class="form-control">
                                            @error('director')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">URL</label>
                                            <input type="text" name="trailer_url" class="form-control">
                                            @error('trailer_url')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Thời lượng phim</label>
                                            <input type="number" name="duration" min="1" step="1"
                                                class="form-control" placeholder="Nhập số phút">
                                            @error('duration')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-4">
                                            <label>Ngày trình chiếu</label>
                                            @error('end_date')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            @error('release_date')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <div class="input-daterange input-group" id="datepicker6"
                                                data-date-format="dd M, yyyy" data-date-autoclose="true"
                                                data-provide="datepicker" data-date-container="#datepicker6">
                                                <input type="datetime-local" class="form-control" name="release_date"
                                                    placeholder="Start Date" />

                                                <input type="datetime-local" class="form-control" name="end_date"
                                                    placeholder="End Date" />

                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Phụ phí</label>
                                            <input type="number" name="surcharge" class="form-control">
                                            @error('surcharge')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Phiên bản phim</label>
                                            <input type="text" name="movie_genres" class="form-control">
                                            @error('movie_genres')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Đánh giá</label>
                                            <input type="number" name="rating" class="form-control">
                                            @error('rating')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Khối 3/12 -->
                    <!-- Khối 3/12 -->
                    <div class="col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <!-- Trạng thái Active -->
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div class="square-switch">
                                        <input type="checkbox" id="square-switch3" switch="bool" checked />
                                        <label for="square-switch3" data-on-label="Yes" data-off-label="No"></label>
                                    </div>
                                    <span>Active</span>
                                </div>
                                <input type="hidden" name="is_active" id="is_active" value="1">

                                <!-- Trạng thái Hot -->
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div class="square-switch">
                                        <input type="checkbox" id="square-switch_hot" switch="bool" checked />
                                        <label for="square-switch_hot" data-on-label="Yes" data-off-label="No"></label>
                                    </div>
                                    <span>Hot</span>
                                </div>
                                <input type="hidden" name="is_hot" id="is_hot" value="1">

                                <!-- Trạng thái Special -->
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div class="square-switch">
                                        <input type="checkbox" id="square-switch_special" switch="bool" checked />
                                        <label for="square-switch_special" data-on-label="Yes"
                                            data-off-label="No"></label>
                                    </div>
                                    <span>Special</span>
                                </div>
                                <input type="hidden" name="is_special" id="is_special" value="1">

                                <!-- Trạng thái Publish -->
                                <div class="d-flex align-items-center gap-2">
                                    <div class="square-switch">
                                        <input type="checkbox" id="square-switch_publish" switch="bool" checked />
                                        <label for="square-switch_publish" data-on-label="Yes"
                                            data-off-label="No"></label>
                                    </div>
                                    <span>Publish</span>
                                </div>
                                <input type="hidden" name="is_publish" id="is_publish" value="1">
                            </div>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">
                                Submit
                            </button>
                            <button type="reset" class="btn btn-secondary waves-effect">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>

            </form>

        </div>
        <!-- end select2 -->
        <script>
            document.querySelectorAll('.square-switch input[type="checkbox"]').forEach(function(checkbox) {
                checkbox.addEventListener("change", function() {
                    let hiddenInput = document.getElementById(this.id.replace("square-switch_", "is_"));
                    hiddenInput.value = this.checked ? 1 : 0;
                });
            });
        </script>
    </div>
@endsection
