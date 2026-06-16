<?php

namespace App\Http\Requests\Offer;

use Illuminate\Foundation\Http\FormRequest;

class ValidateOfferRequest extends FormRequest
{
    public function authorize() { return true; }
    
    public function rules()
    {
        return [
            'code' => ['required', 'string'],
            'amount' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
