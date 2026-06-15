<?php

namespace App\Actions\Cart;

use App\Models\Cart;
use Illuminate\Support\Facades\DB;

class RemoveCartItemAction
{
    public function execute(Cart $cart, string $itemId): Cart
    {
        $cartItem = $cart->items()->findOrFail($itemId);

        return DB::transaction(function () use ($cart, $cartItem) {
            $cartItem->delete();
            $cart->calculateTotals();
            return $cart->load(['items.meal.category', 'items.meal.subcategory']);
        });
    }
}