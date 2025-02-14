<?php

namespace App\Http\Requests;

use App\Models\Type_room;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TypeRoomRequest extends FormRequest
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

        // post => rulesForCreate 
        // put/patch => rulesForUpdate

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
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Type_room::class), // Bỏ qua ID hiện tại
            ],
            'surcharge' => 'required|numeric|min:0|max:9999999999',
        ];
    }

    public function rulesForUpdate()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Type_room::class)->ignore($this->route('type_room')), // Bỏ qua ID hiện tại
            ],
            'surcharge' => 'required|numeric|min:0|max:9999999999',
        ];
    }
    
    // messages chung
    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên loại phòng.',
            'name.unique' => 'Tên loại phòng đã tồn tại, vui lòng chọn tên khác.',
            'surcharge.required' => 'Vui lòng nhập phụ thu.',
            'surcharge.numeric' => 'Phụ thu phải là một số.',
            'surcharge.min' => 'Phụ thu không được nhỏ hơn 0.',
            'surcharge.max' => 'Phụ thu quá lớn.',
        ];
    }
}
