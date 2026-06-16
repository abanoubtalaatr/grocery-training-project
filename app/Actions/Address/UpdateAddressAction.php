<?php

namespace App\Actions\Address;

use App\Models\User;
use App\Repositories\AddressRepository;
use App\Traits\FormatsAddress;
use Illuminate\Support\Facades\DB;

class UpdateAddressAction
{
    use FormatsAddress;

    public function __construct(private readonly AddressRepository $addressRepository) {}

    public function __invoke(User $user, string $id, array $data): array
    {
        return DB::transaction(function () use ($user, $id, $data) {
            $address = $this->addressRepository->findForUser($user, $id);

            if (isset($data['phone'], $data['country_code']) && $data['country_code'] !== '' && str_starts_with(trim($data['phone']), $data['country_code'])) {
                $data['phone'] = substr(trim($data['phone']), strlen($data['country_code']));
            }

            $address->fill($data)->save();

            return $this->formatAddress($address->fresh());
        });
    }
}
