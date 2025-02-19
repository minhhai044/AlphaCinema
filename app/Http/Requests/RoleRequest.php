<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'required|array',
        ];
    }
    public function rulesForUpdate()
    {
        return [
            'name' => 'required|string|unique:roles,name,' . $this->route('role')->id,
            'permissions' => 'required|array',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên vai trò không được để trống.',
            'name.unique' => 'Tên vai trò đã tồn tại, vui lòng chọn tên khác.',
            'permissions.required' => 'Bạn cần chọn ít nhất một quyền.',
        ];
    }
}
