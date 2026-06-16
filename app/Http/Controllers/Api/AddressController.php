<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\AddressRequest;
use App\Http\Resources\Api\V1\AddressResource;
use App\Models\Address;
use App\Services\AddressService;
use App\Traits\V1\ApiResponse;
use App\Traits\V1\ApiResponseCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    use ApiResponse, ApiResponseCollection;

    protected $addressService;

    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }

    /**
     * Get all user addresses
     */
    public function index(Request $request): JsonResponse
    {
        $addresses = $this->addressService->getAddresses($request->user());

        return self::collectionResponse(
            'Addresses retrieved successfully',
            AddressResource::collection($addresses)
        );
    }

    /**
     * Get single address
     */
    public function show(Address $address): JsonResponse
    {
        return self::successResponse(
            'Address retrieved successfully',
            new AddressResource($address)
        );
    }

    /**
     * Create new address
     */
    public function store(AddressRequest $request): JsonResponse
    {
        $address = $this->addressService->createAddress(
            $request->user(),
            $request->validated()
        );

        return self::successResponse(
            'Address created successfully',
            new AddressResource($address),
            201
        );
    }

    /**
     * Update address
     */
    public function update(AddressRequest $request, Address $address): JsonResponse
    {
        $updatedAddress = $this->addressService->updateAddress(
            $address,
            $request->validated()
        );

        return self::successResponse(
            'Address updated successfully',
            new AddressResource($updatedAddress)
        );
    }

    /**
     * Delete address
     */
    public function destroy(Request $request, Address $address): JsonResponse
    {
        $this->addressService->deleteAddress($request->user(), $address);

        return self::successResponse('Address deleted successfully');
    }
}
