<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChatbotRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->filled('message') && !$this->filled('question')) {
            $this->merge([
                'question' => $this->input('message'),
            ]);
        }
        
        // Trim question if it is a string
        if (is_string($this->input('question'))) {
            $this->merge([
                'question' => trim($this->input('question')),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'question' => ['required', 'string', 'max:1000'],
            'conversation_id' => ['nullable', 'uuid'],
            'session_id' => ['nullable', 'uuid'],
            'rating' => ['nullable', 'integer', 'between:1,5'],
            'locale' => ['nullable', 'string', 'in:ar,en'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'question.required' => 'A non-empty question is required.',
            'question.string' => 'The question must be a text value.',
        ];
    }
}
