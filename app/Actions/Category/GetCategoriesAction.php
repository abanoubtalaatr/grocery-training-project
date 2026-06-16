<?php

namespace App\Actions\Category;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Category;

class GetCategoriesAction
{
    public function execute(): Collection
    {
        return Category::active()
            ->ordered()
            ->withCount('meals')
            ->get();
    }
}
