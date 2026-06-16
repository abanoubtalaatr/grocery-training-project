<?php

namespace App\Http\Actions\Api\Offer;

use App\Models\Offer;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ValidateOfferAction
{
    public function execute(
        string $code,
        ?float $amount
    ): array {

        $offer = Offer::where(
            'code',
            $code
        )->first();

        if (!$offer) {
            throw new ModelNotFoundException();
        }

        $isValid = $offer->isValid();

        $canApply = true;

        $message = 'Offer is valid';

        if ($isValid && $amount !== null) {

            $canApply = $offer->canApplyToAmount(
                $amount
            );

            if (!$canApply) {
                $message =
                    'Minimum purchase required: $'
                    . $offer->minimum_purchase;
            }
        }

        return [
            'offer' => $offer,

            'valid' => $isValid && $canApply,

            'discount_amount' =>
                ($isValid && $canApply)
                    ? $offer->calculateDiscount(
                        $amount ?? 0
                    )
                    : 0,

            'message' => $message,
        ];
    }
}