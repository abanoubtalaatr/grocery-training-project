<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StaticPageRequest extends FormRequest
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
        $pageId = $this->route('static_page')?->id;

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('static_pages', 'slug')->ignore($pageId)],
            'content' => ['nullable', 'string'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'array'],
            'meta_keywords.*' => ['string', 'max:50'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_published' => ['required', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $keywords = $this->input('meta_keywords');

        if (is_string($keywords)) {
            $keywords = collect(explode(',', $keywords))
                ->map(fn ($keyword) => trim($keyword))
                ->filter()
                ->values()
                ->all();
        }

        $this->merge([
            'slug' => $this->filled('slug') ? Str::slug($this->input('slug')) : Str::slug($this->input('title')),
            'meta_keywords' => $keywords,
            'is_published' => $this->boolean('is_published'),
        ]);
    }
}
