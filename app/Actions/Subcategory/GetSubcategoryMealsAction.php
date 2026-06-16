<?php

namespace App\Actions\Subcategory;

use App\Repositories\SubcategoryRepository;
use Illuminate\Http\Request;

class GetSubcategoryMealsAction
{
    public function __construct(private readonly SubcategoryRepository $subcategoryRepository) {}

    public function __invoke(string $id, Request $request): array
    {
        $subcategory = $this->subcategoryRepository->findById($id);

        $query = $subcategory->meals()->with('category')->available();

        if ($request->has('featured')) {
            $request->boolean('featured') ? $query->featured() : $query->where('is_featured', false);
        }

        if ($request->has('in_stock')) {
            $request->boolean('in_stock') ? $query->inStock() : $query->outOfStock();
        }

        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = strtolower($request->input('sort_order', 'desc')) === 'asc' ? 'asc' : 'desc';
        if ($sortBy === 'newest') {
            $sortBy = 'created_at';
            $sortOrder = 'desc';
        }
        $allowedSortFields = ['created_at', 'price', 'rating', 'title', 'sold_count'];
        if (in_array($sortBy, $allowedSortFields)) {
            if ($sortBy === 'price') {
                $query->orderByRaw('COALESCE(discount_price, price) ' . $sortOrder);
            } else {
                $query->orderBy($sortBy, $sortOrder);
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $perPage = min(max((int) $request->input('per_page', 15), 1), 50);
        $paginator = $query->paginate($perPage)->withQueryString();

        $meals = $paginator->getCollection()->map(function ($meal) {
            return [
                'id' => $meal->id,
                'title' => $meal->title,
                'slug' => $meal->slug,
                'description' => $meal->description,
                'image_url' => $meal->image_url,
                'offer_title' => $meal->offer_title,
                ...$meal->getApiPriceAttributes(),
                'rating' => (float) $meal->rating,
                'rating_count' => (int) $meal->rating_count,
                'has_offer' => $meal->hasOffer(),
                'is_featured' => $meal->is_featured,
                'in_stock' => $meal->isInStock(),
                'features' => $meal->features,
            ];
        });

        $total = $paginator->total();

        return [
            'total' => $total,
            'data' => [
                'subcategory' => [
                    'id' => $subcategory->id,
                    'name' => $subcategory->name,
                    'slug' => $subcategory->slug,
                ],
                'meals' => $meals->values()->all(),
                'pagination' => [
                    'current_page' => $paginator->currentPage(),
                    'last_page' => $paginator->lastPage(),
                    'per_page' => $paginator->perPage(),
                    'total' => $total,
                    'from' => $paginator->firstItem(),
                    'to' => $paginator->lastItem(),
                ],
            ]
        ];
    }
}
