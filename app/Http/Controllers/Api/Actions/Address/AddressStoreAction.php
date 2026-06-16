<?php

namespace App\Http\Controllers\Api\Actions\Address;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class AddressStoreAction
{
    public function handle(User $user, array $data): \App\Models\Address
    {
        // If this is the first address, make it default
        $isFirstAddress = $user->addresses()->count() === 0;
        $data['is_default'] = $data['is_default'] ?? false || $isFirstAddress;

        // Normalize phone: strip country code prefix if already included
        $phone = trim($data['phone'] ?? '');
        $code  = trim($data['country_code'] ?? '');
        if ($code !== '' && str_starts_with($phone, $code)) {
            $data['phone'] = substr($phone, strlen($code));
        }

        return DB::transaction(fn () => $user->addresses()->create($data));
    }
}