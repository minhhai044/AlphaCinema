<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserVoucherRequest extends FormRequest
{
    /**
     * Xác định xem người dùng có được phép gửi request này hay không.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Xác định rule validation áp dụng cho request.
     */
    public function rules(): array
    {

        if ($this->isMethod('post')) {
            return $this->rulesForCreate();
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            return $this->rulesForUpdate();
        }

        return [];

    }

    /**
     * Rule validate khi tạo mới User Voucher.
     */
    public function rulesForCreate(): array
    {
        return [

            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',

            'voucher_id' => 'required|exists:vouchers,id',
            'usage_count' => 'nullable|integer|min:1',
        ];
    }

    /**
     * Rule validate khi cập nhật User Voucher.
     */
    public function rulesForUpdate(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'voucher_id' => 'required|exists:vouchers,id',
            'usage_count' => 'nullable|integer|min:1',
        ];
    }

    /**
     * Tùy chỉnh thông báo lỗi.
     */
    public function messages(): array
    {
        return [

            'user_ids.required' => 'Vui lòng chọn ít nhất một người dùng.',
            'user_ids.array' => 'Danh sách người dùng không hợp lệ.',
            'user_ids.*.exists' => 'Một hoặc nhiều người dùng không hợp lệ.',


            'user_id.required' => 'Vui lòng chọn người dùng.',
            'user_id.exists' => 'Người dùng không hợp lệ.',
            'voucher_id.required' => 'Vui lòng chọn voucher.',
            'voucher_id.exists' => 'Voucher không hợp lệ.',
            'usage_count.integer' => 'Số lần sử dụng phải là số nguyên.',
            'usage_count.min' => 'Số lần sử dụng phải lớn hơn hoặc bằng 1.',
        ];
    }

}

