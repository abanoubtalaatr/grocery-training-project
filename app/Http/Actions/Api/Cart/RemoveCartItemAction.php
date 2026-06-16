<?php

namespace App\Http\Actions\Api\Cart;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Cart;

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

            $cart->items()
                ->findOrFail($itemId)
                ->delete();

            $cart->calculateTotals();

            return $cart->load([
                'items.meal.category',
                'items.meal.subcategory',
            ]);
        });
    }
}
