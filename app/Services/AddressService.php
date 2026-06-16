<?php

namespace App\Services;

use App\Models\User;
use App\Models\Address;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AddressService
{
    public function getAddresses(User $user): Collection
    {
        return $user->addresses()
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function createAddress(User $user, array $data): Address
    {
        return DB::transaction(function () use ($user, $data) {
            if (!empty($data['is_default'])) {
                $user->addresses()->update(['is_default' => false]);
            }

            return $user->addresses()->create($data);
        });
    }

    public function updateAddress(Address $address, array $data): Address
    {
        return DB::transaction(function () use ($address, $data) {
            if (!empty($data['is_default'])) {
                $address->user->addresses()->update(['is_default' => false]);
            }

            $address->update($data);
            return $address;
        });
    }

    public function deleteAddress(User $user, Address $address): bool
    {
        if ($address->is_default) {
            $lastAddress = $user->addresses()
                ->where('id', '!=', $address->id)
                ->latest()
                ->first();

            if ($lastAddress) {
                $lastAddress->update(['is_default' => true]);
            }
        }

        return $address->delete();
    }

    public function setDefaultAddress(User $user, Address $address): Address
    {
        return DB::transaction(function () use ($user, $address) {
            $user->addresses()->update(['is_default' => false]);
            $address->update(['is_default' => true]);
            return $address;
        });
    }
}
