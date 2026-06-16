<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Traits\V1\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SetDefaultAddressController extends Controller
{
    use ApiResponse;

    /**
     * Set the given address as the default for its user.
     *
     * @param Request $request
     * @param Address $address
     * @return JsonResponse
     */
    public function __invoke(Request $request, Address $address): JsonResponse
    {
        // Security check: Ensure address belongs to the user
        if ($address->user_id !== $request->user()->id) {
            return self::errorResponse('Unauthorized', null, 403);
        }

        $defaultAddress = DB::transaction(function () use ($address) {
            $address->user->addresses()
                ->where('id', '!=', $address->id)
                ->update(['is_default' => false]);

            $address->update(['is_default' => true]);

            return $address->fresh();
        });

        return self::successResponse(
            'Address set as default successfully',
            $defaultAddress
        );
    }
}
