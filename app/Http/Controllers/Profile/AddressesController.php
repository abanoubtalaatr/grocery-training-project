<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddressRequest;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddressesController extends Controller
{
    /**
     * Display addresses page.
     */
    public function index(Request $request)
    {
        $user = auth()->user() ?? \App\Models\User::first();
        
        $addresses = $user->addresses()
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('dashboard.addresses', compact('user', 'addresses'));
    }

    /**
     * Store new address.
     */
    public function store(AddressRequest $request)
    {
        $user = auth()->user() ?? \App\Models\User::first();

        DB::transaction(function () use ($request, $user) {
            $isFirstAddress = $user->addresses()->count() === 0;
            $isDefault = $request->boolean('is_default') || $isFirstAddress;
            
            $data = array_merge(
                $request->validated(),
                ['is_default' => $isDefault]
            );

            $phone = trim($data['phone'] ?? '');
            $code = trim($data['country_code'] ?? '');
            if ($code !== '' && str_starts_with($phone, $code)) {
                $data['phone'] = substr($phone, strlen($code));
            }

            if ($isDefault) {
                $user->addresses()->update(['is_default' => false]);
            }

            $user->addresses()->create($data);
        });

        return redirect()->back()->with('success', 'Address created successfully.');
    }

    /**
     * Update address.
     */
    public function update(AddressRequest $request, $id)
    {
        $user = auth()->user() ?? \App\Models\User::first();
        $address = $user->addresses()->findOrFail($id);

        DB::transaction(function () use ($request, $address, $user) {
            $updateData = $request->validated();
            
            if (isset($updateData['phone'], $updateData['country_code']) && $updateData['country_code'] !== '' && str_starts_with(trim($updateData['phone']), $updateData['country_code'])) {
                $updateData['phone'] = substr(trim($updateData['phone']), strlen($updateData['country_code']));
            }

            $isDefault = $request->boolean('is_default');
            if ($isDefault) {
                $user->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
            }
            $updateData['is_default'] = $isDefault;

            $address->fill($updateData)->save();
        });

        return redirect()->back()->with('success', 'Address updated successfully.');
    }

    /**
     * Set address as default.
     */
    public function setDefault(Request $request, $id)
    {
        $user = auth()->user() ?? \App\Models\User::first();
        $address = $user->addresses()->findOrFail($id);

        DB::transaction(function () use ($user, $address) {
            $user->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
            $address->update(['is_default' => true]);
        });

        return redirect()->back()->with('success', 'Default address updated successfully.');
    }

    /**
     * Delete address.
     */
    public function destroy(Request $request, $id)
    {
        $user = auth()->user() ?? \App\Models\User::first();
        $address = $user->addresses()->findOrFail($id);

        DB::transaction(function () use ($user, $address) {
            $wasDefault = $address->is_default;
            $address->delete();

            if ($wasDefault) {
                $newDefault = $user->addresses()->first();
                if ($newDefault) {
                    $newDefault->update(['is_default' => true]);
                }
            }
        });

        return redirect()->back()->with('success', 'Address deleted successfully.');
    }
}
