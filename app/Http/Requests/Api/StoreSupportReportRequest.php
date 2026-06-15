<?php

namespace App\Http\Requests\Api;

use App\Models\Order;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreSupportReportRequest extends FormRequest
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
            'issue_type' => ['required', 'string', 'min:2', 'max:255'],
            'order_number' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $orderNumber = $this->filled('order_number')
                ? trim((string) $this->input('order_number'))
                : null;

            if ($orderNumber === null || $orderNumber === '') {
                return;
            }

            $orderExists = Order::query()
                ->where('user_id', $this->user()->id)
                ->where('order_number', $orderNumber)
                ->exists();

            if (! $orderExists) {
                $validator->errors()->add('order_number', 'Order number not found on your account.');
            }
        });
    }

    /**
     * @return array{issue_type: string, order_number: string|null, message: string}
     */
    public function supportData(): array
    {
        $orderNumber = $this->filled('order_number')
            ? trim((string) $this->input('order_number'))
            : null;

        return [
            'issue_type' => trim((string) $this->input('issue_type')),
            'order_number' => $orderNumber !== '' ? $orderNumber : null,
            'message' => trim((string) $this->input('message')),
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors(),
        ], 422));
    }
}
