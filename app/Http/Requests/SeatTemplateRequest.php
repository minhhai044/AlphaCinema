<?php

namespace App\Http\Requests;

use App\Models\Seat_template;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SeatTemplateRequest extends FormRequest
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
            'name' => ['required',Rule::unique(Seat_template::class)],
            'matrix' => 'required',
            'row_regular' => 'required',
            'row_vip' => 'required',
            'row_double' => 'required',
            'description' => 'nullable',
        ];
    }

    public function rulesForUpdate()
    {
        return [
            'name' => ['required',Rule::unique(Seat_template::class)->ignore($this->route('id'))],
            'matrix' => 'nullable',
            'row_regular' => 'required',
            'row_vip' => 'required',
            'row_double' => 'required',
            'description' => 'nullable',
        ];
    }

    // messages chung
    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên mẫu ghế',
            'matrix.required' => 'Vui lòng chọn ma trận ghế',
            'row_regular.required' => 'Vui lòng nhập ghế thuờng',
            'row_vip.required' => 'Vui lòng nhập ghế vip',
            'row_double.required' => 'Vui lòng nhập ghế đôi',
        ];
    }
}
