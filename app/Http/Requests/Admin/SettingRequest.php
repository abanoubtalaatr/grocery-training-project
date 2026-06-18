<?php

namespace App\Http\Requests\Admin;

use App\Models\Setting;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'site_name' => ['nullable', 'string', 'max:255'],
            'site_description' => ['nullable', 'string', 'max:1000'],
            'copyright_text' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'string', 'max:2048'],
            'favicon' => ['nullable', 'string', 'max:2048'],

            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'support_email' => ['nullable', 'email', 'max:255'],
            'support_phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:500'],
            'store_address' => ['nullable', 'string', 'max:500'],

            'facebook' => ['nullable', 'string', 'max:255'],
            'instagram' => ['nullable', 'string', 'max:255'],
            'twitter' => ['nullable', 'string', 'max:255'],
            'linkedin' => ['nullable', 'string', 'max:255'],
            'youtube' => ['nullable', 'string', 'max:255'],
            'tiktok' => ['nullable', 'string', 'max:255'],
            'snapchat' => ['nullable', 'string', 'max:255'],
            'whatsapp' => ['nullable', 'string', 'max:255'],

            'store_status' => ['required', Rule::in([
                Setting::STORE_STATUS_OPEN,
                Setting::STORE_STATUS_CLOSED,
                Setting::STORE_STATUS_MAINTENANCE,
            ])],
            'maintenance_mode' => ['required', 'boolean'],
            'store_hours' => ['nullable', 'string', 'max:255'],
            'shipping_note' => ['nullable', 'string', 'max:500'],

            'currency_code' => ['nullable', 'string', 'max:10'],
            'currency_symbol' => ['nullable', 'string', 'max:10'],
            'tax_rate' => ['nullable', 'numeric', 'min:0'],
            'shipping_fee' => ['nullable', 'numeric', 'min:0'],
            'free_shipping_min_order' => ['nullable', 'numeric', 'min:0'],
            'locale' => ['nullable', 'string', 'max:10'],
            'timezone' => ['nullable', 'string', 'max:64'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'maintenance_mode' => $this->boolean('maintenance_mode'),
        ]);
    }
}
