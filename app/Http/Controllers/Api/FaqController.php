<?php

namespace App\Http\Controllers\Api;

use App\Http\Actions\Api\Faq\GetFaqsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Faq\FaqIndexRequest;
use App\Http\Requests\Faq\StoreFaqRequest;
use App\Http\Requests\Faq\UpdateFaqRequest;
use App\Http\Resources\FaqCollection;
use App\Http\Resources\FaqResource;
use App\Models\Faq;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class FaqController extends Controller
{
    use ApiResponse;

    public function index(
        FaqIndexRequest $request,
        GetFaqsAction $action
    ): JsonResponse {

        $result = $action->execute(
            $request->validated()
        );

        return $this->successResponse(
            'FAQs retrieved successfully',
            [
                'faqs' => new FaqCollection(
                    $result['faqs']
                ),

                'categories'
                    => $result['categories'],
            ]
        );
    }

    public function store(
        StoreFaqRequest $request
    ): JsonResponse {

        $faq = Faq::create(
            $request->validated()
        );

        return $this->successResponse(
            'FAQ created successfully',
            new FaqResource($faq),
            201
        );
    }

    public function show(
        Faq $faq
    ): FaqResource {

        return new FaqResource($faq);
    }

    public function update(
        UpdateFaqRequest $request,
        Faq $faq
    ): JsonResponse {

        $faq->update(
            $request->validated()
        );

        return $this->successResponse(
            'FAQ updated successfully',
            new FaqResource($faq)
        );
    }

    public function destroy(
        Faq $faq
    ): JsonResponse {

        $faq->delete();

        return $this->successResponse(
            'FAQ deleted successfully'
        );
    }

    public function categories(): JsonResponse
    {
        $categories = Faq::active()
            ->distinct()
            ->pluck('category')
            ->filter()
            ->values();

        return $this->successResponse(
            'Categories retrieved successfully',
            $categories
        );
    }

    public function byCategory(
        string $category
    ): JsonResponse {

        $faqs = Faq::active()
            ->category($category)
            ->ordered()
            ->get();

        return $this->successResponse(
            'FAQs retrieved successfully',
            FaqResource::collection($faqs)
        );
    }
}