<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCartItemRequest extends FormRequest
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
        $maxPerProduct = config('cart.max_quantity_per_product', 10);

        return [
            'quantity' => ['required', 'integer', 'min:1', 'max:' . $maxPerProduct],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        $maxPerProduct = config('cart.max_quantity_per_product', 10);

        return [
            'quantity.max' => "Maximum {$maxPerProduct} units per product allowed.",
        ];
    }
}
