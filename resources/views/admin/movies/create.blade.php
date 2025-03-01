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
                                            <label for="name" class="form-label">Tên phim
                                                <span class="required" style="color: red" style="color: red">*</span>
                                            </label>
                                            <input type="text" name="name" id="name"
                                                class="form-control {{ $errors->has('name') ? 'is-invalid' : (old('name') ? 'is-valid' : '') }}"
                                                value="{{ old('name') }}" placeholder="Nhập tên phim">

                                            <div class="{{ $errors->has('name') ? 'invalid-feedback' : 'valid-feedback' }}">
                                                @if ($errors->has('name'))
                                                    {{ $errors->first('name') }}
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <la class="form-label">slug
                                                <span class="required" style="color: red">*</span> </label>
                                                <input type="text" id="slug" name="slug"
                                                    class="form-control {{ $errors->has('slug') ? 'is-invalid' : (old('slug') ? 'is-valid' : '') }}"
                                                    value="{{ old('slug') }}" readonly>
                                                <div
                                                    class="{{ $errors->has('slug') ? 'invalid-feedback' : 'valid-feedback' }}">
                                                    @if ($errors->has('slug'))
                                                        {{ $errors->first('slug') }}
                                                    @endif
                                                </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Danh mục phim
                                                <span class="required" style="color: red">*</span> </label>
                                            <input type="text" name="category"
                                                class="form-control {{ $errors->has('category') ? 'is-invalid' : (old('category') ? 'is-valid' : '') }}"
                                                value="{{ old('category') }}">
                                            <div
                                                class="{{ $errors->has('category') ? 'invalid-feedback' : 'valid-feedback' }}">
                                                @if ($errors->has('category'))
                                                    {{ $errors->first('category') }}
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Ảnh
                                                <span class="required" style="color: red">*</span> </label>
                                            <input type="file" name="img_thumbnail"
                                                class="form-control {{ $errors->has('img_thumbnail') ? 'is-invalid' : (old('img_thumbnail') ? 'is-valid' : '') }}"
                                                value="{{ old('img_thumbnail') }}">
                                            <div
                                                class="{{ $errors->has('img_thumbnail') ? 'invalid-feedback' : 'valid-feedback' }}">
                                                @if ($errors->has('img_thumbnail'))
                                                    {{ $errors->first('img_thumbnail') }}
                                                @endif
                                            </div>
                                        </div>
                                        {{-- <div class="mb-3">
                                                <label class="form-label">Thể loại
                                                    <span class="required" style="color: red">*</span>  phim</label>
                                                <input type="checkbox" name="movie_genres[]" value="Action"> Hành động
                                                <input type="checkbox" name="movie_genres[]" value="Horror"> Kinh dị
                                                <input type="checkbox" name="movie_genres[]" value="Comedy"> Hài hước
                                                <div
                                                    class="{{ $errors->has('movie_genres') ? 'invalid-feedback' : 'valid-feedback' }}">
                                                    @if ($errors->has('movie_genres'))
                                                        {{ $errors->first('movie_genres') }}
                                                    @endif
                                                </div>
                                            </div> --}}

                                        <div class="mb-3">
                                            <label class="form-label">Thể loại phim
                                                <span class="required" style="color: red">*</span> </label>
                                            <div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox" name="movie_genres[]"
                                                        value="Action" id="genre_action"
                                                        {{ in_array('Action', old('movie_genres', [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="genre_action">Hành động</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox" name="movie_genres[]"
                                                        value="Horror" id="genre_horror"
                                                        {{ in_array('Horror', old('movie_genres', [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="genre_horror">Kinh dị</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox" name="movie_genres[]"
                                                        value="Comedy" id="genre_comedy"
                                                        {{ in_array('Comedy', old('movie_genres', [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="genre_comedy">Hài hước</label>
                                                </div>
                                            </div>
                                            <div
                                                class="{{ $errors->has('movie_genres') ? 'invalid-feedback' : 'valid-feedback' }} d-block">
                                                @if ($errors->has('movie_genres'))
                                                    {{ $errors->first('movie_genres') }}
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Mô tả
                                                <span class="required" style="color: red">*</span> </label>
                                            <textarea name="description"
                                                class="form-control {{ $errors->has('description') ? 'is-invalid' : (old('description') ? 'is-valid' : '') }}"
                                                value="{{ old('description') }}"></textarea>
                                            <div
                                                class="{{ $errors->has('description') ? 'invalid-feedback' : 'valid-feedback' }}">
                                                @if ($errors->has('description'))
                                                    {{ $errors->first('description') }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Tác giả
                                                <span class="required" style="color: red">*</span> </label>
                                            <input type="text" name="director"
                                                class="form-control {{ $errors->has('director') ? 'is-invalid' : (old('director') ? 'is-valid' : '') }}"
                                                value="{{ old('director') }}">
                                            <div
                                                class="{{ $errors->has('director') ? 'invalid-feedback' : 'valid-feedback' }}">
                                                @if ($errors->has('director'))
                                                    {{ $errors->first('director') }}
                                                @endif
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">URL
                                                <span class="required" style="color: red">*</span> </label>
                                            <input type="text" name="trailer_url"
                                                class="form-control {{ $errors->has('trailer_url') ? 'is-invalid' : (old('trailer_url') ? 'is-valid' : '') }}"
                                                value="{{ old('trailer_url') }}">
                                            <div
                                                class="{{ $errors->has('trailer_url') ? 'invalid-feedback' : 'valid-feedback' }}">
                                                @if ($errors->has('trailer_url'))
                                                    {{ $errors->first('trailer_url') }}
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Thời lượng phim
                                                <span class="required" style="color: red">*</span></label>
                                            <input type="number" name="duration" min="1" step="1"
                                                class="form-control {{ $errors->has('duration') ? 'is-invalid' : (old('duration') ? 'is-valid' : '') }}"
                                                value="{{ old('duration') }}" placeholder="Nhập số phút">
                                            <div class="{{ $errors->has('duration') ? 'invalid-feedback' : 'valid-feedback' }}"
                                                value="{{ old('duration') }}">
                                                @if ($errors->has('duration'))
                                                    {{ $errors->first('duration') }}
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mb-4">
                                            <label>Ngày trình chiếu
                                                <span class="required" style="color: red" style="color: red">*</span>
                                            </label>
                                            <div
                                                class="{{ $errors->has('release_date') ? 'invalid-feedback' : 'valid-feedback' }}">
                                                @if ($errors->has('release_date'))
                                                    {{ $errors->first('release_date') }}
                                                @endif
                                            </div>
                                            <div
                                                class="{{ $errors->has('end_date') ? 'invalid-feedback' : 'valid-feedback' }}">
                                                @if ($errors->has('end_date'))
                                                    {{ $errors->first('end_date') }}
                                                @endif
                                            </div>
                                            <div class="input-daterange input-group" id="datepicker6"
                                                data-date-format="dd M, yyyy" data-date-autoclose="true"
                                                data-provide="datepicker" data-date-container="#datepicker6">
                                                <input type="date"
                                                    class="form-control {{ $errors->has('release_date') ? 'is-invalid' : (old('release_date') ? 'is-valid' : '') }}"
                                                    value="{{ old('release_date') }}" name="release_date"
                                                    placeholder="Start Date" />

                                                <input type="date"
                                                    class="form-control {{ $errors->has('end_date') ? 'is-invalid' : (old('end_date') ? 'is-valid' : '') }}"
                                                    value="{{ old('end_date') }}" name="end_date"
                                                    placeholder="End Date" />

                                            </div>
                                        </div>

                                        {{-- <div class="mb-3">
                                                <label class="form-label">Phiên bả
                                                    <span class="required" style="color: red">*</span> n phim</label>
                                                <input type="checkbox" name="movie_versions[]" value="2D"> 2D
                                                <input type="checkbox" name="movie_versions[]" value="3D"> 3D
                                                <input type="checkbox" name="movie_versions[]" value="4D"> 4D
                                                <div
                                                    class="{{ $errors->has('movie_versions') ? 'invalid-feedback' : 'valid-feedback' }}">
                                                    @if ($errors->has('movie_versions'))
                                                        {{ $errors->first('movie_versions') }}
                                                    @endif
                                                </div>
                                            </div> --}}
                                        <div class="mb-3">
                                            <label class="form-label">Phiên bản phim
                                                <span class="required" style="color: red">*</span> </label>
                                            <div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="movie_versions[]" value="2D" id="version_2d"
                                                        {{ in_array('2D', old('movie_versions', [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="version_2d">2D</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="movie_versions[]" value="3D" id="version_3d"
                                                        {{ in_array('3D', old('movie_versions', [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="version_3d">3D</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="movie_versions[]" value="4D" id="version_4d"
                                                        {{ in_array('4D', old('movie_versions', [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="version_4d">4D</label>
                                                </div>
                                            </div>
                                            <div
                                                class="{{ $errors->has('movie_versions') ? 'invalid-feedback' : 'valid-feedback' }} d-block">
                                                @if ($errors->has('movie_versions'))
                                                    {{ $errors->first('movie_versions') }}
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mb-3" id="surcharge_container" style="display: none;">
                                            <label class="form-label">Phụ phí
                                                <span class="required" style="color: red">*</span> </label>
                                            <input type="number" name="surcharge"
                                                class="form-control {{ $errors->has('surcharge') ? 'is-invalid' : (old('surcharge') ? 'is-valid' : '') }}"
                                                value="{{ old('surcharge') }}">
                                            <div
                                                class="{{ $errors->has('surcharge') ? 'invalid-feedback' : 'valid-feedback' }}">
                                                @if ($errors->has('surcharge'))
                                                    {{ $errors->first('surcharge') }}
                                                @endif
                                            </div>
                                        </div>


                                        <div class="mb-3">
                                            <label class="form-label">Đánh giá
                                                <span class="required" style="color: red">*</span>
                                            </label>
                                            <input type="number" name="rating"
                                                class="form-control {{ $errors->has('rating') ? 'is-invalid' : (old('rating') ? 'is-valid' : '') }}"
                                                value="{{ old('rating') }}">
                                            <div
                                                class="{{ $errors->has('rating') ? 'invalid-feedback' : 'valid-feedback' }}">
                                                @if ($errors->has('rating'))
                                                    {{ $errors->first('rating') }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Khối 3/12 -->
                    <div class="col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <!-- Active -->
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div class="square-switch">
                                        <input type="checkbox" id="square-switch_active" switch="bool"
                                            {{ old('is_active', 1) ? 'checked' : '' }} />
                                        <label for="square-switch_active" data-on-label="Yes"
                                            data-off-label="No"></label>
                                    </div>
                                    <span>Active</span>
                                </div>
                                <input type="hidden" name="is_active" id="is_active"
                                    value="{{ old('is_active', 1) }}">

                                <!-- Hot -->

                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div class="square-switch">
                                        <input type="checkbox" id="square-switch_hot" switch="bool"
                                            {{ old('is_hot', 1) ? 'checked' : '' }} />
                                        <label for="square-switch_hot" data-on-label="Yes" data-off-label="No"></label>
                                    </div>
                                    <span>Hot</span>
                                </div>
                                <input type="hidden" name="is_hot" id="is_hot" value="{{ old('is_hot', 1) }}">

                                <!-- Special -->
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div class="square-switch">
                                        <input type="checkbox" id="square-switch_special" switch="bool"
                                            {{ old('is_special', 1) ? 'checked' : '' }} />
                                        <label for="square-switch_special" data-on-label="Yes"
                                            data-off-label="No"></label>
                                    </div>
                                    <span>Special</span>
                                </div>
                                <input type="hidden" name="is_special" id="is_special"
                                    value="{{ old('is_special', 1) }}">

                                <!-- Publish -->
                                <div class="d-flex align-items-center gap-2">
                                    <div class="square-switch">
                                        <input type="checkbox" id="square-switch_publish" switch="bool"
                                            {{ old('is_publish', 1) ? 'checked' : '' }} />
                                        <label for="square-switch_publish" data-on-label="Yes"
                                            data-off-label="No"></label>
                                    </div>
                                    <span>Publish</span>
                                </div>
                                <input type="hidden" name="is_publish" id="is_publish"
                                    value="{{ old('is_publish', 1) }}">
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
            // Cập nhật giá trị của hidden input cho các checkbox
            document.querySelectorAll('.square-switch input[type="checkbox"]').forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    var hiddenInput = document.getElementById(this.id.replace('square-switch', 'is'));
                    if (hiddenInput) {
                        hiddenInput.value = this.checked ? '1' : '0';
                    }
                });
            });

            document.addEventListener('DOMContentLoaded', function() {
                var publishCheckbox = document.getElementById('square-switch_special');
                var surchargeContainer = document.getElementById('surcharge_container');
                // Lấy thẻ input trong container "Phụ phí"
                var surchargeInput = surchargeContainer.querySelector('input[name="surcharge"]');

                function toggleSurchargeInput() {
                    console.log('toggleSurchargeInput triggered, publishCheckbox.checked:', publishCheckbox.checked);
                    if (publishCheckbox.checked) {
                        surchargeContainer.style.display = 'block';
                        surchargeInput.disabled = false; // Cho phép nhập liệu, gửi dữ liệu lên server
                    } else {
                        surchargeContainer.style.display = 'none';
                        surchargeInput.disabled = true; // Vô hiệu hóa input, không gửi dữ liệu
                    }
                }

                // Gọi khi tải trang để đảm bảo trạng thái ban đầu đúng
                toggleSurchargeInput();

                // Lắng nghe sự kiện thay đổi của checkbox (dùng "change" để đảm bảo trạng thái cập nhật)
                publishCheckbox.addEventListener('change', toggleSurchargeInput);
            });

            function generateUUID() {
                return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                    const r = Math.random() * 16 | 0,
                        v = c === 'x' ? r : (r & 0x3 | 0x8);
                    return v.toString(16);
                });
            }

            function convertToSlug(text) {
                return text.toLowerCase()
                    .normalize("NFD").replace(/[\u0300-\u036f]/g, "") // Loại bỏ dấu tiếng Việt
                    .replace(/[^a-z0-9 -]/g, '') // Xóa ký tự đặc biệt
                    .replace(/\s+/g, '-') // Thay khoảng trắng bằng -
                    .replace(/-+/g, '-'); // Xóa dấu gạch ngang dư thừa
            }

            document.getElementById('name').addEventListener('input', function() {
                let name = this.value.trim();
                if (name !== '') {
                    let slug = convertToSlug(name) + '-' + generateUUID().substring(0, 8); // Chỉ lấy 8 ký tự UUID
                    document.getElementById('slug').value = slug;
                } else {
                    document.getElementById('slug').value = '';
                }
            });
        </script>

    </div>
@endsection
