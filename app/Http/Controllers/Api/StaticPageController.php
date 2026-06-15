<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaticPageRequest;
use App\Http\Requests\UpdateStaticPageRequest;
use App\Http\Resources\StaticPageResource;
use App\Http\Resources\StaticPageCollection;
use App\Models\StaticPage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StaticPageController extends Controller
{
    /**
     * Display a listing of static pages.
     */
    public function index(Request $request): StaticPageCollection
    {
        $query = StaticPage::query();

        // Show only published pages for non-admin users
        if (!$request->user() || !$request->user()->is_admin) {
            $query->published();
        }

        // Filter by published status
        if ($request->has('published')) {
            $query->where('is_published', $request->boolean('published'));
        }

        // Search in title and content
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('content', 'LIKE', "%{$search}%");
            });
        }

        $query->ordered();

        $perPage = $request->get('per_page', 20);
        $pages = $query->paginate($perPage);

        return new StaticPageCollection($pages);
    }

    /**
     * Store a newly created static page.
     */
    public function store(StoreStaticPageRequest $request): JsonResponse
    {
        $page = StaticPage::create($request->validated());

        return response()->json([
            'message' => 'Page created successfully',
            'data' => new StaticPageResource($page)
        ], 201);
    }

    /**
     * Display the specified static page by slug.
     */
    public function showBySlug(Request $request, StaticPage $staticPage): StaticPageResource
    {
        // Check if page is published for non-admin users
        if (!$staticPage->is_published && (!$request->user() || !$request->user()->is_admin)) {
            abort(404, 'Page not found');
        }

        return new StaticPageResource($staticPage);
    }

    /**
     * Display the specified static page by ID.
     */
    public function show(StaticPage $staticPage): StaticPageResource
    {
        return new StaticPageResource($staticPage);
    }

    /**
     * Update the specified static page.
     */
    public function update(UpdateStaticPageRequest $request, StaticPage $staticPage): JsonResponse
    {
        $staticPage->update($request->validated());

        return response()->json([
            'message' => 'Page updated successfully',
            'data' => new StaticPageResource($staticPage)
        ]);
    }

    /**
     * Remove the specified static page.
     */
    public function destroy(StaticPage $staticPage): JsonResponse
    {
        $staticPage->delete();

        return response()->json([
            'message' => 'Page deleted successfully'
        ]);
    }

    /**
     * Get important pages (for footer/menu).
     */
    public function importantPages(): JsonResponse
    {
        $pages = StaticPage::published()
            ->whereIn('slug', ['terms-and-conditions', 'policies', 'about-us', 'contact-us'])
            ->ordered()
            ->get(['slug', 'title']);

        return response()->json([
            'data' => $pages
        ]);
    }
}