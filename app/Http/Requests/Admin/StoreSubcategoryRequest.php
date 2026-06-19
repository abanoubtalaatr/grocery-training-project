<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubcategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }

    public function rules(): array
    {
        return [
            'category_id' => [
                'required',
                'exists:categories,id',
            ],

            'name' => [
                'required',
                'max:255',
            ],

            'description' => [
                'nullable',
            ],

            'is_active' => [
                'boolean',
            ],

            'order' => [
                'nullable',
                'integer',
            ],
        ];
    }
}