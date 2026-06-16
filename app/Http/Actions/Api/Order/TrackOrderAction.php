<?php

namespace App\Http\Actions\Api\Order;

use App\Models\Order;
use App\Models\User;

class TrackOrderAction
{
    public function execute(
        User $user
    ): ?Order {

        return Order::query()
            ->where('user_id', $user->id)
            ->whereNotIn(
                'status',
                ['cancelled', 'delivered']
            )
            ->with([
                'items.meal.category',
                'items.meal.subcategory',
                'address',
            ])
            ->latest()
            ->first();
    }
}