<?php

namespace App\Http\Requests;

use App\Models\Type_seat;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class TypeSeatRequest extends FormRequest
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
                Rule::unique(Type_seat::class), // Bỏ qua ID hiện tại
            ],
            'price' => 'required|numeric|min:0|max:9999999999',
        ];
    }

    public function rulesForUpdate()
    {
        return [
            'price' => 'required|numeric|min:0|max:9999999999',
        ];
    }
    // kiểm tra logic
    // public function withValidator(Validator $validator)
    // {
    //     $validator->after(
    //         function ($validator) {
    //             $typeSeatId = $this->route('type_seat'); // Lấy ID loại ghế từ route
    //             $typeSeat = Type_seat::find($typeSeatId);

    //             // Kiểm tra nếu loại ghế không tồn tại
    //             if (!$typeSeat) {
    //                 $validator->errors()->add('price', 'Loại ghế không tồn tại!');
    //                 return;
    //             }
    //             $newPrice = (float)$this->input('price');

    //             // Lấy giá hiện tại của tất cả loại ghế
    //             $allSeats = Type_seat::all()->pluck('price', 'id')->map(fn($value) => (float) $value);

    //             $priceRegular = $allSeats[1] ?? 0;
    //             $priceVIP = $allSeats[2] ?? 0;
    //             $priceDouble = $allSeats[3] ?? 0;
    //             // dd([
    //             //     'typeSeatId' => $typeSeatId,
    //             //     'newPrice' => (int) $this->input('price'),
    //             //     'allSeats' => $allSeats,
    //             //     'priceRegular' => $allSeats[1] ?? 0,
    //             //     'priceVIP' => $allSeats[2] ?? 0,
    //             //     'priceDouble' => $allSeats[3] ?? 0,
    //             // ]);
    //             // Kiểm tra điều kiện giá theo ID
    //             if ($typeSeat->id == 1 && $newPrice >= $priceVIP) {
    //                 $validator->errors()->add('price', 'Giá ghế thường phải nhỏ hơn giá ghế VIP!');
    //             }

    //             if ($typeSeat->id == 2 && ($newPrice <= $priceRegular || $newPrice >= $priceDouble)) {
    //                 $validator->errors()->add('price', 'Giá ghế VIP phải lớn hơn ghế thường và nhỏ hơn ghế đôi!');
    //             }

    //             if ($typeSeat->id == 3 && $newPrice <= $priceVIP) {
    //                 $validator->errors()->add('price', 'Giá ghế đôi phải lớn hơn giá ghế VIP!');
    //             }
    //         }
    //     );
    // }
    // messages chung
    public function messages()
    {
        return [
            'name.required' => 'Vui lòng điền name',
            'price.required' => 'Vui lòng điền lệch giá ',
        ];
    }
}
