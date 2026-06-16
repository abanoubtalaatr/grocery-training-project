<?php

namespace App\Http\Controllers\Api\Actions\Cart;

use App\Models\Cart;
use App\Models\User;

class LoadUserCartAction
{
    public function handle(User $user): Cart
    {
        $cart = $user->getOrCreateCart();
        $cart->load(['items.meal.category', 'items.meal.subcategory']);

        return $cart;
    }
}
