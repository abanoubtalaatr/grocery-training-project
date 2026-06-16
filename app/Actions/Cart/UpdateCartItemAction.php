<?php

namespace App\Actions\Cart;

use App\Models\User;
use App\Repositories\CartRepository;
use App\Traits\FormatsCart;
use Illuminate\Support\Facades\DB;
use Exception;

class UpdateCartItemAction
{
    use FormatsCart;

    public function __construct(private readonly CartRepository $cartRepository) {}

    public function __invoke(User $user, string $itemId, array $validated): array
    {
        $cart = $this->cartRepository->getOrCreateForUser($user);
        $cartItem = $this->cartRepository->findItemById($cart, $itemId);
        $meal = $cartItem->meal;

        if ($meal->stock_quantity < $validated['quantity']) {
            throw new Exception("Only {$meal->stock_quantity} items available in stock", 400);
        }

        return DB::transaction(function () use ($cart, $cartItem, $validated) {
            $cartItem->update(['quantity' => $validated['quantity']]);

            $cart->calculateTotals();
            $cart->load(['items.meal.category', 'items.meal.subcategory']);

            return $this->formatCart($cart);
        });
    }
}
