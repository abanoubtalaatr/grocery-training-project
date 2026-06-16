<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class FaqRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $isStore = $this->isMethod('post');
        return [
            'question'  => [$isStore ? 'required' : 'sometimes', 'string', 'max:255'],
            'answer'    => [$isStore ? 'required' : 'sometimes', 'string'],
            'category'  => ['nullable', 'string', 'max:100'],
            'order'     => ['nullable', 'integer'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}