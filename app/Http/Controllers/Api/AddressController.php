<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Actions\Address\CreateAddressAction;
use App\Actions\Address\UpdateAddressAction;
use App\Actions\Address\DeleteAddressAction;
use App\Actions\Address\SetDefaultAddressAction;
use App\Http\Requests\Address\StoreAddressRequest;
use App\Http\Requests\Address\UpdateAddressRequest;
use App\Http\Resources\AddressResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\ApiResponse;

class AddressController extends Controller
{
    use ApiResponse;
    /**
     * Get all user addresses
     */
    public function index(Request $request): JsonResponse
    {
        if ($request->allFiles() !== []) {
            return $this->error('This endpoint does not accept file uploads.', ['files' => ['Remove file attachments from the request.']], 422);
        }
        $user = $request->user();

        $addresses = $user->addresses()->orderBy('is_default', 'desc')->orderBy('created_at', 'desc')->get();

        return $this->success('Addresses retrieved successfully', AddressResource::collection($addresses), 200, ['total_count' => $addresses->count()]);
    }

    /**
     * Get single address
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $user = $request->user();
        $address = $user->addresses()->findOrFail($id);

        return $this->success('Address retrieved successfully', new AddressResource($address));
    }

    /**
     * Create new address
     */
    public function store(StoreAddressRequest $request, CreateAddressAction $action): JsonResponse
    {
        $user = $request->user();

        $data = $request->validated();

        $address = $action->execute($user, $data);

        return $this->created('Address created successfully', new AddressResource($address));
    }

    /**
     * Update address
     */
    public function update(UpdateAddressRequest $request, string $id, UpdateAddressAction $action): JsonResponse
    {
        $user = $request->user();
        $address = $user->addresses()->findOrFail($id);

        $updateData = $request->validated();

        $address = $action->execute($user, $address, $updateData);

        return $this->success('Address updated successfully', new AddressResource($address));
    }

    /**
     * Delete address
     */
    public function destroy(Request $request, string $id, DeleteAddressAction $action): JsonResponse
    {
        $user = $request->user();
        $address = $user->addresses()->findOrFail($id);

        $action->execute($user, $address);

        return $this->success('Address deleted successfully');
    }

    /**
     * Set address as default
     */
    public function setDefault(Request $request, string $id, SetDefaultAddressAction $action): JsonResponse
    {
        $user = $request->user();
        $address = $user->addresses()->findOrFail($id);

        if ($address->is_default) {
            return $this->success('This address is already your default.', new AddressResource($address), 200, ['already_default' => true]);
        }

        $address = $action->execute($user, $address);

        return $this->success('Default address updated successfully', new AddressResource($address));
    }

    /**
     * Format address data for response
     */
    // Controller intentionally minimal; formatting handled by AddressResource
}
