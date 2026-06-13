<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewsController extends Controller
{
    public function index(Request $request)
    {
        // Assuming you have a Review model and it has a relationship with Meal
        $reviews = Review::query()->filter($request);

        return response()->json($reviews->get());
    }

}
