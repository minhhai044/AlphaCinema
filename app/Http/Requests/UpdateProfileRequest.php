<?php

namespace App\Http\Requests;

use App\Traits\ApiRequestJsonTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
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
        return [
            'name' => 'required|string|max:255',
            'phone' => [
                'nullable',
                'regex:/^\+?[0-9]{10,15}$/',
                Rule::unique('users')->ignore($this->route('user'))
            ],
            'birthday' => [
                'required',
                'date',
                'before:' . now()->subYears(12)->format('Y-m-d'),
            ],
            'gender' => 'nullable|in:0,1',
            'address' => 'nullable',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => "Tên là bắt buộc",
            'name.max' => "Tên không được vượt quá 255 kí tự",

            'phone.regex' => "Số điện thoại không hợp lệ",
            'phone.unique' => "Số điện thoại đã tồn tại",

            'birthday.required' => "Ngày sinh là bắt buộc",
            'birthday.date' => "Ngày sinh định dạng không hợp lệ",
            'birthday.before' => 'Bạn phải ít nhất 12 tuổi để cập nhật thông tin.',

            'gender.in' => "Chỉ được nam hoặc nữ",

            'avatar.image' => 'Ảnh không phù hợp',
            'avatar.mimes' => 'Định dạng ảnh không phù hợp',
        ];
    }
}
