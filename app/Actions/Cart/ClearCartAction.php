<?php

namespace App\Actions\Cart;

use App\Models\Cart;
use Illuminate\Support\Facades\DB;

class ClearCartAction
{
    public function execute(Cart $cart): Cart
    {
        return DB::transaction(function () use ($cart) {
            $cart->items()->delete();
            $cart->calculateTotals();
            return $cart;
        });
    }
}