<?php

namespace App\Http\Requests\Review;

use Illuminate\Foundation\Http\FormRequest;

class GetReviewsRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'meal_id' => ['nullable', 'integer'],
            'user_id' => ['nullable', 'integer'],
            'rating' => ['nullable', 'integer', 'between:1,5'],
            'approved_only' => ['nullable', 'boolean'],
            'min_rating' => ['nullable', 'integer', 'between:1,5'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
