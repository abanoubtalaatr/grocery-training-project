<?php

namespace App\Actions\Offer;

use App\Repositories\OfferRepository;

class ValidateOfferAction
{
    public function __construct(private readonly OfferRepository $offerRepository) {}

    public function __invoke(string $code, ?float $amount)
    {
        $offer = $this->offerRepository->findByCodeNullable($code);
        
        if (!$offer) {
            return [
                'valid' => false, 
                'message' => 'Invalid offer code', 
                'status' => 404
            ];
        }
        
        $isValid = $offer->isValid();
        $canApply = true;
        $message = 'Offer is valid';
        
        if ($isValid && $amount !== null) {
            $canApply = $offer->canApplyToAmount($amount);
            if (!$canApply) {
                $message = 'Minimum purchase required: $' . $offer->minimum_purchase;
            }
        }
        
        $discount = $canApply && $isValid 
            ? $offer->calculateDiscount($amount ?? 0)
            : 0;
            
        return [
            'valid' => $isValid && $canApply,
            'offer' => $offer,
            'discount_amount' => $discount,
            'message' => $message,
            'status' => 200
        ];
    }
}
