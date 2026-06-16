<header class="header">

    <div>

        <h3 class="mb-0">
            @yield('page-title')
        </h3>

    </div>

    <div class="d-flex align-items-center gap-3">

        @if(app()->getLocale() == 'ar')

            <a href="{{ route('language.switch', 'en') }}"
               class="btn btn-light">

                EN

            </a>

        @else

            <a href="{{ route('language.switch', 'ar') }}"
               class="btn btn-light">

                العربية

            </a>

        @endif

        <div class="user-box">

            <div class="avatar">

                {{ strtoupper(substr(auth()->user()->name ?? 'A',0,1)) }}

            </div>

            <span>

                {{ auth()->user()->name ?? 'Admin' }}

            </span>

        </div>

    </div>

</header>