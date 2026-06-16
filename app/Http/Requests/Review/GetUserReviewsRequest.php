<?php

namespace App\Http\Requests\Review;

use Illuminate\Foundation\Http\FormRequest;

class GetUserReviewsRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'user_id' => ['nullable', 'integer'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
