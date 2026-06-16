<?php
namespace App\Services;

use App\Models\Cart;
use Exception;

class CartService
{
    public function validateAndProcessItems(Cart $cart): array
    {
        $items = [];
        $subtotal = 0;
        $maxPerProduct = config('cart.max_quantity_per_product', 10);

        foreach ($cart->items as $cartItem) {
            $meal = $cartItem->meal;

            if (!$meal || !$meal->is_available) {
                throw new Exception($meal ? "Meal '{$meal->title}' is currently unavailable." : "One or more items are no longer available.");
            }

            if ($meal->stock_quantity < $cartItem->quantity) {
                throw new Exception("Only {$meal->stock_quantity} items available for '{$meal->title}'.");
            }

            if ($cartItem->quantity > $maxPerProduct) {
                throw new Exception("Maximum {$maxPerProduct} units per product allowed for '{$meal->title}'.");
            }

            $items[] = [
                'meal'            => $meal,
                'quantity'        => $cartItem->quantity,
                'unit_price'      => $cartItem->unit_price,
                'discount_amount' => $cartItem->discount_amount,
                'subtotal'        => $cartItem->subtotal,
            ];

            $subtotal += $cartItem->subtotal;
        }

        return ['items' => $items, 'subtotal' => $subtotal];
    }

    public function clearCart(Cart $cart): void
    {
        $cart->items()->delete();
        $cart->update(['status' => 'completed']);
    }
}