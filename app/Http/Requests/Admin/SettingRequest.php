<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string',

            // Contact
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:50',
            'support_email' => 'nullable|email',
            'support_phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'store_address' => 'nullable|string',

            // Media
            'logo' => 'nullable|image|max:2048',
            'favicon' => 'nullable|image|max:1024',

            // Social
            'facebook' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'instagram' => 'nullable|url',
            'twitter' => 'nullable|url',
            'whatsapp' => 'nullable|string|max:100',
            'tiktok' => 'nullable|url',
            'snapchat' => 'nullable|url',
            'youtube' => 'nullable|url',

            // Store / commerce
            'copyright_text' => 'nullable|string',
            'store_status' => 'nullable|in:open,closed,maintenance',
            'maintenance_mode' => 'nullable|boolean',
            'store_hours' => 'nullable|string',
            'currency_code' => 'nullable|string|max:10',
            'currency_symbol' => 'nullable|string|max:8',
            'tax_rate' => 'nullable|numeric',
            'payment_methods' => 'nullable|array',
            'payment_methods.*' => 'nullable|string|max:255',
            'shipping_note' => 'nullable|string',
            'shipping_fee' => 'nullable|numeric',
            'free_shipping_min_order' => 'nullable|numeric',

            // Localization
            'locale' => 'nullable|string|max:10',
            'timezone' => 'nullable|timezone',
        ];
    }
}
