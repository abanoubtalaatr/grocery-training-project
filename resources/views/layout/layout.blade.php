<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Grocery Admin')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body class="bg-body-tertiary">
    <div class="app-shell min-vh-100">
        @include('layout.navSidebar')

        <main class="main-shell min-width-0">
            <header class="topbar d-flex align-items-center justify-content-between gap-3 border-bottom">
                <div class="topbar__title">
                    <p class="mb-1 text-secondary small">@yield('breadcrumb', 'Dashboard')</p>
                    <h1 class="mb-0 fw-bold">@yield('page-title', 'Grocery Admin')</h1>
                </div>

                <div class="topbar__actions d-flex align-items-center gap-2">
                    @yield('page-actions')
                </div>
            </header>

            <div class="content-wrap mx-auto">
                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                <section class="content-panel bg-white border">
                    @yield('content')
                </section>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
