<?php

namespace App\Traits;

trait ApiResponseTrait
{
    protected function successResponse($data, $message, $statusCode)
    {
        if (empty($data)) {
            return response()->json([
                'status' => 'success',
                'message' => $message,
            ], status: $statusCode);
        }
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }
    protected function errorResponse($e, $statusCode = 500)
    {
        return response()->json([
            'status' => 'error',
            'error' => $e,
        ], $statusCode);
    }
}
