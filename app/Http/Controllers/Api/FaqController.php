<?php

namespace App\Http\Controllers\Api;

use App\Actions\Faq\GetFaqDataAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\FaqRequest;
use App\Http\Resources\{FaqResource, FaqCollection};
use App\Models\Faq;
use App\Traits\ApiResponse;
use Illuminate\Http\{JsonResponse, Request};

class FaqController extends Controller
{
    use ApiResponse;

    public function index(Request $request, GetFaqDataAction $action): JsonResponse
    {
        $result = $action->execute($request->all());

        $data = collect(['faqs' => new FaqCollection($result['faqs'])])
            ->when(isset($result['categories']), fn($c) => $c->put('categories', $result['categories']))
            ->toArray();

        return $this->successResponse($data, 'FAQs retrieved successfully.');
    }

    public function store(FaqRequest $request): JsonResponse
    {
        return $this->successResponse(new FaqResource(Faq::create($request->validated())), 'FAQ created.', 201);
    }

    public function show(Faq $faq): FaqResource
    {
        return new FaqResource($faq);
    }

    public function update(FaqRequest $request, Faq $faq): JsonResponse
    {
        return $this->successResponse(new FaqResource(tap($faq)->update($request->validated())), 'FAQ updated.');
    }

    public function destroy(Faq $faq): JsonResponse
    {
        return $this->successResponse($faq->delete(), 'FAQ deleted.');
    }

    public function categories(GetFaqDataAction $action): JsonResponse
    {
        return $this->successResponse($action->getCategories(), 'Categories retrieved.');
    }

    public function byCategory($category): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return FaqResource::collection(Faq::active()->where('category', $category)->ordered()->get());
    }
}