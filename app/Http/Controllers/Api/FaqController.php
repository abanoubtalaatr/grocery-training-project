<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FaqResource;
use App\Models\Faq;
use App\Traits\V1\ApiResponse;
use App\Traits\V1\ApiResponseCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FaqController extends Controller
{
    use ApiResponse, ApiResponseCollection;

    /**
     * Display a listing of the FAQs.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Faq::query();

        // Filter by category
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // Filter active only
        if ($request->boolean('active_only', true)) {
            $query->active();
        }

        // Search in question and answer
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('question', 'LIKE', "%{$search}%")
                    ->orWhere('answer', 'LIKE', "%{$search}%");
            });
        }

        // Order by
        $query->ordered();

        $meta = [];
        // Get categories list
        if ($request->boolean('with_categories', false)) {
            $meta['categories'] = Faq::active()
                ->distinct('category')
                ->pluck('category')
                ->filter()
                ->values();
        }

        $perPage = $request->get('per_page', 15);
        $faqs = $query->paginate($perPage);

        return self::collectionResponse(
            'FAQs retrieved successfully',
            FaqResource::collection($faqs),
            $meta
        );
    }

    /**
     * Store a newly created FAQ.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'category' => 'nullable|string|max:100',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return self::errorResponse('Validation failed', $validator->errors(), 422);
        }

        $faq = Faq::create($validator->validated());

        return self::successResponse(
            'FAQ created successfully',
            new FaqResource($faq),
            201
        );
    }

    /**
     * Display the specified FAQ.
     */
    public function show(Faq $faq): JsonResponse
    {
        return self::successResponse('FAQ retrieved successfully', new FaqResource($faq));
    }

    /**
     * Update the specified FAQ.
     */
    public function update(Request $request, Faq $faq): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'question' => 'sometimes|required|string|max:255',
            'answer' => 'sometimes|required|string',
            'category' => 'nullable|string|max:100',
            'order' => 'nullable|integer',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return self::errorResponse('Validation failed', $validator->errors(), 422);
        }

        $faq->update($validator->validated());

        return self::successResponse(
            'FAQ updated successfully',
            new FaqResource($faq)
        );
    }

    /**
     * Remove the specified FAQ.
     */
    public function destroy(Faq $faq): JsonResponse
    {
        $faq->delete();

        return self::successResponse('FAQ deleted successfully');
    }

    /**
     * Get all FAQ categories.
     */
    public function categories(): JsonResponse
    {
        $categories = Faq::active()
            ->distinct('category')
            ->pluck('category')
            ->filter()
            ->values();

        return self::successResponse('FAQ categories retrieved successfully', $categories);
    }

    /**
     * Get FAQs by category.
     */
    public function byCategory($category): JsonResponse
    {
        $faqs = Faq::active()
            ->category($category)
            ->ordered()
            ->get();

        return self::collectionResponse(
            'FAQs for category retrieved successfully',
            FaqResource::collection($faqs)
        );
    }
}
