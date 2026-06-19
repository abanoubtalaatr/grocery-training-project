<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrderStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => [
                'required',
                Rule::in([
                    'placed',
                    'processing',
                    'shipping',
                    'out_for_delivery',
                    'delivered',
                    'cancelled',
                ]),
            ],
        ];
    }
}