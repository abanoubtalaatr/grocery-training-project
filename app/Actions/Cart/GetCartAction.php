<?php

namespace App\Actions\Cart;

use App\Models\User;
use App\Repositories\CartRepository;
use App\Services\ShippingService;
use App\Traits\FormatsCart;

class GetCartAction
{
    use FormatsCart;

    public function __construct(private readonly CartRepository $cartRepository, private readonly ShippingService $shippingService) {}

    public function __invoke(User $user, ?string $deliveryType): array
    {
        $cart = $this->cartRepository->getOrCreateForUser($user);
        
        $shippingFee = null;
        $totalWithShipping = null;
        
        if ($deliveryType && in_array($deliveryType, ['delivery', 'pickup'], true)) {
            $shippingFee = $this->shippingService->calculateShippingFee((float) $cart->subtotal, $deliveryType);
            $totalWithShipping = (float) $cart->total + $shippingFee;
        }

        return $this->formatCart($cart, $shippingFee, $totalWithShipping);
    }
}
