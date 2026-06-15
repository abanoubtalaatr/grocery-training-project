<?php

namespace App\Http\Requests;

use App\Support\EmailValidation;
use Illuminate\Foundation\Http\FormRequest;

class ContactSubmitRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', ...EmailValidation::formatRules(), 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:10', 'max:250'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'email.not_regex' => EmailValidation::trailingHyphenDotBeforeAtMessage(),
            'email.regex' => EmailValidation::domainStructureMessage(),
            'email.max' => 'The email address may not exceed 255 characters.',
        ];
    }
}
