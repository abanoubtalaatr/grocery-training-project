<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Grocery Training') }} - Admin Auth</title>

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-linear-to-br from-slate-950 via-slate-900 to-slate-800 antialiased" x-data>
<div class="mx-auto flex min-h-screen w-full max-w-6xl items-center px-4 py-8 sm:px-6 lg:px-8">
    <div class="grid w-full gap-10 lg:grid-cols-2 lg:items-center">
        <div class="hidden lg:block">
            <h1 class="text-4xl font-bold tracking-tight text-white">Admin Console</h1>
            <p class="mt-4 max-w-md text-base leading-relaxed text-slate-300">
                Manage users, orders, and reporting from one secure place.
            </p>
        </div>

        <div class="mx-auto w-full max-w-md rounded-2xl border border-white/10 bg-white p-6 shadow-2xl sm:p-8">
            <div class="mb-6">
                <p class="text-sm font-medium uppercase tracking-wide text-slate-500">Admin Portal</p>
                <h2 class="mt-1 text-2xl font-semibold text-slate-900">Sign in</h2>
                <p class="mt-2 text-sm text-slate-600">Use your admin credentials to continue.</p>
            </div>
            {{ $slot }}
        </div>
    </div>
</div>

@livewireScripts
</body>
</html>
