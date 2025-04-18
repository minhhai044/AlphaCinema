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
            if ($this->routeIs('login')) {
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
            'name' => 'required|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'phone' => 'required|regex:/^\+?[0-9]{10,15}$/|unique:users,phone',  // Kiểm tra số điện thoại duy nhất
            // 'email' => 'required|email|unique:users,email',  // Kiểm tra email duy nhất
            'email' => [
                'required',
                'unique:users,email',
                'regex:/^[\w\.\-]+@([\w\-]+\.)+[a-zA-Z]{2,}$/'
            ],

            'password' => 'required|string|min:8|confirmed',
            'address' => 'nullable|string|max:255',
            'gender' => 'nullable',
            'birthday' => 'nullable|date|before_or_equal:today',
            'total_amount' => 'nullable|numeric',  // Nếu cần kiểu dữ liệu số cho 'total_amount'
            'type_user' => 'boolean',
            'cinema_id' => 'nullable|exists:cinemas,id',
            'branch_id' => 'nullable|exists:branches,id',
        ];
    }

    public function rulesForUpdate($userId)
    {
        return [
            'name' => 'required|string|max:255',
            // 'avatar' => 'nullable',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'phone' => 'nullable|regex:/^\+?[0-9]{10,15}$/|unique:users,phone,' . $userId,  // Bỏ qua kiểm tra số điện thoại nếu là bản ghi hiện tại
            // 'email' => 'required|email|unique:users,email,' . $userId,  // Bỏ qua kiểm tra email nếu là bản ghi hiện tại
            'email' => [
                'required',
                'email',
                'unique:users,email,' . $userId,
                'regex:/^[\w\.\-]+@([\w\-]+\.)+[a-zA-Z]{2,}$/'
            ],
            'password' => 'nullable|string|min:8|confirmed',  // Mật khẩu có thể không thay đổi
            'address' => 'nullable|string|max:255',
            'gender' => 'boolean',
            'birthday' => 'nullable|date|before_or_equal:today',
            'total_amount' => 'nullable|numeric',  // Kiểm tra dữ liệu số cho 'total_amount'
            'type_user' => 'boolean',
            'cinema_id' => 'nullable|exists:cinemas,id',
            'branch_id' => 'nullable|exists:branches,id',
        ];
    }

    public function rulesForSignIn()
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên là bắt buộc.',
            'name.string' => 'Tên phải là một chuỗi ký tự.',
            'name.max' => 'Tên không được vượt quá 255 ký tự.',

            // 'avatar.image' => 'Ảnh đại diện phải là một file ảnh.',
            // 'avatar.max' => 'Ảnh đại diện không được vượt quá 2MB.',
            // 'avatar' => [
            //     'image' => 'Tệp tải lên phải là một hình ảnh.',
            //     'mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, svg.',
            //     'max' => 'Hình ảnh không được vượt quá 2MB.',
            //     'url' => 'Hình ảnh phải là một URL hợp lệ.'
            // ],

            'avatar' => [
                'required' => 'Vui lòng chọn hình ảnh.',
                'image' => 'Tệp tải lên phải là một hình ảnh.',
                'mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, svg.',
                'max' => 'Hình ảnh không được vượt quá 2MB.',
                'url' => 'Hình ảnh phải là một URL hợp lệ.'
            ],

            'phone.required' => 'Số điện thoại là bắt buộc',
            'phone.regex' => 'Số điện thoại không hợp lệ. Vui lòng nhập lại.',
            'phone.unique' => 'Số điện thoại này đã được đăng ký. Vui lòng chọn một số khác.',  // Thông báo lỗi cho số điện thoại trùng

            // 'email.required' => 'Email là bắt buộc.',
            // 'email.email' => 'Email phải là một địa chỉ email hợp lệ.',
            // 'email.unique' => 'Email đã tồn tại. Vui lòng chọn một email khác.',  // Thông báo lỗi cho email trùng

            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email phải đúng định dạng (ví dụ: yourname@example.com)',
            'email.unique' => 'Email này đã tồn tại',
            'email.regex' => 'Email không đúng định dạng (phải có @ và tên miền đầy đủ)',


            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.string' => 'Mật khẩu phải là một chuỗi ký tự.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Mật khẩu và xác nhận mật khẩu không khớp.',

            'address.required' => 'Địa chỉ là bắt buộc.',
            'address.string' => 'Địa chỉ phải là một chuỗi ký tự.',
            'address.max' => 'Địa chỉ không được vượt quá 255 ký tự.',

            'gender.boolean' => 'Giới tính phải là một giá trị boolean hợp lệ.',

            'birthday.date' => 'Ngày sinh phải là một ngày hợp lệ.',
            'birthday.before_or_equal' => 'Ngày sinh không được là ngày trong tương lai.',

            'type_user.boolean' => 'Loại người dùng phải là một giá trị boolean hợp lệ.',

            'total_amount.numeric' => 'Tổng số tiền phải là một số hợp lệ.',  // Thông báo lỗi cho 'total_amount'

            'cinema_id.exists' => 'ID rạp chiếu không tồn tại.',

            'branch_id.exists' => 'ID chi nhánh không tồn tại.',
        ];
    }
}
