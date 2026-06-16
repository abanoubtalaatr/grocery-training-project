<?php

namespace App\Http\Controllers\Api\Actions\Cart;

use App\Exceptions\Cart\CartOperationException;
use App\Models\Cart;
use App\Models\Meal;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CartAddItemAction
{
    public function __construct(private readonly LoadUserCartAction $loadUserCart) {}

    public function handle(User $user, array $data): Cart
    {
        $maxPerProduct = config('cart.max_quantity_per_product', 10);
        $meal = Meal::findOrFail($data['meal_id']);
        $quantity = (int) $data['quantity'];

        if (!$meal->is_available) {
            throw new CartOperationException('This meal is currently unavailable');
        }

        if (!$meal->isInStock()) {
            throw new CartOperationException('This meal is out of stock');
        }

        if ($meal->stock_quantity < $quantity) {
            throw new CartOperationException("Only {$meal->stock_quantity} items available in stock");
        }

        return DB::transaction(function () use ($user, $meal, $quantity, $maxPerProduct) {
            $cart = $user->getOrCreateCart();
            $cartItem = $cart->items()->where('meal_id', $meal->id)->first();

            if ($cartItem) {
                $newQuantity = $cartItem->quantity + $quantity;
                $effectiveMax = min($maxPerProduct, $meal->stock_quantity);

                if ($newQuantity > $effectiveMax) {
                    throw new CartOperationException(
                        "Maximum {$maxPerProduct} units per product. You already have {$cartItem->quantity} in cart; maximum total is {$effectiveMax}."
                    );
                }

                if ($meal->stock_quantity < $newQuantity) {
                    throw new CartOperationException("Only {$meal->stock_quantity} items available in stock");
                }

                $cartItem->update(['quantity' => $newQuantity]);
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

            return $this->loadUserCart->handle($user);
        });
    }
}
