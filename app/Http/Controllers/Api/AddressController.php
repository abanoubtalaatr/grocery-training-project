<?php

namespace App\Http\Controllers\Api;

use App\Actions\Address\CreateAddressAction;
use App\Actions\Address\DeleteAddressAction;
use App\Actions\Address\ListAddressesAction;
use App\Actions\Address\UpdateAddressAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Address\StoreAddressRequest;
use App\Http\Requests\Address\UpdateAddressRequest;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use Illuminate\Http\JsonResponse;

class AddressController extends Controller
{
    /**
     * Get all user addresses.
     */
    public function index(ListAddressesAction $listAddresses): JsonResponse
    {
        $addresses = $listAddresses(auth()->user());

        return response()->success(
            AddressResource::collection($addresses),
            'Addresses retrieved successfully',
            200,
            ['total_count' => $addresses->count()]
        );
    }

    /**
     * Get a single address.
     */
    public function show(Address $address): JsonResponse
    {
        return response()->success(
            new AddressResource($address),
            'Address retrieved successfully'
        );
    }

    /**
     * Create a new address.
     */
    public function store(StoreAddressRequest $request, CreateAddressAction $createAddress): JsonResponse
    {
        $address = $createAddress(auth()->user(), $request->validated());

        return response()->success(
            new AddressResource($address),
            'Address created successfully',
            201
        );
    }

    /**
     * Update an address.
     */
    public function update(UpdateAddressRequest $request, Address $address, UpdateAddressAction $updateAddress): JsonResponse
    {
        $address = $updateAddress($address, $request->validated());

        return response()->success(
            new AddressResource($address),
            'Address updated successfully'
        );
    }

    /**
     * Delete an address.
     */
    public function destroy(Address $address, DeleteAddressAction $deleteAddress): JsonResponse
    {
        $deleteAddress($address);

        return response()->success(null, 'Address deleted successfully');
    }

   
    
}
