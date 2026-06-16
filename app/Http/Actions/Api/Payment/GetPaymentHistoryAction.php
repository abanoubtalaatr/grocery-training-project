<?php

namespace App\Http\Actions\Api\Payment;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Collection;

class GetPaymentHistoryAction
{
    public function execute(
        User $user
    ): Collection {

        return Order::query()
            ->where('user_id', $user->id)
            ->where('status', '!=', 'cancelled')
            ->with([
                'items.meal.category',
                'address',
            ])
            ->latest()
            ->get();
    }
}