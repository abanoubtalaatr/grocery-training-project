<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    public function reviews(Request $request)
    {
        $reviews = Review::query()->filter($request);

        return response()->json($reviews->get());
    }
}
