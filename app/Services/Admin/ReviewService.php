<?php

namespace App\Services\Admin;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewService
{
    public function paginate(
        Request $request,
        int $perPage = 10
    )
    {
        return Review::query()
            ->with([
                'user',
                'meal',
            ])
            ->filter($request)
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function approve(
        Review $review
    ): bool {
        return $review->update([
            'is_approved' => true,
        ]);
    }

    public function reject(
        Review $review
    ): bool {
        return $review->update([
            'is_approved' => false,
        ]);
    }

    public function delete(
        Review $review
    ): bool {
        return $review->delete();
    }
}