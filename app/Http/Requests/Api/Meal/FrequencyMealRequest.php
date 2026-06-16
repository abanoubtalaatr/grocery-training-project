<?php

namespace App\Http\Requests\Meal;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\FrequencyService;

class FrequencyMealRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'frequency_type' => [
                'nullable',
                'in:' . implode(',', FrequencyService::VALID_TYPES),
            ],

            'subcategory_id' => [
                'nullable',
                'integer',
            ],
        ];
    }
}