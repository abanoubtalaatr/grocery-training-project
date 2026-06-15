<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Meal;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CartService
{
    public function getCart(User $user, ?string $deliveryType = null): array
    {
        $cart = $user->getOrCreateCart();
        $cart->load(['items.meal.category', 'items.meal.subcategory']);

        $shippingFee = null;
        $totalWithShipping = null;

        if ($deliveryType && in_array($deliveryType, ['delivery', 'pickup'], true)) {
            $shippingService = app(ShippingService::class);
            $shippingFee = $shippingService->calculateShippingFee((float) $cart->subtotal, $deliveryType);
            $totalWithShipping = (float) $cart->total + $shippingFee;
        }

        return $this->formatCart($cart, $shippingFee, $totalWithShipping);
    }

    public function addItem(User $user, int $mealId, int $quantity): Cart
    {
        $cart = $user->getOrCreateCart();
        $meal = Meal::findOrFail($mealId);

        if (!$meal->is_available || !$meal->isInStock()) {
            throw new \Exception('Product is unavailable or out of stock.');
        }

        if ($meal->stock_quantity < $quantity) {
            throw new \Exception("Only {$meal->stock_quantity} items available in stock.");
        }

        return DB::transaction(function () use ($cart, $meal, $quantity) {
            $cartItem = $cart->items()->where('meal_id', $meal->id)->first();
            $maxPerProduct = config('cart.max_quantity_per_product', 10);

            if ($cartItem) {
                $newQuantity = $cartItem->quantity + $quantity;
                if ($newQuantity > min($maxPerProduct, $meal->stock_quantity)) {
                    throw new \Exception("Maximum quantity reached for this product.");
                }
                $cartItem->update(['quantity' => $newQuantity]);
            } else {
                $discountAmount = $meal->resolved_discount_price ? ($meal->price - $meal->resolved_discount_price) * $quantity : 0;
                $cart->items()->create([
                    'meal_id' => $meal->id,
                    'quantity' => $quantity,
                    'unit_price' => $meal->final_price,
                    'discount_amount' => $discountAmount,
                    'subtotal' => $meal->final_price * $quantity,
                ]);
            }

            $cart->calculateTotals();
            return $cart->load(['items.meal.category', 'items.meal.subcategory']);
        });
    }

    public function updateItem(Cart $cart, int $itemId, int $quantity): Cart
    {
        $cartItem = $cart->items()->findOrFail($itemId);
        $meal = $cartItem->meal;

        if ($meal->stock_quantity < $quantity) {
            throw new \Exception("Only {$meal->stock_quantity} items available in stock.");
        }

        return DB::transaction(function () use ($cart, $cartItem, $quantity) {
            $cartItem->update(['quantity' => $quantity]);
            $cart->calculateTotals();
            return $cart->load(['items.meal.category', 'items.meal.subcategory']);
        });
    }

    public function removeItem(Cart $cart, int $itemId): Cart
    {
        return DB::transaction(function () use ($cart, $itemId) {
            $cart->items()->where('id', $itemId)->delete();
            $cart->calculateTotals();
            return $cart->load(['items.meal.category', 'items.meal.subcategory']);
        });
    }

    public function clearCart(Cart $cart): Cart
    {
        return DB::transaction(function () use ($cart) {
            $cart->items()->delete();
            $cart->calculateTotals();
            return $cart;
        });
    }

    public function formatCart(Cart $cart, ?float $shippingFee = null, ?float $totalWithShipping = null): array
    {
        $data = [
            'id' => $cart->id,
            'status' => $cart->isEmpty() ? 'empty' : 'not empty',
            'items' => $cart->items->map(fn($item) => [
                'id' => $item->id,
                'meal' => [
                    'id' => $item->meal->id,
                    'title' => $item->meal->title,
                    'slug' => $item->meal->slug,
                    'image_url' => $item->meal->image_url,
                    ...$item->meal->getApiPriceAttributes(),
                    'rating' => (float) $item->meal->rating,
                    'size' => $item->meal->size,
                    'brand' => $item->meal->brand,
                    'stock_quantity' => $item->meal->stock_quantity,
                    'is_available' => $item->meal->is_available,
                    'in_stock' => $item->meal->isInStock(),
                    'category' => $item->meal->category ? [
                        'id' => $item->meal->category->id,
                        'name' => $item->meal->category->name,
                    ] : null,
                ],
                'quantity' => $item->quantity,
                'unit_price' => (float) $item->unit_price,
                'discount_amount' => (float) $item->discount_amount,
                'subtotal' => (float) $item->subtotal,
            ]),
            'item_count' => $cart->item_count,
            'subtotal' => (float) $cart->subtotal,
            'tax' => (float) $cart->tax,
            'discount' => (float) $cart->discount,
            'total' => (float) $cart->total,
            'is_empty' => $cart->isEmpty(),
            'created_at' => $cart->created_at,
        ];

        if ($shippingFee !== null) {
            $data['shipping_fee'] = $shippingFee;
            $data['total_with_shipping'] = $totalWithShipping;
        }

        return $data;
    }
}
