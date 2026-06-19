<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar'? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-surface-muted antialiased">

    <div class="flex min-h-screen">

        {{-- Sidebar --}}
        <x-sidebar />

        {{-- Main wrapper --}}
        <div class="flex flex-1 flex-col {{ app()->getLocale() === 'ar' ? 'lg:pr-[240px]' : 'lg:pl-[240px]' }}">
            {{-- Top navbar --}}
            <x-navbar />

            {{-- Page content --}}
            <main class="flex-1 px-6 py-7 md:px-8">

                {{-- Optional page header slot --}}
                @hasSection('header')
                    <div class="mb-6 flex items-start justify-between gap-4">
                        <div>
                            <h1 class="font-display text-[22px] font-bold tracking-tight text-gray-900 leading-snug">
                                @yield('heading')
                            </h1>
                            @hasSection('subheading')
                                <p class="mt-1 text-sm text-gray-500">@yield('subheading')</p>
                            @endif
                        </div>
                        @hasSection('actions')
                            <div class="flex items-center gap-2 shrink-0">
                                @yield('actions')
                            </div>
                        @endif
                    </div>
                @endif

                <x-alerts />
                @yield('content')

            </main>

        </div>
    </div>

    @stack('scripts')
</body>
</html>