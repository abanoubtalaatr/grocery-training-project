<?php

namespace App\Actions\Offer;

use App\Repositories\OfferRepository;

class GetFeaturedOffersAction
{
    public function __construct(private readonly OfferRepository $offerRepository) {}

    public function __invoke()
    {
        return $this->offerRepository->getFeatured();
    }
}
