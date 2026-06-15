<?php

namespace App\Actions\Address;

use App\Models\Address;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class SetDefaultAddressAction
{
    public function execute(UserContract $user, Address $address): Address
    {
        return DB::transaction(function () use ($user, $address) {
            $user->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
            $address->update(['is_default' => true]);

            return $address->fresh();
        });
    }
}
