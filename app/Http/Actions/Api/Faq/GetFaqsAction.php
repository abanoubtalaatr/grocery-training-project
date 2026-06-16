<?php

namespace App\Http\Actions\Api\Faq;

use App\Models\Faq;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetFaqsAction
{
    public function execute(array $filters): array
    {
        $query = Faq::query();

        if (! empty($filters['category'])) {
            $query->where(
                'category',
                $filters['category']
            );
        }

        if (
            ($filters['active_only'] ?? true)
        ) {
            $query->active();
        }

        if (! empty($filters['search'])) {

            $search = $filters['search'];

            $query->where(function ($query) use ($search) {

                $query->where(
                    'question',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'answer',
                    'like',
                    "%{$search}%"
                );
            });
        }

        $query->ordered();

        $faqs = $query->paginate(
            $filters['per_page'] ?? 15
        );

        $categories = null;

        if (
            $filters['with_categories']
            ?? false
        ) {

            $categories = Faq::active()
                ->distinct()
                ->pluck('category')
                ->filter()
                ->values();
        }

        return [
            'faqs' => $faqs,
            'categories' => $categories,
        ];
    }
}