@php
    $isArabic = app()->getLocale() === 'ar';
@endphp

<aside
    class="bg-dark text-white vh-100 position-fixed"
    style="
        width: 260px;
        top: 0;
        {{ $isArabic ? 'right:0;' : 'left:0;' }}
    ">

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

    {{-- Dashboard --}}
    <li class="nav-item mb-3">

        <small class="text-uppercase text-secondary fw-bold">
            Dashboard
        </small>

        <a
            href="{{ route('admin.dashboard') }}"
            class="nav-link text-white mt-2
            {{ request()->routeIs('admin.dashboard')
                ? 'active bg-primary rounded'
                : '' }}">

            <i class="bi bi-speedometer2 me-2"></i>

            {{ __('sidebar.dashboard') }}

        </a>

    </li>

    {{-- Catalog --}}
    <li class="nav-item mb-3">

        <small class="text-uppercase text-secondary fw-bold">

            Catalog Management

        </small>

        <a
            href="{{ route('admin.categories.index') }}"
            class="nav-link text-white mt-2
            {{ request()->routeIs('admin.categories.*')
                ? 'active bg-primary rounded'
                : '' }}">

            <i class="bi bi-grid me-2"></i>

            {{ __('sidebar.categories') }}

        </a>

        <a
            href="{{ route('admin.subcategories.index') }}"
            class="nav-link text-white
            {{ request()->routeIs('admin.subcategories.*')
                ? 'active bg-primary rounded'
                : '' }}">

            <i class="bi bi-diagram-3 me-2"></i>

            {{ __('sidebar.subcategories') }}

        </a>

        <a
            href="{{ route('admin.meals.index') }}"
            class="nav-link text-white
            {{ request()->routeIs('admin.meals.*')
                ? 'active bg-primary rounded'
                : '' }}">

            <i class="bi bi-cup-hot me-2"></i>

            {{ __('sidebar.meals') }}

        </a>

    </li>

    {{-- Sales --}}
    <li class="nav-item mb-3">

        <small class="text-uppercase text-secondary fw-bold">

            Sales

        </small>

        <a
            href="{{ route('admin.orders.index') }}"
            class="nav-link text-white mt-2
            {{ request()->routeIs('admin.orders.*')
                ? 'active bg-primary rounded'
                : '' }}">

            <i class="bi bi-bag-check me-2"></i>

            {{ __('sidebar.orders') }}

        </a>

        <a
            href="{{ route('admin.offers.index') }}"
            class="nav-link text-white
            {{ request()->routeIs('admin.offers.*')
                ? 'active bg-primary rounded'
                : '' }}">

            <i class="bi bi-percent me-2"></i>

            {{ __('sidebar.offers') }}

        </a>

    </li>

    {{-- Users --}}
    <li class="nav-item mb-3">

        <small class="text-uppercase text-secondary fw-bold">

            User Management

        </small>

        <a
            href="{{ route('admin.users.index') }}"
            class="nav-link text-white mt-2
            {{ request()->routeIs('admin.users.*')
                ? 'active bg-primary rounded'
                : '' }}">

            <i class="bi bi-people me-2"></i>

            {{ __('sidebar.users') }}

        </a>

        <a
            href="{{ route('admin.reviews.index') }}"
            class="nav-link text-white
            {{ request()->routeIs('admin.reviews.*')
                ? 'active bg-primary rounded'
                : '' }}">

            <i class="bi bi-star me-2"></i>

            {{ __('sidebar.reviews') }}

        </a>

    </li>

    {{-- Support --}}
    <li class="nav-item mb-3">

        <small class="text-uppercase text-secondary fw-bold">

            Support Center

        </small>

        <a
            href="{{ route('admin.contact-messages.index') }}"
            class="nav-link text-white mt-2
            {{ request()->routeIs('admin.contact-messages.*')
                ? 'active bg-primary rounded'
                : '' }}">

            <i class="bi bi-envelope me-2"></i>

            {{ __('sidebar.contact-messages') }}

        </a>

        <a
            href="{{ route('admin.support-reports.index') }}"
            class="nav-link text-white
            {{ request()->routeIs('admin.support-reports.*')
                ? 'active bg-primary rounded'
                : '' }}">

            <i class="bi bi-life-preserver me-2"></i>

            {{ __('sidebar.support-reports') }}

        </a>

        <a
            href="{{ route('admin.faqs.index') }}"
            class="nav-link text-white
            {{ request()->routeIs('admin.faqs.*')
                ? 'active bg-primary rounded'
                : '' }}">

            <i class="bi bi-question-circle me-2"></i>

            {{ __('sidebar.faqs') }}

        </a>

    </li>

    {{-- System --}}
    <li class="nav-item">

        <small class="text-uppercase text-secondary fw-bold">

            System

        </small>

        <a
            href="{{ route('admin.settings.edit') }}"
            class="nav-link text-white mt-2
            {{ request()->routeIs('admin.settings.*')
                ? 'active bg-primary rounded'
                : '' }}">

            <i class="bi bi-gear me-2"></i>

            {{ __('sidebar.settings') }}

        </a>

    </li>

</ul>

</aside>