<?php

namespace App\Http\Requests\Admin\Category;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $categoryId = $this->route('category')->id;

        return [
            'name'        => 'required|string|min:3|max:255|unique:categories,name,' . $categoryId,
            'slug'        => 'nullable|string|min:3|max:255|unique:categories,slug,' . $categoryId,
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active'   => 'boolean',
            'sort_order'  => 'nullable|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('The category name is required.'),
            'name.unique'   => __('A category with this name already exists.'),
            'image.image'   => __('The file must be an image.'),
            'image.max'     => __('The image size must not exceed 2MB.'),
        ];
    }
}