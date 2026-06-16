<?php

namespace App\Http\Requests\Api\Category;

use Illuminate\Foundation\Http\FormRequest;

class CategoryMealsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'featured' => ['nullable', 'boolean'],
            'in_stock' => ['nullable', 'boolean'],
            'subcategory_id' => ['nullable', 'integer'],

            'sort_by' => [
                'nullable',
                'in:created_at,price,rating,title,sold_count,newest'
            ],

            'sort_order' => [
                'nullable',
                'in:asc,desc'
            ],

            'per_page' => [
                'nullable',
                'integer',
                'min:1',
                'max:50'
            ],
        ];
    }
}