<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SlideShowRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // post => rulesForCreate
        // put/patch => rulesForUpdate
        if ($this->isMethod('post')) {
            return $this->rulesForCreate();
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            return $this->rulesForUpdate();
        }

        return [];
    }

    public function rulesForCreate()
    {
        return [
            'img_thumbnail' => 'required|array|max:10', // Tối đa 5 ảnh
            'img_thumbnail.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Kiểm tra từng file ảnh
            'description' => 'nullable|string|max:255', // Mô tả không bắt buộc, tối đa 255 ký tự
        ];
    }

    public function rulesForUpdate()
    {
        return [
            'img_thumbnail' => 'nullable|array|max:10', // Tối đa 5 ảnh
            'img_thumbnail.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'existing_images' => 'nullable|array',
            'description' => 'nullable|string|max:1000',
        ];
    }

    // messages chung
    public function messages()
    {
        return [
            'img_thumbnail.required' => 'Vui lòng tải lên ít nhất một ảnh.',
            'img_thumbnail.array' => 'Ảnh phải ở định dạng danh sách (mảng).',
            'img_thumbnail.max' => 'Chỉ được tải lên tối đa :max ảnh.',
            'img_thumbnail.*.required' => 'Ảnh không được để trống.',
            'img_thumbnail.*.image' => 'Tệp tải lên phải là một hình ảnh.',
            'img_thumbnail.*.mimes' => 'Ảnh phải có định dạng: jpeg, png, jpg, gif, svg.',
            'img_thumbnail.*.max' => 'Kích thước mỗi ảnh không được vượt quá 2MB.',
            'existing_images.array' => 'Danh sách ảnh cũ phải là một mảng.',
            'description.string' => 'Mô tả phải là chuỗi ký tự.',
            'description.max' => 'Mô tả không được vượt quá :max ký tự.',
        ];
    }
}
