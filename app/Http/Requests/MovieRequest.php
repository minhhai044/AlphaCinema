<?php

namespace App\Http\Requests;

use App\Traits\ApiResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class MovieRequest extends FormRequest
{
    use ApiResponseTrait;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if ($this->isMethod('post')) {
            return $this->rulesForCreate();
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            return $this->rulesForUpdate();
        }

        return [];
    }

    public function rulesForCreate(): array
    {
        return [
            'name' => 'required|string|max:255|unique:movies,name',
            'slug' => 'required|string|max:255|unique:movies,slug',
            'category' => 'required|string|max:255',
            'description' => 'required|string',
            'img_thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'director' => 'required|string|max:255',
            'duration' => 'required|integer|min:1',
            'rating' => 'required|numeric|min:0|max:10',
            'release_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:release_date',
            'trailer_url' => 'required|url',
            'surcharge' => 'nullable|numeric|min:0',
            'movie_versions' => 'required|array',
            'movie_versions.*' => 'string|max:255',
            'movie_genres' => 'required|array',
            'movie_genres.*' => 'string|max:255',
            'is_active' => 'boolean',
            'is_hot' => 'boolean',
            'is_special' => 'boolean',
            'is_publish' => 'boolean',
            'branch_ids' => 'required|array',
            'branch_ids.*' => 'exists:branches,id',
        ];
    }

    public function rulesForUpdate(): array
    {
        return [
            'branch_ids' => 'required|array',
            'branch_ids.*' => 'exists:branches,id',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'required|string',
            'img_thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'director' => 'required|string|max:255',
            'duration' => 'required|integer|min:1',
            'rating' => 'required|numeric|min:0|max:10',
            'release_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:release_date',
            'trailer_url' => 'required|url',
            'surcharge' => 'nullable|numeric|min:0',
            'movie_versions' => 'sometimes|array',
            'movie_versions.*' => 'string|max:255',
            'movie_genres' => 'sometimes|array',
            'movie_genres.*' => 'string|max:255',
            'is_active' => 'sometimes|nullable|boolean',
            'is_hot' => 'sometimes|nullable|boolean',
            'is_special' => 'sometimes|nullable|boolean',
            'is_publish' => 'sometimes|nullable|boolean',
        ];
    }

    public function messages()
    {
        return [
            'branch_ids.required' => 'Danh sách chi nhánh là bắt buộc.',
            'name.required' => 'Tên phim là bắt buộc.',
            'name.string' => 'Tên phim phải là chuỗi ký tự.',
            'name.max' => 'Tên phim không được vượt quá 255 ký tự.',
            'name.unique' => 'Tên phim đã tồn tại.',
            'slug.required' => 'Slug phim là bắt buộc.',
            'slug.string' => 'Slug phim phải là chuỗi ký tự.',
            'slug.max' => 'Slug phim không được vượt quá 255 ký tự.',
            'slug.unique' => 'Slug phim đã tồn tại.',
            'category.required' => 'Danh mục phim là bắt buộc.',
            'category.string' => 'Danh mục phim phải là chuỗi ký tự.',
            'category.max' => 'Danh mục phim không được vượt quá 255 ký tự.',
            'description.required' => 'Mô tả phim là bắt buộc.',
            'description.string' => 'Mô tả phim phải là chuỗi ký tự.',
            'img_thumbnail.required' => 'Ảnh thumbnail là bắt buộc.',
            'img_thumbnail.image' => 'Ảnh thumbnail phải là một tệp hình ảnh.',
            'img_thumbnail.mimes' => 'Ảnh thumbnail phải có định dạng jpeg, png, jpg, gif hoặc svg.',
            'img_thumbnail.max' => 'Ảnh thumbnail không được vượt quá 2MB.',
            'director.required' => 'Tên đạo diễn là bắt buộc.',
            'director.string' => 'Tên đạo diễn phải là chuỗi ký tự.',
            'director.max' => 'Tên đạo diễn không được vượt quá 255 ký tự.',
            'duration.required' => 'Thời gian phim là bắt buộc.',
            'duration.integer' => 'Thời gian phim phải là số nguyên.',
            'duration.min' => 'Thời gian phim phải lớn hơn 0.',
            'rating.required' => 'Điểm đánh giá là bắt buộc.',
            'rating.numeric' => 'Điểm đánh giá phải là số.',
            'rating.min' => 'Điểm đánh giá phải từ 0 trở lên.',
            'rating.max' => 'Điểm đánh giá không được vượt quá 10.',
            'release_date.required' => 'Ngày phát hành là bắt buộc.',
            'release_date.date' => 'Ngày phát hành phải là một ngày hợp lệ.',
            'end_date.required' => 'Ngày kết thúc là bắt buộc.',
            'end_date.date' => 'Ngày kết thúc phải là một ngày hợp lệ.',
            'end_date.after_or_equal' => 'Ngày kết thúc phải lớn hơn hoặc bằng ngày phát hành.',
            'release_date.after_or_equal' => 'Ngày phát hành phải là hôm nay hoặc sau hôm nay.',
            'release_date.after' => 'Ngày phát hành phải lớn hơn ngày hôm nay.',
            'trailer_url.required' => 'URL trailer là bắt buộc.',
            'trailer_url.url' => 'URL trailer phải là một URL hợp lệ.',
            'surcharge.numeric' => 'Phụ phí phải là số.',
            'surcharge.min' => 'Phụ phí phải lớn hơn hoặc bằng 0.',
            'movie_versions.required' => 'Danh sách phiên bản phim là bắt buộc.',
            'movie_versions.*.array' => 'Mỗi phiên bản phim phải là chuỗi ký tự.',
            'movie_versions.*.max' => 'Tên mỗi phiên bản phim không được vượt quá 255 ký tự.',
            'movie_genres.required' => 'Danh sách thể loại phim là bắt buộc.',
            'movie_genres.*.array' => 'Mỗi thể loại phim phải là chuỗi ký tự.',
            'movie_genres.*.max' => 'Tên mỗi thể loại phim không được vượt quá 255 ký tự.',
        ];
    }
}
