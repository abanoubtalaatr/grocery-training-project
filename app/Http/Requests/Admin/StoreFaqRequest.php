<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreFaqRequest extends FormRequest
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

            'question' => [
                'required',
                'string',
                'max:1000',
            ],

            'answer' => [
                'required',
                'string',
            ],

            'category' => [
                'nullable',
                'string',
                'max:255',
            ],

            'order' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'is_active' => [
                'boolean',
            ],

        ];
    }
}