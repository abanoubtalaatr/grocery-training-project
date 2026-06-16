<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class FaqUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            
          'question' => 'sometimes|required|string|max:255',
            'answer' => 'sometimes|required|string',
            'category' => 'nullable|string|max:100',
            'order' => 'nullable|integer',
            'is_active' => 'sometimes|boolean',
        ];
    }
}
