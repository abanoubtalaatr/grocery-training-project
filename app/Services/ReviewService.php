<?php

namespace App\Services;

use App\Models\Review;
use App\Models\Meal;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class ReviewService
{
    public function getReviews(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = Review::query()
            ->with(['user', 'meal'])
            ->latest();

        if (isset($filters['meal_id'])) {
            $query->where('meal_id', $filters['meal_id']);
        }

        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (isset($filters['rating'])) {
            $query->where('rating', $filters['rating']);
        }

        if ($filters['approved_only'] ?? true) {
            $query->approved();
        }

        if (isset($filters['min_rating'])) {
            $query->where('rating', '>=', $filters['min_rating']);
        }

        return $query->paginate($perPage);
    }

    public function createReview(array $data): Review
    {
        return Review::create([
            'user_id' => Auth::id(),
            'meal_id' => $data['meal_id'],
            'rating' => $data['rating'],
            'comment' => $data['comment'],
            'images' => $data['images'] ?? null,
            'is_approved' => false,
        ]);
    }

    public function getMealStats(int $mealId): array
    {
        $stats = Review::where('meal_id', $mealId)
            ->approved()
            ->selectRaw('
                COUNT(*) as total_reviews,
                AVG(rating) as average_rating,
                COUNT(CASE WHEN rating = 5 THEN 1 END) as five_star,
                COUNT(CASE WHEN rating = 4 THEN 1 END) as four_star,
                COUNT(CASE WHEN rating = 3 THEN 1 END) as three_star,
                COUNT(CASE WHEN rating = 2 THEN 1 END) as two_star,
                COUNT(CASE WHEN rating = 1 THEN 1 END) as one_star
            ')
            ->first();

        return [
            'total_reviews' => (int) ($stats->total_reviews ?? 0),
            'average_rating' => round($stats->average_rating ?? 0, 1),
            'rating_distribution' => [
                'five_star' => (int) ($stats->five_star ?? 0),
                'four_star' => (int) ($stats->four_star ?? 0),
                'three_star' => (int) ($stats->three_star ?? 0),
                'two_star' => (int) ($stats->two_star ?? 0),
                'one_star' => (int) ($stats->one_star ?? 0),
            ]
        ];
    }
}
