@extends('admin.layouts.master')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <h1 class="card-title">Tạo mới phim</h1>
            {{-- <a href="{{ route('admin.movies.index') }}" class="btn btn-primary mb-3">Quay lại</a> --}}
            <form action="{{ route('admin.movies.store') }}" method="POST" enctype="multipart/form-data"
                class="custom-validation">
                @csrf
                <div class="row">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="{{ route('admin.movies.index') }}">Danh sách
                                                phim</a></li>
                                        <li class="breadcrumb-item active">Thêm mới</li>
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
                                                <span class="required" style="color: red">*</span>
                                            </label>
                                            <input type="text" name="name" id="name"
                                                class="form-control {{ $errors->has('name') ? 'is-invalid' : (old('name') ? 'is-valid' : '') }}"
                                                value="{{ old('name') }}" placeholder="Nhập tên phim">
                                            <div class="{{ $errors->has('name') ? 'invalid-feedback' : 'valid-feedback' }}">
                                                @if ($errors->has('name'))
                                                    <span class="text-danger fw-medium">{{ $errors->first('name') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Slug
                                                <span class="required" style="color: red">*</span></label>
                                            <input type="text" id="slug" name="slug"
                                                class="form-control {{ $errors->has('slug') ? 'is-invalid' : (old('slug') ? 'is-valid' : '') }}"
                                                value="{{ old('slug') }}" readonly>
                                            <div class="{{ $errors->has('slug') ? 'invalid-feedback' : 'valid-feedback' }}">
                                                @if ($errors->has('slug'))
                                                    <span class="text-danger fw-medium">{{ $errors->first('slug') }}</span>
                                                @endif
                                            </div>
                                        </div>



                                        <div class="mb-3">
                                            <label class="form-label">Diễn viên
                                                <span class="required" style="color: red">*</span></label>
                                            <input type="text" name="category"
                                                class="form-control {{ $errors->has('category') ? 'is-invalid' : (old('category') ? 'is-valid' : '') }}"
                                                value="{{ old('category') }}">
                                            <div
                                                class="{{ $errors->has('category') ? 'invalid-feedback' : 'valid-feedback' }}">
                                                @if ($errors->has('category'))
                                                    <span
                                                        class="text-danger fw-medium">{{ $errors->first('category') }}</span>
                                                @endif
                                            </div>
                                        </div>




                                        <div class="mb-3">
                                            <label class="form-label">Ảnh
                                                <span class="required" style="color: red">*</span></label>
                                            <input type="file" name="img_thumbnail"
                                                class="form-control {{ $errors->has('img_thumbnail') ? 'is-invalid' : (old('img_thumbnail') ? 'is-valid' : '') }}"
                                                value="{{ old('img_thumbnail') }}">
                                            <div
                                                class="{{ $errors->has('img_thumbnail') ? 'invalid-feedback' : 'valid-feedback' }}">
                                                @if ($errors->has('img_thumbnail'))
                                                    <span
                                                        class="text-danger fw-medium">{{ $errors->first('img_thumbnail') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="branch_ids" class="form-label">Chi nhánh chiếu phim
                                                <span class="required" style="color: red">*</span>
                                            </label>
                                            <select name="branch_ids[]" class="form-control select2" multiple
                                                id="branch_ids" style="width: 100%;">
                                                @foreach ($branches as $branch)
                                                    <option value="{{ $branch->id }}"
                                                        {{ in_array($branch->id, old('branch_ids', $selectedBranches ?? [])) ? 'selected' : '' }}>
                                                        {{ $branch->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div
                                                class="{{ $errors->has('branch_ids') ? 'invalid-feedback' : 'valid-feedback' }} d-block">
                                                @if ($errors->has('branch_ids'))
                                                    <span
                                                        class="text-danger fw-medium">{{ $errors->first('branch_ids') }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Thể loại phim
                                                <span class="required" style="color: red">*</span></label>
                                            <select name="movie_genres[]" id="movie_genres"
                                                class="form-control {{ $errors->has('movie_genres') ? 'is-invalid' : (old('movie_genres') ? 'is-valid' : '') }}"
                                                multiple>
                                                <option value="Action"
                                                    {{ in_array('Action', old('movie_genres', [])) ? 'selected' : '' }}>
                                                    Hành động</option>
                                                <option value="Horror"
                                                    {{ in_array('Horror', old('movie_genres', [])) ? 'selected' : '' }}>
                                                    Kinh dị</option>
                                                <option value="Comedy"
                                                    {{ in_array('Comedy', old('movie_genres', [])) ? 'selected' : '' }}>Hài
                                                    hước</option>
                                            </select>
                                            <div
                                                class="{{ $errors->has('movie_genres') ? 'invalid-feedback' : 'valid-feedback' }} d-block">
                                                @if ($errors->has('movie_genres'))
                                                    <span
                                                        class="text-danger fw-medium">{{ $errors->first('movie_genres') }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Đánh giá <span class="required"
                                                    style="color: red">*</span></label>
                                            <div class="star-rating d-flex gap-1"
                                                style="font-size: 1.5rem; cursor: pointer;">
                                                <i class="bi bi-star" data-rating="1"></i>
                                                <i class="bi bi-star" data-rating="2"></i>
                                                <i class="bi bi-star" data-rating="3"></i>
                                                <i class="bi bi-star" data-rating="4"></i>
                                                <i class="bi bi-star" data-rating="5"></i>
                                            </div>
                                            <!-- Hidden input để gửi giá trị rating -->
                                            <input type="hidden" name="rating" id="rating-value"
                                                class="form-control {{ $errors->has('rating') ? 'is-invalid' : (old('rating') ? 'is-valid' : '') }}"
                                                value="{{ old('rating') }}">
                                            <div
                                                class="{{ $errors->has('rating') ? 'invalid-feedback' : 'valid-feedback' }} d-block">
                                                @if ($errors->has('rating'))
                                                    <span
                                                        class="text-danger fw-medium">{{ $errors->first('rating') }}</span>
                                                @elseif (old('rating'))
                                                    Đã chọn {{ old('rating') }} sao
                                                @endif
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Đạo diễn
                                                <span class="required" style="color: red">*</span></label>
                                            <input type="text" name="director"
                                                class="form-control {{ $errors->has('director') ? 'is-invalid' : (old('director') ? 'is-valid' : '') }}"
                                                value="{{ old('director') }}">
                                            <div
                                                class="{{ $errors->has('director') ? 'invalid-feedback' : 'valid-feedback' }}">
                                                @if ($errors->has('director'))
                                                    <span
                                                        class="text-danger fw-medium">{{ $errors->first('director') }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">URL Youtube
                                                <span class="required" style="color: red">*</span></label>
                                            <input type="text" name="trailer_url"
                                                class="form-control {{ $errors->has('trailer_url') ? 'is-invalid' : (old('trailer_url') ? 'is-valid' : '') }}"
                                                value="{{ old('trailer_url') }}">
                                            <div
                                                class="{{ $errors->has('trailer_url') ? 'invalid-feedback' : 'valid-feedback' }}">
                                                @if ($errors->has('trailer_url'))
                                                    <span
                                                        class="text-danger fw-medium">{{ $errors->first('trailer_url') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Thời lượng phim
                                                <span class="required" style="color: red">*</span></label>
                                            <input type="number" name="duration" step="1"
                                                class="form-control {{ $errors->has('duration') ? 'is-invalid' : (old('duration') ? 'is-valid' : '') }}"
                                                value="{{ old('duration') }}" placeholder="Nhập số phút">
                                            <div
                                                class="{{ $errors->has('duration') ? 'invalid-feedback' : 'valid-feedback' }}">
                                                @if ($errors->has('duration'))
                                                    <span
                                                        class="text-danger fw-medium">{{ $errors->first('duration') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label>Ngày trình chiếu <span class="required"
                                                    style="color: red">*</span></label>

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

                                            {{-- Đưa phần hiển thị lỗi ra ngoài input-group --}}
                                            @if ($errors->has('release_date') || $errors->has('end_date'))
                                                <div class="invalid-feedback d-block">
                                                    @if ($errors->has('release_date'))
                                                        <span
                                                            class="text-danger fw-medium d-block">{{ $errors->first('release_date') }}</span>
                                                    @endif
                                                    @if ($errors->has('end_date'))
                                                        <span
                                                            class="text-danger fw-medium d-block">{{ $errors->first('end_date') }}</span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>


                                        <div class="mb-3">
                                            <label class="form-label">Phiên bản phim
                                                <span class="required" style="color: red">*</span>
                                            </label>
                                            <select name="movie_versions[]" id="movie_versions"
                                                class="form-control {{ $errors->has('movie_versions') ? 'is-invalid' : (old('movie_versions') ? 'is-valid' : '') }}"
                                                multiple>
                                                <option value="2D"
                                                    {{ in_array('2D', old('movie_versions', [])) ? 'selected' : '' }}>2D
                                                </option>
                                                <option value="3D"
                                                    {{ in_array('3D', old('movie_versions', [])) ? 'selected' : '' }}>3D
                                                </option>
                                                <option value="4D"
                                                    {{ in_array('4D', old('movie_versions', [])) ? 'selected' : '' }}>4D
                                                </option>
                                            </select>
                                            <div
                                                class="{{ $errors->has('movie_versions') ? 'invalid-feedback' : 'valid-feedback' }} d-block">
                                                @if ($errors->has('movie_versions'))
                                                    <span
                                                        class="text-danger fw-medium">{{ $errors->first('movie_versions') }}</span>
                                                @endif
                                            </div>
                                        </div>



                                        <div class="mb-3" id="surcharge_container" style="display: none;">
                                            <label class="form-label">Phụ phí </span>
                                            </label>
                                            <input type="number" name="surcharge"
                                                class="form-control {{ $errors->has('surcharge') ? 'is-invalid' : (old('surcharge') ? 'is-valid' : '') }}"
                                                value="{{ old('surcharge') }}">
                                            <div
                                                class="{{ $errors->has('surcharge') ? 'invalid-feedback' : 'valid-feedback' }}">
                                                @if ($errors->has('surcharge'))
                                                    <span class="text-danger fw-medium">
                                                        {{ $errors->first('surcharge') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-12">
                                        <label class="form-label">Mô tả
                                            <span class="required" style="color: red">*</span></label>
                                        <textarea name="description" id="description"
                                            class="form-control {{ $errors->has('description') ? 'is-invalid' : (old('description') ? 'is-valid' : '') }}">{{ old('description') }}</textarea>
                                        <div
                                            class="{{ $errors->has('description') ? 'invalid-feedback' : 'valid-feedback' }}">
                                            @if ($errors->has('description'))
                                                <span
                                                    class="text-danger fw-medium">{{ $errors->first('description') }}</span>
                                            @endif
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
                                    <div class="custom-switch">
                                        <input type="checkbox" id="switch_active" switch="primary"
                                            {{ old('is_active', 1) ? 'checked' : '' }} />
                                        <label for="switch_active" data-on-label="Yes" data-off-label="No"></label>
                                    </div>
                                    <span>Hoạt động</span>
                                </div>
                                <input type="hidden" name="is_active" id="is_active"
                                    value="{{ old('is_active', 1) }}">

                                <!-- Hot -->
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div class="custom-switch">
                                        <input type="checkbox" id="switch_hot" switch="primary"
                                            {{ old('is_hot', 1) ? 'checked' : '' }} />
                                        <label for="switch_hot" data-on-label="Yes" data-off-label="No"></label>
                                    </div>
                                    <span>Nổi bật</span>
                                </div>
                                <input type="hidden" name="is_hot" id="is_hot" value="{{ old('is_hot', 1) }}">

                                <!-- Special -->
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div class="custom-switch">
                                        <input type="checkbox" id="switch_special" switch="primary"
                                            {{ old('is_special', 1) ? 'checked' : '' }} />
                                        <label for="switch_special" data-on-label="Yes" data-off-label="No"></label>
                                    </div>
                                    <span>Đặc biệt</span>
                                </div>
                                <input type="hidden" name="is_special" id="is_special"
                                    value="{{ old('is_special', 1) }}">

                                <!-- Publish -->
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div class="custom-switch">
                                        <input type="checkbox" id="switch_publish" switch="primary"
                                            {{ old('is_publish', 1) ? 'checked' : '' }} />
                                        <label for="switch_publish" data-on-label="Yes" data-off-label="No"></label>
                                    </div>
                                    <span>Xuất Bản</span>
                                </div>
                                <input type="hidden" name="is_publish" id="is_publish"
                                    value="{{ old('is_publish', 1) }}">
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-2">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">
                                Lưu
                            </button>
                            <button type="reset" class="btn btn-secondary waves-effect">
                                Hủy
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                ClassicEditor
                    .create(document.querySelector('#description'), {
                        toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList',
                            'imageUpload', 'undo', 'redo'
                        ]
                    })
                    .then(editor => {
                        console.log('CKEditor đã được khởi tạo!', editor);
                    })
                    .catch(error => {
                        console.error('Lỗi khi khởi tạo CKEditor:', error);
                    });

                $('#movie_genres').select2({

                    placeholder: "Chọn thể loại phim",
                    allowClear: true
                });

                $('#movie_versions').select2({
                    placeholder: "Chọn phiên bản phim",
                    allowClear: true
                });

                $('#branch_ids').select2({
                    placeholder: "Chọn chi nhánh...",
                    allowClear: true
                });

                document.querySelectorAll('.custom-switch input[type="checkbox"]').forEach(function(checkbox) {
                    checkbox.addEventListener('change', function() {
                        var hiddenInput = document.getElementById(this.id.replace('switch_', 'is_'));
                        if (hiddenInput) {
                            hiddenInput.value = this.checked ? '1' : '0';
                        }
                    });
                });

                var specialCheckbox = document.getElementById('switch_special');
                var surchargeContainer = document.getElementById('surcharge_container');
                var surchargeInput = surchargeContainer.querySelector('input[name="surcharge"]');

                function toggleSurchargeInput() {
                    if (specialCheckbox.checked) {
                        surchargeContainer.style.display = 'block';
                        surchargeInput.disabled = false;
                    } else {
                        surchargeContainer.style.display = 'none';
                        surchargeInput.disabled = true;
                    }
                }

                toggleSurchargeInput();

                specialCheckbox.addEventListener('change', toggleSurchargeInput);

                function generateUUID() {
                    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                        const r = Math.random() * 16 | 0,
                            v = c === 'x' ? r : (r & 0x3 | 0x8);
                        return v.toString(16);
                    });
                }

                function convertToSlug(text) {
                    return text.toLowerCase()
                        .normalize("NFD").replace(/[\u0300-\u036f]/g, "")
                        .replace(/[^a-z0-9 -]/g, '')
                        .replace(/\s+/g, '-')
                        .replace(/-+/g, '-');
                }

                document.getElementById('name').addEventListener('input', function() {
                    let name = this.value.trim();
                    if (name !== '') {
                        let slug = convertToSlug(name) + '-' + generateUUID().substring(0, 8);
                        document.getElementById('slug').value = slug;
                    } else {
                        document.getElementById('slug').value = '';
                    }
                });
            });


            document.addEventListener('DOMContentLoaded', function() {
                const stars = document.querySelectorAll('.star-rating .bi-star, .star-rating .bi-star-fill');
                const ratingInput = document.getElementById('rating-value');
                let currentRating = ratingInput.value || 0;

                if (currentRating) {
                    updateStars(currentRating);
                }

                stars.forEach(star => {
                    star.addEventListener('click', function() {
                        currentRating = this.getAttribute('data-rating');
                        ratingInput.value = currentRating;
                        updateStars(currentRating);
                    });

                    star.addEventListener('mouseover', function() {
                        const hoverRating = this.getAttribute('data-rating');
                        updateStars(hoverRating, true);
                    });

                    star.addEventListener('mouseout', function() {
                        updateStars(currentRating);
                    });
                });

                function updateStars(rating, hover = false) {
                    stars.forEach(star => {
                        const starRating = star.getAttribute('data-rating');
                        if (starRating <= rating) {
                            star.classList.remove('bi-star');
                            star.classList.add('bi-star-fill');
                        } else {
                            star.classList.remove('bi-star-fill');
                            star.classList.add('bi-star');
                        }
                    });
                }
            });

            $(document).ready(function() {
                const $branchSelect = $('#branch_ids');

                $branchSelect.select2({
                    placeholder: "Chọn chi nhánh",
                    closeOnSelect: false,
                    width: '100%',
                    // templateResult: formatOptionWithCheckbox,
                });

                function formatOptionWithCheckbox(option) {
                    if (!option.id) return option.text;

                    const selectedValues = $branchSelect.val() || [];
                    const isSelected = selectedValues.includes(option.id);

                    //             return $(`
            //     <span>
            //         <input type="checkbox" style="margin-right: 6px;" ${isSelected ? 'checked' : ''}/>
            //         ${option.text}
            //     </span>
            // `)
                    ;
                }

                function updateSelectedNames() {
                    const selectedOptions = $branchSelect.select2('data');
                    if (!selectedOptions.length) {
                        $('#selected-branch-text').text("Chọn chi nhánh");
                        return;
                    }
                    const names = selectedOptions.map(opt => opt.text);
                    $('#selected-branch-text').text(`Đã chọn: ${names.join(', ')}`);
                }

                $branchSelect.on('change', function() {
                    updateSelectedNames();
                });

                updateSelectedNames();
            });
        </script>
    </div>
@endsection

@section('style')
    <style>
        .star-rating .bi-star-fill {
            color: #f1c40f;
            /* Màu vàng cho ngôi sao được chọn */
        }

        .star-rating .bi-star:hover,
        .star-rating .bi-star-fill:hover {
            color: #f39c12;
            /* Màu khi hover */
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #545cee;
            /* Nền xanh đậm cho mục đã chọn */
            color: white;
            /* Chữ trắng để nổi bật */
            border: 1px solid darkblue;
            /* Viền xanh đậm hơn */
        }
    </style>
@endsection
