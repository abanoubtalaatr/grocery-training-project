<x-admin.app-layout title="User">
    <x-slot name="header">
        <h1 class="truncate text-xl font-semibold text-slate-900">{{ $user->name }}</h1>
    </x-slot>

    <div class="max-w-3xl space-y-6">
        <x-admin.card>
            <dl class="grid grid-cols-2 gap-4 text-sm">
                <div><dt class="text-slate-500">Email</dt><dd class="font-medium text-slate-900">{{ $user->email }}</dd></div>
                <div><dt class="text-slate-500">Phone</dt><dd class="font-medium text-slate-900">{{ $user->phone ?? '—' }}</dd></div>
                <div><dt class="text-slate-500">Role</dt><dd><x-admin.badge :color="$user->is_admin ? 'indigo' : 'slate'">{{ $user->is_admin ? 'Admin' : 'Customer' }}</x-admin.badge></dd></div>
                <div><dt class="text-slate-500">Status</dt><dd><x-admin.badge :color="$user->is_active ? 'green' : 'red'">{{ $user->is_active ? 'Active' : 'Inactive' }}</x-admin.badge></dd></div>
                <div><dt class="text-slate-500">Orders</dt><dd class="font-medium text-slate-900">{{ $user->orders_count }}</dd></div>
                <div><dt class="text-slate-500">Favorites</dt><dd class="font-medium text-slate-900">{{ $user->favorites_count }}</dd></div>
                <div><dt class="text-slate-500">Addresses</dt><dd class="font-medium text-slate-900">{{ $user->addresses_count }}</dd></div>
                <div><dt class="text-slate-500">Loyalty Points</dt><dd class="font-medium text-slate-900">{{ $user->loyalty_points }}</dd></div>
                <div><dt class="text-slate-500">Joined</dt><dd class="font-medium text-slate-900">{{ $user->created_at?->format('M d, Y') }}</dd></div>
            </dl>
        </x-admin.card>

        <div class="flex gap-3">
            <a href="{{ route('admin.users.edit', $user) }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">Edit</a>
            <a href="{{ route('admin.users.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100">Back</a>
        </div>
    </div>
</x-admin.app-layout>
