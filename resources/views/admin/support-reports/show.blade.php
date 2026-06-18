<x-admin.app-layout title="Support Report">
    <x-slot name="header">
        <h1 class="truncate text-xl font-semibold text-slate-900">{{ $report->issue_type }}</h1>
    </x-slot>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <x-admin.card>
                <dl class="grid grid-cols-2 gap-4 text-sm">
                    <div><dt class="text-slate-500">User</dt><dd class="font-medium text-slate-900">{{ $report->user?->name ?? '—' }}</dd></div>
                    <div><dt class="text-slate-500">Order Number</dt><dd class="font-medium text-slate-900">{{ $report->order_number ?? '—' }}</dd></div>
                    <div><dt class="text-slate-500">Issue Type</dt><dd class="font-medium text-slate-900">{{ $report->issue_type }}</dd></div>
                    <div><dt class="text-slate-500">Received</dt><dd class="font-medium text-slate-900">{{ $report->created_at?->format('M d, Y H:i') }}</dd></div>
                </dl>
                <div class="mt-4 border-t border-slate-200 pt-4">
                    <p class="text-sm text-slate-700">{{ $report->message }}</p>
                </div>
            </x-admin.card>
        </div>

        <div class="space-y-6">
            <x-admin.card>
                <h2 class="mb-3 text-base font-semibold text-slate-900">Status</h2>
                <form method="POST" action="{{ route('admin.support-reports.status', $report) }}" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    <x-admin.select name="status" :options="$statuses" :selected="$report->status" />
                    <button type="submit" class="w-full rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">Update Status</button>
                </form>
            </x-admin.card>

            <a href="{{ route('admin.support-reports.index') }}" class="inline-flex rounded-lg px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100">Back to reports</a>
        </div>
    </div>
</x-admin.app-layout>
