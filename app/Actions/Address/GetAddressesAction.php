<?php

namespace App\Actions\Address;

use App\Models\User;
use App\Repositories\AddressRepository;
use App\Traits\FormatsAddress;
use Illuminate\Support\Collection;

class GetAddressesAction
{
    use FormatsAddress;

    public function __construct(private readonly AddressRepository $addressRepository) {}

    public function __invoke(User $user): Collection
    {
        return $this->addressRepository->getForUser($user)->map(function ($address) {
            return $this->formatAddress($address);
        });
    }
}
