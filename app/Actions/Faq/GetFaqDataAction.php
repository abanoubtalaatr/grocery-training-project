<?php


namespace App\Actions\Faq;

use App\Models\Faq;

class GetFaqDataAction
{
    public function execute(array $filters): array
    {
        $query = Faq::query()
            ->when($filters['category'] ?? null, fn($q, $cat) => $q->where('category', $cat))
            ->when(filter_var($filters['active_only'] ?? true, FILTER_VALIDATE_BOOLEAN), fn($q) => $q->active())
            ->when($filters['search'] ?? null, function ($q, $search) {
                $q->where(fn($sub) => $sub->where('question', 'LIKE', "%{$search}%")->orWhere('answer', 'LIKE', "%{$search}%"));
            })
            ->ordered();

        $data = ['faqs' => $query->paginate($filters['per_page'] ?? 15)];

        if (filter_var($filters['with_categories'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
            $data['categories'] = $this->getCategories();
        }

        return $data;
    }

    public function getCategories(): \Illuminate\Support\Collection
    {
        return Faq::active()->distinct('category')->pluck('category')->filter()->values();
    }
}