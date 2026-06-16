<?php

namespace App\Actions\Address;

use App\Models\User;
use App\Repositories\AddressRepository;
use App\Traits\FormatsAddress;

class GetAddressAction
{
    use FormatsAddress;

    public function __construct(private readonly AddressRepository $addressRepository) {}

    public function __invoke(User $user, string $id): array
    {
        $address = $this->addressRepository->findForUser($user, $id);
        
        return $this->formatAddress($address);
    }
}
