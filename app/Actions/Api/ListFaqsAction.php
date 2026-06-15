<?php

namespace App\Actions\Api;

use App\Http\Resources\FaqCollection;
use App\Models\Faq;
use Illuminate\Http\Request;

class ListFaqsAction
{
    /**
     * @return array<string, mixed>
     */
    public function execute(Request $request): array
    {
        $query = Faq::query();

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->boolean('active_only', true)) {
            $query->active();
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('question', 'LIKE', "%{$search}%")
                    ->orWhere('answer', 'LIKE', "%{$search}%");
            });
        }

        $query->ordered();

        $response = [
            'data' => new FaqCollection($query->paginate($request->get('per_page', 15))),
        ];

        if ($request->boolean('with_categories', false)) {
            $response['categories'] = $this->categories();
        }

        return $response;
    }

    public function categories(): mixed
    {
        return Faq::active()
            ->distinct('category')
            ->pluck('category')
            ->filter()
            ->values();
    }
}
