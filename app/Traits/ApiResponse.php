<?php
namespace App\Traits;

trait ApiResponse
{
    public function sendResponse($message,$data=[], $statusCode = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    } 
      public function sendError($error, $statusCode = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $error
        ], $statusCode);
    }
}