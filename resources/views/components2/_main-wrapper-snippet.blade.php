{{--
    In your layout, the main content area that sits beside the sidebar
    must use ms-64 (margin-start) instead of ml-64 (margin-left).
    ms-* respects RTL automatically — no extra logic needed.
--}}

<div class="ms-64 flex flex-col min-h-screen bg-gray-50">
    <x-navbar />
    <main class="flex-1 p-6">
        @yield('content')
    </main>
    <x-footer />
</div>
