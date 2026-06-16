<?php

namespace App\Actions\Address;

use App\Models\Address;
use Illuminate\Support\Facades\DB;

class UpdateAddressAction
{
    public function execute(Address $address, array $data): Address
    {
        return DB::transaction(function () use ($address, $data) {
            if (isset($data['phone'], $data['country_code'])) {
                $data['phone'] = $this->normalizePhone($data['phone'], $data['country_code']);
            }
            $address->fill($data)->save();
            return $address->fresh();
        });
    }

    private function normalizePhone(string $phone, string $countryCode): string
    {
        $phone       = trim($phone);
        $countryCode = trim($countryCode);
        if ($countryCode !== '' && str_starts_with($phone, $countryCode)) {
            return substr($phone, strlen($countryCode));
        }

        return $phone;
    }
}