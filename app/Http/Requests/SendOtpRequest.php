<?php

namespace App\Http\Requests;

use App\Traits\ApiRequestJsonTrait;
use Illuminate\Foundation\Http\FormRequest;

class SendOtpRequest extends FormRequest
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
            'email' => 'required|email|exists:users'
        ];
    }

    public function messages()
    {
        return [
            'email.required' => "Email không được để trống",
            'email.email' => "Email không hợp lệ",
            'email.exists' => "Email không tồn tại",
        ];
    }
}
