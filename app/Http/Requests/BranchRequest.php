<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BranchRequest extends FormRequest
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

    /**
     * Validation rules for creating a branch.
     */
    public function rulesForCreate(): array
    {
        return [
            'name' => 'required|string|max:255|unique:branches,name',
            'surcharge' => 'required|numeric|min:1000|max:100000'
        ];
    }

    /**
     * Validation rules for updating a branch.
     */
    public function rulesForUpdate(): array
    {
        return [
            'name' => 'required|string|max:255|unique:branches,name,' . $this->route('branch'),
            'surcharge' => 'nullable|numeric|min:1000|max:100000', 
        ];
    }

    /**
     * Custom error messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng điền tên chi nhánh.',
            'name.unique' => 'Tên chi nhánh đã tồn tại.',
            'name.max' => 'Tên chi nhánh không được vượt quá 255 ký tự.',
            'surcharge.required' => 'Vui lòng điền phụ phí.',
            'surcharge.numeric' => 'Phụ phí phải là một số.',
            'surcharge.min' => 'Phụ phí phải lớn hơn hoặc bằng 1000.',
            'surcharge.max' => 'Phụ phí phải nhỏ hơn hoặc bằng 100000.',
        ];
    }
}
