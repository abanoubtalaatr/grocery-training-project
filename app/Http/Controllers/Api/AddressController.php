<?php

namespace App\Http\Controllers\Api;

use App\Http\Actions\Address\Api\CreateAddressAction;
use App\Http\Actions\Address\Api\DefaultAddressAction;
use App\Http\Actions\Address\Api\DeleteAddressAction;
use App\Http\Actions\Address\Api\GetAddressesAction;
use App\Http\Actions\Address\Api\UpdateAddressAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Address\StoreAddressRequest;
use App\Http\Requests\Api\Address\UpdateAddressRequest;
use App\Http\Resources\Api\AddressResource;
use App\Models\Address;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{

    use ApiResponse;
    /**
     * Get all user addresses
     */
    public function index(Request $request, GetAddressesAction $action): JsonResponse
    {
        $addresses = $action->execute($request->user());

        return $this->success(
            message: 'Addresses retrieved successfully',
            data: AddressResource::collection($addresses)
        );
    }

    /**
     * Get single address
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $address = $request->user()->addresses()->find($id);

        return $this->success(
            message: 'Address retrieved successfully',
            data: $address ? new AddressResource($address) : null
        );
    }

    /**
     * Create new address
     */
    public function store(StoreAddressRequest $request, CreateAddressAction $action): JsonResponse
    {
        $address = $action->execute($request->user(), $request->validated());

        return $this->success(
            message: 'Address created successfully',
            data: new AddressResource($address)
        );
    }

    /**
     * Update address
     */
    public function update(
        UpdateAddressRequest $request,
        string $id,
        UpdateAddressAction $action
    ): JsonResponse {

        $address = $request->user()
            ->addresses()
            ->findOrFail($id);

        $address = $action->execute(
            $address,
            $request->validated()
        );

        return $this->success(
            message: 'Address updated successfully',
            data: new AddressResource($address)
        );
    }

    /**
     * Delete address
     */
    public function destroy(
        Request $request,
        string $id,
        DeleteAddressAction $action
    ): JsonResponse {

        $address = $request->user()
            ->addresses()
            ->findOrFail($id);

        $action->execute(
            $request->user(),
            $address
        );

        return $this->success(
            message: 'Address deleted successfully'
        );
    }

    /**
     * Set address as default
     */
    public function setDefault(
        Request $request,
        string $id,
        DefaultAddressAction $action
    ): JsonResponse {

        $address = $request->user()
            ->addresses()
            ->findOrFail($id);

        if ($address->is_default) {
            return $this->success(
                message: 'This address is already your default.',
                data: new AddressResource($address)
            );
        }

        $address = $action->execute(
            $request->user(),
            $address
        );

        return $this->success(
            message: 'Default address updated successfully',
            data: new AddressResource($address)
        );
    }

    /**
     * Format address data for response
     */
    // private function formatAddress(Address $address): array
    // {
    //     return [
    //         'id' => $address->id,
    //         'label' => $address->label,
    //         'full_name' => $address->full_name,
    //         'phone' => $address->phone,
    //         'country_code' => $address->country_code,
    //         'formatted_phone' => $address->formatted_phone,
    //         'street_address' => $address->street_address,
    //         'building_number' => $address->building_number,
    //         'floor' => $address->floor,
    //         'apartment' => $address->apartment,
    //         'landmark' => $address->landmark,
    //         'city' => $address->city,
    //         'state' => $address->state,
    //         'postal_code' => $address->postal_code,
    //         'country' => $address->country,
    //         'notes' => $address->notes,
    //         'is_default' => $address->is_default,
    //         'latitude' => $address->latitude,
    //         'longitude' => $address->longitude,
    //         'full_address' => $address->full_address,
    //         'created_at' => $address->created_at,
    //         'updated_at' => $address->updated_at,
    //     ];
    // }
}
