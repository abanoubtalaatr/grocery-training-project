<?php

namespace App\Actions\Review;

use App\Repositories\ReviewRepository;
use Illuminate\Support\Facades\Auth;

class GetUserReviewsAction
{
    public function __construct(private readonly ReviewRepository $reviewRepository) {}

    public function __invoke(?int $requestedUserId, int $perPage)
    {
        $userId = $requestedUserId ?? Auth::id();
        
        return $this->reviewRepository->getForUser($userId, $perPage);
    }
}
