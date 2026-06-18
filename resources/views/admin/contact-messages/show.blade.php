<x-admin.app-layout title="Message">
    <x-slot name="header">
        <h1 class="truncate text-xl font-semibold text-slate-900">Message from {{ $message->name }}</h1>
    </x-slot>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <x-admin.card>
                <dl class="grid grid-cols-2 gap-4 text-sm">
                    <div><dt class="text-slate-500">Name</dt><dd class="font-medium text-slate-900">{{ $message->name }}</dd></div>
                    <div><dt class="text-slate-500">Email</dt><dd class="font-medium text-slate-900">{{ $message->email }}</dd></div>
                    <div><dt class="text-slate-500">Phone</dt><dd class="font-medium text-slate-900">{{ $message->phone ?? '—' }}</dd></div>
                    <div><dt class="text-slate-500">Subject</dt><dd class="font-medium text-slate-900">{{ $message->subject ?? '—' }}</dd></div>
                </dl>
                <div class="mt-4 border-t border-slate-200 pt-4">
                    <p class="text-sm text-slate-700">{{ $message->message }}</p>
                </div>
            </x-admin.card>
        </div>

        <div class="space-y-6">
            <x-admin.card>
                <h2 class="mb-3 text-base font-semibold text-slate-900">Manage</h2>
                <form method="POST" action="{{ route('admin.contact-messages.status', $message) }}" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    <x-admin.select name="status" label="Status" :options="$statuses" :selected="$message->status" />
                    <x-admin.textarea name="admin_notes" label="Admin Notes" :value="$message->admin_notes" rows="4" />
                    <button type="submit" class="w-full rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">Save</button>
                </form>
            </x-admin.card>

            <a href="{{ route('admin.contact-messages.index') }}" class="inline-flex rounded-lg px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100">Back to messages</a>
        </div>
    </div>
</x-admin.app-layout>
