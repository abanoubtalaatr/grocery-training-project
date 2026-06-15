<?php

namespace App\Actions\Cart;

use App\Exceptions\BusinessException;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateCartItemAction
{
    use AsAction;

    /**
     * Handle updating the quantity of an item in the cart.
     *
     * @throws BusinessException
     */
    public function handle(Cart $cart, string $itemId, int $quantity): Cart
    {
        $cartItem = $cart->items()->findOrFail($itemId);
        $meal = $cartItem->meal;

        if ($meal->stock_quantity < $quantity) {
            throw new BusinessException("Only {$meal->stock_quantity} items available in stock");
        }

        return DB::transaction(function () use ($cart, $cartItem, $quantity) {
            $cartItem->update([
                'quantity' => $quantity,
            ]);

            $cart->calculateTotals();
            $cart->load(['items.meal.category', 'items.meal.subcategory']);

            return $cart;
        });
    }
}
