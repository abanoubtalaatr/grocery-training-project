<?php

namespace App\Traits\V1;

use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponseCollection
{
    public static function collectionResponse($message = null, $result = null, $code = 200)
    {
        $count = 0;
        if ($result instanceof Collection) {
            $count = $result->count();
        } elseif ($result instanceof LengthAwarePaginator) {
            $count = $result->total();
        } elseif (is_array($result)) {
            $count = count($result);
        }

        $response = [
            'status' => $code,
            'message' => $message,
            'data'    => $result,
            'total_count' => $count,
        ];

        return response()->json($response, $code);
    }
}
