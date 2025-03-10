<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $userId = $this->route('user');
        if ($this->isMethod(method: 'POST')) {
            if ($this->routeIs('api.users.signin')) { // Nếu là API đăng nhập
                return $this->rulesForSignIn();
            }
            return $this->rulesForCreate();
        } elseif ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            return $this->rulesForUpdate($userId);
        }

        return [];
    }

    public function rulesForCreate()
    {
        return [
            'name'      => 'required|string|max:255',
            'avatar'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'phone'     => 'required|regex:/^\+?[0-9]{10,15}$/|unique:users,phone',  // Kiểm tra số điện thoại duy nhất
            'email'     => 'required|email|unique:users,email',  // Kiểm tra email duy nhất
            'password'  => 'required|string|min:8|confirmed',
            'address'   => 'nullable|string|max:255',
            'gender'    => 'nullable',
            'birthday'  => 'nullable|date',
            'total_amount' => 'nullable|numeric',  // Nếu cần kiểu dữ liệu số cho 'total_amount'
            'type_user' => 'boolean',
            'cinema_id' => 'nullable|exists:cinemas,id',
        ];
    }

    public function rulesForUpdate($userId)
    {
        return [
            'name'      => 'required|string|max:255',
            'avatar'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'phone'     => 'nullable|regex:/^\+?[0-9]{10,15}$/|unique:users,phone,' . $userId,  // Bỏ qua kiểm tra số điện thoại nếu là bản ghi hiện tại
            'email'     => 'required|email|unique:users,email,' . $userId,  // Bỏ qua kiểm tra email nếu là bản ghi hiện tại
            'password'  => 'nullable|string|min:8|confirmed',  // Mật khẩu có thể không thay đổi
            'address'   => 'required|string|max:255',
            'gender'    => 'boolean',
            'birthday'      => 'nullable|date',
            'total_amount' => 'nullable|numeric',  // Kiểm tra dữ liệu số cho 'total_amount'
            'type_user' => 'boolean',
            'cinema_id' => 'nullable|exists:cinemas,id',
        ];
    }

    public function rulesForSignIn()
    {
        return [
            'email'    => 'required|email|exists:users,email',
            'password' => 'required|string|min:8',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên là bắt buộc.',
            'name.string'   => 'Tên phải là một chuỗi ký tự.',
            'name.max'      => 'Tên không được vượt quá 255 ký tự.',

            'avatar.image'  => 'Ảnh đại diện phải là một file ảnh.',
            'avatar.max'    => 'Ảnh đại diện không được vượt quá 2MB.',

            'phone.required' => 'Số điện thoại là bắt buộc',
            'phone.regex'   => 'Số điện thoại không hợp lệ. Vui lòng nhập lại.',
            'phone.unique'  => 'Số điện thoại này đã được đăng ký. Vui lòng chọn một số khác.',  // Thông báo lỗi cho số điện thoại trùng

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

            'birthday.date'    => 'Ngày sinh phải là một ngày hợp lệ.',

            'type_user.boolean' => 'Loại người dùng phải là một giá trị boolean hợp lệ.',

            'total_amount.numeric' => 'Tổng số tiền phải là một số hợp lệ.',  // Thông báo lỗi cho 'total_amount'

            'cinema_id.exists' => 'ID rạp chiếu không tồn tại.',
        ];
    }
}
