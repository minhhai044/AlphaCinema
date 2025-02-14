<?php

namespace App\Http\Requests;

use App\Models\Combo;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ComboRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Combo::class),
            ],
            'img_thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'price' => 'required|numeric|min:0|max:99999999',
            'price_sale' => 'nullable|numeric|min:0|max:99999999|lt:price', // Giá sale phải nhỏ hơn giá gốc
            'description' => 'required|string',
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
                Rule::unique(Combo::class)->ignore($this->route('combo')), // Bỏ qua ID hiện tại
            ],
            'img_thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'price' => 'nullable|numeric|min:0|max:99999999',
            'price_sale' => 'nullable|numeric|min:0|max:99999999|lt:price',
            'description' => 'required|string',
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
        if ($this->has('price_sale')) {
            // Loại bỏ dấu phẩy và chuyển thành số
            $this->merge([
                'price_sale' => str_replace(',', '', $this->input('price_sale')),
            ]);
        }
    }

    // messages chung
    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên Combo.',
            'name.string' => 'Tên Combo phải là chuỗi ký tự.',
            'name.max' => 'Tên Combo không được vượt quá 255 ký tự.',
            'name.unique' => 'Tên Combo đã tồn tại. Vui lòng chọn tên khác.',

            'img_thumbnail' => [
                'required' => 'Vui lòng chọn hình ảnh.',
                'image' => 'Tệp tải lên phải là một hình ảnh.',
                'mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, svg.',
                'max' => 'Hình ảnh không được vượt quá 2MB.',
                'url' => 'Hình ảnh phải là một URL hợp lệ.'
            ],
            'price.required' => 'Vui lòng nhập giá Combo.',
            'price.numeric' => 'Giá Combo phải là một số.',
            'price.min' => 'Giá Combo không được nhỏ hơn 0.',
            'price.max' => 'Giá Combo không được vượt quá 99,999,999.',


            'price_sale.numeric' => 'Giá Combo Sale phải là một số.',
            'price_sale.min' => 'Giá Combo không được nhỏ hơn 0.',
            'price_sale.max' => 'Giá Combo không được vượt quá 99,999,999.',
            'price_sale.lt' => 'Giá giảm giá Combo phải nhỏ hơn giá Combo.',

            'description.required' => 'Vui lòng nhập mô tả Combo.',
            'description.string' => 'Mô tả Combo phải là chuỗi ký tự.',

            'is_active.boolean' => 'Trạng thái kích hoạt phải là hoạt động hoặc không hoạt động.',
        ];
    }
}
