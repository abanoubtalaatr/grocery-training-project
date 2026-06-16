<?php

namespace App\Actions\Cart;

use App\Models\User;
use App\Repositories\CartRepository;
use App\Traits\FormatsCart;
use Illuminate\Support\Facades\DB;
use Exception;

class AddItemToCartAction
{
    use FormatsCart;

    public function __construct(private readonly CartRepository $cartRepository) {}

    public function __invoke(User $user, array $validated): array
    {
        $cart = $this->cartRepository->getOrCreateForUser($user);
        $meal = $this->cartRepository->findMeal($validated['meal_id']);
        $maxPerProduct = config('cart.max_quantity_per_product', 10);

        if (!$meal->is_available) {
            throw new Exception('This meal is currently unavailable', 400);
        }

        if (!$meal->isInStock()) {
            throw new Exception('This meal is out of stock', 400);
        }

        if ($meal->stock_quantity < $validated['quantity']) {
            throw new Exception("Only {$meal->stock_quantity} items available in stock", 400);
        }

        return DB::transaction(function () use ($cart, $meal, $validated, $maxPerProduct) {
            $cartItem = $this->cartRepository->findItemInCart($cart, $meal->id);

            if ($cartItem) {
                $newQuantity = $cartItem->quantity + $validated['quantity'];
                $effectiveMax = min($maxPerProduct, $meal->stock_quantity);
                
                if ($newQuantity > $effectiveMax) {
                    throw new Exception("Maximum {$maxPerProduct} units per product. You already have {$cartItem->quantity} in cart; maximum total is {$effectiveMax}.", 400);
                }
                
                if ($meal->stock_quantity < $newQuantity) {
                    throw new Exception("Only {$meal->stock_quantity} items available in stock", 400);
                }

                $cartItem->update(['quantity' => $newQuantity]);
            } else {
                $discountAmount = 0;
                if ($meal->resolved_discount_price) {
                    $discountAmount = ($meal->price - $meal->resolved_discount_price) * $validated['quantity'];
                }

                $this->cartRepository->createItem($cart, [
                    'meal_id' => $meal->id,
                    'quantity' => $validated['quantity'],
                    'unit_price' => $meal->final_price,
                    'discount_amount' => $discountAmount,
                    'subtotal' => $meal->final_price * $validated['quantity'],
                ]);
            }

            $cart->calculateTotals();
            $cart->load(['items.meal.category', 'items.meal.subcategory']);

            return $this->formatCart($cart);
        });
    }
}
