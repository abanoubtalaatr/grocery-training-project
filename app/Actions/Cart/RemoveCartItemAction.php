<?php

namespace App\Actions\Cart;

use App\Models\Cart;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class RemoveCartItemAction
{
    use AsAction;

    /**
     * Handle removing an item from the cart.
     */
    public function handle(Cart $cart, string $itemId): Cart
    {
        $cartItem = $cart->items()->findOrFail($itemId);

        return DB::transaction(function () use ($cart, $cartItem) {
            $cartItem->delete();

            $cart->calculateTotals();
            $cart->load(['items.meal.category', 'items.meal.subcategory']);

            return $cart;
        });
    }
}
