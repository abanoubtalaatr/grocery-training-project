<?php

namespace App\Actions\Address;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GetAddressAction
{
    public function execute(User $user, Address $address): Address
    {
        if ((int) $address->user_id !== (int) $user->id) {
            throw (new ModelNotFoundException())->setModel(Address::class, [$address->getKey()]);
        }

        return $address;
    }
}
