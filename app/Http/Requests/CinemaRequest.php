<?php

namespace App\Http\Requests;

use App\Traits\ApiRequestJsonTrait;
use Illuminate\Foundation\Http\FormRequest;

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
            'name' => 'required',
            'address' => 'required',
            'image' => 'required'
        ];
    }

    public function rulesForUpdate()
    {
        return [
            'name' => 'required',
            'address' => 'required',
            'image' => 'required'
        ];
    }
    
    // messages chung
    public function messages()
    {
        return [
            'name.required' => 'Vui lòng điền name',
            'address.required' => 'Vui lòng điền address',
            'image.required' => 'Vui lòng điền image',
        ];
    }
}
