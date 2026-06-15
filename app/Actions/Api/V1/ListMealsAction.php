<?php

namespace App\Actions\Api\V1;

use App\Models\Meal;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ListMealsAction
{
    public function execute(Request $request): Collection
    {
        return Meal::query()->filter($request)->get();
    }
}
