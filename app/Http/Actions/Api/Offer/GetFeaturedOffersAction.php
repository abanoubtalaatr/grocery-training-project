<?php

namespace App\Http\Actions\Api\Offer;

use App\Models\Offer;
use Illuminate\Support\Collection;

class GetFeaturedOffersAction
{
    public function execute(): Collection
    {
        return Offer::featured()
            ->latest()
            ->limit(5)
            ->get();
    }
}