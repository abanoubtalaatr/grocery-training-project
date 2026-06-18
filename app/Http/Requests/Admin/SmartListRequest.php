<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SmartListRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'image' => ['nullable', 'image', 'max:2048'],
            'notify_on_price_drop' => ['sometimes', 'boolean'],
            'notify_on_offers' => ['sometimes', 'boolean'],
            'meal_ids' => ['sometimes', 'array'],
            'meal_ids.*' => ['required', 'exists:meals,id'],
        ];
    }
}
