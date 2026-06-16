<?php

namespace App\Actions\Address;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class SetDefaultAddressAction
{
    public function __invoke(
        User $user,
        Address $address
    ): Address {
        if ((int) $address->user_id !== (int) $user->id) {
            throw (new ModelNotFoundException())
                ->setModel(Address::class, [$address->getKey()]);
        }

        return DB::transaction(function () use ($user, $address) {
            if (! $address->is_default) {
                $user->addresses()
                    ->whereKeyNot($address->id)
                    ->update([
                        'is_default' => false,
                    ]);

                $address->update([
                    'is_default' => true,
                ]);
            }

            return $address->fresh();
        });
    }
}