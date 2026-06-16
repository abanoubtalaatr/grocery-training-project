<?php

namespace App\Actions\Cart;

use App\Models\User;
use App\Repositories\CartRepository;
use App\Traits\FormatsCart;
use Illuminate\Support\Facades\DB;

class ClearCartAction
{
    use FormatsCart;

    public function __construct(private readonly CartRepository $cartRepository) {}

    public function __invoke(User $user): array
    {
        $cart = $this->cartRepository->getOrCreateForUser($user);

        return DB::transaction(function () use ($cart) {
            $cart->items()->delete();
            $cart->calculateTotals();

            return $this->formatCart($cart);
        });
    }
}
