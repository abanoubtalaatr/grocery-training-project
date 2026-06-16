<?php

namespace App\Traits\V1;

use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponseCollection
{
    public static function collectionResponse($message = null, $result = null, $extra = [], $code = 200)
    {
        // If 3rd argument is Numeric, treat it as the status code (BC support)
        if (is_numeric($extra)) {
            $code = $extra;
            $extra = [];
        }

        $count = 0;
        if ($result instanceof Collection) {
            $count = $result->count();
        } elseif ($result instanceof LengthAwarePaginator) {
            $count = $result->total();
        } elseif ($result instanceof \Illuminate\Http\Resources\Json\AnonymousResourceCollection) {
            $count = $result->resource instanceof LengthAwarePaginator ? $result->resource->total() : count($result);
        } elseif (is_array($result)) {
            $count = count($result);
        }

        $response = [
            'status' => $code,
            'message' => $message,
            'data'    => $result,
            'total_count' => $count,
        ];

        if (!empty($extra)) {
            $response = array_merge($response, $extra);
        }

        return response()->json($response, $code);
    }
}
