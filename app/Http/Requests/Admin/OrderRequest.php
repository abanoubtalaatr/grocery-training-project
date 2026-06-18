<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'status' => 'required|string',
            'estimated_delivery_time' => 'nullable|date',
            'notes' => 'nullable|string',
            'delivery_speed' => 'nullable|string',
        ];
    }
}
