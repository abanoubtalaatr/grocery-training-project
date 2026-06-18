<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SpecialNoteRequest;
use App\Models\SpecialNote;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SpecialNoteController extends Controller
{
    public function index(): View
    {
        $specialNotes = SpecialNote::query()->latest()->paginate(15);

        return view('admin.special-notes.index', compact('specialNotes'));
    }

    public function create(): View
    {
        return view('admin.special-notes.create', ['specialNote' => new SpecialNote()]);
    }

    public function store(SpecialNoteRequest $request): RedirectResponse
    {
        SpecialNote::create($request->validated());

        return redirect()->route('admin.special-notes.index')->with('success', 'Special note created successfully.');
    }

    public function edit(SpecialNote $specialNote): View
    {
        return view('admin.special-notes.edit', compact('specialNote'));
    }

    public function update(SpecialNoteRequest $request, SpecialNote $specialNote): RedirectResponse
    {
        $specialNote->update($request->validated());

        return redirect()->route('admin.special-notes.index')->with('success', 'Special note updated successfully.');
    }

    public function destroy(SpecialNote $specialNote): RedirectResponse
    {
        $specialNote->delete();

        return redirect()->route('admin.special-notes.index')->with('success', 'Special note deleted successfully.');
    }
}
