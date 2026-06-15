<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Actions\Address\CreateAddressAction;
use App\Actions\Address\UpdateAddressAction;
use App\Actions\Address\DeleteAddressAction;
use App\Actions\Address\SetDefaultAddressAction;
use App\Http\Requests\V1\AddressRequest;
use App\Http\Resources\Api\V1\AddressResource;
use App\Models\Address;
use App\Models\User;
use App\Traits\V1\ApiResponse;
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
  public function index(Request $request): JsonResponse
  {
    if ($request->allFiles() !== []) {
      return
        ApiResponse::errorResponse(
          'This endpoint does not accept file uploads.',
          ['files' => ['Remove file attachments from the request.']],
          422
        );
    }

    try {
      $user = $request->user();

      $addresses = $user->addresses()
        ->orderBy('is_default', 'desc')
        ->orderBy('created_at', 'desc')
        ->get();

      return ApiResponse::successResponse(
        'Addresses retrieved successfully',
        AddressResource::collection($addresses)
      );
    } catch (\Exception $e) {
      return ApiResponse::errorResponse(
        'Failed to retrieve addresses',
        $e->getMessage(),
        500
      );
    }
  }

  /**
   * Get single address
   */
  public function show(Request $request, Address $id): JsonResponse
  {
    return $this->successResponse('Address retrieved successfully', new AddressResource($id), 200);
  }


  /**
   * Create new address
   */
  public function store(AddressRequest $request, CreateAddressAction $createAddressAction): JsonResponse
  {
    try {
      $address = $createAddressAction->execute(
        $request->user(),
        $request->validated()
      );

      return ApiResponse::successResponse(
        'Address created successfully',
        new AddressResource($address),
        201
      );
    } catch (\Exception $e) {
      return ApiResponse::errorResponse(
        'Failed to create address',
        $e->getMessage(),
        500
      );
    }
  }

  /**
   * Update address
   */
  public function update(AddressRequest $request, string $id, UpdateAddressAction $updateAddressAction): JsonResponse
  {
    try {
      $user = $request->user();
      $address = $user->addresses()->findOrFail($id);

      $address = $updateAddressAction->execute(
        $address,
        $request->validated()
      );

      return ApiResponse::successResponse(
        'Address updated successfully',
        new AddressResource($address)
      );
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      return ApiResponse::errorResponse('Address not found');
    } catch (\Exception $e) {
      return ApiResponse::errorResponse(
        'Failed to update address',
        $e->getMessage(),
        500
      );
    }
  }

  /**
   * Delete address
   */
  public function destroy(Request $request, string $id, DeleteAddressAction $deleteAddressAction): JsonResponse
  {
    try {
      $user = $request->user();
      $address = $user->addresses()->findOrFail($id);

      $deleteAddressAction->execute($user, $address);

      return ApiResponse::successResponse('Address deleted successfully');
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      return ApiResponse::errorResponse('Address not found');
    } catch (\Exception $e) {
      return ApiResponse::errorResponse(
        'Failed to delete address',
        $e->getMessage(),
        500
      );
    }
  }
}
