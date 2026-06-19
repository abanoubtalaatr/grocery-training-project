<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Rules\UsernameMustContainLetter;
use App\Support\EgyptianPhoneRules;
use App\Support\EmailValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
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
            'username' => [
                'required',
                'string',
                'max:' . User::USERNAME_MAX_LENGTH,
                Rule::unique('users')->ignore($userId),
                'not_regex:/\s/u',
                'alpha_dash',
                new UsernameMustContainLetter
            ],
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'gender' => ['nullable', 'string', 'max:20', Rule::in(['male', 'female', 'other', 'prefer_not_to_say'])],
            'birthday' => ['nullable', 'date', 'before:today'],
            'email' => [
                'required',
                ...EmailValidation::formatRules(),
                'max:255',
                Rule::unique('users')->ignore($userId)
            ],
            'phone' => [
                'required',
                'string',
                EgyptianPhoneRules::internationalPrefixRule(),
                'min:11',
                'max:13',
                EgyptianPhoneRules::mobileRule(),
                Rule::unique('users')->ignore($userId)
            ],
            'country_code' => ['required', 'string', 'max:5', 'regex:/^\+\d{1,4}$/'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'username.max' => 'Maximum ' . User::USERNAME_MAX_LENGTH . ' characters allowed.',
            'username.not_regex' => 'Username must not contain spaces.',
            'username.alpha_dash' => 'Username may only contain letters, numbers, dashes and underscores.',
            'email.not_regex' => EmailValidation::trailingHyphenDotBeforeAtMessage(),
            'email.regex' => EmailValidation::domainStructureMessage(),
            'email.max' => 'The email address may not exceed 255 characters.',
            'phone.not_regex' => EgyptianPhoneRules::foreignPrefixMessage(),
            'phone.regex' => EgyptianPhoneRules::invalidMessage(),
            'phone.min' => EgyptianPhoneRules::lengthMessage(),
            'phone.max' => EgyptianPhoneRules::lengthMessage(),
        ];
    }
}
