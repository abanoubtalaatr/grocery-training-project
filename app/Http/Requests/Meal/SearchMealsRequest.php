<?php

namespace App\Http\Requests\Meal;

use Illuminate\Foundation\Http\FormRequest;

class SearchMealsRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string'],
            'category_id' => ['nullable', 'integer'],
            'subcategory_id' => ['nullable', 'integer'],
            'featured' => ['nullable', 'boolean'],
            'in_stock' => ['nullable', 'boolean'],
            'min_price' => ['nullable', 'numeric'],
            'max_price' => ['nullable', 'numeric'],
            'min_rating' => ['nullable', 'numeric'],
            'brand' => ['nullable', 'string'],
            'sort_by' => ['nullable', 'string', 'in:created_at,price,rating,title,sold_count,newest'],
            'sort_order' => ['nullable', 'string', 'in:asc,desc,ASC,DESC'],
        ];
    }
    
    public function getFilters(): array
    {
        $filters = $this->validated();
        
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = strtolower($filters['sort_order'] ?? 'desc') === 'asc' ? 'asc' : 'desc';
        
        if ($sortBy === 'newest') {
            $sortBy = 'created_at';
            $sortOrder = 'desc';
        }
        
        $filters['sort_by'] = $sortBy;
        $filters['sort_order'] = $sortOrder;
        
        return $filters;
    }
}
