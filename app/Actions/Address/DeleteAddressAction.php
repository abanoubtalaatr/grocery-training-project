<?php

namespace App\Actions\Address;

use App\Models\Address;
use App\Services\AddressService;

class DeleteAddressAction
{
    public function __construct(
        private readonly AddressService $addresses
    ) {
    }

    public function __invoke(Address $address): void
    {
        $this->addresses->delete($address);
    }
}
