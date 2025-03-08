<?php

namespace App\Http\Requests;

use App\Models\Cinema;
use App\Models\Movie;
use App\Models\Room;
use App\Models\Showtime;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TicketRequest extends FormRequest
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
            'user_id'           => ['required', Rule::exists(User::class, 'id')],
            'cinema_id'         => ['required', Rule::exists(Cinema::class, 'id')],
            'room_id'           => ['required', Rule::exists(Room::class, 'id')],
            'movie_id'          => ['required', Rule::exists(Movie::class, 'id')],
            'showtime_id'       => ['required', Rule::exists(Showtime::class, 'id')],
            'voucher_code'      => 'nullable|string|max:50',
            'voucher_discount'  => 'nullable|numeric|min:0',
            'point_use'         => 'nullable|integer|min:0',
            'point_discount'    => 'nullable|numeric|min:0',
            'payment_name'      => 'nullable|string|max:50',
            'ticket_seats'      => 'nullable|array',
            'ticket_combos'     => 'nullable|array',
            'ticket_foods'     => 'nullable|array',
            'total_price'       => 'nullable|numeric',
            'expiry'            => 'nullable|date',
            'status'            => 'nullable|in:pending,confirmed',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required'       => 'Người dùng là bắt buộc.',
            'user_id.exists'         => 'Người dùng không tồn tại.',
            'cinema_id.required'     => 'Rạp chiếu phim là bắt buộc.',
            'cinema_id.exists'       => 'Rạp chiếu phim không tồn tại.',
            'room_id.required'       => 'Phòng chiếu là bắt buộc.',
            'room_id.exists'         => 'Phòng chiếu không tồn tại.',
            'movie_id.required'      => 'Phim là bắt buộc.',
            'movie_id.exists'        => 'Phim không tồn tại.',
            'showtime_id.required'   => 'Suất chiếu là bắt buộc.',
            'showtime_id.exists'     => 'Suất chiếu không tồn tại.',

            'voucher_code.string'    => 'Mã giảm giá phải là một chuỗi ký tự.',
            'voucher_code.max'       => 'Mã giảm giá không được vượt quá 50 ký tự.',

            'voucher_discount.numeric' => 'Giá trị giảm giá phải là một số.',
            'voucher_discount.min'   => 'Giá trị giảm giá không thể nhỏ hơn 0.',

            'point_use.integer'      => 'Điểm sử dụng phải là một số nguyên.',
            'point_use.min'          => 'Điểm sử dụng không thể nhỏ hơn 0.',

            'point_discount.numeric' => 'Giá trị giảm giá bằng điểm phải là một số.',
            'point_discount.min'     => 'Giá trị giảm giá bằng điểm không thể nhỏ hơn 0.',

            'payment_name.string'    => 'Tên phương thức thanh toán phải là một chuỗi ký tự.',
            'payment_name.max'       => 'Tên phương thức thanh toán không được vượt quá 50 ký tự.',

            'ticket_seats.array'     => 'Danh sách ghế phải là một mảng.',

            'total_price.numeric'    => 'Tổng giá phải là một số hợp lệ.',

            'status.in'              => 'Trạng thái phải là pending, paid hoặc canceled.',
        ];
    }
}
