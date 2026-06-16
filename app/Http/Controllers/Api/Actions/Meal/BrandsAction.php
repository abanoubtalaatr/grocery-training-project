<?php

namespace App\Http\Controllers\Api\Actions\Meal;

use App\Models\Meal;

class BrandsAction
{
    public function handle()
    {
        return Meal::distinct()->pluck('brand');
    }
}
