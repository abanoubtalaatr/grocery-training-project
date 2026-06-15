<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $maxPerProduct = config('cart.max_quantity_per_product', 10);
        
        return [
            'meal_id'  => ['required', 'exists:meals,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:' . $maxPerProduct],
        ];
    }

    public function messages(): array
    {
        return [
            'quantity.max' => "Maximum " . config('cart.max_quantity_per_product', 10) . " units per product allowed.",
        ];
    }
}