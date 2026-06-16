<?php

namespace App\Http\Controllers\Api;

use App\Actions\Address\DeleteAddressAction;
use App\Actions\Address\StoreAddressAction;
use App\Actions\Address\UpdateAddressAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Addresses\StoreAddressRequest;
use App\Http\Requests\Api\V1\Addresses\UpdateAddressRequest;
use App\Http\Resources\Api\V1\Addresses\AddressResource;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        if ($request->allFiles() !== []) {
            return $this->errorResponse(
                message: 'This endpoint does not accept file uploads.',
                errors: ['files' => ['Remove file attachments from the request.']],
                statusCode: 422,
            );
        }

        try {
            $addresses = $request->user()
                ->addresses()
                ->orderBy('is_default', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();

            return $this->successResponse(
                'Addresses retrieved successfully',
                [
                    'addresses'   => AddressResource::collection($addresses),
                    'total_count' => $addresses->count(),
                ],
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve addresses');
        }
    }

    public function show(Request $request, string $id): JsonResponse
    {
        try {
            $address = $request->user()->addresses()->findOrFail($id);

            return $this->successResponse(
                'Address retrieved successfully',
                new AddressResource($address),
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return $this->errorResponse('Address not found', 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve address');
        }
    }

    public function store(StoreAddressRequest $request, StoreAddressAction $action): JsonResponse
    {
        try {
            $address = $action->execute($request->user(), $request->validated());

            return $this->successResponse(
                'Address created successfully',
                new AddressResource($address),
                201,
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create address');
        }
    }

    public function update(UpdateAddressRequest $request, string $id, UpdateAddressAction $action): JsonResponse
    {
        try {
            $address = $request->user()->addresses()->findOrFail($id);
            $address = $action->execute($address, $request->validated());

            return $this->successResponse(
                'Address updated successfully',
                new AddressResource($address),
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return $this->errorResponse('Address not found',404);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update address');
        }
    }

    public function destroy(Request $request, string $id, DeleteAddressAction $action): JsonResponse
    {
        try {
            $user    = $request->user();
            $address = $user->addresses()->findOrFail($id);

            $action->execute($user, $address);

            return $this->successResponse('Address deleted successfully');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return $this->errorResponse('Address not found',404);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete address');
        }
    }
}