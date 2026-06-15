<?php

namespace App\Traits\V1;

trait ApiResponse
{
  public static function successResponse($message = null, $result = null, $code = 200)
  {
    $response = [
      'status' => $code,
      'message' => $message,
      'data'    => $result,
    ];
    return response()->json($response, $code);
  }

  public static function errorResponse($message = null, $result = null, $code = 404)
  {
    $response = [
      'status' => $code,
      'message' => $message,
      'data'    => $result,
    ];
    return response()->json($response, $code);
  }
}
