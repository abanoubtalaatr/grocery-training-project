<?php

namespace App\Actions\Cart;

use App\Models\User;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;

class ClearCartAction
{
    public function execute(User $user): Cart
    {
        return DB::transaction(function () use ($user) {

            $cart = $user->getOrCreateCart();

            $cart->items()->delete();

            $cart->calculateTotals();

            return $cart->load([
                'items.meal.category',
                'items.meal.subcategory',
            ]);
        });
    }
}