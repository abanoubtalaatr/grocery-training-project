<?php

namespace App\Actions\Api;

use App\Models\StaticPage;
use Illuminate\Http\Request;

class FindStaticPageBySlugAction
{
    public function execute(Request $request, string $slug): ?StaticPage
    {
        $page = StaticPage::bySlug($slug)->first();

        if (! $page) {
            return null;
        }

        if (! $page->is_published && (! $request->user() || ! $request->user()->is_admin)) {
            return null;
        }

        return $page;
    }
}
