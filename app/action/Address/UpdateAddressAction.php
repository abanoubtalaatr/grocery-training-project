<?php

namespace App\Action\Address;

use Illuminate\Support\Facades\DB;

class UpdateAddressAction
{
    public function execute($address ,array $data  )
    {
        DB::beginTransaction();

            $updateData = $data;       
                 // Normalize phone on update: if phone already starts with country code, store only national part
            if (isset($updateData['phone'], $updateData['country_code']) && $updateData['country_code'] !== '' && str_starts_with(trim($updateData['phone']), $updateData['country_code'])) {
                $updateData['phone'] = substr(trim($updateData['phone']), strlen($updateData['country_code']));
            }
            $address->fill($updateData)->save();

            DB::commit();
            return $address;
    }
}