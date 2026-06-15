<?php

namespace App\Actions\Cart;

use App\Exceptions\BusinessException;
use App\Models\Cart;
use App\Models\Meal;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class AddToCartAction
{
    use AsAction;

    /**
     * Handle adding a meal to the cart.
     *
     * @throws BusinessException
     */
    public function handle(Cart $cart, Meal $meal, int $quantity): Cart
    {
        // 1. Check if meal is available
        if (!$meal->is_available) {
            throw new BusinessException('This meal is currently unavailable');
        }

        // 2. Check if meal is in stock
        if (!$meal->isInStock()) {
            throw new BusinessException('This meal is out of stock');
        }

        // 3. Check stock quantity
        if ($meal->stock_quantity < $quantity) {
            throw new BusinessException("Only {$meal->stock_quantity} items available in stock");
        }

        $maxPerProduct = config('cart.max_quantity_per_product', 10);

        return DB::transaction(function () use ($cart, $meal, $quantity, $maxPerProduct) {
            $cartItem = $cart->items()->where('meal_id', $meal->id)->first();

            if ($cartItem) {
                $newQuantity = $cartItem->quantity + $quantity;
                $effectiveMax = min($maxPerProduct, $meal->stock_quantity);

                if ($newQuantity > $effectiveMax) {
                    throw new BusinessException("Maximum {$maxPerProduct} units per product. You already have {$cartItem->quantity} in cart; maximum total is {$effectiveMax}.");
                }

                if ($meal->stock_quantity < $newQuantity) {
                    throw new BusinessException("Only {$meal->stock_quantity} items available in stock");
                }

                $cartItem->update([
                    'quantity' => $newQuantity,
                ]);
            } else {
                $discountAmount = 0;
                if ($meal->resolved_discount_price) {
                    $discountAmount = ($meal->price - $meal->resolved_discount_price) * $quantity;
                }

                $cart->items()->create([
                    'meal_id' => $meal->id,
                    'quantity' => $quantity,
                    'unit_price' => $meal->final_price,
                    'discount_amount' => $discountAmount,
                    'subtotal' => $meal->final_price * $quantity,
                ]);
            }

            $cart->calculateTotals();
            $cart->load(['items.meal.category', 'items.meal.subcategory']);

            return $cart;
        });
    }
}
