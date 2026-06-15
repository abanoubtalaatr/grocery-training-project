<?php

namespace App\Actions\Cart;

use App\Models\Meal;
use App\Models\User;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;

class AddCartItemAction
{
    public function execute(User $user, array $data): Cart
    {
        return DB::transaction(function () use ($user, $data) {

            $maxPerProduct = config('cart.max_quantity_per_product', 10);

            $cart = $user->getOrCreateCart();

            $meal = Meal::findOrFail($data['meal_id']);

            if (! $meal->is_available) {
                abort(400, 'This meal is currently unavailable');
            }

            if (! $meal->isInStock()) {
                abort(400, 'This meal is out of stock');
            }

            if ($meal->stock_quantity < $data['quantity']) {
                abort(
                    400,
                    "Only {$meal->stock_quantity} items available in stock"
                );
            }

            $cartItem = $cart->items()
                ->where('meal_id', $meal->id)
                ->first();

            if ($cartItem) {

                $newQuantity =
                    $cartItem->quantity + $data['quantity'];

                $effectiveMax = min(
                    $maxPerProduct,
                    $meal->stock_quantity
                );

                if ($newQuantity > $effectiveMax) {
                    abort(
                        400,
                        "Maximum {$maxPerProduct} units per product."
                    );
                }

                $cartItem->update([
                    'quantity' => $newQuantity,
                ]);

            } else {

                $discountAmount = 0;

                if ($meal->resolved_discount_price) {
                    $discountAmount =
                        ($meal->price - $meal->resolved_discount_price)
                        * $data['quantity'];
                }

                $cart->items()->create([
                    'meal_id' => $meal->id,
                    'quantity' => $data['quantity'],
                    'unit_price' => $meal->final_price,
                    'discount_amount' => $discountAmount,
                    'subtotal' => $meal->final_price * $data['quantity'],
                ]);
            }

            $cart->calculateTotals();

            return $cart->load([
                'items.meal.category',
                'items.meal.subcategory',
            ]);
        });
    }
}