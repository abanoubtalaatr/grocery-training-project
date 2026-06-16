<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Http\Resources\AddressResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        if ($request->allFiles() !== []) {
            return response()->json([
                'success' => false,
                'message' => 'This endpoint does not accept file uploads.',
                'errors'  => ['files' => ['Remove file attachments from the request.']],
            ], 422);
        }

        try {
            $addresses = $request->user()->addresses()
                ->orderBy('is_default', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success'     => true,
                'message'     => 'Addresses retrieved successfully',
                'data'        => AddressResource::collection($addresses),
                'total_count' => $addresses->count(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve addresses',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Request $request, string $id): JsonResponse
    {
        try {
            $address = $request->user()->addresses()->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Address retrieved successfully',
                'data'    => new AddressResource($address),
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json(['success' => false, 'message' => 'Address not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to retrieve address', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(StoreAddressRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $user = $request->user();
            $isFirstAddress = $user->addresses()->count() === 0;
            $data = array_merge($request->validated(), [
                'is_default' => $request->boolean('is_default') || $isFirstAddress,
            ]);

            $data = $this->normalizePhone($data);
            $address = $user->addresses()->create($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Address created successfully',
                'data'    => new AddressResource($address),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to create address', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(UpdateAddressRequest $request, string $id): JsonResponse
    {
        try {
            $address = $request->user()->addresses()->findOrFail($id);

            DB::beginTransaction();

            $data = $this->normalizePhone($request->validated());
            $address->fill($data)->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Address updated successfully',
                'data'    => new AddressResource($address->fresh()),
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json(['success' => false, 'message' => 'Address not found'], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to update address', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        try {
            $address = $request->user()->addresses()->findOrFail($id);

            DB::beginTransaction();

            $wasDefault = $address->is_default;
            $address->delete();

            if ($wasDefault) {
                $request->user()->addresses()->first()?->update(['is_default' => true]);
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Address deleted successfully']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json(['success' => false, 'message' => 'Address not found'], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to delete address', 'error' => $e->getMessage()], 500);
        }
    }

    public function setDefault(Request $request, string $id): JsonResponse
    {
        try {
            $user = $request->user();
            $address = $user->addresses()->findOrFail($id);

            if ($address->is_default) {
                return response()->json([
                    'success'         => true,
                    'message'         => 'This address is already your default.',
                    'already_default' => true,
                    'data'            => new AddressResource($address),
                ]);
            }

            DB::beginTransaction();

            $user->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
            $address->update(['is_default' => true]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Default address updated successfully',
                'data'    => new AddressResource($address->fresh()),
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json(['success' => false, 'message' => 'Address not found'], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to set default address', 'error' => $e->getMessage()], 500);
        }
    }

    private function normalizePhone(array $data): array
    {
        $phone = trim($data['phone'] ?? '');
        $code  = trim($data['country_code'] ?? '');

        if ($code !== '' && str_starts_with($phone, $code)) {
            $data['phone'] = substr($phone, strlen($code));
        }

        return $data;
    }
}