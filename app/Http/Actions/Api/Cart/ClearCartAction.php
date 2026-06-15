<?php 

namespace App\Http\Actions\Api\Cart;


use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Cart;


class ClearCartAction
{
    public function execute(User $user): Cart
    {
        return DB::transaction(function () use (
            $user
        ) {

            $cart = $user->getOrCreateCart();

            $cart->items()->delete();

            $cart->calculateTotals();

            return $cart->fresh();
        });
    }
}