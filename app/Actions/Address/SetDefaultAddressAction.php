<?php

namespace App\Actions\Address;

use App\Models\Address;
use App\Services\AddressService;

class SetDefaultAddressAction
{
    public function __construct(
        private readonly AddressService $addresses
    ) {
    }

    /**
     * @return array{address: Address, already_default: bool}
     */
    public function __invoke(Address $address): array
    {
        $alreadyDefault = $address->is_default;

        if (! $alreadyDefault) {
            $address = $this->addresses->setDefault($address);
        }

        return [
            'address' => $address,
            'already_default' => $alreadyDefault,
        ];
    }
}
