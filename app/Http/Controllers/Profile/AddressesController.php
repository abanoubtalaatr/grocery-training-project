<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddressRequest;
use App\Services\ProfileService;
use Illuminate\Http\Request;

class AddressesController extends Controller
{
    public function __construct(
        protected ProfileService $profileService
    ) {}

    /**
     * Display addresses page.
     */
    public function index(Request $request)
    {
        $user = auth()->user() ?? \App\Models\User::first();
        
        $addresses = $this->profileService->getAddresses($user);
            
        return view('dashboard.addresses', compact('user', 'addresses'));
    }

    /**
     * Store new address.
     */
    public function store(AddressRequest $request)
    {
        $user = auth()->user() ?? \App\Models\User::first();

        $this->profileService->createAddress($user, $request->validated());

        return redirect()->back()->with('success', 'Address created successfully.');
    }

    /**
     * Update address.
     */
    public function update(AddressRequest $request, $id)
    {
        $user = auth()->user() ?? \App\Models\User::first();
        $address = $user->addresses()->findOrFail($id);

        $this->profileService->updateAddress($user, $address, $request->validated());

        return redirect()->back()->with('success', 'Address updated successfully.');
    }

    /**
     * Set address as default.
     */
    public function setDefault(Request $request, $id)
    {
        $user = auth()->user() ?? \App\Models\User::first();
        $address = $user->addresses()->findOrFail($id);

        $this->profileService->setDefaultAddress($user, $address);

        return redirect()->back()->with('success', 'Default address updated successfully.');
    }

    /**
     * Delete address.
     */
    public function destroy(Request $request, $id)
    {
        $user = auth()->user() ?? \App\Models\User::first();
        $address = $user->addresses()->findOrFail($id);

        $this->profileService->deleteAddress($user, $address);

        return redirect()->back()->with('success', 'Address deleted successfully.');
    }
}
