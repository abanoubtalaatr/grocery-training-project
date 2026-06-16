<?php

namespace App\Actions\Review;

use App\Repositories\ReviewRepository;
use Illuminate\Support\Facades\Auth;
use Exception;

class UpdateReviewAction
{
    public function __construct(private readonly ReviewRepository $reviewRepository) {}

    public function __invoke(int $id, array $data)
    {
        $review = $this->reviewRepository->findById($id);
        
        if (Auth::id() !== $review->user_id && !Auth::user()->is_admin) {
            throw new Exception('Unauthorized', 403);
        }
        
        $review->update($data);
        
        return $review->load(['user', 'meal']);
    }
}
