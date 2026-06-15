<?php

namespace App\Actions\Cart;

use App\Services\CartService;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class AddCartItemAction
{
    public function __construct(protected CartService $service)
    {
    }

    public function execute(UserContract $user, int $mealId, int $quantity)
    {
        return $this->service->addItem($user, $mealId, $quantity);
    }
}
