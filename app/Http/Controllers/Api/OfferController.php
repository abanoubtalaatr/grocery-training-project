<?php

namespace App\Http\Controllers\Api;

use App\Http\Actions\Api\Offer\GetFeaturedOffersAction;
use App\Http\Actions\Api\Offer\GetOfferByCodeAction;
use App\Http\Actions\Api\Offer\GetOffersAction;
use App\Http\Actions\Api\Offer\ValidateOfferAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Offer\OfferIndexRequest;
use App\Http\Requests\Api\Offer\ValidateOfferRequest;
use App\Http\Resources\Api\OfferResource;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;



class OfferController extends Controller
{
    use ApiResponse;

    public function index(
        OfferIndexRequest $request,
        GetOffersAction $action
    ): JsonResponse {

        $offers = $action->execute(
            $request->validated()
        );

        return $this->successResponse(
            'Offers retrieved successfully',
            OfferResource::collection($offers)
        );
    }

    public function featured(
        GetFeaturedOffersAction $action
    ): JsonResponse {

        return $this->successResponse(
            'Featured offers retrieved successfully',
            OfferResource::collection(
                $action->execute()
            )
        );
    }

    public function showByCode(
        string $code,
        GetOfferByCodeAction $action
    ): JsonResponse {

        $offer = $action->execute($code);

        return $this->successResponse(
            'Offer retrieved successfully',
            new OfferResource($offer)
        );
    }

    public function validateOffer(
        ValidateOfferRequest $request,
        ValidateOfferAction $action
    ): JsonResponse {

        try {

            $result = $action->execute(
                $request->validated()['code'],
                $request->validated()['amount'] ?? null
            );

            return $this->successResponse(
                $result['message'],
                [
                    'valid' => $result['valid'],
                    'offer' => new OfferResource(
                        $result['offer']
                    ),
                    'discount_amount' =>
                        $result['discount_amount'],
                ]
            );

        } catch (ModelNotFoundException) {

            return $this->errorResponse(
                'Invalid offer code',
                [],
                404
            );
        }
    }
}