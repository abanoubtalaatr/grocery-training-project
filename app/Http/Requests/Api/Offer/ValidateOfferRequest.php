<?php

namespace App\Http\Requests\Api\Offer;

// use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ValidateOfferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => [
                'required',
                'string',
            ],

            'amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],
        ];
    }
}