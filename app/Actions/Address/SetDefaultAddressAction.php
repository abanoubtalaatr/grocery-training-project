<?php

namespace App\Actions\Address;

use App\Models\Address;
use Illuminate\Support\Facades\DB;

class SetDefaultAddressAction
{
    /**
     * Set the given address as the default for its user.
     *
     * @param Address $address
     * @return Address
     */
    public function execute(Address $address): Address
    {
        return DB::transaction(function () use ($address) {
            $address->user->addresses()
                ->where('id', '!=', $address->id)
                ->update(['is_default' => false]);

            $address->update(['is_default' => true]);

            return $address->fresh();
        });
    }
}
