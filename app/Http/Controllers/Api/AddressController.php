<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Address\ListAddressesRequest;
use App\Http\Requests\Address\StoreAddressRequest;
use App\Http\Requests\Address\UpdateAddressRequest;
use App\Actions\Address\GetAddressesAction;
use App\Actions\Address\GetAddressAction;
use App\Actions\Address\CreateAddressAction;
use App\Actions\Address\UpdateAddressAction;
use App\Actions\Address\DeleteAddressAction;
use App\Actions\Address\SetDefaultAddressAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index(ListAddressesRequest $request, GetAddressesAction $action): JsonResponse
    {
        try {
            $addresses = $action($request->user());

            return response()->json([
                'success' => true,
                'message' => 'Addresses retrieved successfully',
                'data' => $addresses,
                'total_count' => $addresses->count(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve addresses',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Request $request, string $id, GetAddressAction $action): JsonResponse
    {
        try {
            $data = $action($request->user(), $id);

            return response()->json([
                'success' => true,
                'message' => 'Address retrieved successfully',
                'data' => $data,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Address not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve address',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(StoreAddressRequest $request, CreateAddressAction $action): JsonResponse
    {
        try {
            $data = $action($request->user(), $request->validated(), $request->boolean('is_default'));

            return response()->json([
                'success' => true,
                'message' => 'Address created successfully',
                'data' => $data,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create address',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(UpdateAddressRequest $request, string $id, UpdateAddressAction $action): JsonResponse
    {
        try {
            $data = $action($request->user(), $id, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Address updated successfully',
                'data' => $data,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Address not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update address',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Request $request, string $id, DeleteAddressAction $action): JsonResponse
    {
        try {
            $action($request->user(), $id);

            return response()->json([
                'success' => true,
                'message' => 'Address deleted successfully',
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Address not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete address',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function setDefault(Request $request, string $id, SetDefaultAddressAction $action): JsonResponse
    {
        try {
            $result = $action($request->user(), $id);

            if ($result['already_default']) {
                return response()->json([
                    'success' => true,
                    'message' => 'This address is already your default.',
                    'already_default' => true,
                    'data' => $result['data'],
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Default address updated successfully',
                'data' => $result['data'],
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Address not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to set default address',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
