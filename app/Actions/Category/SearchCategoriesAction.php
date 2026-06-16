<?php

namespace App\Actions\Category;

use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;

class SearchCategoriesAction
{
    public function __construct(private readonly CategoryRepository $categoryRepository) {}

    public function __invoke(Request $request)
    {
        return $this->categoryRepository->searchActiveWithMealsCount($request);
    }
}
