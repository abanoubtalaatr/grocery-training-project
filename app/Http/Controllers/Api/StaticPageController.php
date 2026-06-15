<?php

namespace App\Http\Controllers\Api;

use App\Actions\Api\CreateStaticPageAction;
use App\Actions\Api\DeleteStaticPageAction;
use App\Actions\Api\FindStaticPageBySlugAction;
use App\Actions\Api\ListImportantStaticPagesAction;
use App\Actions\Api\ListStaticPagesAction;
use App\Actions\Api\UpdateStaticPageAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreStaticPageRequest;
use App\Http\Requests\Api\UpdateStaticPageRequest;
use App\Http\Resources\StaticPageResource;
use App\Models\StaticPage;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StaticPageController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of static pages.
     */
    public function index(Request $request, ListStaticPagesAction $action)
    {
        return $action->execute($request);
    }

    /**
     * Store a newly created static page.
     */
    public function store(StoreStaticPageRequest $request, CreateStaticPageAction $action): JsonResponse
    {
        return $this->jsonResponse([
            'message' => 'Page created successfully',
            'data' => new StaticPageResource($action->execute($request->validated())),
        ], 201);
    }

    /**
     * Display the specified static page by slug.
     */
    public function showBySlug(Request $request, string $slug, FindStaticPageBySlugAction $action)
    {
        $page = $action->execute($request, $slug);

        if (! $page) {
            return $this->messageResponse('Page not found', 404);
        }

        return new StaticPageResource($page);
    }

    /**
     * Display the specified static page by ID.
     */
    public function show(StaticPage $staticPage)
    {
        return new StaticPageResource($staticPage);
    }

    /**
     * Update the specified static page.
     */
    public function update(UpdateStaticPageRequest $request, StaticPage $staticPage, UpdateStaticPageAction $action): JsonResponse
    {
        return $this->jsonResponse([
            'message' => 'Page updated successfully',
            'data' => new StaticPageResource($action->execute($staticPage, $request->validated())),
        ]);
    }

    /**
     * Remove the specified static page.
     */
    public function destroy(StaticPage $staticPage, DeleteStaticPageAction $action): JsonResponse
    {
        $action->execute($staticPage);

        return $this->messageResponse('Page deleted successfully');
    }

    /**
     * Get important pages (for footer/menu).
     */
    public function importantPages(ListImportantStaticPagesAction $action): JsonResponse
    {
        return $this->dataResponse($action->execute());
    }
}
