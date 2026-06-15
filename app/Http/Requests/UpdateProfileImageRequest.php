<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileImageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Pre-validate to ensure only a single image file is present.
     */
    protected function prepareForValidation(): void
    {
        if (count($this->allFiles()) > 1 || is_array($this->file('image'))) {
            abort(response()->json([
                'success' => false,
                'message' => 'Only one profile image is allowed',
                'errors' => ['image' => ['Only one profile image is allowed']],
            ], 422));
        }
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ];
    }
}
