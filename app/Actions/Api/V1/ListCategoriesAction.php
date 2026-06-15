<?php

namespace App\Actions\Api\V1;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ListCategoriesAction
{
    public function execute(Request $request): Collection
    {
        return Category::query()->filter($request)->get();
    }
}
