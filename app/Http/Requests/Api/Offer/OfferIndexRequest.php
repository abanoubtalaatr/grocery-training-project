<?php

namespace App\Http\Requests\Api\Offer;

// use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;


class OfferIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['nullable', 'string'],

            'min_purchase' => ['nullable', 'numeric'],

            'featured' => ['nullable', 'boolean'],

            'search' => ['nullable', 'string'],

            'order_by' => [
                'nullable',
                'in:created_at,title,start_date,end_date'
            ],

            'order_direction' => [
                'nullable',
                'in:asc,desc'
            ],

            'per_page' => [
                'nullable',
                'integer',
                'min:1',
                'max:100'
            ],
        ];
    }
}