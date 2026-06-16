<?php

namespace App\Actions\Address;

use App\Models\User;
use App\Repositories\AddressRepository;
use App\Traits\FormatsAddress;
use Illuminate\Support\Facades\DB;

class CreateAddressAction
{
    use FormatsAddress;

    public function __construct(private readonly AddressRepository $addressRepository) {}

    public function __invoke(User $user, array $data, bool $isDefaultRequested): array
    {
        return DB::transaction(function () use ($user, $data, $isDefaultRequested) {
            $isFirstAddress = $this->addressRepository->countForUser($user) === 0;
            $data['is_default'] = $isDefaultRequested || $isFirstAddress;
            
            $phone = trim($data['phone'] ?? '');
            $code = trim($data['country_code'] ?? '');
            
            if ($code !== '' && str_starts_with($phone, $code)) {
                $data['phone'] = substr($phone, strlen($code));
            }
            
            $address = $this->addressRepository->createForUser($user, $data);
            
            return $this->formatAddress($address);
        });
    }
}
