<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Http\Resources\ReviewResource;
class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $reviews = Review::query()->filter($request);

        return response()->json(ReviewResource::collection($reviews->get()));
    }
}
