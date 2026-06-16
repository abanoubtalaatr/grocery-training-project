<aside class="sidebar">

    <div class="sidebar-logo">
        <i class="fa-solid fa-store"></i>

        <span>{{ config('app.name') }}</span>
    </div>

    <ul class="sidebar-menu">

        <li>
            <a href="#"
               class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">

                <i class="fa-solid fa-gauge"></i>

                <span>{{ __('general.dashboard') }}</span>

            </a>
        </li>

        <li>
            <a href="{{ route('admin.categories.index') }}"
               class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">

                <i class="fa-solid fa-layer-group"></i>

                <span>{{ __('general.categories') }}</span>

            </a>
        </li>

    </ul>

</aside>