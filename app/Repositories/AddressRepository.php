<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Address;

class AddressRepository
{
    public function getForUser(User $user)
    {
        return $user->addresses()->orderBy('is_default', 'desc')->orderBy('created_at', 'desc')->get();
    }

    public function findForUser(User $user, string $id): Address
    {
        return $user->addresses()->findOrFail($id);
    }

    public function countForUser(User $user): int
    {
        return $user->addresses()->count();
    }

    public function createForUser(User $user, array $data): Address
    {
        return $user->addresses()->create($data);
    }

    public function getFirstForUser(User $user): ?Address
    {
        return $user->addresses()->first();
    }

    public function resetDefaultForUser(User $user, string $exceptId): void
    {
        $user->addresses()->where('id', '!=', $exceptId)->update(['is_default' => false]);
    }
}
