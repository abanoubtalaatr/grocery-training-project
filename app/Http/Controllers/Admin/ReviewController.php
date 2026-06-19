<?php

namespace App\Http\Controllers\Admin;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Admin\ReviewService;

class ReviewController extends Controller
{
    public function __construct(
        private ReviewService $reviewService
    ) {}

    public function index(Request $request)
    {
        $reviews = $this->reviewService
            ->paginate($request);

        return view(
            'admin.reviews.index',
            compact('reviews')
        );
    }

    public function show(
        Review $review
    ) {
        $review->load([
            'user',
            'meal',
        ]);

        return view(
            'admin.reviews.show',
            compact('review')
        );
    }

    public function approve(
        Review $review
    ) {
        $this->reviewService
            ->approve($review);

        return back()->with(
            'success',
            'Review approved successfully.'
        );
    }

    public function reject(
        Review $review
    ) {
        $this->reviewService
            ->reject($review);

        return back()->with(
            'success',
            'Review rejected successfully.'
        );
    }

    public function destroy(
        Review $review
    ) {
        $this->reviewService
            ->delete($review);

        return back()->with(
            'success',
            'Review deleted successfully.'
        );
    }
}