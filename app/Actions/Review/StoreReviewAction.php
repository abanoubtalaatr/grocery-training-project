<?php

namespace App\Actions\Review;

use App\Repositories\ReviewRepository;
use Illuminate\Support\Facades\Auth;
use Exception;

class StoreReviewAction
{
    public function __construct(private readonly ReviewRepository $reviewRepository) {}

    public function __invoke(array $data)
    {
        $userId = Auth::id();
        
        if ($this->reviewRepository->hasUserReviewed($userId, $data['meal_id'])) {
            throw new Exception('You have already reviewed this meal', 400);
        }
        
        $reviewData = [
            'user_id' => $userId,
            'meal_id' => $data['meal_id'],
            'rating' => $data['rating'],
            'comment' => $data['comment'],
            'images' => $data['images'] ?? null,
            'is_approved' => false,
        ];
        
        return $this->reviewRepository->create($reviewData)->load(['user', 'meal']);
    }
}
