<?php 

namespace App\Traits;


trait ApiResponse
{
    protected function successResponse($data = [], $message, $status = 200)
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => $message,
            'key' => $status,
        ], $status);
    }

    protected function errorResponse($message, $errors = [], $status = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'code' => $status
        ], $status);
    }
}