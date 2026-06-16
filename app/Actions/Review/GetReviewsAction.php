<?php

namespace App\Actions\Review;

use App\Repositories\ReviewRepository;

class GetReviewsAction
{
    public function __construct(private readonly ReviewRepository $reviewRepository) {}

    public function __invoke(array $filters, int $perPage)
    {
        return $this->reviewRepository->getFiltered($filters, $perPage);
    }
}
