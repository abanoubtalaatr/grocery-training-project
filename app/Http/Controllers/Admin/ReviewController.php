<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(Request $request): View
    {
        $reviews = Review::query()
            ->with(['user', 'meal'])
            ->when($request->string('status')->toString() === 'approved', fn ($query) => $query->where('is_approved', true))
            ->when($request->string('status')->toString() === 'pending', fn ($query) => $query->where('is_approved', false))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.reviews.index', compact('reviews'));
    }

    public function show(Review $review): View
    {
        $review->load(['user', 'meal']);

        return view('admin.reviews.show', compact('review'));
    }

    public function toggleApproval(Review $review): RedirectResponse
    {
        $review->update(['is_approved' => ! $review->is_approved]);

        return back()->with('success', 'Review approval updated.');
    }

    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();

        return redirect()->route('admin.reviews.index')->with('success', 'Review deleted successfully.');
    }
}
