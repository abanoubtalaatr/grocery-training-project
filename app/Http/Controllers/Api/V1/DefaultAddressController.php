<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use App\Traits\V1\ApiResponse;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\Api\V1\AddressResource;

class DefaultAddressController extends Controller
{
  use ApiResponse;


  /**
   * Set address as default
   */

  public function __invoke(Request $request, string $id): JsonResponse
  {
    $user = $request->user();
    $address = $user->addresses()->findOrFail($request->address);
    $address->update(['is_default' => true]);

    return ApiResponse::successResponse(
      'Address set as default',
      new AddressResource($address),
      200
    );
  }
}
