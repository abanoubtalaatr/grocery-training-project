<x-admin.app-layout title="Review">
    <x-slot name="header">
        <h1 class="truncate text-xl font-semibold text-slate-900">Review</h1>
    </x-slot>

    <div class="max-w-2xl space-y-6">
        <x-admin.card>
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-semibold text-slate-900">{{ $review->meal?->title ?? '—' }}</p>
                    <p class="text-sm text-slate-500">by {{ $review->user?->name ?? '—' }} · {{ $review->created_at?->format('M d, Y') }}</p>
                </div>
                <x-admin.badge :color="$review->is_approved ? 'green' : 'amber'">{{ $review->is_approved ? 'Approved' : 'Pending' }}</x-admin.badge>
            </div>
            <p class="mt-3 text-amber-500">{{ str_repeat('★', (int) $review->rating) }}<span class="text-slate-300">{{ str_repeat('★', 5 - (int) $review->rating) }}</span></p>
            @if ($review->comment)
                <p class="mt-3 text-sm text-slate-700">{{ $review->comment }}</p>
            @endif
        </x-admin.card>

        <div class="flex gap-3">
            <form method="POST" action="{{ route('admin.reviews.toggle-approval', $review) }}">
                @csrf
                @method('PATCH')
                <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">{{ $review->is_approved ? 'Unapprove' : 'Approve' }}</button>
            </form>
            <a href="{{ route('admin.reviews.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100">Back</a>
        </div>
    </div>
</x-admin.app-layout>
