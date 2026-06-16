<?php 

namespace App\Action\Address;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class StoreAddressAction
{
    public function execute(User $user , array $data)
    {
          DB::beginTransaction();

            // If this is the first address, make it default
            $isFirstAddress = $user->addresses()->count() === 0;
            $data = array_merge($data,
                ['is_default' => $isFirstAddress]
            );
            // Normalize phone: if phone already starts with country code, store only the national part
            $phone = trim($data['phone'] ?? '');
            $code = trim($data['country_code'] ?? '');
            if ($code !== '' && str_starts_with($phone, $code)) {
                $data['phone'] = substr($phone, strlen($code));
            }
            $address = $user->addresses()->create($data);

            DB::commit();
            return $address;
    }
}
