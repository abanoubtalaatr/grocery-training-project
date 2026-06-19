<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Collection;

class OrderHistoryService
{
    /**
     * Get user's order history with items and address.
     */
    public function getOrderHistory(User $user): Collection
    {
        return Order::where('user_id', $user->id)
            ->with(['items.meal.category', 'address'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get user's active billing / payment transactions (excluding cancelled orders).
     */
    public function getPaymentHistory(User $user): Collection
    {
        return Order::where('user_id', $user->id)
            ->where('status', '!=', 'cancelled')
            ->with(['items.meal.category', 'address'])
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
