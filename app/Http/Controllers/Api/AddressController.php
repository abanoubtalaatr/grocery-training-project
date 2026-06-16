<?php

namespace App\Http\Controllers\Api;

use App\Action\Address\DeleteAddressAction;
use App\Action\Address\StoreAddressAction;
use App\Action\Address\UpdateAddressAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\AddressRequest;
use App\Models\Address;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    /**
     * Get all user addresses
     */
    public function index(Request $request): JsonResponse
    {
        if ($request->allFiles() !== []) {
            return $this->sendError('This endpoint does not accept file uploads.');
        }

     
            $user = $request->user();

            $addresses = $user->addresses()
                ->orderBy('is_default', 'desc')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($address) {
                    return $this->formatAddress($address);
                });

           return $this->sendResponse('Addresses retrieved successfully', $addresses);
  
      
    }

    /**
     * Get single address
     */
    public function show(Request $request, Address $address): JsonResponse
    {
        return $this->sendResponse('Address retrieved successfully', $this->formatAddress($address));
     
    }

    /**
     * Create new address
     */
    public function store(AddressRequest $request, StoreAddressAction $storeAddressAction): JsonResponse
    {
    
      
            $user = $request->user();
            return $this->sendResponse('Address created successfully', $this->formatAddress($storeAddressAction->execute($user, $request())));
    }

    /**
     * Update address
     */
    public function update(AddressRequest  $request , UpdateAddressAction $updateAddressAction): JsonResponse
    {
     
            $user = $request->user();
             

          return $this->sendResponse('Address updated successfully', $this->formatAddress($updateAddressAction->execute($request(), $user)));
        
    }

    /**
     * Delete address
     */
    public function destroy(Request $request, DeleteAddressAction $DeleteAddressAction): JsonResponse
    {
            $user = $request->user();
            return $this->sendResponse('Address deleted successfully', $DeleteAddressAction->execute($user,$request()));
    }

   
    public function setDefault(Request $request, string $id): JsonResponse
    {
        try {
            $user = $request->user();
            $address = $user->addresses()->findOrFail($id);

            if ($address->is_default) {
                return response()->json([
                    'success' => true,
                    'message' => 'This address is already your default.',
                    'already_default' => true,
                    'data' => $this->formatAddress($address),
                ]);
            }

            DB::beginTransaction();

            $user->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
            $address->update(['is_default' => true]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Default address updated successfully',
                'data' => $this->formatAddress($address->fresh()),
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Address not found',
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to set default address',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Format address data for response
     */
    private function formatAddress(Address $address): array
    {
        return [
            'id' => $address->id,
            'label' => $address->label,
            'full_name' => $address->full_name,
            'phone' => $address->phone,
            'country_code' => $address->country_code,
            'formatted_phone' => $address->formatted_phone,
            'street_address' => $address->street_address,
            'building_number' => $address->building_number,
            'floor' => $address->floor,
            'apartment' => $address->apartment,
            'landmark' => $address->landmark,
            'city' => $address->city,
            'state' => $address->state,
            'postal_code' => $address->postal_code,
            'country' => $address->country,
            'notes' => $address->notes,
            'is_default' => $address->is_default,
            'latitude' => $address->latitude,
            'longitude' => $address->longitude,
            'full_address' => $address->full_address,
            'created_at' => $address->created_at,
            'updated_at' => $address->updated_at,
        ];
    }
}
