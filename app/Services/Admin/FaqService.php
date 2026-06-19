<?php

namespace App\Services\Admin;

use App\Models\Faq;
use Illuminate\Http\Request;

class FaqService
{
    public function paginate(
        Request $request,
        int $perPage = 10
    )
    {
        return Faq::query()
            ->filter($request)
            ->ordered()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function store(array $data): Faq
    {
        return Faq::create($data);
    }

    public function update(
        Faq $faq,
        array $data
    ): bool {
        return $faq->update($data);
    }

    public function delete(
        Faq $faq
    ): bool {
        return $faq->delete();
    }
}