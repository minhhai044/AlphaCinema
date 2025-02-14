<?php

namespace App\Http\Requests;

use App\Traits\ApiRequestJsonTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class RankRequest extends FormRequest
{
    // Chuyển validate thành Json
    // use ApiRequestJsonTrait;
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
            'name'              => 'required|string|max:255|unique:ranks',
            'total_spent'       => ['required', 'integer', 'min:500000', 'max:5000000', 'unique:ranks,total_spent', 'regex:/^\d*00000$/'],
            'ticket_percentage' => 'required|integer|min:0|max:20',
            'combo_percentage'  => 'required|integer|min:0|max:20'
        ];
    }

    public function rulesForUpdate()
    {
        return [
            'name' => 'required|string|max:255|unique:ranks,name,' . $this->route('rank')->id,
            'ticket_percentage' => 'required|integer|min:0|max:20',
            'combo_percentage' => 'required|integer|min:0|max:20',
        ];
    }

    // messages chung
    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên cấp bậc.',
            'name.string' => 'Tên cấp bậc phải là một chuỗi ký tự.',
            'name.max' => 'Tên cấp bậc không được vượt quá 255 ký tự.',
            'name.unique' => 'Tên cấp bậc đã tồn tại, vui lòng chọn tên khác.',
            'total_spent.required' => 'Vui lòng nhập tổng chi tiêu.',
            'total_spent.integer' => 'Tổng chi tiêu phải là một số nguyên.',
            'total_spent.min' => 'Tổng chi tiêu tối thiểu phải bằng 500.000 VNĐ.',
            'total_spent.max' => 'Tổng chi tiêu không được quá 5.000.000 VNĐ.',
            'total_spent.regex' => 'Tổng chi tiêu chia hết cho 100.000.',
            'total_spent.unique' => 'Tổng chi tiêu đã tồn tại cho cấp bậc khác',
            'ticket_percentage.required' => 'Vui lòng nhập phần trăm tích điểm vé.',
            'ticket_percentage.integer' => 'Phần trăm tích điểm vé phải là một số nguyên.',
            'ticket_percentage.min' => 'Phần trăm tích điểm vé tối thiểu phải bằng 0%',
            'ticket_percentage.max' => 'Phần trăm tích điểm vé không được quá 20%',
            'combo_percentage.required' => 'Vui lòng nhập phần trăm tích điểm combo.',
            'combo_percentage.integer' => 'Phần trăm tích điểm combo phải là một số nguyên.',
            'combo_percentage.min' => 'Phần trăm tích điểm combo tối thiểu phải bằng 0%.',
            'combo_percentage.max' => 'Phần trăm tích điểm combo không được vượt quá 20%.',
            'ticket_percentage.greater_than_previous' => 'Phần trăm tích điểm vé phải lớn hơn cấp bậc có tổng chi tiêu thấp hơn.',
            'combo_percentage.greater_than_previous' => 'Phần trăm tích điểm combo phải lớn hơn cấp bậc có tổng chi tiêu thấp hơn.',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $errors = $validator->errors();
        $errorMessages = [];

        if ($this->isMethod('post')) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        foreach ($errors->messages() as $field => $message) {
            $errorMessages[$field] = array_shift($message);
        }

        $response = response()->json([
            'status' => false,
            'errors' => $errorMessages
        ], Response::HTTP_BAD_REQUEST);


        throw  new HttpResponseException($response);
    }
}
