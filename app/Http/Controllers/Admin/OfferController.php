<?php

namespace App\Http\Controllers\Admin;

use App\Models\Offer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Admin\OfferService;
use App\Http\Requests\Admin\StoreOfferRequest;
use App\Http\Requests\Admin\UpdateOfferRequest;

class OfferController extends Controller
{
    public function __construct(
        private OfferService $offerService
    ) {}

    public function index(Request $request)
    {
        $offers = $this->offerService
            ->paginate($request);

        return view(
            'admin.offers.index',
            compact('offers')
        );
    }

    public function create()
    {
        return view(
            'admin.offers.create'
        );
    }

    public function store(
        StoreOfferRequest $request
    ) {
        $this->offerService->store(
            $request->validated()
        );

        return redirect()
            ->route('admin.offers.index')
            ->with(
                'success',
                'Offer created successfully.'
            );
    }

    public function edit(
        Offer $offer
    ) {
        return view(
            'admin.offers.edit',
            compact('offer')
        );
    }

    public function update(
        UpdateOfferRequest $request,
        Offer $offer
    ) {
        $this->offerService->update(
            $offer,
            $request->validated()
        );

        return redirect()
            ->route('admin.offers.index')
            ->with(
                'success',
                'Offer updated successfully.'
            );
    }

    public function destroy(
        Offer $offer
    ) {
        $this->offerService->delete(
            $offer
        );

        return back()->with(
            'success',
            'Offer deleted successfully.'
        );
    }
}