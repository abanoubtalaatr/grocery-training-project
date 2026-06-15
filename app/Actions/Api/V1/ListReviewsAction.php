<?php

namespace App\Actions\Api\V1;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ListReviewsAction
{
    public function execute(Request $request): Collection
    {
        return Review::query()->filter($request)->get();
    }
}
