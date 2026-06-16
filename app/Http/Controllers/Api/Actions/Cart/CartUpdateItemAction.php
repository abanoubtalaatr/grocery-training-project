<?php

namespace App\Http\Controllers\Api\Actions\Cart;

use App\Exceptions\Cart\CartOperationException;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CartUpdateItemAction
{
    public function __construct(private readonly LoadUserCartAction $loadUserCart) {}

    public function handle(User $user, string $itemId, array $data): Cart
    {
        $quantity = (int) $data['quantity'];

        return DB::transaction(function () use ($user, $itemId, $quantity) {
            $cart = $user->getOrCreateCart();
            $cartItem = $cart->items()->findOrFail($itemId);
            $meal = $cartItem->meal;

            if ($meal->stock_quantity < $quantity) {
                throw new CartOperationException("Only {$meal->stock_quantity} items available in stock");
            }

            $cartItem->update(['quantity' => $quantity]);
            $cart->calculateTotals();

            return $this->loadUserCart->handle($user);
        });
    }
}
