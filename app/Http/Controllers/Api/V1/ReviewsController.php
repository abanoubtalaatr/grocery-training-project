<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;

class ReviewsController extends Controller
{
    public function index(Request $request)
    {
        $reviews = Review::query()->filter($request)->get();
        
        return response()->json($reviews);
    }
}
