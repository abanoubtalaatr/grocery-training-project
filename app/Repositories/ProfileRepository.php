<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;

class ProfileRepository
{
    public function loadUserRelations(User $user): User
    {
        return $user->load(['addresses', 'favorites.meal.category', 'favorites.meal.subcategory']);
    }

    public function getUserAddresses(User $user): Collection
    {
        return $user->addresses()
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getUserOrders(User $user): Collection
    {
        return Order::where('user_id', $user->id)
            ->with(['items.meal.category', 'items.meal.subcategory', 'address'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getUserOrderNotifications(User $user): Collection
    {
        return $user->notifications()
            ->where(function ($q) {
                $q->where('data->type', 'order_confirmation')
                    ->orWhere('data->type', 'order_shipped')
                    ->orWhere('data->type', 'delivery_updates');
            })
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();
    }
}
