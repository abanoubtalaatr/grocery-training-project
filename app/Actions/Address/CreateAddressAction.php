<?php

namespace App\Actions\Address;

use App\Models\Address;
use App\Models\User;
use App\Services\AddressService;

class CreateAddressAction
{
    public function __construct(
        private readonly AddressService $addresses
    ) {
    }

    public function __invoke(User $user, array $data): Address
    {
        return $this->addresses->create($user, $data);
    }
}