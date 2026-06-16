<?php

namespace App\Http\Requests\Meal;

use Illuminate\Foundation\Http\FormRequest;

class MealIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

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

            'sort_by' => [
                'nullable',
                'in:created_at,price,rating,title,sold_count,newest'
            ],

            'sort_order' => [
                'nullable',
                'in:asc,desc'
            ],
        ];
    }
}