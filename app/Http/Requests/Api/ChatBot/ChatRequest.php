<?php

namespace App\Http\Requests\Api\ChatBot;

use Illuminate\Foundation\Http\FormRequest;

class ChatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (
            $this->filled('message')
            && ! $this->filled('question')
        ) {
            $this->merge([
                'question' => $this->input('message'),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'question' => [
                'required',
                'string',
                'max:1000',
            ],

            'conversation_id' => [
                'nullable',
                'uuid',
            ],

            'session_id' => [
                'nullable',
                'uuid',
            ],

            'rating' => [
                'nullable',
                'integer',
                'min:1',
                'max:5',
            ],

            'locale' => [
                'nullable',
                'in:ar,en',
            ],
        ];
    }
}