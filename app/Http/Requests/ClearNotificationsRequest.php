<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClearNotificationsRequest extends FormRequest
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
            'type' => ['sometimes', 'string', 'in:read,unread,all'],
            'confirmation' => ['required', 'boolean', 'accepted'],
        ];
    }
}
