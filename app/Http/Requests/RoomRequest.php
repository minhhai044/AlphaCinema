<?php

namespace App\Http\Requests;

use App\Models\Branch;
use App\Models\Cinema;
use App\Models\Room;
use App\Models\Seat_template;
use App\Models\Type_room;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoomRequest extends FormRequest
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
            'name'              => ['required', Rule::unique(Room::class)],
            'branch_id'         => ['required', Rule::exists(Branch::class, 'id')],
            'cinema_id'         => ['required', Rule::exists(Cinema::class, 'id')],
            'seat_template_id'  => ['required', Rule::exists(Seat_template::class, 'id')],
            'type_room_id'      => ['required', Rule::exists(Type_room::class, 'id')],
            'description'       => 'nullable',
            'is_publish'       => 'nullable',
            'seat_structure'   => 'required',
            'matrix_colume'    => 'required',
        ];
    }

    public function rulesForUpdate()
    {
        return [
            'name'              => ['required', Rule::unique(Room::class)->ignore($this->route('id'))],
            'branch_id'         => ['required', Rule::exists(Branch::class, 'id')],
            'cinema_id'         => ['required', Rule::exists(Cinema::class, 'id')],
            'seat_template_id'  => ['required', Rule::exists(Seat_template::class, 'id')],
            'type_room_id'      => ['required', Rule::exists(Type_room::class, 'id')],
            'description'       => 'nullable',
            'is_publish'       => 'nullable',
            'seat_structure'   => 'required',
            'matrix_colume'    => 'required',
        ];
    }

    // messages chung
    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên !!!',
            'name.unique' => 'Tên đã tồn tại !!!',
            'branch_id.required' => 'Vui lòng chọn chi nhánh !!!',
            'branch_id.exists' => 'Chi nhánh không tồn tại !!!',
            'cinema_id.required' => 'Vui lòng chọn rạp phim !!!',
            'cinema_id.exists' => 'Rạp phim không tồn tại !!!',
            'seat_template_id.required' => 'Vui lòng chọn mẫu ghế !!!',
            'seat_template_id.exists' => 'Mẫu ghế không tồn tại !!!',
            'type_room_id.required' => 'Vui lòng chọn loại phòng !!!',
            'type_room_id.exists' => 'Loại phòng không tồn tại !!!',
            'seat_structures.required' => 'Chưa có mô hình ghế trong mẫu ghế !!!',
        ];
    }
}
