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
        $user = $this->user() ?? \App\Models\User::first();
        $userId = $user ? $user->id : null;

        return [
            'issue_type' => ['required', 'string', 'min:2', 'max:255'],
            'order_number' => [
                'nullable',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($userId) {
                    if ($value && $userId) {
                        $orderExists = Order::query()
                            ->where('user_id', $userId)
                            ->where('order_number', trim($value))
                            ->exists();
                        if (!$orderExists) {
                            $fail('Order number not found on your account.');
                        }
                    }
                }
            ],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
        ];
    }
}
