<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use App\Services\ReviewService;
use App\Traits\V1\ApiResponse;
use App\Traits\V1\ApiResponseCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    use ApiResponse, ApiResponseCollection;

    protected $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    /**
     * Get all reviews (with filters)
     */
    public function index(Request $request): JsonResponse
    {
        $reviews = $this->reviewService->getReviews($request->all(), $request->input('per_page', 15));

        return self::collectionResponse(
            'Reviews retrieved successfully',
            \App\Http\Resources\Api\ReviewResource::collection($reviews)
        );
    }

    /**
     * Store a new review
     */
    public function store(StoreReviewRequest $request): JsonResponse
    {
        if (Review::hasUserReviewed(Auth::id(), $request->meal_id)) {
            return self::errorResponse('You have already reviewed this meal', null, 400);
        }

        $review = $this->reviewService->createReview($request->validated());

        return self::successResponse(
            'Review submitted successfully. Waiting for admin approval.',
            new ReviewResource($review->load(['user', 'meal'])),
            201
        );
    }

    /**
     * Get single review
     */
    public function show(Review $review): JsonResponse
    {
        $review->load(['user', 'meal']);
        return self::successResponse('Review retrieved successfully', new ReviewResource($review));
    }

    /**
     * Update review
     */
    public function update(UpdateReviewRequest $request, Review $review): JsonResponse
    {
        $this->authorize('update', $review);

        $review->update($request->validated());

        return self::successResponse(
            'Review updated successfully',
            new ReviewResource($review->load(['user', 'meal']))
        );
    }

    /**
     * Delete review
     */
    public function destroy(Review $review): JsonResponse
    {
        $this->authorize('delete', $review);

        $review->delete();

        return self::successResponse('Review deleted successfully');
    }
}
