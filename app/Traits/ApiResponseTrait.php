<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait ApiResponseTrait
{
    protected function successResponse($data = [], $message = '', $statusCode = Response::HTTP_OK)
    {
        $response = [
            'status' => true,
            'message' => $message,
        ];

        if (!empty($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $statusCode);
    }
    protected function errorResponse($error, $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        return response()->json([
            'status' => false,
            'error' => $error,
        ], $statusCode);
    }
}
