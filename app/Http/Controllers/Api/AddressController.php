<?php

namespace App\Http\Controllers\Api;

use App\Actions\Address\CreateAddressAction;
use App\Actions\Address\GetAddressAction;
use App\Actions\Address\GetAddressesAction;
use App\Actions\Address\SetDefaultAddressAction;
use App\Actions\Address\UpdateAddressAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    public function index(Request $request, GetAddressesAction $action): JsonResponse
    {
        $addresses = $action->execute($request->user());

        return response()->json([
            'success' => true,
            'message' => 'Addresses retrieved successfully',
            'data' => AddressResource::collection($addresses),
            'total_count' => $addresses->count(),
        ]);
    }

    public function show(Request $request, Address $address, GetAddressAction $action): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Address retrieved successfully',
            'data' => AddressResource::make($action->execute($request->user(), $address)),
        ]);
    }

    public function store(StoreAddressRequest $request, CreateAddressAction $action): JsonResponse
    {
        $address = $action->execute($request->user(), $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Address created successfully',
            'data' => AddressResource::make($address),
        ], 201);
    }

    public function update(
        UpdateAddressRequest $request,
        Address $address,
        UpdateAddressAction $action
    ): JsonResponse {
        $address = $action->execute($request->user(), $address, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Address updated successfully',
            'data' => AddressResource::make($address),
        ]);
    }

  public function destroy(
    Request $request,
    Address $address
    ): JsonResponse {
    abort_if(
        $address->user_id !== $request->user()->id,
        404
    );

    DB::transaction(function () use ($request, $address) {
        $wasDefault = $address->is_default;

        $address->delete();

        if ($wasDefault) {
            $request->user()
                ->addresses()
                ->first()?->update([
                    'is_default' => true,
                ]);
        }
    });

    return response()->json([
        'success' => true,
        'message' => 'Address deleted successfully',
    ]);
}

    public function setDefault(
    Request $request,
    Address $address,
    SetDefaultAddressAction $action
): JsonResponse {
    $address = $action(
        $request->user(),
        $address
    );

    return response()->json([
        'success' => true,
        'message' => 'Default address updated successfully',
        'data' => AddressResource::make($address),
    ]);
}
}
