<?php

namespace App\Services\Admin;

use App\Models\Offer;
use Illuminate\Http\Request;

class OfferService
{
    public function paginate(
        Request $request,
        int $perPage = 10
    )
    {
        return Offer::query()
            ->filter($request)
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function store(array $data): Offer
    {
        return Offer::create($data);
    }

    public function update(
        Offer $offer,
        array $data
    ): bool {
        return $offer->update($data);
    }

    public function delete(
        Offer $offer
    ): bool {
        return $offer->delete();
    }
}