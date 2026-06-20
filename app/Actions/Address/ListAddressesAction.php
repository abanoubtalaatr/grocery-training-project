<?php

namespace App\Actions\Address;

use App\Models\User;
use App\Services\AddressService;
use Illuminate\Database\Eloquent\Collection;

class ListAddressesAction
{
    public function __construct(
        private readonly AddressService $addresses
    ) {
    }

    public function __invoke(User $user): Collection
    {
        return $this->addresses->listForUser($user);
    }
}