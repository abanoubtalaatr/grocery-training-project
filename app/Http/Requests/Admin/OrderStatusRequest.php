<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderStatusRequest extends FormRequest
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
            'status' => ['required', Rule::in([
                'awaiting_payment',
                'placed',
                'processing',
                'shipping',
                'out_for_delivery',
                'delivered',
                'cancelled',
            ])],
        ];
    }
}
