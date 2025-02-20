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
        return [
            'movie_id.required'       => 'Vui lòng chọn phim !!!',
            'movie_id.exists'         => 'Phim không hợp lệ !!!',

            'branch_id.required'      => 'Vui lòng chọn chi nhánh !!!',
            'branch_id.exists'        => 'Chi nhánh không hợp lệ !!!',

            'cinema_id.required'      => 'Vui lòng chọn rạp chiếu !!!',
            'cinema_id.exists'        => 'Rạp chiếu không hợp lệ !!!',

            'day_id.required'         => 'Vui lòng chọn ngày chiếu !!!',
            'day_id.exists'           => 'Ngày chiếu không hợp lệ !!!',

            'room_id.required'        => 'Vui lòng chọn phòng chiếu !!!',
            'room_id.exists'          => 'Phòng chiếu không hợp lệ !!!',

            'seat_structure.required' => 'Vui lòng nhập cấu trúc ghế !!!',

            'slug.required'           => 'Vui lòng nhập slug !!!',
            'slug.string'             => 'Slug phải là chuỗi !!!',
            'slug.max'                => 'Slug không được vượt quá 255 ký tự !!!',

            'date.required'           => 'Vui lòng chọn ngày chiếu !!!',
            'date.date'               => 'Ngày chiếu không hợp lệ !!!',

            'start_time.required'     => 'Vui lòng nhập thời gian bắt đầu !!!',
            'start_time.array'        => 'Thời gian bắt đầu phải là một mảng !!!',
            'start_time.*.date_format' => 'Thời gian bắt đầu phải có định dạng HH:mm !!!',

            'end_time.required'       => 'Vui lòng nhập thời gian kết thúc !!!',
            'end_time.array'          => 'Thời gian kết thúc phải là một mảng !!!',
            'end_time.*.date_format'  => 'Thời gian kết thúc phải có định dạng HH:mm !!!',

            'status_special.required' => 'Vui lòng chọn trạng thái đặc biệt !!!',
        ];
    }
}
