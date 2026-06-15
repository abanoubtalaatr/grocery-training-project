<?php

namespace App\Actions\Address;

use App\Models\Address;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class StoreAddressAction
{
    public function execute(User $user, array $data): Address
    {
        return DB::transaction(function () use ($user, $data) {
            $data['is_default'] = ($data['is_default'] ?? false) || $user->addresses()->count() === 0;
            $data['phone'] = $this->normalizePhone($data['phone'] ?? '', $data['country_code'] ?? '');
            return $user->addresses()->create($data);
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