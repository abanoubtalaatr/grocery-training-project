<?php

namespace App\Actions\Cart;

use App\Models\User;
use App\Repositories\CartRepository;
use App\Traits\FormatsCart;
use Illuminate\Support\Facades\DB;

class RemoveItemFromCartAction
{
    use FormatsCart;

    public function __construct(private readonly CartRepository $cartRepository) {}

    public function __invoke(User $user, string $itemId): array
    {
        $cart = $this->cartRepository->getOrCreateForUser($user);
        $cartItem = $this->cartRepository->findItemById($cart, $itemId);

        return DB::transaction(function () use ($cart, $cartItem) {
            $cartItem->delete();

            $cart->calculateTotals();
            $cart->load(['items.meal.category', 'items.meal.subcategory']);

            return $this->formatCart($cart);
        });
    }
}
