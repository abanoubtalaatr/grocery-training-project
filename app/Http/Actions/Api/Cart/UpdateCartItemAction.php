<?php

namespace App\Http\Actions\Api\Cart;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\Cart;


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

            if (
                $cartItem->meal->stock_quantity
                < $quantity
            ) {
                throw ValidationException::withMessages([
                    'quantity' => [
                        'Insufficient stock'
                    ]
                ]);
            }

            $cartItem->update([
                'quantity' => $quantity
            ]);

            $cart->calculateTotals();

            return $cart->load([
                'items.meal.category',
                'items.meal.subcategory',
            ]);
        });
    }
}