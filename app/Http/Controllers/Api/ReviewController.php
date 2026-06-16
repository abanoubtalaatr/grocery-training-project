<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Http\Requests\Review\GetReviewsRequest;
use App\Http\Requests\Review\GetMealReviewsRequest;
use App\Http\Requests\Review\GetUserReviewsRequest;
use App\Actions\Review\GetReviewsAction;
use App\Actions\Review\StoreReviewAction;
use App\Actions\Review\GetReviewAction;
use App\Actions\Review\UpdateReviewAction;
use App\Actions\Review\DeleteReviewAction;
use App\Actions\Review\GetMealReviewsAction;
use App\Actions\Review\GetUserReviewsAction;
use App\Actions\Review\GetMealReviewStatsAction;
use App\Actions\Review\SearchReviewsAction;
use App\Http\Resources\Api\ReviewResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

class ReviewController extends Controller
{
    public function index(GetReviewsRequest $request, GetReviewsAction $action): JsonResponse
    {
        $reviews = $action($request->validated(), $request->input('per_page', 15));
        
        return response()->json([
            'success' => true,
            'data' => ReviewResource::collection($reviews),
            'meta' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'per_page' => $reviews->perPage(),
                'total' => $reviews->total(),
            ]
        ]);
    }

    public function store(StoreReviewRequest $request, StoreReviewAction $action): JsonResponse
    {
        try {
            $review = $action($request->validated());
            
            return response()->json([
                'success' => true,
                'message' => 'Review submitted successfully. Waiting for admin approval.',
                'data' => new ReviewResource($review)
            ], 201);
        } catch (Exception $e) {
            if ($e->getCode() === 400) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 400);
            }
            throw $e;
        }
    }

    public function show($id, GetReviewAction $action): JsonResponse
    {
        $review = $action($id);
        
        return response()->json([
            'success' => true,
            'data' => new ReviewResource($review)
        ]);
    }

    public function update(UpdateReviewRequest $request, $id, UpdateReviewAction $action): JsonResponse
    {
        try {
            $review = $action($id, $request->validated());
            
            return response()->json([
                'success' => true,
                'message' => 'Review updated successfully',
                'data' => new ReviewResource($review)
            ]);
        } catch (Exception $e) {
            if ($e->getCode() === 403) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
            throw $e;
        }
    }

    public function destroy($id, DeleteReviewAction $action): JsonResponse
    {
        try {
            $action($id);
            
            return response()->json([
                'success' => true,
                'message' => 'Review deleted successfully'
            ]);
        } catch (Exception $e) {
            if ($e->getCode() === 403) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
            throw $e;
        }
    }

    public function getMealReviews($mealId, GetMealReviewsRequest $request, GetMealReviewsAction $action): JsonResponse
    {
        $result = $action($mealId, $request->input('per_page', 10));
        
        return response()->json([
            'success' => true,
            'meal' => [
                'id' => $result['meal']->id,
                'name' => $result['meal']->name,
                'average_rating' => round($result['average_rating'], 1),
                'total_reviews' => $result['total_reviews'],
            ],
            'data' => ReviewResource::collection($result['reviews']),
            'meta' => [
                'current_page' => $result['reviews']->currentPage(),
                'last_page' => $result['reviews']->lastPage(),
                'per_page' => $result['reviews']->perPage(),
                'total' => $result['reviews']->total(),
            ]
        ]);
    }

    public function getUserReviews(GetUserReviewsRequest $request, GetUserReviewsAction $action): JsonResponse
    {
        $reviews = $action($request->input('user_id'), $request->input('per_page', 10));
        
        return response()->json([
            'success' => true,
            'data' => ReviewResource::collection($reviews),
            'meta' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'per_page' => $reviews->perPage(),
                'total' => $reviews->total(),
            ]
        ]);
    }

    public function getMealReviewStats($mealId, GetMealReviewStatsAction $action): JsonResponse
    {
        $stats = $action($mealId);
        
        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    public function Search(Request $request, SearchReviewsAction $action): JsonResponse
    {
        $reviews = $action($request);        
        
        return response()->json([
            'success' => true,
            'data' => ReviewResource::collection($reviews)
        ]);
    }
}