<?php

namespace App\Services;

use App\Models\Cart;
use App\Services\ShippingService;

class GetCartWithShippingService
{
    public function __construct(protected ShippingService $shippingService) {}

    public function execute(Cart $cart, ?string $deliveryType): array
    {
        $cart->load(['items.meal.category', 'items.meal.subcategory']);
        
        if ($deliveryType && in_array($deliveryType, ['delivery', 'pickup'], true)) {
            $shippingFee = $this->shippingService->calculateShippingFee((float) $cart->subtotal, $deliveryType);
            return [$cart, $shippingFee, (float) $cart->total + $shippingFee];
        }

        return [$cart, null, null];
    }
}