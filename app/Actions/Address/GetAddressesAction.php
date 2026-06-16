<?php

namespace App\Actions\Address;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class GetAddressesAction
{
    public function execute(User $user): Collection
    {
        return $user->addresses()
            ->orderByDesc('is_default')
            ->latest()
            ->get();
    }
}
