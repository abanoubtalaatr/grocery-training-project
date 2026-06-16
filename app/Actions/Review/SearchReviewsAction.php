<?php

namespace App\Actions\Review;

use App\Repositories\ReviewRepository;
use Illuminate\Http\Request;

class SearchReviewsAction
{
    public function __construct(private readonly ReviewRepository $reviewRepository) {}

    public function __invoke(Request $request)
    {
        return $this->reviewRepository->search($request);
    }
}
