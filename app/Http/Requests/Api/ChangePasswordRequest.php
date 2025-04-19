<?php

namespace App\Http\Requests\Api;

use App\Traits\ApiRequestJsonTrait;
use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    use ApiRequestJsonTrait;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Xác định xem người dùng có quyền truy cập hay không
        return true; // Đổi thành true nếu bạn muốn cho phép tất cả các người dùng gửi yêu cầu này
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'password'  => 'required|string|min:8', // Mật khẩu yêu cầu xác nhận
            'password_old'  => 'required|string|min:8', // Mật khẩu yêu cầu xác nhận
        ];
    }

    /**
     * Custom error messages for validation failures.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'password.required'     => 'Mật khẩu là bắt buộc.',
            'password.string'       => 'Mật khẩu phải là một chuỗi ký tự.',
            'password.min'          => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed'    => 'Mật khẩu và xác nhận mật khẩu không khớp.',
            'password_old.required' => 'Mật khẩu cũ là bắt buộc.',
            'password_old.min'      => 'Mật khẩu cũ phải có ít nhất 8 ký tự.',
        ];
    }
}
