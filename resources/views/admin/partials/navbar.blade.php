<nav class="navbar bg-white shadow-sm">
    <div class="container-fluid">

        <h5 class="mb-0">
            @yield('page-title')
        </h5>

        <div class="d-flex align-items-center gap-3">

            <a
                href="{{ route('language.switch', 'en') }}"
                class="btn btn-sm btn-outline-primary"
            >
                EN
            </a>

            <a
                href="{{ route('language.switch', 'ar') }}"
                class="btn btn-sm btn-outline-primary"
            >
                AR
            </a>

            <span class="fw-semibold">
                {{ auth()->user()->username }}
            </span>

       

        </div>

    </div>
</nav>