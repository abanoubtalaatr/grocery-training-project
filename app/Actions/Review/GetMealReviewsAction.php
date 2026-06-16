<?php

namespace App\Actions\Review;

use App\Repositories\ReviewRepository;

class GetMealReviewsAction
{
    public function __construct(private readonly ReviewRepository $reviewRepository) {}

    public function __invoke(int $mealId, int $perPage)
    {
        $meal = $this->reviewRepository->findMeal($mealId);
        
        $reviews = $this->reviewRepository->getForMeal($mealId, $perPage);
        $averageRating = $this->reviewRepository->getAverageRating($mealId);
        $totalReviews = $this->reviewRepository->getTotalReviews($mealId);
        
        return [
            'meal' => $meal,
            'reviews' => $reviews,
            'average_rating' => $averageRating,
            'total_reviews' => $totalReviews,
        ];
    }
}
