<?php

namespace App\Http\Actions\Api\Address;

use App\Models\Address;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DefaultAddressAction
{
    public function execute(
        User $user,
        Address $address
    ): Address {

        return DB::transaction(function () use ($user, $address) {

            $user->addresses()
                ->where('id', '!=', $address->id)
                ->update([
                    'is_default' => false
                ]);

            $address->update([
                'is_default' => true
            ]);

            return $address->fresh();
        });
    }
}