<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DayRequest extends FormRequest
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
    public function rulesForCreate(): array
    {
        return [
            'name' => 'required|string|max:255|unique:days,name',
            'day_surcharge'=> 'required|numeric|min:0',

        ];
    }
    public function rulesForUpdate(): array
    {
        return [
            'name' => 'required|string|max:255',
            'day_surcharge' => 'required|numeric|min:0',
        ];
    }
}