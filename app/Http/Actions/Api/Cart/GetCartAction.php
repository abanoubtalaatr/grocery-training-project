<?php

namespace App\Http\Actions\Api\Cart;

use App\Models\Cart;
use App\Models\User;
use App\Services\ShippingService;

class GetCartAction
{
    public function __construct(
        private ShippingService $shippingService
    ) {}

    public function execute(
        User $user,
        ?string $deliveryType = null
    ): Cart {

        $cart = $user->getOrCreateCart();

        $cart->load([
            'items.meal.category',
            'items.meal.subcategory',
        ]);

        if (
            $deliveryType &&
            in_array(
                $deliveryType,
                ['delivery', 'pickup'],
                true
            )
        ) {
            $shippingFee =
                $this->shippingService
                    ->calculateShippingFee(
                        (float) $cart->subtotal,
                        $deliveryType
                    );

            $cart->shipping_fee = $shippingFee;

            $cart->total_with_shipping =
                (float) $cart->total +
                $shippingFee;
        }

        return $cart;
    }
}