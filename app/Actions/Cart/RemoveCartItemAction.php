<?php

namespace App\Actions\Cart;

use App\Services\CartService;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class RemoveCartItemAction
{
    public function __construct(protected CartService $service)
    {
    }

    public function execute(UserContract $user, string $itemId)
    {
        return $this->service->removeItem($user, $itemId);
    }
}
