<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VoucherRequest extends FormRequest
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
     */
    public function rules(): array
    {
        if ($this->isMethod('post')) {
            return $this->rulesForCreate();
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            return $this->rulesForUpdate();
        }

        return [
            'discount_hidden' => ['required', 'numeric'], // Chỉ validate hidden input
        ];
    }

    /**
     * Validation rules for creating a voucher.
     */
    public function rulesForCreate(): array
    {
        return [
            'title' => 'required|string|max:255',
            'discount' => 'required|numeric|min:1000',
            'quantity' => 'required|integer|min:1',
            'start_date_time' => 'required|date',
            'end_date_time' => 'required|date|after:start_date_time',
            'limit_by_user' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
            'type_voucher' => 'required|in:0,1',
        ];
    }

    /**
     * Validation rules for updating a voucher.
     */
    public function rulesForUpdate(): array
    {
        return [
            'title' => 'required|string|max:255',
            'discount' => 'required|numeric|min:1000',
            'quantity' => 'required|integer|min:1',
            'start_date_time' => 'required|date',
            'end_date_time' => 'required|date|after:start_date_time',
            'limit_by_user' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
        ];
    }

    /**
     * Custom error messages.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Vui lòng nhập tiêu đề.',
            'discount.required' => 'Vui lòng nhập số tiền giảm giá.',
            'discount.numeric' => 'Số tiền giảm giá phải là số.',
            'discount.min' => 'Số tiền giảm giá phải lớn hơn hoặc bằng 1,000 VNĐ.',
            'quantity.required' => 'Vui lòng nhập số lượng mã giảm giá.',
            'quantity.integer' => 'Số lượng phải là số nguyên.',
            'quantity.min' => 'Số lượng mã giảm giá phải lớn hơn hoặc bằng 1.',
            'limit_by_user.integer' => 'Giới hạn sử dụng cho mỗi người dùng phải là số nguyên.',
            'limit_by_user.min' => 'Giới hạn sử dụng cho mỗi người dùng phải lớn hơn hoặc bằng 1.',
            'start_date_time.required' => 'Vui lòng chọn ngày bắt đầu.',
            'end_date_time.required' => 'Vui lòng chọn ngày kết thúc.',
            'end_date_time.after' => 'Ngày kết thúc phải sau ngày bắt đầu.',
        ];
    }
}
