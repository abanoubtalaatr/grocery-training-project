<?php

namespace App\Actions\Cart;

use App\Services\CartService;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class ClearCartAction
{
    public function __construct(protected CartService $service)
    {
    }

    public function execute(UserContract $user)
    {
        return $this->service->clearCart($user);
    }
}
