<?php

namespace App\Http\Requests;

use App\Traits\ApiRequestJsonTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CinemaRequest extends FormRequest
{
    // Chuyển validate thành Json
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
        if ($this->isMethod('post')) {
            return $this->rulesForCreate();
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            return $this->rulesForUpdate();
        }

        return [];
    }
    public function rulesForCreate()
    {
        return [
            'branch_id'             => 'required',
            'name'                  => 'required|unique:cinemas',
            'slug'                  => 'nullable',
            'address'               => 'required',
            'description'           => 'nullable',
            'is_active'             => 'nullable|in:0,1',
        ];
    }

    public function rulesForUpdate()
    {
        return [
            'branch_id'             => 'required',
            'name'                  => ['required', Rule::unique('cinemas')->ignore($this->route('cinema'))],
            'slug'                  => 'nullable',
            'address'               => 'required',
            'description'           => 'nullable',
            'is_active'             => 'nullable|in:0,1',
        ];
    }

    // messages chung
    public function messages()
    {
        return [
            'name.required'         => 'Vui lòng điền tên',
            'name.unique'           => 'Tên phòng đã tồn tại',
            'address.required'      => 'Vui lòng điền địa chỉ',
            'image.required'        => 'Vui lòng tải ảnh',
            'branch_id.required'    => 'Vui lòng chọn chi nhánh',
        ];
    }
}
