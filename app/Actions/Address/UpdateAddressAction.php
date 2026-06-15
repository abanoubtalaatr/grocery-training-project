<?php

namespace App\Actions\Address;

use App\Models\Address;
use Illuminate\Support\Facades\DB;

class UpdateAddressAction
{
  /**
   * Update an existing address.
   *
   * @param Address $address
   * @param array $data
   * @return Address
   */
  public function execute(Address $address, array $data): Address
  {
    return DB::transaction(function () use ($address, $data) {
      // If setting as default, unset other addresses for this user
      if (isset($data['is_default']) && $data['is_default']) {
        $address->user->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
      }

      // Normalize phone: if phone already starts with country code, store only national part
      if (isset($data['phone'], $data['country_code']) && $data['country_code'] !== '') {
        $phone = trim($data['phone']);
        $code = trim($data['country_code']);
        if (str_starts_with($phone, $code)) {
          $data['phone'] = substr($phone, strlen($code));
        }
      }

      $address->update($data);

      return $address->fresh();
    });
  }
}
