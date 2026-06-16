<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\OfferResource;
use App\Models\Offer;
use App\Traits\ApiResponse;
use Illuminate\Http\{JsonResponse, Request};

class OfferController extends Controller
{
    use ApiResponse;

    // Get all active offers
    public function index(Request $request): JsonResponse
    {
        $offers = Offer::active()
            ->when($request->type, fn($q, $type) => $q->where('type', $type))
            ->when($request->min_purchase, fn($q, $min) => $q->where(fn($sub) => $sub->where('minimum_purchase', '<=', $min)->orWhereNull('minimum_purchase')))
            ->when($request->boolean('featured'), fn($q) => $q->featured())
            ->when($request->search, fn($q, $s) => $q->where(fn($sub) => $sub->where('title', 'like', "%{$s}%")->orWhere('code', 'like', "%{$s}%")))
            ->orderBy($request->get('order_by', 'created_at'), $request->get('order_direction', 'desc'))
            ->paginate($request->get('per_page', 15));

        return $this->successResponse(OfferResource::collection($offers));
    }

    // Get featured offers
    public function featured(): JsonResponse
    {
        $offers = Offer::featured()->latest()->take(5)->get();
        return $this->successResponse(OfferResource::collection($offers));
    }

    // Get offer by code
    public function showByCode(string $code): JsonResponse
    {
        $offer = Offer::where('code', $code)->firstOrFail();
        return $this->successResponse(new OfferResource($offer));
    }

    // Validate offer code
    public function validateOffer(Request $request): JsonResponse
    {
        $request->validate(['code' => 'required|string', 'amount' => 'nullable|numeric|min:0']);
        
        $offer = Offer::where('code', $request->code)->first();
        if (!$offer) return $this->errorResponse('Invalid offer code', 404);

        $isValid = $offer->isValid();
        $canApply = !$request->has('amount') || $offer->canApplyToAmount($request->amount);
        
        return $this->successResponse([
            'valid'           => $isValid && $canApply,
            'offer'           => new OfferResource($offer),
            'discount_amount' => ($isValid && $canApply) ? $offer->calculateDiscount($request->amount ?? 0) : 0,
        ], ($isValid && $canApply) ? 'Offer is valid' : 'Minimum purchase required: $' . $offer->minimum_purchase);
    }
}