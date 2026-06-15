<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNotificationPreferencesRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'order_updates' => ['sometimes', 'boolean'],
            'promotion_emails' => ['sometimes', 'boolean'],
            'nutrition_insights' => ['sometimes', 'boolean'],
            'price_alerts' => ['sometimes', 'boolean'],
        ];
    }
}
