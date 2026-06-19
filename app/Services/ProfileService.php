<?php

namespace App\Services;

use App\Models\Address;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileService
{
    /**
     * Update user's personal information.
     */
    public function updatePersonalInfo(User $user, array $data): bool
    {
        if (isset($data['phone'])) {
            $data['phone'] = preg_replace('/\s+/', '', $data['phone']);
        }
        return $user->update($data);
    }

    /**
     * Update user's password.
     */
    public function updatePassword(User $user, string $plainPassword): bool
    {
        return $user->update([
            'password' => Hash::make($plainPassword),
        ]);
    }

    /**
     * Get user's active login sessions/tokens.
     */
    public function getSessions(User $user): Collection
    {
        return $user->tokens()->get();
    }

    /**
     * Get user's addresses ordered by default status.
     */
    public function getAddresses(User $user): Collection
    {
        return $user->addresses()
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Create a new address for the user.
     */
    public function createAddress(User $user, array $data): Address
    {
        return DB::transaction(function () use ($user, $data) {
            $isFirstAddress = $user->addresses()->count() === 0;
            $isDefault = ($data['is_default'] ?? false) || $isFirstAddress;
            
            $data['is_default'] = $isDefault;

            $phone = trim($data['phone'] ?? '');
            $code = trim($data['country_code'] ?? '');
            if ($code !== '' && str_starts_with($phone, $code)) {
                $data['phone'] = substr($phone, strlen($code));
            }

            if ($isDefault) {
                $user->addresses()->update(['is_default' => false]);
            }

            return $user->addresses()->create($data);
        });
    }

    /**
     * Update an existing address.
     */
    public function updateAddress(User $user, Address $address, array $data): Address
    {
        return DB::transaction(function () use ($user, $address, $data) {
            if (isset($data['phone'], $data['country_code']) && $data['country_code'] !== '' && str_starts_with(trim($data['phone']), $data['country_code'])) {
                $data['phone'] = substr(trim($data['phone']), strlen($data['country_code']));
            }

            $isDefault = $data['is_default'] ?? false;
            if ($isDefault) {
                $user->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
            }

            $address->fill($data)->save();
            return $address->fresh();
        });
    }

    /**
     * Set an address as the default address.
     */
    public function setDefaultAddress(User $user, Address $address): void
    {
        DB::transaction(function () use ($user, $address) {
            $user->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
            $address->update(['is_default' => true]);
        });
    }

    /**
     * Delete an address and resolve new default if needed.
     */
    public function deleteAddress(User $user, Address $address): void
    {
        DB::transaction(function () use ($user, $address) {
            $wasDefault = $address->is_default;
            $address->delete();

            if ($wasDefault) {
                $newDefault = $user->addresses()->first();
                if ($newDefault) {
                    $newDefault->update(['is_default' => true]);
                }
            }
        });
    }
}
