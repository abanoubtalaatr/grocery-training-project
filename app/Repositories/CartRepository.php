<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Cart;
use App\Models\Meal;
use App\Models\CartItem;

class CartRepository
{
    public function getOrCreateForUser(User $user): Cart
    {
        $cart = $user->getOrCreateCart();
        $cart->load(['items.meal.category', 'items.meal.subcategory']);
        return $cart;
    }

    public function findMeal(string $mealId): Meal
    {
        return Meal::findOrFail($mealId);
    }

    public function findItemInCart(Cart $cart, string $mealId): ?CartItem
    {
        return $cart->items()->where('meal_id', $mealId)->first();
    }

    public function findItemById(Cart $cart, string $itemId): CartItem
    {
        return $cart->items()->findOrFail($itemId);
    }

    public function createItem(Cart $cart, array $data): CartItem
    {
        return $cart->items()->create($data);
    }
}
