<?php

namespace App\Http\Actions\Api\Address;

use App\Models\Address;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreateAddressAction
{
    public function execute(User $user, array $data): Address
    {
        return DB::transaction(function () use ($user, $data) {

            $isFirstAddress = $user->addresses()->count() === 0;

            $data['is_default'] =
                ($data['is_default'] ?? false) || $isFirstAddress;

            $this->normalizePhone($data);

            return $user->addresses()->create($data);
        });
    }

    private function normalizePhone(array &$data): void
    {
        $phone = trim($data['phone'] ?? '');
        $countryCode = trim($data['country_code'] ?? '');

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