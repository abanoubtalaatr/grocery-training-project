<?php

namespace App\Actions\Review;

use App\Repositories\ReviewRepository;

class GetMealReviewStatsAction
{
    public function __construct(private readonly ReviewRepository $reviewRepository) {}

    public function __invoke(int $mealId)
    {
        $stats = $this->reviewRepository->getStats($mealId);
        
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
