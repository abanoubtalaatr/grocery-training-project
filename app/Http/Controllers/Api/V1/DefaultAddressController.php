<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Services\AddressService;
use App\Traits\V1\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DefaultAddressController extends Controller
{
    use ApiResponse;

    protected $addressService;

    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }

    /**
     * Set address as default.
     */
    public function store(Request $request, Address $address): JsonResponse
    {
        $this->addressService->setDefaultAddress($request->user(), $address);
        return self::successResponse('Address set as default successfully');
    }
}
