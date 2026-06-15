<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Actions\Address\AddressStoreAction;
use App\Http\Controllers\Api\Actions\Address\AddressUpdateAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AddressRequest;
use App\Http\Resources\Api\V1\AddressResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponse;

class AddressController extends Controller
{
    use ApiResponse;

    public function index(Request $request): JsonResponse
    {
        if ($request->allFiles() !== []) {
            return $this->errorResponse('This endpoint does not accept file uploads.', ['files' => ['Remove file attachments from the request.']], 422);
        }

        try {
            $addresses = $request->user()
                ->addresses()
                ->orderBy('is_default', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();

            return $this->successResponse(
                'Addresses retrieved successfully',
                AddressResource::collection($addresses),
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve addresses', $e->getMessage(), 500);
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
            return $this->errorResponse('Address not found', null, 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve address', $e->getMessage(), 500);
        }
    }

    public function store(AddressRequest $request, AddressStoreAction $action): JsonResponse
    {
        try {
            $address = $action->handle($request->user(), $request->validated());

            return $this->successResponse(
                'Address created successfully',
                new AddressResource($address),
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create address', $e->getMessage());
        }
    }

    public function update(AddressRequest $request, string $id, AddressUpdateAction $action): JsonResponse
    {
        try {
            $address = $action->handle($request->user()->addresses()->findOrFail($id), $request->validated());

            return $this->successResponse(
                'Address updated successfully',
                new AddressResource($address),
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return $this->errorResponse('Address not found', null, 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update address', $e->getMessage(), 500);
        }
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        try {
            $user    = $request->user();
            $address = $user->addresses()->findOrFail($id);

            DB::transaction(function () use ($user, $address) {
                $wasDefault = $address->is_default;
                $address->delete();

                if ($wasDefault) {
                    $user->addresses()->first()?->update(['is_default' => true]);
                }
            });

            return $this->successResponse('Address deleted successfully');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return $this->errorResponse('Address not found', null, 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete address', $e->getMessage(), 500);
        }
    }
}