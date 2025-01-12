<?php

namespace App\Traits;

trait ApiRequestJsonTrait
{
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $errors = $validator->errors();

        $response = response()->json([
            'message' => 'Validation failed',
            'errors' => $errors->messages(),
        ], \Illuminate\Http\Response::HTTP_BAD_REQUEST);

        throw new \Illuminate\Http\Exceptions\HttpResponseException($response);
    }
}
