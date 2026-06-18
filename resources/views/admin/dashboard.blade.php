<x-admin.app-layout title="Dashboard">
    <x-slot name="header">
        <h1 class="truncate text-xl font-semibold text-slate-900">Dashboard</h1>
    </x-slot>

    @php
        $cards = [
            ['label' => 'Total Users', 'value' => number_format($stats['users']), 'sub' => $stats['active_orders'] . ' active orders'],
            ['label' => 'Total Meals', 'value' => number_format($stats['meals']), 'sub' => 'Catalog items'],
            ['label' => 'Total Orders', 'value' => number_format($stats['orders']), 'sub' => 'All time'],
            ['label' => 'Revenue', 'value' => '$' . number_format($stats['revenue'], 2), 'sub' => 'Delivered orders'],
        ];
        $statusColors = [
            'awaiting_payment' => 'amber', 'placed' => 'blue', 'processing' => 'indigo',
            'shipping' => 'purple', 'out_for_delivery' => 'purple', 'delivered' => 'green', 'cancelled' => 'red',
        ];
    @endphp

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($cards as $card)
            <x-admin.card>
                <p class="text-sm font-medium text-slate-500">{{ $card['label'] }}</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $card['value'] }}</p>
                <p class="mt-1 text-xs text-slate-400">{{ $card['sub'] }}</p>
            </x-admin.card>
        @endforeach
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <x-admin.card padding="p-0" class="lg:col-span-2">
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
                <h2 class="text-base font-semibold text-slate-900">Recent Orders</h2>
                <a href="{{ route('admin.orders.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">View all</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-6 py-3 font-medium">Order</th>
                            <th class="px-6 py-3 font-medium">Customer</th>
                            <th class="px-6 py-3 font-medium">Status</th>
                            <th class="px-6 py-3 text-right font-medium">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($recentOrders as $order)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-3 font-medium text-slate-900">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="hover:underline">{{ $order->order_number }}</a>
                                </td>
                                <td class="px-6 py-3 text-slate-600">{{ $order->user?->name ?? 'Guest' }}</td>
                                <td class="px-6 py-3">
                                    <x-admin.badge :color="$statusColors[$order->status] ?? 'slate'">{{ $order->status_description }}</x-admin.badge>
                                </td>
                                <td class="px-6 py-3 text-right font-medium text-slate-900">${{ number_format((float) $order->total, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-slate-500">No orders yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-admin.card>

        <x-admin.card padding="p-0">
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
                <h2 class="text-base font-semibold text-slate-900">New Users</h2>
                <a href="{{ route('admin.users.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">View all</a>
            </div>
            <ul class="divide-y divide-slate-100">
                @forelse ($recentUsers as $user)
                    <li class="flex items-center gap-3 px-6 py-3">
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-900 text-sm font-semibold text-white">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </span>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium text-slate-900">{{ $user->name }}</p>
                            <p class="truncate text-xs text-slate-500">{{ $user->email }}</p>
                        </div>
                    </li>
                @empty
                    <li class="px-6 py-8 text-center text-slate-500">No users yet.</li>
                @endforelse
            </ul>
        </x-admin.card>
    </div>
</x-admin.app-layout>
