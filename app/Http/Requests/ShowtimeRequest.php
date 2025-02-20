<?php

namespace App\Http\Requests;

use App\Models\Branch;
use App\Models\Cinema;
use App\Models\Day;
use App\Models\Movie;
use App\Models\Room;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ShowtimeRequest extends FormRequest
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
            'movie_id'       => ['required', Rule::exists('movies', 'id')],
            'branch_id'      => ['required', Rule::exists('branches', 'id')],
            'cinema_id'      => ['required', Rule::exists('cinemas', 'id')],
            'day_id'         => ['required', Rule::exists('days', 'id')],
            'room_id'        => ['required', Rule::exists('rooms', 'id')],
            'seat_structure' => ['required'],
            'slug'           => ['required', 'string', 'max:255'],
            'date'           => ['required', 'date'],
            'start_time'     => ['required', 'array'],
            'start_time.*'   => ['date_format:H:i'],
            'end_time'       => ['required', 'array'],
            'end_time.*'     => ['date_format:H:i'],
            'price_special'  => ['nullable'],
            'status_special' => ['required']
        ];
    }

    public function rulesForUpdate()
    {
        return [];
    }

    // messages chung
    public function messages()
    {
        return [];
    }
}
