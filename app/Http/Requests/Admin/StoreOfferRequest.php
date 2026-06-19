<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreOfferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'is_featured' => $this->boolean('is_featured'),
        ]);
    }

    public function rules(): array
    {
        return [

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'code' => [
                'required',
                'string',
                'max:255',
                'unique:offers,code',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'type' => [
                'required',
                'in:percentage,fixed',
            ],

            'discount_value' => [
                'required',
                'numeric',
                'min:0',
            ],

            'minimum_purchase' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'start_date' => [
                'required',
                'date',
            ],

            'end_date' => [
                'required',
                'date',
                'after_or_equal:start_date',
            ],

            'usage_limit' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'is_active' => [
                'boolean',
            ],

            'is_featured' => [
                'boolean',
            ],
        ];
    }
}