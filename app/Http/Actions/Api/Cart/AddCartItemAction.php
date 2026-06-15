<?php 


namespace App\Http\Actions\Api\Cart;

use App\Http\Requests\Api\Cart\AddCartItemRequest;
use App\Http\Resources\Api\CartResource;


class AddCartItemAction
{
    public function execute(
        User $user,
        int $mealId,
        int $quantity
    ): Cart {

        return DB::transaction(function () use (
            $user,
            $mealId,
            $quantity
        ) {

            $cart = $user->getOrCreateCart();

            $meal = Meal::findOrFail($mealId);

            $this->validateMeal($meal, $quantity);

            $maxPerProduct = config(
                'cart.max_quantity_per_product',
                10
            );

            $cartItem = $cart->items()
                ->where('meal_id', $meal->id)
                ->first();

            if ($cartItem) {

                $newQuantity =
                    $cartItem->quantity + $quantity;

                $effectiveMax = min(
                    $maxPerProduct,
                    $meal->stock_quantity
                );

                if ($newQuantity > $effectiveMax) {
                    throw ValidationException::withMessages([
                        'quantity' => [
                            "Maximum {$effectiveMax} units allowed."
                        ]
                    ]);
                }

                $cartItem->update([
                    'quantity' => $newQuantity
                ]);
            } else {

                $discountAmount = 0;

                if ($meal->resolved_discount_price) {
                    $discountAmount =
                        ($meal->price -
                        $meal->resolved_discount_price)
                        * $quantity;
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

            return $cart->load([
                'items.meal.category',
                'items.meal.subcategory',
            ]);
        });
    }

    private function validateMeal(
        Meal $meal,
        int $quantity
    ): void {

        if (! $meal->is_available) {
            throw ValidationException::withMessages([
                'meal' => ['Meal unavailable']
            ]);
        }

        if (! $meal->isInStock()) {
            throw ValidationException::withMessages([
                'meal' => ['Meal out of stock']
            ]);
        }

        if ($meal->stock_quantity < $quantity) {
            throw ValidationException::withMessages([
                'quantity' => [
                    "Only {$meal->stock_quantity} available"
                ]
            ]);
        }
    }
}