<?php

namespace App\Http\Controllers\Api;

use App\Actions\Address\StoreAddressAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\AddressRequest;
use App\Models\Address;
use App\Traits\ResponseApi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    use ResponseApi;
    /**
     * Get all user addresses
     */
    public function index(Request $request): JsonResponse
    {
      
            $user = $request->user();

            $addresses = $user->addresses()
                ->orderBy('is_default', 'desc')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($address) {
                    return $this->formatAddress($address);
                });

            return $this->success('Addresses retrieved successfully',$addresses,200,'total_count'=> $addresses->count());
               
       
    }

    /**
     * Get single address
     */
    public function show(Request $request, Address $address): JsonResponse
    {
       
            $address = $request->user->addresses()->findOrFail($address->id);

            return $this->success(true,'Address retrieved successfully',$this->formatAddress($address));
                
        } 
    /**
     * Create new address
     */
    public function store(AddressRequest $request, StoreAddressAction $storeAddressAction): JsonResponse
    {
              $user=$request->user();

            return $this->success('Address created successfully', $this->formatAddress($storeAddressAction->excute($user,$request)),201);
         
    }

    /**
     * Update address
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $user = $request->user();
            $address = $user->addresses()->findOrFail($id);

            $validator = Validator::make($request->all(), [
                'label' => ['sometimes', 'string', 'max:255'],
                'full_name' => ['sometimes', 'string', 'min:2', 'max:255'],
                'phone' => ['sometimes', 'string', 'min:10', 'max:20', 'regex:/^\+?[1-9]\d{9,14}$/'],
                'country_code' => ['sometimes', 'nullable', 'string', 'max:5', 'regex:/^\+\d{1,4}$/'],
                'street_address' => ['sometimes', 'string', 'min:5', 'max:500'],
                'building_number' => ['nullable', 'string', 'max:50'],
                'floor' => ['nullable', 'string', 'max:50'],
                'apartment' => ['nullable', 'string', 'max:50'],
                'landmark' => ['nullable', 'string', 'max:255'],
                'city' => ['sometimes', 'string', 'min:2', 'max:100'],
                'state' => ['nullable', 'string', 'max:100'],
                'postal_code' => ['nullable', 'string', 'max:20'],
                'country' => ['nullable', 'string', 'max:100'],
                'notes' => ['nullable', 'string', 'max:1000'],
                'is_default' => ['nullable', 'boolean'],
                'latitude' => ['nullable', 'numeric', 'between:-90,90'],
                'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            DB::beginTransaction();

            $updateData = $validator->validated();
            // Normalize phone on update: if phone already starts with country code, store only national part
            if (isset($updateData['phone'], $updateData['country_code']) && $updateData['country_code'] !== '' && str_starts_with(trim($updateData['phone']), $updateData['country_code'])) {
                $updateData['phone'] = substr(trim($updateData['phone']), strlen($updateData['country_code']));
            }
            $address->fill($updateData)->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Address updated successfully',
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
                'message' => 'Failed to update address',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete address
     */
    public function destroy(Request $request, Address $address): JsonResponse
    {
      
            $user = $request->user();
            $address = $user->addresses()->findOrFail($address->id);

           

            $wasDefault = $address->is_default;
            $address->delete();

            // If deleted address was default, set another address as default
            if ($wasDefault) {
                $newDefault = $user->addresses()->first();
                if ($newDefault) {
                    $newDefault->update(['is_default' => true]);
                }
            }

            DB::commit();

            return $this->success('Address deleted successfully');
       
    }

    /**
     * Set address as default
     */
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
