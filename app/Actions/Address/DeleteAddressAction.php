<?php

namespace App\Actions\Address;

use App\Models\User;
use App\Repositories\AddressRepository;
use Illuminate\Support\Facades\DB;

class DeleteAddressAction
{
    public function __construct(private readonly AddressRepository $addressRepository) {}

    public function __invoke(User $user, string $id): void
    {
        DB::transaction(function () use ($user, $id) {
            $address = $this->addressRepository->findForUser($user, $id);
            $wasDefault = $address->is_default;
            
            $address->delete();

            if ($wasDefault) {
                $newDefault = $this->addressRepository->getFirstForUser($user);
                if ($newDefault) {
                    $newDefault->update(['is_default' => true]);
                }
            }
        });
    }
}
