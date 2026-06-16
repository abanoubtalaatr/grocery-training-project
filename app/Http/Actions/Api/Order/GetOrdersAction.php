<?php

namespace App\Http\Actions\Api\Order;

use App\Models\Order;
use App\Models\User;

class GetOrdersAction
{
    public function execute(User $user)
    {
        return Order::query()
            ->where('user_id', $user->id)
            ->with([
                'items.meal.category',
                'items.meal.subcategory',
                'address',
            ])
            ->latest()
            ->get();
    }
}