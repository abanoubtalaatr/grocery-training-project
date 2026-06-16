<?php

namespace App\Http\Controllers\Api\Actions\Cart;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CartClearAction
{
    public function __construct(private readonly LoadUserCartAction $loadUserCart) {}

    public function handle(User $user): Cart
    {
        return DB::transaction(function () use ($user) {
            $cart = $user->getOrCreateCart();
            $cart->items()->delete();
            $cart->calculateTotals();

            return $this->loadUserCart->handle($user);
        });
    }
}
