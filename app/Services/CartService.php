<?php

namespace App\Services;

use App\Exceptions\CartValidationException;
use App\Models\User;

class CartService
{
    public function clearUserCart(User $user): void
    {
        $cart = $user->activeCart()->first();
        if ($cart) {
            $cart->items()->delete();
            $cart->update(['status' => 'completed']);
        }
    }

    public function validateAndProcessCartItems($cartItems): array
    {
        $items = [];
        $subtotal = 0;

        foreach ($cartItems as $cartItem) {
            $meal = $cartItem->meal;

            if (! $meal) {
                throw new CartValidationException('One or more items are no longer available.');
            }

            if (! $meal->is_available) {
                throw new CartValidationException("Meal '{$meal->title}' is currently unavailable");
            }

            if ($meal->stock_quantity < $cartItem->quantity) {
                throw new CartValidationException("Only {$meal->stock_quantity} items available for '{$meal->title}'");
            }

            $max = config('cart.max_quantity_per_product', 10);
            if ($cartItem->quantity > $max) {
                throw new CartValidationException("Max {$max} units allowed for '{$meal->title}'");
            }

            $items[] = [
                'meal' => $meal,
                'quantity' => $cartItem->quantity,
                'unit_price' => $cartItem->unit_price,
                'discount_amount' => $cartItem->discount_amount,
                'subtotal' => $cartItem->subtotal,
            ];

            $subtotal += $cartItem->subtotal;
        }

        return compact('items', 'subtotal');
    }
}
