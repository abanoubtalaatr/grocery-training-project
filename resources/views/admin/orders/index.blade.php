<x-admin.app-layout title="Orders">
    <x-slot name="header">
        <h1 class="truncate text-xl font-semibold text-slate-900">Orders</h1>
    </x-slot>

    @php
        $statusColors = [
            'awaiting_payment' => 'amber', 'placed' => 'blue', 'processing' => 'indigo',
            'shipping' => 'purple', 'out_for_delivery' => 'purple', 'delivered' => 'green', 'cancelled' => 'red',
        ];
    @endphp

    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <form method="GET" class="flex w-full flex-col gap-2 sm:flex-row sm:max-w-lg">
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Search order number..."
                class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500" />
            <select name="status" onchange="this.form.submit()"
                class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500">
                <option value="">All statuses</option>
                @foreach ($statuses as $value => $label)
                    <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <x-admin.card padding="p-0">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 py-3 font-medium">Order</th>
                        <th class="px-6 py-3 font-medium">Customer</th>
                        <th class="px-6 py-3 font-medium">Status</th>
                        <th class="px-6 py-3 font-medium">Date</th>
                        <th class="px-6 py-3 text-right font-medium">Total</th>
                        <th class="px-6 py-3 text-right font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-3 font-medium text-slate-900">
                                <a href="{{ route('admin.orders.show', $order) }}" class="hover:underline">{{ $order->order_number }}</a>
                            </td>
                            <td class="px-6 py-3 text-slate-600">{{ $order->user?->name ?? 'Guest' }}</td>
                            <td class="px-6 py-3"><x-admin.badge :color="$statusColors[$order->status] ?? 'slate'">{{ $order->status_description }}</x-admin.badge></td>
                            <td class="px-6 py-3 text-xs text-slate-500">{{ $order->created_at?->format('M d, Y H:i') }}</td>
                            <td class="px-6 py-3 text-right font-medium text-slate-900">${{ number_format((float) $order->total, 2) }}</td>
                            <td class="px-6 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="rounded-md px-2.5 py-1.5 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900">View</a>
                                    <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" onsubmit="return confirm('Delete this order?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-md px-2.5 py-1.5 text-sm font-medium text-red-600 transition hover:bg-red-50">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-10 text-center text-slate-500">No orders found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-admin.card>

    <div class="mt-4">{{ $orders->links() }}</div>
</x-admin.app-layout>
