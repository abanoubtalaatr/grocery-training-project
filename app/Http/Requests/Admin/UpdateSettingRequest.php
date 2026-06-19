<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'site_name' => ['nullable', 'string'],
            'site_description' => ['nullable', 'string'],

            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'string'],

            'support_email' => ['nullable', 'email'],
            'support_phone' => ['nullable', 'string'],

            'address' => ['nullable', 'string'],
            'store_address' => ['nullable', 'string'],

            'facebook' => ['nullable', 'string'],
            'instagram' => ['nullable', 'string'],
            'linkedin' => ['nullable', 'string'],
            'twitter' => ['nullable', 'string'],
            'whatsapp' => ['nullable', 'string'],
            'tiktok' => ['nullable', 'string'],
            'snapchat' => ['nullable', 'string'],
            'youtube' => ['nullable', 'string'],

            'store_status' => [
                'nullable',
                'in:open,closed,maintenance'
            ],

            'store_hours' => ['nullable', 'string'],

            'tax_rate' => ['nullable', 'numeric'],
            'shipping_fee' => ['nullable', 'numeric'],
            'free_shipping_min_order' => ['nullable', 'numeric'],

            'shipping_note' => ['nullable', 'string'],

            'currency_code' => ['nullable', 'string'],
            'currency_symbol' => ['nullable', 'string'],

            'locale' => ['nullable', 'string'],
            'timezone' => ['nullable', 'string'],

            'payment_methods' => ['nullable', 'array'],
            'payment_methods.*' => ['string'],
        ];
    }
    
    protected function prepareForValidation(): void
    {
        $this->merge([
            'maintenance_mode' => $this->boolean('maintenance_mode'),
        ]);
    }
}