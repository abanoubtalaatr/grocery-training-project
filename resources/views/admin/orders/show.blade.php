<x-admin.app-layout title="Order">
    <x-slot name="header">
        <h1 class="truncate text-xl font-semibold text-slate-900">Order {{ $order->order_number }}</h1>
    </x-slot>

    @php
        $statusColors = [
            'awaiting_payment' => 'amber', 'placed' => 'blue', 'processing' => 'indigo',
            'shipping' => 'purple', 'out_for_delivery' => 'purple', 'delivered' => 'green', 'cancelled' => 'red',
        ];
    @endphp

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <x-admin.card padding="p-0">
                <div class="border-b border-slate-200 px-6 py-4">
                    <h2 class="text-base font-semibold text-slate-900">Items</h2>
                </div>
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-6 py-3 font-medium">Meal</th>
                            <th class="px-6 py-3 font-medium">Qty</th>
                            <th class="px-6 py-3 font-medium">Unit</th>
                            <th class="px-6 py-3 text-right font-medium">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($order->items as $item)
                            <tr>
                                <td class="px-6 py-3 font-medium text-slate-900">{{ $item->meal?->title ?? 'Deleted meal' }}</td>
                                <td class="px-6 py-3 text-slate-600">{{ $item->quantity }}</td>
                                <td class="px-6 py-3 text-slate-600">${{ number_format((float) $item->unit_price, 2) }}</td>
                                <td class="px-6 py-3 text-right font-medium text-slate-900">${{ number_format((float) $item->subtotal, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-8 text-center text-slate-500">No items.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="space-y-1 border-t border-slate-200 px-6 py-4 text-sm">
                    <div class="flex justify-between"><span class="text-slate-500">Subtotal</span><span>${{ number_format((float) $order->subtotal, 2) }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-500">Tax</span><span>${{ number_format((float) $order->tax, 2) }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-500">Discount</span><span>-${{ number_format((float) $order->discount, 2) }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-500">Shipping</span><span>${{ number_format((float) $order->shipping_fee, 2) }}</span></div>
                    <div class="flex justify-between border-t border-slate-200 pt-2 text-base font-semibold text-slate-900"><span>Total</span><span>${{ number_format((float) $order->total, 2) }}</span></div>
                </div>
            </x-admin.card>
        </div>

        <div class="space-y-6">
            <x-admin.card>
                <h2 class="mb-3 text-base font-semibold text-slate-900">Status</h2>
                <x-admin.badge :color="$statusColors[$order->status] ?? 'slate'">{{ $order->status_description }}</x-admin.badge>
                <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="mt-4 space-y-3">
                    @csrf
                    @method('PATCH')
                    <x-admin.select name="status" :options="$statuses" :selected="$order->status" />
                    <button type="submit" class="w-full rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">Update Status</button>
                </form>
            </x-admin.card>

            <x-admin.card>
                <h2 class="mb-3 text-base font-semibold text-slate-900">Customer</h2>
                <dl class="space-y-2 text-sm">
                    <div><dt class="text-slate-500">Name</dt><dd class="font-medium text-slate-900">{{ $order->user?->name ?? 'Guest' }}</dd></div>
                    <div><dt class="text-slate-500">Email</dt><dd class="font-medium text-slate-900">{{ $order->user?->email ?? '—' }}</dd></div>
                    <div><dt class="text-slate-500">Payment</dt><dd class="font-medium text-slate-900">{{ $order->payment_method ?? '—' }}</dd></div>
                    @if ($order->address)
                        <div><dt class="text-slate-500">Address</dt><dd class="font-medium text-slate-900">{{ $order->address->address ?? '' }}</dd></div>
                    @endif
                </dl>
            </x-admin.card>

            <a href="{{ route('admin.orders.index') }}" class="inline-flex rounded-lg px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100">Back to orders</a>
        </div>
    </div>
</x-admin.app-layout>
