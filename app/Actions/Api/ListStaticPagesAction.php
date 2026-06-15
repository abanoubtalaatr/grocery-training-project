<?php

namespace App\Actions\Api;

use App\Http\Resources\StaticPageCollection;
use App\Models\StaticPage;
use Illuminate\Http\Request;

class ListStaticPagesAction
{
    public function execute(Request $request): StaticPageCollection
    {
        $query = StaticPage::query();

        if (! $request->user() || ! $request->user()->is_admin) {
            $query->published();
        }

        if ($request->has('published')) {
            $query->where('is_published', $request->boolean('published'));
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('content', 'LIKE', "%{$search}%");
            });
        }

        $query->ordered();

        return new StaticPageCollection($query->paginate($request->get('per_page', 20)));
    }
}
