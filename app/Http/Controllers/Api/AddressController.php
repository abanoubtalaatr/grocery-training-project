<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;

use App\Actions\Address\CreateAddressAction;
use App\Actions\Address\UpdateAddressAction;
use App\Actions\Address\DeleteAddressAction;
use App\Actions\Address\SetDefaultAddressAction;

class AddressController extends Controller
{
    /**
     * Get all user addresses
     */
   public function index(Request $request): JsonResponse
{
    $addresses = $request->user()
        ->addresses()
        ->latest()
        ->orderByDesc('is_default')
        ->get()
        ->map(fn ($address) => $this->formatAddress($address));

    return response()->json([
        'success' => true,
        'message' => 'Addresses retrieved successfully',
        'data' => $addresses,
        'total_count' => $addresses->count(),
    ]);
}
    /**
     * Get single address
     */
   public function show(
    Request $request,
    string $id,
    GetAddressAction $action
): JsonResponse
{
    $address = $action->execute(
        $request->user(),
        $id
    );

    return response()->json([
        'success' => true,
        'message' => 'Address retrieved successfully',
        'data' => $this->formatAddress($address),
    ]);
}

    /**
     * Create new address
     */
  public function store(
    StoreAddressRequest $request,
    CreateAddressAction $action
): JsonResponse
{
    $address = $action->execute(
        $request->user(),
        $request->validated()
    );

    return response()->json([
        'success' => true,
        'message' => 'Address created successfully',
        'data' => $this->formatAddress($address),
    ], 201);
}
    /**
     * Update address
     */
    public function update(
    UpdateAddressRequest $request,
    string $id,
    UpdateAddressAction $action
    ): JsonResponse
        {
      $address = $action->execute(
        $request->user(),
        $id,
        $request->validated()
    );

    return response()->json(['success' => true,'message' => 'Address updated successfully','data' => $this->formatAddress($address),
    ]);
}

    /**
     * Delete address
     */
   public function destroy(
    Request $request,
    string $id,
    DeleteAddressAction $action
): JsonResponse
{
    $action->execute(
        $request->user(),
        $id
    );

    return response()->json([
        'success' => true,
        'message' => 'Address deleted successfully',
    ]);
}

    /**
     * Set address as default
     */
    public function setDefault(
    Request $request,
    string $id,
    SetDefaultAddressAction $action
): JsonResponse
{
    $address = $action->execute(
        $request->user(),
        $id
    );

    return response()->json([
        'success' => true,
        'message' => 'Default address updated successfully',
        'data' => AddressResource::make($address),
    ]);
}

   
}
