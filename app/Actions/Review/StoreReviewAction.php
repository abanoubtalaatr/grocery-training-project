<?php

namespace App\Actions\Review;

use App\Exceptions\BusinessException;
use App\Models\Meal;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreReviewAction
{
    use AsAction;

    /**
     * Create a review for a meal and recalculate the meal's rating.
     */
    public function handle(User $user, Meal $meal, array $data): Review
    {
        return DB::transaction(function () use ($user, $meal, $data) {
            // Check if user has already reviewed this meal
            if (Review::hasUserReviewed($user->id, $meal->id)) {
                throw new BusinessException('You have already reviewed this meal', 400);
            }

            // Create review (default to pending approval)
            $review = Review::create([
                'user_id' => $user->id,
                'meal_id' => $meal->id,
                'rating' => $data['rating'],
                'comment' => $data['comment'] ?? null,
                'images' => $data['images'] ?? [],
                'is_approved' => false,
            ]);

            // Recalculate and update the meal's rating and rating_count using only approved reviews
            $averageRating = Review::getAverageRating($meal->id);
            $totalReviews = Review::getTotalReviews($meal->id);

            $meal->update([
                'rating' => $averageRating,
                'rating_count' => $totalReviews,
            ]);

            return $review;
        });
    }
}
