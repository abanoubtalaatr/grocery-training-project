<?php

namespace App\Http\Requests\Admin\Subcategory;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubcategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255|unique:subcategories,name',
            'slug'        => 'nullable|string|max:255|unique:subcategories,slug',
            'description' => 'nullable|string',
            'image_url'   => 'nullable|url|max:2048',
            'is_active'   => 'boolean',
            'order'       => 'nullable|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => __('The parent category is required.'),
            'category_id.exists'   => __('The selected category does not exist.'),
            'name.required'        => __('The subcategory name is required.'),
            'name.unique'          => __('A subcategory with this name already exists.'),
            'image_url.url'        => __('The image must be a valid URL.'),
        ];
    }
}
