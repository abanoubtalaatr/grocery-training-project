<?php

namespace App\Actions\Review;

use App\Repositories\ReviewRepository;

class GetReviewAction
{
    public function __construct(private readonly ReviewRepository $reviewRepository) {}

    public function __invoke(int $id)
    {
        return $this->reviewRepository->findByIdWithRelations($id);
    }
}
