<?php

namespace App\Actions\Offer;

use App\Repositories\OfferRepository;

class GetOffersAction
{
    public function __construct(private readonly OfferRepository $offerRepository) {}

    public function __invoke(array $filters, int $perPage, string $orderBy, string $orderDirection)
    {
        return $this->offerRepository->getFiltered($filters, $perPage, $orderBy, $orderDirection);
    }
}
