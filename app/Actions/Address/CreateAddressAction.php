<?php

namespace App\Actions\Address;

use App\Models\Address;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreateAddressAction
{
  /**
   * Create a new address for the user.
   *
   * @param User $user
   * @param array $data
   * @return Address
   */
  public function execute(User $user, array $data): Address
  {
    return DB::transaction(function () use ($user, $data) {
      // Check if this is the first address
      $isFirstAddress = $user->addresses()->count() === 0;

      // Determine if the new address should be the default
      $isDefault = ($data['is_default'] ?? false) || $isFirstAddress;

      // If this address is set as default, unset other default addresses
      if ($isDefault) {
        $user->addresses()->update(['is_default' => false]);
      }

      // Normalize phone: if phone already starts with country code, store only national part
      $phone = trim($data['phone'] ?? '');
      $code = trim($data['country_code'] ?? '');
      if ($code !== '' && str_starts_with($phone, $code)) {
        $data['phone'] = substr($phone, strlen($code));
      }

      $data['is_default'] = $isDefault;

      return $user->addresses()->create($data);
    });
  }
}
