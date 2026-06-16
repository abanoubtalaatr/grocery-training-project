<?php

namespace App\Actions\Cart;

use App\Models\User;
use App\Models\Cart;
use App\Services\ShippingService;

class GetCartAction
{
    public function __construct(
        private readonly ShippingService $shippingService
    ) {
    }

    public function execute(
        User $user,
        ?string $deliveryType = null
    ): Cart {

        $cart = $user->getOrCreateCart();

        $cart->load([
            'items.meal.category',
            'items.meal.subcategory',
        ]);

        $shippingFee = null;
        $totalWithShipping = null;

        if (
            $deliveryType &&
            in_array($deliveryType, ['delivery', 'pickup'], true)
        ) {
            $shippingFee = $this->shippingService
                ->calculateShippingFee(
                    (float) $cart->subtotal,
                    $deliveryType
                );

            $totalWithShipping =
                (float) $cart->total +
                $shippingFee;
        }

        $cart->setAttribute('shipping_fee', $shippingFee);
        $cart->setAttribute('total_with_shipping', $totalWithShipping);

        return $cart;
    }
}
