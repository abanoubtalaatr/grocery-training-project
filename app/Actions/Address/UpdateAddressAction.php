<?php

namespace App\Actions\Address;

use App\Models\User;
use App\Models\Address;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class UpdateAddressAction
{
    public function execute(User $user, Address $address, array $data): Address
    {
        $this->ensureOwnedByUser($user, $address);

        return DB::transaction(function () use ($address, $data) {
            if (
                isset($data['phone'], $data['country_code']) &&
                $data['country_code'] &&
                str_starts_with(trim($data['phone']), $data['country_code'])
            ) {
                $data['phone'] = substr(
                    trim($data['phone']),
                    strlen($data['country_code'])
                );
            }

            $address->update($data);

            return $address->fresh();
        });
    }

    private function ensureOwnedByUser(User $user, Address $address): void
    {
        if ((int) $address->user_id !== (int) $user->id) {
            throw (new ModelNotFoundException())->setModel(Address::class, [$address->getKey()]);
        }
    }
}
