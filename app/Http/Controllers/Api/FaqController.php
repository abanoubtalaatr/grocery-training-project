<?php

namespace App\Http\Controllers\Api;

use App\Actions\Api\CreateFaqAction;
use App\Actions\Api\DeleteFaqAction;
use App\Actions\Api\GetFaqsByCategoryAction;
use App\Actions\Api\ListFaqsAction;
use App\Actions\Api\UpdateFaqAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreFaqRequest;
use App\Http\Requests\Api\UpdateFaqRequest;
use App\Models\Faq;
use App\Http\Resources\FaqResource;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the FAQs.
     */
    public function index(Request $request, ListFaqsAction $action): JsonResponse
    {
        return $this->jsonResponse($action->execute($request));
    }

    /**
     * Store a newly created FAQ.
     */
    public function store(StoreFaqRequest $request, CreateFaqAction $action): JsonResponse
    {
        return $this->jsonResponse([
            'message' => 'FAQ created successfully',
            'data' => new FaqResource($action->execute($request->validated())),
        ], 201);
    }

    /**
     * Display the specified FAQ.
     */
    public function show(Faq $faq)
    {
        return new FaqResource($faq);
    }

    /**
     * Update the specified FAQ.
     */
    public function update(UpdateFaqRequest $request, Faq $faq, UpdateFaqAction $action): JsonResponse
    {
        return $this->jsonResponse([
            'message' => 'FAQ updated successfully',
            'data' => new FaqResource($action->execute($faq, $request->validated())),
        ]);
    }

    /**
     * Remove the specified FAQ.
     */
    public function destroy(Faq $faq, DeleteFaqAction $action): JsonResponse
    {
        $action->execute($faq);

        return $this->messageResponse('FAQ deleted successfully');
    }

    /**
     * Get all FAQ categories.
     */
    public function categories(ListFaqsAction $action): JsonResponse
    {
        return $this->dataResponse($action->categories());
    }

    /**
     * Get FAQs by category.
     */
    public function byCategory($category, GetFaqsByCategoryAction $action)
    {
        return FaqResource::collection($action->execute($category));
    }
}
