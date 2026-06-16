<?php

namespace App\Http\Actions\Api\Address;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class GetAddressesAction
{
    public function execute(User $user): Collection
    {
        return $user->addresses()
            ->orderByDesc('is_default')
            ->orderByDesc('created_at')
            ->get();
    }
}