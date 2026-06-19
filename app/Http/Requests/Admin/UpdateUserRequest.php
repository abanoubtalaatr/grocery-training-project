<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => [
                'required',
                'string',
                'max:50',
                Rule::unique('users')->ignore($this->user),
            ],

            'firstname' => ['required', 'string', 'max:255'],

            'lastname' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($this->user),
            ],

            'password' => ['nullable', 'min:8'],

            'is_active' => ['required', 'boolean'],

            'is_admin' => ['required', 'boolean'],
        ];
    }
}