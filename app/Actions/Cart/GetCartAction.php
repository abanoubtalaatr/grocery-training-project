<?php

namespace App\Actions\Cart;

use App\Services\CartService;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class GetCartAction
{
    public function __construct(protected CartService $service)
    {
    }

    public function execute(UserContract $user, ?string $deliveryType = null): array
    {
        return $this->service->getCart($user, $deliveryType);
    }
}
