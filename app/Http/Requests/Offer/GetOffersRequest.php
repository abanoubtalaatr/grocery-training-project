<?php

namespace App\Http\Requests\Offer;

use Illuminate\Foundation\Http\FormRequest;

class GetOffersRequest extends FormRequest
{
    public function authorize() { return true; }
    
    public function rules()
    {
        return [
            'type' => ['nullable', 'string'],
            'min_purchase' => ['nullable', 'numeric'],
            'featured' => ['nullable', 'boolean'],
            'search' => ['nullable', 'string'],
            'order_by' => ['nullable', 'string'],
            'order_direction' => ['nullable', 'string', 'in:asc,desc,ASC,DESC'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
