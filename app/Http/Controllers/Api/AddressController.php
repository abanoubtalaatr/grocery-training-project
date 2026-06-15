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

class AddressController extends Controller
{
    /**
     * Get all user addresses
     */
    public function index(Request $request): JsonResponse
    {
        if ($request->allFiles() !== []) {
            return response()->json([
                'success' => false,
                'message' => 'This endpoint does not accept file uploads.',
                'errors' => ['files' => ['Remove file attachments from the request.']],
            ], 422);
        }
        $user = $request->user();

        $addresses = $user->addresses()->orderBy('is_default', 'desc')->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'message' => 'Addresses retrieved successfully',
            'data' => AddressResource::collection($addresses),
            'total_count' => $addresses->count(),
        ]);
    }

    /**
     * Get single address
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $user = $request->user();
        $address = $user->addresses()->findOrFail($id);

        return response()->json(['success' => true, 'message' => 'Address retrieved successfully', 'data' => new AddressResource($address)]);
    }

    /**
     * Create new address
     */
    public function store(StoreAddressRequest $request, CreateAddressAction $action): JsonResponse
    {
        $user = $request->user();

        $data = $request->validated();

        $address = $action->execute($user, $data);

        return response()->json(['success' => true, 'message' => 'Address created successfully', 'data' => new AddressResource($address)], 201);
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

        return response()->json(['success' => true, 'message' => 'Address updated successfully', 'data' => new AddressResource($address)]);
    }

    /**
     * Delete address
     */
    public function destroy(Request $request, string $id, DeleteAddressAction $action): JsonResponse
    {
        $user = $request->user();
        $address = $user->addresses()->findOrFail($id);

        $action->execute($user, $address);

        return response()->json(['success' => true, 'message' => 'Address deleted successfully']);
    }

    /**
     * Set address as default
     */
    public function setDefault(Request $request, string $id, SetDefaultAddressAction $action): JsonResponse
    {
        $user = $request->user();
        $address = $user->addresses()->findOrFail($id);

        if ($address->is_default) {
            return response()->json(['success' => true, 'message' => 'This address is already your default.', 'already_default' => true, 'data' => new AddressResource($address)]);
        }

        $address = $action->execute($user, $address);

        return response()->json(['success' => true, 'message' => 'Default address updated successfully', 'data' => new AddressResource($address)]);
    }

    /**
     * Format address data for response
     */
    // Controller intentionally minimal; formatting handled by AddressResource
}
