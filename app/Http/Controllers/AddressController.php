<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Models\Address;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AddressController extends Controller
{
    public function index(): View
    {
        $addresses = Address::with('user')
            ->latest()
            ->paginate(15);

        return view('admin.addresses.index', compact('addresses'));
    }

    public function create(): View
    {
        return view('admin.addresses.create');
    }

    public function store(AddressRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if (!empty($validated['is_default'])) {
            Address::where('user_id', $validated['user_id'])->update(['is_default' => false]);
        }

        Address::create($validated);

        return redirect()
            ->route('addresses.index')
            ->with('success', 'تم إضافة العنوان بنجاح.');
    }

    public function show(Address $address): View
    {
        $address->load('user');

        return view('admin.addresses.show', compact('address'));
    }

    public function edit(Address $address): View
    {
        return view('admin.addresses.edit', compact('address'));
    }

    public function update(AddressRequest $request, Address $address): RedirectResponse
    {
        $validated = $request->validated();

        if (!empty($validated['is_default'])) {
            Address::where('user_id', $validated['user_id'])
                ->where('id', '!=', $address->id)
                ->update(['is_default' => false]);
        }

        $address->update($validated);

        return redirect()
            ->route('addresses.index')
            ->with('success', 'تم تحديث العنوان بنجاح.');
    }

    public function destroy(Address $address): RedirectResponse
    {
        $address->delete();

        return redirect()
            ->route('addresses.index')
            ->with('success', 'تم حذف العنوان بنجاح.');
    }
}