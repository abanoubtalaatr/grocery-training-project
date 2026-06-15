<?php

namespace App\Actions\Api;

use App\Models\Faq;
use Illuminate\Support\Collection;

class GetFaqsByCategoryAction
{
    public function execute(string $category): Collection
    {
        return Faq::active()
            ->category($category)
            ->ordered()
            ->get();
    }
}
