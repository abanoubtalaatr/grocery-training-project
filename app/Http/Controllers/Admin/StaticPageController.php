<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StaticPageRequest;
use App\Models\StaticPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StaticPageController extends Controller
{
    public function index(Request $request): View
    {
        $pages = StaticPage::query()
            ->when($request->string('search')->toString(), fn ($query, $search) => $query->where('title', 'like', "%{$search}%"))
            ->ordered()
            ->paginate(15)
            ->withQueryString();

        return view('admin.static-pages.index', compact('pages'));
    }

    public function create(): View
    {
        return view('admin.static-pages.create', ['page' => new StaticPage()]);
    }

    public function store(StaticPageRequest $request): RedirectResponse
    {
        StaticPage::create($request->validated());

        return redirect()->route('admin.static-pages.index')->with('success', 'Page created successfully.');
    }

    public function show(StaticPage $staticPage): View
    {
        return view('admin.static-pages.show', ['page' => $staticPage]);
    }

    public function edit(StaticPage $staticPage): View
    {
        return view('admin.static-pages.edit', ['page' => $staticPage]);
    }

    public function update(StaticPageRequest $request, StaticPage $staticPage): RedirectResponse
    {
        $staticPage->update($request->validated());

        return redirect()->route('admin.static-pages.index')->with('success', 'Page updated successfully.');
    }

    public function destroy(StaticPage $staticPage): RedirectResponse
    {
        $staticPage->delete();

        return redirect()->route('admin.static-pages.index')->with('success', 'Page deleted successfully.');
    }
}
