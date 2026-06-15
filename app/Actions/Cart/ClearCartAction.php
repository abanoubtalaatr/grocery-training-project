<?php

namespace App\Actions\Cart;

use App\Models\Cart;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class ClearCartAction
{
    use AsAction;

    /**
     * Handle clearing all items from the cart.
     */
    public function handle(Cart $cart): Cart
    {
        return DB::transaction(function () use ($cart) {
            $cart->items()->delete();
            $cart->calculateTotals();

            return $cart;
        });
    }
}
