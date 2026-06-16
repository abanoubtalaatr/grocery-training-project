<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ChatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    protected function prepareForValidation(): void
    {
   
        if ($this->filled('message') && !$this->filled('question')) {
            $this->merge(['question' => $this->input('message')]);
        }
    }

    public function rules(): array
    {
        return [
            'question' => [
                'required', 'string', 'max:1000',
                function ($attribute, $value, $fail) {
                    if ($this->hasFile($attribute)) {
                        $fail('Send the question as plain text, not as a file upload.');
                    }
                    if (is_array($value)) {
                        $fail('Send a single question text only.');
                    }
                    if (trim((string) $value) === '') {
                        $fail('A non-empty question is required.');
                    }
                },
            ],
            'conversation_id' => ['nullable', 'uuid'],
            'session_id' => ['nullable', 'uuid'],
            'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'locale' => ['nullable', 'string', 'in:ar,en'],
        ];
    }

    public function getConversationId(): ?string
    {
        return $this->validated('conversation_id') ?? $this->validated('session_id');
    }

    public function toActionData(): array
{
    return [
        'question' => $this->validated('question'),
        'conversation_id' => $this->getConversationId(),
        'locale' => $this->validated('locale'),
        'rating' => $this->validated('rating'),
    ];
}
}