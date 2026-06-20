<?php

namespace App\Actions\Address;

use App\Models\Address;
use App\Services\AddressService;

class UpdateAddressAction
{
    public function __construct(
        private readonly AddressService $addresses
    ) {
    }

    public function __invoke(Address $address, array $data): Address
    {
        return $this->addresses->update($address, $data);
    }
}
