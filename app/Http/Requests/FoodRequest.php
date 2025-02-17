<?php

namespace App\Http\Requests;

use App\Models\Food;
use Illuminate\Validation\Rule;
use App\Traits\ApiRequestJsonTrait;
use Illuminate\Foundation\Http\FormRequest;

class FoodRequest extends FormRequest
{
    use ApiRequestJsonTrait;
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
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Food::class),
            ],
            'type' => 'required',
            'img_thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'price' => 'required|numeric|min:0|max:99999999',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ];
    }

    public function rulesForUpdate()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Food::class)->ignore($this->route('food') ), // Bỏ qua ID hiện tại
            ],
            'type' => 'nullable',
            'img_thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'price' => 'required|numeric|min:0|max:99999999',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ];
    }

    /**
     * Fix lỗi Price không nhận dấu phẩy
     */
    public function prepareForValidation()
    {
        if ($this->has('price')) {
            // Loại bỏ dấu phẩy và chuyển thành số
            $this->merge([
                'price' => str_replace(',', '', $this->input('price')),
            ]);
        }
    }

    // messages chung
    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên món ăn.',
            'name.string' => 'Tên món ăn phải là chuỗi ký tự.',
            'name.max' => 'Tên món ăn không được vượt quá 255 ký tự.',
            'name.unique' => 'Tên món ăn đã tồn tại. Vui lòng chọn tên khác.',

            'img_thumbnail' => [
                'required' => 'Vui lòng chọn hình ảnh.',
                'image' => 'Tệp tải lên phải là một hình ảnh.',
                'mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, svg.',
                'max' => 'Hình ảnh không được vượt quá 2MB.',
                'url' => 'Hình ảnh phải là một URL hợp lệ.'
            ],
            'price.required' => 'Vui lòng nhập giá món ăn.',
            'price.numeric' => 'Giá món ăn phải là một số.',
            'price.min' => 'Giá món ăn không được nhỏ hơn 0.',
            'price.max' => 'Giá món ăn không được vượt quá 99,999,999.',

            'description.required' => 'Vui lòng nhập mô tả món ăn.',
            'description.string' => 'Mô tả món ăn phải là chuỗi ký tự.',
            'type.required' => 'Vui lòng chọn loại đồ ăn.',
            'is_active.boolean' => 'Trạng thái kích hoạt phải là hoạt động hoặc không hoạt động.',
        ];
    }
}
