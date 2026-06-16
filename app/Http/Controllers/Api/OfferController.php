<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Offer\GetOffersRequest;
use App\Http\Requests\Offer\ValidateOfferRequest;
use App\Actions\Offer\GetOffersAction;
use App\Actions\Offer\GetFeaturedOffersAction;
use App\Actions\Offer\GetOfferByCodeAction;
use App\Actions\Offer\ValidateOfferAction;
use App\Http\Resources\Api\OfferResource;

class OfferController extends Controller
{
    public function index(GetOffersRequest $request, GetOffersAction $action)
    {
        $offers = $action(
            $request->validated(), 
            $request->input('per_page', 15), 
            $request->input('order_by', 'created_at'), 
            $request->input('order_direction', 'desc')
        );
        
        return OfferResource::collection($offers);
    }

    public function featured(GetFeaturedOffersAction $action)
    {
        return OfferResource::collection($action());
    }

    public function showByCode(string $code, GetOfferByCodeAction $action)
    {
        return new OfferResource($action($code));
    }

    public function validateOffer(ValidateOfferRequest $request, ValidateOfferAction $action)
    {
        $result = $action($request->input('code'), $request->input('amount'));
        
        if ($result['status'] === 404) {
            return response()->json([
                'valid' => $result['valid'], 
                'message' => $result['message']
            ], 404);
        }
        
        return response()->json([
            'valid' => $result['valid'],
            'offer' => new OfferResource($result['offer']),
            'discount_amount' => $result['discount_amount'],
            'message' => $result['message'],
        ]);
    }
}