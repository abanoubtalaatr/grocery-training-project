<x-admin.app-layout title="Notification">
    <x-slot name="header">
        <h1 class="truncate text-xl font-semibold text-slate-900">Notification</h1>
    </x-slot>

    <div class="max-w-2xl space-y-6">
        <x-admin.card>
            <dl class="grid grid-cols-2 gap-4 text-sm">
                <div><dt class="text-slate-500">Type</dt><dd class="font-medium text-slate-900">{{ class_basename($notification->type) }}</dd></div>
                <div><dt class="text-slate-500">State</dt><dd><x-admin.badge :color="$notification->read_at ? 'slate' : 'blue'">{{ $notification->read_at ? 'Read' : 'Unread' }}</x-admin.badge></dd></div>
                <div><dt class="text-slate-500">Notifiable</dt><dd class="font-medium text-slate-900">{{ class_basename($notification->notifiable_type) }} #{{ $notification->notifiable_id }}</dd></div>
                <div><dt class="text-slate-500">Date</dt><dd class="font-medium text-slate-900">{{ $notification->created_at?->format('M d, Y H:i') }}</dd></div>
            </dl>
            <div class="mt-4 border-t border-slate-200 pt-4">
                <p class="mb-2 text-sm font-medium text-slate-700">Data</p>
                <pre class="overflow-x-auto rounded-lg bg-slate-50 p-4 text-xs text-slate-700">{{ json_encode($notification->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
            </div>
        </x-admin.card>

        <a href="{{ route('admin.notifications.index') }}" class="inline-flex rounded-lg px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100">Back</a>
    </div>
</x-admin.app-layout>
