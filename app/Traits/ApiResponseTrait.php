<?php

namespace App\Traits;

trait ApiResponseTrait
{
    protected function successResponse($data, $message, $statusCode)
    {
        $response = [
            'status' => 'success',
            'message' => $message,
        ];

        if (!empty($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $statusCode);
    }
    protected function errorResponse($e, $statusCode = 500)
    {
        return response()->json([
            'status' => 'error',
            'error' => $e,
        ], $statusCode);
    }
}
