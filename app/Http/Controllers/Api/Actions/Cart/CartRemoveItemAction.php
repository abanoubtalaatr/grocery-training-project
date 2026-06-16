<?php

namespace App\Http\Controllers\Api\Actions\Cart;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CartRemoveItemAction
{
    public function __construct(private readonly LoadUserCartAction $loadUserCart) {}

    public function handle(User $user, string $itemId): Cart
    {
        return DB::transaction(function () use ($user, $itemId) {
            $cart = $user->getOrCreateCart();
            $cart->items()->findOrFail($itemId)->delete();
            $cart->calculateTotals();

            return $this->loadUserCart->handle($user);
        });
    }
}
