<aside
    class="bg-dark text-white vh-100 position-fixed"
    style="width:260px; left:0; top:0;"
>
    <div class="p-4 border-bottom">
        <h4 class="mb-0">
            Grocery Admin
        </h4>
    </div>

    <ul class="nav flex-column p-3">

        <li class="nav-item mb-2">

            <a
                href="{{ route('admin.dashboard') }}"
                class="nav-link text-white {{ request()->routeIs('admin.dashboard') ? 'fw-bold' : '' }}"
            >
                📊 {{ __('sidebar.dashboard') }}
            </a>

        </li>

        <li class="nav-item mb-2">

            <a
                href="{{ route('admin.users.index') }}"
                class="nav-link text-white {{ request()->routeIs('admin.users.*') ? 'fw-bold' : '' }}"
            >
                👤 {{ __('sidebar.users') }}
            </a>

        </li>

        <li class="nav-item mb-2">

            <a
                href="{{ route('admin.categories.index') }}"
                class="nav-link text-white {{ request()->routeIs('admin.categories.*') ? 'fw-bold' : '' }}"
            >
                📁 {{ __('sidebar.categories') }}
            </a>

        </li>

        <li class="nav-item mb-2">

            <a
                href="{{ route('admin.subcategories.index') }}"
                class="nav-link text-white {{ request()->routeIs('admin.subcategories.*') ? 'fw-bold' : '' }}"
            >
                📂 {{ __('sidebar.subcategories') }}
            </a>

        </li>

        <li class="nav-item mb-2">

            <a
                href="{{ route('admin.meals.index') }}"
                class="nav-link text-white {{ request()->routeIs('admin.meals.*') ? 'fw-bold' : '' }}"
            >
                🍽️ {{ __('sidebar.meals') }}
            </a>

        </li>

        <li class="nav-item mb-2">

            <a
                href="{{ route('admin.orders.index') }}"
                class="nav-link text-white {{ request()->routeIs('admin.orders.*') ? 'fw-bold' : '' }}"
            >
                📦 {{ __('sidebar.orders') }}
            </a>

        </li>

    </ul>

</aside>