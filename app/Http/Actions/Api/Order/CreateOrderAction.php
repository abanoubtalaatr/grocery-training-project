<?php

namespace App\Http\Actions\Api\Order;

use App\Models\User;
use App\Models\Order;
use App\Services\OrderService;

class CreateOrderAction
{
    public function __construct(
        private readonly OrderService $orderService
    ) {}

    public function execute(
        User $user,
        array $data
    ): Order {

        return $this->orderService->create(
            $user,
            $data
        );
    }
}