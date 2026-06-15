<?php

namespace App\Http\Controllers\Api;

use App\Actions\Address\DeleteAddressAction;
use App\Actions\Address\SetDefaultAddressAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

        $addresses = $request->user()->addresses()
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

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
    public function show(Request $request, Address $address): JsonResponse
    {
        $this->authorizeOwner($request, $address);

        return response()->json([
            'success' => true,
            'message' => 'Address retrieved successfully',
            'data' => new AddressResource($address),
        ]);
    }

    /**
     * Create new address
     */
    public function store(StoreAddressRequest $request): JsonResponse
    {
        $user = $request->user();
        $isFirstAddress = $user->addresses()->count() === 0;

        $address = $user->addresses()->create(array_merge(
            $request->validated(),
            ['is_default' => $request->boolean('is_default') || $isFirstAddress]
        ));

        return response()->json([
            'success' => true,
            'message' => 'Address created successfully',
            'data' => new AddressResource($address),
        ], 201);
    }

    /**
     * Update address
     */
    public function update(UpdateAddressRequest $request, Address $address): JsonResponse
    {
        $this->authorizeOwner($request, $address);

        $address->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Address updated successfully',
            'data' => new AddressResource($address->fresh()),
        ]);
    }

    /**
     * Delete address
     */
    public function destroy(Request $request, Address $address): JsonResponse
    {
        $this->authorizeOwner($request, $address);

        DeleteAddressAction::run($request->user(), $address);

        return response()->json([
            'success' => true,
            'message' => 'Address deleted successfully',
        ]);
    }

    /**
     * Set address as default
     */
    public function setDefault(Request $request, Address $address): JsonResponse
    {
        $this->authorizeOwner($request, $address);

        if ($address->is_default) {
            return response()->json([
                'success' => true,
                'message' => 'This address is already your default.',
                'already_default' => true,
                'data' => new AddressResource($address),
            ]);
        }

        $address = SetDefaultAddressAction::run($request->user(), $address);

        return response()->json([
            'success' => true,
            'message' => 'Default address updated successfully',
            'data' => new AddressResource($address),
        ]);
    }

    /**
     * Authorize that the user owns the address.
     */
    private function authorizeOwner(Request $request, Address $address): void
    {
        if ($address->user_id !== $request->user()->id) {
            abort(404, 'Address not found');
        }
    }
}
