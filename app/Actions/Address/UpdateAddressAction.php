<?php

namespace App\Actions\Address;

use App\Models\Address;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class UpdateAddressAction
{
    public function execute(UserContract $user, Address $address, array $data): Address
    {
        return DB::transaction(function () use ($user, $address, $data) {
            if (isset($data['phone'], $data['country_code']) && $data['country_code'] !== '' && str_starts_with(trim($data['phone']), $data['country_code'])) {
                $data['phone'] = substr(trim($data['phone']), strlen($data['country_code']));
            }

            $address->fill($data)->save();

            return $address->fresh();
        });
    }
}
