<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $reviews = Review::query()
            ->with(['user', 'meal'])
            ->filter($request);

        return response()->json([
            'success' => true,
            'data' => $reviews->get()
        ]);
    }

    public function show($id)
    {
        $review = Review::with(['user', 'meal'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $review
        ]);
    }
}
