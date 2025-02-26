<?php

namespace App\Http\Requests\Api;

use App\Traits\ApiRequestJsonTrait;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name'      => 'required|string|max:255|min:5',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|string|min:8',
            'birthday'  => 'required|date',
            'gender'     => 'required|in:0,1',
            // 'phone'     => 'required|numeric|regex:/^\+?[0-9]{10,15}$/|unique:users', //Hiện tại chưa check
            'phone'     => 'required|numeric|digits:10',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên là bắt buộc.',
            'name.string'   => 'Tên phải là một chuỗi ký tự.',
            'name.max'      => 'Tên không được vượt quá 255 ký tự.',
            'name.min'      => 'Tên tối thiểu 5 ký tự',

            'avatar.image'  => 'Ảnh đại diện phải là một file ảnh.',
            'avatar.max'    => 'Ảnh đại diện không được vượt quá 2MB.',

            'phone.required' => 'Số điện thoại là bắt buộc',
            'phone.regex'   => 'Số điện thoại không hợp lệ. Vui lòng nhập lại.',
            'phone.unique'  => 'Số điện thoại này đã được đăng ký. Vui lòng chọn một số khác.',  // Thông báo lỗi cho số điện thoại trùng
            'phone.numeric' => 'Số điện thoại phải là số',
            'phone.digits' => 'Số điện thoại phải là 10 số',

            'email.required' => 'Email là bắt buộc.',
            'email.email'    => 'Email phải là một địa chỉ email hợp lệ.',
            'email.unique'   => 'Email đã tồn tại. Vui lòng chọn một email khác.',  // Thông báo lỗi cho email trùng

            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.string'   => 'Mật khẩu phải là một chuỗi ký tự.',
            'password.min'      => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Mật khẩu và xác nhận mật khẩu không khớp.',

            'address.required' => 'Địa chỉ là bắt buộc.',
            'address.string'   => 'Địa chỉ phải là một chuỗi ký tự.',
            'address.max'      => 'Địa chỉ không được vượt quá 255 ký tự.',

            'gender.boolean'   => 'Giới tính phải là một giá trị boolean hợp lệ.',
            'gender.required'   => 'Giới tính là bắt buộc',
            'gender.in'   =>    'Vui lòng chọn nam hoặc nữ',

            'birthday.date'    => 'Ngày sinh phải là một ngày hợp lệ.',
            'birthday.required'    => 'Ngày sinh là bắt buộc.',

            'type_user.boolean' => 'Loại người dùng phải là một giá trị boolean hợp lệ.',

            'total_amount.numeric' => 'Tổng số tiền phải là một số hợp lệ.',  // Thông báo lỗi cho 'total_amount'

            'cinema_id.exists' => 'ID rạp chiếu không tồn tại.',
        ];
    }
}
