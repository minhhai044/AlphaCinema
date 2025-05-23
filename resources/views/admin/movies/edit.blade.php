@extends('admin.layouts.master')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Cập nhật phim</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.movies.index') }}">Phim</a>
                        </li>
                        <li class="breadcrumb-item active">Cập nhật phim</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-12">
            <form action="{{ route('admin.movies.update', $movie->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <!-- Khối 9/12 -->
                    <div class="col-lg-9">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Tên phim</label>
                                            <input type="text" name="name" value="{{ old('name', $movie->name) }}"
                                                class="form-control">
                                            @error('name')
                                                <div class="text-danger fw-medium">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Slug</label>
                                            <input type="text" name="slug" value="{{ old('slug', $movie->slug) }}"
                                                class="form-control">
                                            @error('slug')
                                                <div class="text-danger fw-medium">{{ $message }}</div>
                                            @enderror
                                        </div>




                                        <div class="mb-3">
                                            <label class="form-label">Danh mục phim</label>
                                            <input type="text" name="category"
                                                value="{{ old('category', $movie->category) }}" class="form-control">
                                            @error('category')
                                                <div class="text-danger fw-medium">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="branch_ids" class="form-label">Chi nhánh chiếu phim</label>
                                            <select name="branch_ids[]" class="form-control select2" multiple
                                                id="branch_ids" style="width: 100%;">
                                                @foreach ($branches as $branch)
                                                    <option value="{{ $branch->id }}"
                                                        {{ in_array($branch->id, old('branch_ids', $selectedBranches ?? [])) ? 'selected' : '' }}>
                                                        {{ $branch->name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            @if ($errors->has('branch_ids'))
                                                <div class="invalid-feedback d-block">
                                                    <span
                                                        class="text-danger fw-medium">{{ $errors->first('branch_ids') }}</span>
                                                </div>
                                            @endif
                                        </div>


                                        @php
                                            $selectedVersions = (array) old(
                                                'movie_versions',
                                                $movie->movie_versions ?? [],
                                            );
                                            $selectedGenres = (array) old('movie_genres', $movie->movie_genres ?? []);
                                        @endphp

                                        <!-- Thể loại phim -->
                                        @php
                                            $selectedVersions = (array) old(
                                                'movie_versions',
                                                $movie->movie_versions ?? [],
                                            );
                                            $selectedGenres = (array) old('movie_genres', $movie->movie_genres ?? []);
                                        @endphp

                                        <!-- Thể loại phim với Select2 -->
                                        <div class="mb-3">
                                            <label class="form-label">Thể loại phim
                                                <span class="required" style="color: red">*</span>
                                            </label>
                                            <select name="movie_genres[]" id="movie_genres"
                                                class="form-control select2 {{ $errors->has('movie_genres') ? 'is-invalid' : '' }}"
                                                multiple>
                                                <option value="Action"
                                                    {{ in_array('Action', $selectedGenres) ? 'selected' : '' }}>Hành động
                                                </option>
                                                <option value="Horror"
                                                    {{ in_array('Horror', $selectedGenres) ? 'selected' : '' }}>Kinh dị
                                                </option>
                                                <option value="Comedy"
                                                    {{ in_array('Comedy', $selectedGenres) ? 'selected' : '' }}>Hài hước
                                                </option>
                                            </select>
                                            @error('movie_genres')
                                                <div class="text-danger fw-medium">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Phiên bản phim với Select2 -->
                                        <div class="mb-3">
                                            <label class="form-label">Phiên bản phim
                                                <span class="required" style="color: red">*</span>
                                            </label>
                                            <select name="movie_versions[]" id="movie_versions"
                                                class="form-control select2 {{ $errors->has('movie_versions') ? 'is-invalid' : '' }}"
                                                multiple>
                                                <option value="2D"
                                                    {{ in_array('2D', $selectedVersions) ? 'selected' : '' }}>2D</option>
                                                <option value="3D"
                                                    {{ in_array('3D', $selectedVersions) ? 'selected' : '' }}>3D</option>
                                                <option value="4D"
                                                    {{ in_array('4D', $selectedVersions) ? 'selected' : '' }}>4D</option>
                                                <option value="IMAX"
                                                    {{ in_array('IMAX', $selectedVersions) ? 'selected' : '' }}>IMAX
                                                </option>
                                            </select>
                                            @error('movie_versions')
                                                <div class="text-danger fw-medium">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Tác giả</label>
                                            <input type="text" name="director"
                                                value="{{ old('director', $movie->director) }}" class="form-control">
                                            @error('director')
                                                <div class="text-danger fw-medium">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">URL Youtube</label>
                                            <input type="text" name="trailer_url"
                                                value="{{ old('trailer_url', $movie->trailer_url) }}" class="form-control">
                                            @error('trailer_url')
                                                <div class="text-danger fw-medium">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Thời lượng phim</label>
                                            <input type="text" name="duration"
                                                value="{{ old('duration', $movie->duration) }}" class="form-control">
                                            @error('duration')
                                                <div class="text-danger fw-medium">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3" id="surcharge_container" style="display: none;">
                                            <label class="form-label">Phụ phí</label>
                                            <input type="number" name="surcharge"
                                                class="form-control {{ $errors->has('surcharge') ? 'is-invalid' : (old('surcharge') ? 'is-valid' : '') }}"
                                                value="{{ old('surcharge', $movie->surcharge ?? '') }}">
                                            @if ($errors->has('surcharge'))
                                                <div class="invalid-feedback">{{ $errors->first('surcharge') }}</div>
                                            @endif
                                        </div>

                                        <div class="mb-3">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="release_date" class="form-label">Ngày trình chiếu<span
                                                            class="required" style="color: red">*</span></label>
                                                    <input type="date" class="form-control" id="release_date"
                                                        name="release_date"
                                                        value="{{ old('release_date', $movie->release_date ? date('Y-m-d', strtotime($movie->release_date)) : '') }}"
                                                        placeholder="Start Date" />
                                                    @error('release_date')
                                                        <span
                                                            class="text-danger fw-medium mt-1 d-block">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="end_date" class="form-label">Ngày kết thúc<span
                                                            class="required" style="color: red">*</span></label>
                                                    <input type="date" class="form-control" id="end_date"
                                                        name="end_date"
                                                        value="{{ old('end_date', $movie->end_date ? date('Y-m-d', strtotime($movie->end_date)) : '') }}"
                                                        placeholder="End Date" />
                                                    @error('end_date')
                                                        <span
                                                            class="text-danger fw-medium mt-1 d-block">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Đánh giá</label>
                                            <div class="star-rating d-flex gap-1"
                                                style="font-size: 1.5rem; cursor: pointer;">
                                                <i class="bi bi-star" data-rating="1"></i>
                                                <i class="bi bi-star" data-rating="2"></i>
                                                <i class="bi bi-star" data-rating="3"></i>
                                                <i class="bi bi-star" data-rating="4"></i>
                                                <i class="bi bi-star" data-rating="5"></i>
                                            </div>
                                            <!-- Hidden input để gửi giá trị rating -->
                                            <input type="hidden" name="rating" id="rating-value" class="form-control"
                                                value="{{ old('rating', $movie->rating) }}">
                                            @error('rating')
                                                <div class="text-danger fw-medium">{{ $message }}</div>
                                            @enderror
                                        </div>


                                    </div>
                                </div>
                                <div class="mb-12">
                                    <label class="form-label">Mô tả</label>
                                    <textarea name="description" id="description" class="form-control">{{ old('description', $movie->description) }}</textarea>
                                    @error('description')
                                        <div class="text-danger fw-medium">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div>

                    </div>

                    <!-- Khối 3/12 -->
                    <div class="col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                @php
                                    $statuses = [
                                        'is_active' => 'Hoạt động',
                                        'is_hot' => 'Nổi bật ',
                                        'is_special' => 'Đặc biệt',
                                        // 'is_publish' => 'Xuất bản',
                                    ];
                                @endphp

                                @foreach ($statuses as $key => $label)
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <div class="custom-switch">
                                            <input type="checkbox" id="switch_{{ $key }}" switch="primary"
                                                {{ old($key, $movie->$key) ? 'checked' : '' }} />
                                            <label for="switch_{{ $key }}" data-on-label="Yes"
                                                data-off-label="No"></label>
                                        </div>
                                        <span>{{ $label }}</span>
                                    </div>
                                    <input type="hidden" name="{{ $key }}" id="{{ $key }}"
                                        value="{{ old($key, $movie->$key) }}">
                                @endforeach

                                <div class="mb-3">
                                    <label class="form-label">Ảnh</label>
                                    <input type="file" name="img_thumbnail" id="movie-image" class="form-control"
                                        onchange="previewImage(event)">

                                    <div id="image-container"
                                        class="position-relative text-center {{ $movie->img_thumbnail ? '' : 'd-none' }}">
                                        <img id="image-preview"
                                            src="{{ $movie->img_thumbnail ? asset('storage/' . $movie->img_thumbnail) : '' }}"
                                            alt="Preview" class="img-fluid mb-2"
                                            style="max-width: 100%; max-height: 200px;">

                                        <button type="button" id="delete-image"
                                            class="btn btn-danger position-absolute top-0 end-0 p-1"
                                            onclick="deleteImage()">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>

                                    @error('img_thumbnail')
                                        <div class="text-danger fw-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex flex-wrap gap-2">
                                    <a href=""><button type="submit"
                                            class="btn btn-primary waves-effect waves-light">
                                            Lưu
                                        </button></a>
                                    <a href="{{ route('admin.movies.index') }}" class="btn btn-secondary mb-3">Hủy</a>
                                </div>

                                {{-- <div class="d-flex flex-wrap gap-2">
                                    <a href=""><button type="submit"
                                            class="btn btn-primary waves-effect waves-light">
                                            Lưu
                                        </button></a>
                                    <a href="{{ route('admin.movies.index') }}" class="btn btn-secondary mb-3">Hủy</a>
                                </div> --}}
                            </div>
                        </div>
                    </div>




                </div>
            </form>
        </div>
    </div>
    <!-- end select2 -->

    <!-- Thêm CKEditor qua CDN và khởi tạo -->
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            const reader = new FileReader();

            reader.onload = function() {
                $('#image-preview').attr('src', reader.result);
                $('#image-container').removeClass('d-none');
            }

            if (file) {
                reader.readAsDataURL(file);
            }
        }

        function deleteImage() {
            $('#image-container').addClass('d-none');
            $('#image-preview').attr('src', '');

            const fileInput = $('#movie-image');
            fileInput.val('');
            fileInput[0].type = '';
            fileInput[0].type = 'file';
        }

        function previewImage(event) {
            const file = event.target.files[0];
            const reader = new FileReader();

            reader.onload = function() {
                $('#image-preview').attr('src', reader.result);
                $('#image-container').removeClass('d-none');
            }

            if (file) {
                reader.readAsDataURL(file);
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            // Khởi tạo CKEditor cho trường description
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

            // Cập nhật giá trị của hidden input cho các switch
            document.querySelectorAll('.custom-switch input[type="checkbox"]').forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    var hiddenInput = document.getElementById(this.id.replace('switch_', ''));
                    if (hiddenInput) {
                        hiddenInput.value = this.checked ? '1' : '0';
                        console.log(`${this.id} changed to ${hiddenInput.value}`);
                    } else {
                        console.error(`Hidden input not found for ${this.id}`);
                    }
                });
            });
            // Khởi tạo Select2 cho trường movie_genres
            $('#movie_genres').select2({
                placeholder: "Chọn thể loại phim",
                allowClear: true
            });
            $('#movie_versions').select2({
                placeholder: "Chọn thể Phiên bản",
                allowClear: true
            });

            // Khởi tạo Select2 cho trường branch_ids
            $('#branch_ids').select2({
                placeholder: "Chọn chi nhánh...",
                allowClear: true
            });


            // Xử lý toggle cho surcharge dựa trên switch_is_special
            var specialCheckbox = document.getElementById('switch_is_special');
            var surchargeContainer = document.getElementById('surcharge_container');
            var surchargeInput = surchargeContainer.querySelector('input[name="surcharge"]');

            function toggleSurchargeInput() {
                if (specialCheckbox.checked) {
                    surchargeContainer.style.display = 'block';
                    surchargeInput.disabled = false;
                } else {
                    surchargeContainer.style.display = 'none';
                    surchargeInput.disabled = true;
                    surchargeInput.value = '';
                }
            }

            if (specialCheckbox) {
                toggleSurchargeInput();
                specialCheckbox.addEventListener('change', toggleSurchargeInput);
            } else {
                console.error('Special checkbox not found');
            }
        });
    </script>
@endsection
@section('style')
    <style>
        .star-rating .bi-star-fill {
            color: #f1c40f;
        }

        .star-rating .bi-star:hover,
        .star-rating .bi-star-fill:hover {
            color: #f39c12;
        }

        .select2-container--default .select2-selection--multiple {
            min-height: auto !important;
            height: auto !important;
            overflow-y: auto !important;
            max-height: none !important;
            padding-bottom: 5px;
        }

        /* Cho tag hiển thị đầy đủ */
        .select2-selection__rendered {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            padding: 5px;
        }

        .select2-selection__choice {
            margin: 0;
        }
    </style>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            const $branchSelect = $('#branch_ids');

            $branchSelect.select2({
                closeOnSelect: false,
                width: '100%',
                // templateResult: formatOptionWithCheckbox,
            });

            function formatOptionWithCheckbox(option) {
                if (!option.id) return option.text;

                const selectedValues = $branchSelect.val() || [];
                const isSelected = selectedValues.includes(option.id);

                // return $(`
            //     <span>
            //         <input type="checkbox" style="margin-right: 6px;" ${isSelected ? 'checked' : ''}/>
            //         ${option.text}
            //     </span>
            // `)

                ;
            }

            // function updateSelectedNames() {
            //     const selectedOptions = $branchSelect.select2('data');
            //     if (!selectedOptions.length) {
            //         $('#selected-branch-text').text("Chọn chi nhánh");
            //         return;
            //     }
            //     const names = selectedOptions.map(opt => opt.text);
            //     $('#selected-branch-text').text(`Đã chọn: ${names.join(', ')}`);
            // }

            $branchSelect.on('change', function() {
                updateSelectedNames();
            });

            updateSelectedNames();
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.star-rating .bi-star, .star-rating .bi-star-fill');
            const ratingInput = document.getElementById('rating-value');
            let currentRating = ratingInput.value || 0;

            // Khởi tạo trạng thái ban đầu từ old('rating') hoặc $movie->rating
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
    </script>
@endsection
