<?php

namespace App\Http\Controllers\Api\Actions\Subcategory;

use App\Models\Subcategory;
use Illuminate\Http\Request;

class SubcategoryIndexAction
{
    public function handle(Request $request)
    {
        $query = Subcategory::with('category')->active();

        if ($request->has('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        return $query->inRandomOrder()->get();
    }
}
