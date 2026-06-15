<?php

namespace App\Http\Controllers\Api\Addresses;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\AddressResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SetDefaultAddressController extends Controller
{
    public function __invoke(Request $request, string $id): JsonResponse
    {
        try {
            $user    = $request->user();
            $address = $user->addresses()->findOrFail($id);

            if ($address->is_default) {
                return $this->successResponse(
                    'This address is already your default.',
                    new AddressResource($address),
                );
            }

            DB::transaction(function () use ($user, $address) {
                $user->addresses()
                     ->where('id', '!=', $address->id)
                     ->update(['is_default' => false]);

                $address->update(['is_default' => true]);
            });

            return $this->successResponse(
                'Default address updated successfully',
                new AddressResource($address->fresh()),
            );

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return $this->errorResponse('Address not found', code: 404);

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to set default address', $e->getMessage());
        }
    }
}