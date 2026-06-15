<?php

namespace App\Http\Actions\Api\Address;

use App\Models\Address;
use Illuminate\Support\Facades\DB;

class UpdateAddressAction
{
    public function execute(
        Address $address,
        array $data
    ): Address {

        return DB::transaction(function () use ($address, $data) {

            $this->normalizePhone($data);

            $address->update($data);

            return $address->fresh();
        });
    }

    private function normalizePhone(array &$data): void
    {
        if (
            ! isset($data['phone']) ||
            ! isset($data['country_code'])
        ) {
            return;
        }

        $phone = trim($data['phone']);
        $countryCode = trim($data['country_code']);

        if (
            $countryCode &&
            str_starts_with($phone, $countryCode)
        ) {
            $data['phone'] = substr(
                $phone,
                strlen($countryCode)
            );
        }
    }
}