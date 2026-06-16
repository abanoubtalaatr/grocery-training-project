<?php

namespace App\Actions\Address;

use App\Models\User;
use App\Repositories\AddressRepository;
use App\Traits\FormatsAddress;
use Illuminate\Support\Facades\DB;

class SetDefaultAddressAction
{
    use FormatsAddress;

    public function __construct(private readonly AddressRepository $addressRepository) {}

    public function __invoke(User $user, string $id): array
    {
        $address = $this->addressRepository->findForUser($user, $id);

        if ($address->is_default) {
            return [
                'already_default' => true,
                'data' => $this->formatAddress($address)
            ];
        }

        return DB::transaction(function () use ($user, $address) {
            $this->addressRepository->resetDefaultForUser($user, $address->id);
            $address->update(['is_default' => true]);

            return [
                'already_default' => false,
                'data' => $this->formatAddress($address->fresh())
            ];
        });
    }
}
