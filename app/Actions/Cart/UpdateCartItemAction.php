<?php

namespace App\Actions\Cart;

use App\Models\User;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UpdateCartItemAction
{
    public function execute(
        User $user,
        string $itemId,
        int $quantity
    ): Cart {

        return DB::transaction(function () use (
            $user,
            $itemId,
            $quantity
        ) {

            $cart = $user->getOrCreateCart();

            $cartItem = $cart->items()
                ->findOrFail($itemId);

            $meal = $cartItem->meal;

            if ($meal->stock_quantity < $quantity) {
                throw ValidationException::withMessages([
                    'quantity' => ["Only {$meal->stock_quantity} items available in stock."],
                ]);
            }

            $cartItem->update([
                'quantity' => $quantity,
            ]);

            $cart->calculateTotals();

            return $cart->load([
                'items.meal.category',
                'items.meal.subcategory',
            ]);
        });
    }
}
