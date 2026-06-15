<?php

namespace App\Actions\Cart;

use App\Services\CartService;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class UpdateCartItemAction
{
    public function __construct(protected CartService $service)
    {
    }

    public function execute(UserContract $user, string $itemId, int $quantity)
    {
        return $this->service->updateItem($user, $itemId, $quantity);
    }
}
