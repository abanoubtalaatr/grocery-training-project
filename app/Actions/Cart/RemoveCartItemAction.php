<?php

namespace App\Actions\Cart;

use App\Models\User;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;

class RemoveCartItemAction
{
    public function execute(
        User $user,
        string $itemId
    ): Cart {

        return DB::transaction(function () use (
            $user,
            $itemId
        ) {

            $cart = $user->getOrCreateCart();

            $cartItem = $cart->items()
                ->findOrFail($itemId);

            $cartItem->delete();

            $cart->calculateTotals();

            return $cart->load([
                'items.meal.category',
                'items.meal.subcategory',
            ]);
        });
    }
}