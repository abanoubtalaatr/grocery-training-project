<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
        $userId = $this->route('user')?->id;
        $isCreate = $userId === null;

        return [
            'firstname' => ['nullable', 'string', 'max:255'],
            'lastname' => ['nullable', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:' . User::USERNAME_MAX_LENGTH],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($userId)],
            'phone' => ['nullable', 'string', 'max:30', Rule::unique('users', 'phone')->ignore($userId)],
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'birthday' => ['nullable', 'date'],
            'password' => [$isCreate ? 'required' : 'nullable', 'string', 'min:8'],
            'is_active' => ['required', 'boolean'],
            'is_admin' => ['required', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'is_admin' => $this->boolean('is_admin'),
        ]);
    }
}
