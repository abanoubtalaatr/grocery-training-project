<?php

namespace App\Http\Controllers\Admin;

use App\Models\Faq;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Admin\FaqService;
use App\Http\Requests\Admin\StoreFaqRequest;
use App\Http\Requests\Admin\UpdateFaqRequest;

class FaqController extends Controller
{
    public function __construct(
        private FaqService $faqService
    ) {}

    public function index(Request $request)
    {
        $faqs = $this->faqService
            ->paginate($request);

        return view(
            'admin.faqs.index',
            compact('faqs')
        );
    }

    public function create()
    {
        return view(
            'admin.faqs.create'
        );
    }

    public function store(
        StoreFaqRequest $request
    ) {
        $this->faqService->store(
            $request->validated()
        );

        return redirect()
            ->route('admin.faqs.index')
            ->with(
                'success',
                'FAQ created successfully.'
            );
    }

    public function edit(
        Faq $faq
    ) {
        return view(
            'admin.faqs.edit',
            compact('faq')
        );
    }

    public function update(
        UpdateFaqRequest $request,
        Faq $faq
    ) {
        $this->faqService->update(
            $faq,
            $request->validated()
        );

        return redirect()
            ->route('admin.faqs.index')
            ->with(
                'success',
                'FAQ updated successfully.'
            );
    }

    public function destroy(
        Faq $faq
    ) {
        $this->faqService->delete(
            $faq
        );

        return back()
            ->with(
                'success',
                'FAQ deleted successfully.'
            );
    }
}