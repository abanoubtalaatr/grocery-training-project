<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponse
{
    /**
     * Return a standardized success JSON response.
     */
    protected function success( $data = [], string $message = 'Success', int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'status'  => true,
            'message' => $message,
            'data'    => $data,
        ], $statusCode);
    }

    /**
     * Return a standardized error JSON response.
     */
    protected function error(string $message = 'Error occurred', int $statusCode = Response::HTTP_BAD_REQUEST, mixed $errors = null): JsonResponse
    {
        return response()->json([
            'status'  => false,
            'message' => $message,
            'errors'  => $errors,
        ], $statusCode);
    }
    
   
}
