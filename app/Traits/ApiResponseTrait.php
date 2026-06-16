<?php

namespace App\Traits;

trait ApiResponseTrait
{
    public function successResponse(array $data, string $message = 'Success', $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $code);

    }

    public function errorResponse($message = 'Error', $code = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ], $code);
    }
}