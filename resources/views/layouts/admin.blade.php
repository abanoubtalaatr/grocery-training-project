<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    @vite(['resources/scss/app.scss', 'resources/js/app.js'])

    <title>Grocery Dashboard</title>
</head>
<body>

    {{-- Mobile top bar (hidden on desktop) --}}
    <div class="mobile-topbar d-flex d-lg-none align-items-center px-3 py-2">
        <button class="btn btn-sm text-white border-0 me-3" id="mobile-sidebar-open" style="background:rgba(255,255,255,0.08);">
            <i class="fas fa-bars"></i>
        </button>
        <span class="fw-bold text-white me-auto" style="font-size:0.95rem;">
            <span style="color:#fbbf24;">Grocery</span> Admin
        </span>
        <button class="btn btn-sm text-white border-0 theme-toggle-btn" style="background:rgba(255,255,255,0.08);" title="Toggle theme">
            <i class="fas fa-sun"></i>
        </button>
    </div>


    <div class="admin-wrapper">

        {{-- Sidebar --}}
        @include('partials.sidebar')

        {{-- Page content --}}
        <main class="admin-content">
            @yield('content')
        </main>

    </div>

</body>
</html>