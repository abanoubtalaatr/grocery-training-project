<?php

namespace App\Actions\Offer;

use App\Repositories\OfferRepository;

class GetOfferByCodeAction
{
    public function __construct(private readonly OfferRepository $offerRepository) {}

    public function __invoke(string $code)
    {
        return $this->offerRepository->findByCode($code);
    }
}
