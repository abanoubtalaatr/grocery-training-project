<?php

namespace App\Http\Controllers\Api;

use App\Actions\Address\SetDefaultAddressAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use Illuminate\Http\JsonResponse;

class SetDefaultAddressController extends Controller
{
    public function __invoke(Address $address, SetDefaultAddressAction $setDefaultAddress): JsonResponse
    {
        ['address' => $address, 'already_default' => $alreadyDefault] = $setDefaultAddress($address);

        return response()->success(
            new AddressResource($address),
            $alreadyDefault ? 'This address is already your default.' : 'Default address updated successfully',
            200,
            ['already_default' => $alreadyDefault]
        );
    }
}
