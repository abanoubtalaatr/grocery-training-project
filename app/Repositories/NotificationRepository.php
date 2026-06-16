<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Meal;
use App\Models\Order;

class NotificationRepository
{
    public function getBaseQuery(User $user)
    {
        return $user->notifications();
    }

    public function getUnreadQuery(User $user)
    {
        return $user->unreadNotifications();
    }

    public function getReadQuery(User $user)
    {
        return $user->readNotifications();
    }

    public function getMealsByIds(array $ids)
    {
        return Meal::with('category')->whereIn('id', $ids)->get()->keyBy('id');
    }

    public function getOrdersByIds(array $ids)
    {
        return Order::whereIn('id', $ids)->get()->keyBy('id');
    }
}
