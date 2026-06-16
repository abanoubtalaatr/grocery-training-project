<aside
    class="bg-dark text-white vh-100 position-fixed"
    style="width: 260px; right: 0; top: 0;">

    <div class="p-4 border-bottom">

        <h4 class="mb-0">
            Grocery Admin
        </h4>

    </div>

    <div class="dropdown p-3">

    <button
        class="btn btn-light dropdown-toggle w-100"
        type="button"
        data-bs-toggle="dropdown">

        🌐 {{ strtoupper(app()->getLocale()) }}

    </button>

    <ul class="dropdown-menu">

        <li>
            <a
                class="dropdown-item"
                href="{{ route('language.switch', ['locale' => 'en']) }}">
                English
            </a>
        </li>

        <li>
            <a
                class="dropdown-item"
                href="{{ route('language.switch', ['locale' => 'ar']) }}">
                العربية
            </a>
        </li>

    </ul>

</div>

    <ul class="nav flex-column p-3">

        <li class="nav-item mb-2">
            <a
                href="{{ route('admin.dashboard') }}"
                class="nav-link text-white
                {{ request()->routeIs('admin.dashboard')
                    ? 'active bg-primary rounded'
                    : '' }}">

                {{ __('sidebar.dashboard') }}
            </a>
        </li>

        <li class="nav-item mb-2">
            <a
                {{-- href="{{ route('admin.Users') }}" --}}
                class="nav-link text-white">

                {{ __('sidebar.users') }}
            </a>
        </li>


        <li class="nav-item mb-2">
            <a
                {{-- href="{{ route('admin.Meals') }}" --}}
                class="nav-link text-white">

                {{ __('sidebar.meals') }}
            </a>
        </li>


        <li class="nav-item mb-2">
            <a
                {{-- href="{{ route('admin.Categories') }}" --}}
                class="nav-link text-white">

                {{ __('sidebar.categories') }}
            </a>
        </li>


        <li class="nav-item mb-2">
            <a
                {{-- href="{{ route('admin.Orders') }}" --}}
                class="nav-link text-white">

                {{ __('sidebar.orders') }}
            </a>
        </li>

    </ul>

</aside>