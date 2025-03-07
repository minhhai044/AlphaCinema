<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShowtimeCopyRequest extends FormRequest
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
        return [
            'end_time' => 'required',
            'start_time' => 'required',
            'price_special' => 'nullable',
            'date' => 'required',
            'day_id' => 'required',
            'room_id' => 'required',
            'cinema_id' => 'required',
            'movie_id' => 'required',
            'branch_id' => 'required',
            'seat_structure' => 'required',
        ];
    }
}
