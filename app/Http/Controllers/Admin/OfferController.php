<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OfferRequest;
use App\Models\Offer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OfferController extends Controller
{
    public function index(Request $request): View
    {
        $offers = Offer::query()
            ->when($request->string('search')->toString(), fn ($query, $search) => $query->where('title', 'like', "%{$search}%")->orWhere('code', 'like', "%{$search}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.offers.index', compact('offers'));
    }

    public function create(): View
    {
        return view('admin.offers.create', ['offer' => new Offer()]);
    }

    public function store(OfferRequest $request): RedirectResponse
    {
        Offer::create($request->validated());

        return redirect()->route('admin.offers.index')->with('success', 'Offer created successfully.');
    }

    public function show(Offer $offer): View
    {
        return view('admin.offers.show', compact('offer'));
    }

    public function edit(Offer $offer): View
    {
        return view('admin.offers.edit', compact('offer'));
    }

    public function update(OfferRequest $request, Offer $offer): RedirectResponse
    {
        $offer->update($request->validated());

        return redirect()->route('admin.offers.index')->with('success', 'Offer updated successfully.');
    }

    public function destroy(Offer $offer): RedirectResponse
    {
        $offer->delete();

        return redirect()->route('admin.offers.index')->with('success', 'Offer deleted successfully.');
    }
}
