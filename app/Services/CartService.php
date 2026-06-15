<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Meal;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use App\Services\ShippingService;

class CartService
{
    public function getCart(UserContract $user, ?string $deliveryType = null): array
    {
        $cart = $user->getOrCreateCart();
        $cart->load(['items.meal.category', 'items.meal.subcategory']);

        if ($deliveryType && in_array($deliveryType, ['delivery', 'pickup'], true)) {
            $shippingService = app(ShippingService::class);
            $shippingFee = $shippingService->calculateShippingFee((float) $cart->subtotal, $deliveryType);
            $totalWithShipping = (float) $cart->total + $shippingFee;
        } else {
            $shippingFee = null;
            $totalWithShipping = null;
        }

        return ['cart' => $cart, 'shipping_fee' => $shippingFee, 'total_with_shipping' => $totalWithShipping];
    }

    public function addItem(UserContract $user, int $mealId, int $quantity): Cart
    {
        $maxPerProduct = config('cart.max_quantity_per_product', 10);

        $cart = $user->getOrCreateCart();
        $meal = Meal::findOrFail($mealId);

        if (!$meal->is_available || !$meal->isInStock() || $meal->stock_quantity < $quantity) {
            throw new \RuntimeException('Meal not available or not enough stock');
        }

        $cartItem = $cart->items()->where('meal_id', $meal->id)->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;
            $effectiveMax = min($maxPerProduct, $meal->stock_quantity);
            if ($newQuantity > $effectiveMax) {
                throw new \RuntimeException("Maximum {$maxPerProduct} units per product. You already have {$cartItem->quantity} in cart; maximum total is {$effectiveMax}.");
            }
            if ($meal->stock_quantity < $newQuantity) {
                throw new \RuntimeException('Not enough stock');
            }

            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            $discountAmount = 0;
            if ($meal->resolved_discount_price) {
                $discountAmount = ($meal->price - $meal->resolved_discount_price) * $quantity;
            }

            $cartItem = $cart->items()->create([
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
    }

    public function updateItem(UserContract $user, string $itemId, int $quantity): Cart
    {
        $maxPerProduct = config('cart.max_quantity_per_product', 10);

        $cart = $user->getOrCreateCart();
        $cartItem = $cart->items()->findOrFail($itemId);
        $meal = $cartItem->meal;

        if ($meal->stock_quantity < $quantity) {
            throw new \RuntimeException("Only {$meal->stock_quantity} items available in stock");
        }

        $cartItem->update(['quantity' => $quantity]);

        $cart->calculateTotals();
        $cart->load(['items.meal.category', 'items.meal.subcategory']);

        return $cart;
    }

    public function removeItem(UserContract $user, string $itemId): Cart
    {
        $cart = $user->getOrCreateCart();
        $cartItem = $cart->items()->findOrFail($itemId);

        $cartItem->delete();

        $cart->calculateTotals();
        $cart->load(['items.meal.category', 'items.meal.subcategory']);

        return $cart;
    }

    public function clearCart(UserContract $user): Cart
    {
        $cart = $user->getOrCreateCart();

        $cart->items()->delete();
        $cart->calculateTotals();

        $cart->load(['items.meal.category', 'items.meal.subcategory']);

        return $cart;
    }
}
