<?php

namespace App\Actions\Address;

use App\Models\Address;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DeleteAddressAction
{
    /**
     * Delete an address and ensure a default address exists if needed.
     *
     * @param User $user
     * @param Address $address
     * @return void
     */
    public function execute(User $user, Address $address): void
    {
        DB::transaction(function () use ($user, $address) {
            $wasDefault = $address->is_default;
            $address->delete();

            // If deleted address was default, set another address as default
            if ($wasDefault) {
                $newDefault = $user->addresses()->first();
                if ($newDefault) {
                    $newDefault->update(['is_default' => true]);
                }
            }
        });
    }
}
