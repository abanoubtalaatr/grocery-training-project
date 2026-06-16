<?php

namespace App\Http\Requests\Address;

use Illuminate\Foundation\Http\FormRequest;

class ListAddressesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->allFiles() !== []) {
                $validator->errors()->add('files', 'Remove file attachments from the request.');
            }
        });
    }
}
