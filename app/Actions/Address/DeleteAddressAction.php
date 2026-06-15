<?php

namespace App\Actions\Address;

use App\Models\Address;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class DeleteAddressAction
{
    public function execute(UserContract $user, Address $address): void
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
