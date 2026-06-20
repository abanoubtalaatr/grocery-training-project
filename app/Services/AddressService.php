<?php

namespace App\Services;

use App\Models\Address;
use App\Models\User;
use App\Support\PhoneNormalizer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class AddressService
{
    public function __construct(
        private readonly PhoneNormalizer $phoneNormalizer
    ) {
    }

    public function listForUser(User $user): Collection
    {
        return $user->addresses()
            ->orderByDesc('is_default')
            ->orderByDesc('created_at')
            ->get();
    }

    public function create(User $user, array $data): Address
    {
        return DB::transaction(function () use ($user, $data) {
            if (array_key_exists('phone', $data)) {
                $data['phone'] = ($this->phoneNormalizer)($data['phone'], $data['country_code'] ?? null);
            }

            // The very first address a user adds automatically becomes their default.
            $data['is_default'] = ($data['is_default'] ?? false) || $user->addresses()->doesntExist();

            return $user->addresses()->create($data);
        });
    }

    public function update(Address $address, array $data): Address
    {
        return DB::transaction(function () use ($address, $data) {
            if (array_key_exists('phone', $data)) {
                $data['phone'] = ($this->phoneNormalizer)(
                    $data['phone'],
                    $data['country_code'] ?? $address->country_code
                );
            }

            $address->fill($data)->save();

            return $address->refresh();
        });
    }

    public function delete(Address $address): void
    {
        DB::transaction(function () use ($address) {
            $wasDefault = $address->is_default;
            $user = $address->user;

            $address->delete();

            // If the deleted address was the default, promote another one.
            if ($wasDefault) {
                $user->addresses()->first()?->update(['is_default' => true]);
            }
        });
    }

    public function setDefault(Address $address): Address
    {
        // Model's saving() hook takes care of unsetting every other default for this user.
        $address->update(['is_default' => true]);

        return $address->refresh();
    }
}