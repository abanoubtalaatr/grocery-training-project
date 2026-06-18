<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ ($title ?? 'Dashboard') . ' - ' . config('app.name', 'Grocery') }} Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
    <style>[x-cloak]{display:none!important;}</style>
</head>
<body class="bg-slate-100 text-slate-900 antialiased" x-data="{ sidebarOpen: false }">
@php
    $navGroups = [
        'Overview' => [
            ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'active' => 'admin.dashboard'],
        ],
        'Catalog' => [
            ['label' => 'Categories', 'route' => 'admin.categories.index', 'active' => 'admin.categories.*'],
            ['label' => 'Subcategories', 'route' => 'admin.subcategories.index', 'active' => 'admin.subcategories.*'],
            ['label' => 'Meals', 'route' => 'admin.meals.index', 'active' => 'admin.meals.*'],
            ['label' => 'Offers', 'route' => 'admin.offers.index', 'active' => 'admin.offers.*'],
            ['label' => 'Special Notes', 'route' => 'admin.special-notes.index', 'active' => 'admin.special-notes.*'],
        ],
        'Sales' => [
            ['label' => 'Orders', 'route' => 'admin.orders.index', 'active' => 'admin.orders.*'],
            ['label' => 'Reviews', 'route' => 'admin.reviews.index', 'active' => 'admin.reviews.*'],
        ],
        'People' => [
            ['label' => 'Users', 'route' => 'admin.users.index', 'active' => 'admin.users.*'],
        ],
        'Content' => [
            ['label' => 'FAQs', 'route' => 'admin.faqs.index', 'active' => 'admin.faqs.*'],
            ['label' => 'Static Pages', 'route' => 'admin.static-pages.index', 'active' => 'admin.static-pages.*'],
        ],
        'Support' => [
            ['label' => 'Contact Messages', 'route' => 'admin.contact-messages.index', 'active' => 'admin.contact-messages.*'],
            ['label' => 'Support Reports', 'route' => 'admin.support-reports.index', 'active' => 'admin.support-reports.*'],
            ['label' => 'Notifications', 'route' => 'admin.notifications.index', 'active' => 'admin.notifications.*'],
        ],
        'System' => [
            ['label' => 'Settings', 'route' => 'admin.settings.edit', 'active' => 'admin.settings.*'],
        ],
    ];
@endphp

<div class="min-h-screen md:flex">
    <div
        class="fixed inset-0 z-40 bg-slate-950/50 md:hidden"
        x-cloak
        x-show="sidebarOpen"
        x-on:click="sidebarOpen = false"
        x-transition.opacity
    ></div>

    <aside
        class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col border-r border-slate-800 bg-slate-900 text-slate-100 transition md:static md:translate-x-0"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
    >
        <div class="flex h-16 shrink-0 items-center gap-2 border-b border-slate-800 px-6">
            <span class="text-lg font-semibold tracking-wide">{{ config('app.name', 'Grocery') }} Admin</span>
        </div>

        <nav class="flex-1 space-y-6 overflow-y-auto p-4">
            @foreach ($navGroups as $groupLabel => $items)
                <div>
                    <p class="px-3 pb-2 text-xs font-semibold uppercase tracking-wider text-slate-500">{{ $groupLabel }}</p>
                    <div class="space-y-1">
                        @foreach ($items as $item)
                            <a
                                href="{{ route($item['route']) }}"
                                class="block rounded-lg px-3 py-2 text-sm font-medium transition {{ request()->routeIs($item['active']) ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                            >
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </nav>

        <div class="shrink-0 border-t border-slate-800 p-4">
            <a href="{{ url('/') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-300 transition hover:bg-slate-800 hover:text-white">
                Back to Site
            </a>
        </div>
    </aside>

    <div class="flex min-w-0 flex-1 flex-col">
        <header class="sticky top-0 z-30 border-b border-slate-200 bg-white/90 backdrop-blur">
            <div class="flex h-16 items-center justify-between gap-4 px-4 md:px-8">
                <div class="flex min-w-0 items-center gap-3">
                    <button
                        type="button"
                        class="inline-flex items-center rounded-lg border border-slate-300 p-2 text-slate-700 transition hover:bg-slate-50 md:hidden"
                        x-on:click="sidebarOpen = !sidebarOpen"
                        aria-label="Toggle menu"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div class="min-w-0">
                        @isset($header)
                            {{ $header }}
                        @else
                            <h1 class="truncate text-xl font-semibold text-slate-900">{{ $title ?? 'Dashboard' }}</h1>
                        @endisset
                    </div>
                </div>

                <div class="flex items-center gap-3" x-data="{ open: false }">
                    <div class="hidden text-right sm:block">
                        <p class="text-sm font-medium text-slate-900">{{ auth()->user()?->name }}</p>
                        <p class="text-xs text-slate-500">{{ auth()->user()?->email }}</p>
                    </div>
                    <div class="relative">
                        <button
                            type="button"
                            x-on:click="open = !open"
                            class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-900 text-sm font-semibold text-white"
                        >
                            {{ strtoupper(substr(auth()->user()?->name ?? 'A', 0, 1)) }}
                        </button>
                        <div
                            x-cloak
                            x-show="open"
                            x-on:click.outside="open = false"
                            x-transition
                            class="absolute right-0 mt-2 w-44 rounded-lg border border-slate-200 bg-white py-1 shadow-lg"
                        >
                            <form method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-slate-700 transition hover:bg-slate-50">
                                    Log out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 md:p-8">
            @if (session('success'))
                <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>
</div>

@livewireScripts
</body>
</html>
