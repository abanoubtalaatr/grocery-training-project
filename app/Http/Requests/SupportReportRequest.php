<?php

namespace App\Http\Requests;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;

class SupportReportRequest extends FormRequest
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
        return [
            'issue_type' => ['required', 'string', 'min:2', 'max:255'],
            'order_number' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $orderNumber = $this->input('order_number');
            
            if ($orderNumber !== null && trim($orderNumber) !== '') {
                $orderExists = Order::query()
                    ->where('user_id', $this->user()->id)
                    ->where('order_number', trim($orderNumber))
                    ->exists();

                if (!$orderExists) {
                    $validator->errors()->add('order_number', 'Order number not found on your account.');
                }
            }
        });
    }
}
