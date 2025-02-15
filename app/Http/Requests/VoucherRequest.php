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
            'discount' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'start_date_time' => 'required|date',
            'end_date_time' => 'required|date|after:start_date_time',
            'description' => 'nullable|string',
        ];
    }

    /**
     * Validation rules for updating a voucher.
     */
    public function rulesForUpdate(): array
    {
        return [
            'title' => 'required|string|max:255',
            'discount' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'start_date_time' => 'required|date',
            'end_date_time' => 'required|date|after:start_date_time',
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
            'quantity.required' => 'Vui lòng nhập số lượng mã giảm giá.',
            'quantity.integer' => 'Số lượng phải là số nguyên.',
            'start_date_time.required' => 'Vui lòng chọn ngày bắt đầu.',
            'end_date_time.required' => 'Vui lòng chọn ngày kết thúc.',
            'end_date_time.after' => 'Ngày kết thúc phải sau ngày bắt đầu.',
        ];
    }
}
