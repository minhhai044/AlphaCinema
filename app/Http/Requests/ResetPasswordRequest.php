<?php

namespace App\Http\Requests;

use App\Traits\ApiRequestJsonTrait;
use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
            'password' => 'required|min:8|string',
            'password_confirm' => 'required|min:8|string|same:password',
        ];
    }

    public function messages(): array
    {
        return [
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất :min ký tự.',

            'password_confirm.required' => 'Vui lòng nhập mật khẩu xác nhận.',
            'password_confirm.min' => 'Mật khẩu phải có ít nhất :min ký tự.',
            'password_confirm.same' => 'Mật khẩu xác nhận không khớp.',
        ];
    }
}
