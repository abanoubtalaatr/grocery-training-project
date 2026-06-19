{{-- resources/views/partials/sidebar.blade.php --}}
<aside class="admin-sidebar" id="admin-sidebar">

    {{-- Header / Brand --}}
    <a class="sidebar-header" href="{{ route('dashboard') }}">
        <span class="sidebar-brand-icon">
            <i class="fas fa-shopping-basket"></i>
        </span>
        <span class="sidebar-brand-name">Grocery <span>Admin</span></span>
    </a>

    {{-- Navigation --}}
    <nav class="sidebar-nav">

        {{-- Dashboard (Top-level) --}}
        <div class="sidebar-item mb-1">
            <a href="{{ route('dashboard') }}"
               class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="sidebar-item-icon"><i class="fas fa-home"></i></span>
                <span class="sidebar-label">Dashboard</span>
            </a>
        </div>

        {{-- ── PROFILE INFO ── --}}
        @php
            $profileOpen = request()->routeIs('profile.personal-info*') ||
                           request()->routeIs('profile.security*') ||
                           request()->routeIs('profile.addresses*');
        @endphp
        <div class="nav-group">
            <div class="nav-group-label {{ $profileOpen ? '' : 'collapsed' }}"
                 data-bs-toggle="collapse"
                 data-bs-target="#group-profile"
                 aria-expanded="{{ $profileOpen ? 'true' : 'false' }}">
                <span class="group-label-text">Profile Info</span>
                <i class="fas fa-chevron-down nav-chevron"></i>
            </div>
            <div class="collapse {{ $profileOpen ? 'show' : '' }} nav-group-items" id="group-profile">
                <div class="sidebar-item">
                    <a href="{{ route('profile.personal-info') }}"
                       class="{{ request()->routeIs('profile.personal-info*') ? 'active' : '' }}">
                        <span class="sidebar-item-icon"><i class="fas fa-user"></i></span>
                        <span class="sidebar-label">Personal Info</span>
                    </a>
                </div>
                <div class="sidebar-item">
                    <a href="{{ route('profile.addresses') }}"
                       class="{{ request()->routeIs('profile.addresses*') ? 'active' : '' }}">
                        <span class="sidebar-item-icon"><i class="fas fa-map-marker-alt"></i></span>
                        <span class="sidebar-label">Addresses</span>
                    </a>
                </div>
                <div class="sidebar-item">
                    <a href="{{ route('profile.security') }}"
                       class="{{ request()->routeIs('profile.security*') ? 'active' : '' }}">
                        <span class="sidebar-item-icon"><i class="fas fa-shield-alt"></i></span>
                        <span class="sidebar-label">Security & Login</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- ── ORDERS & WALLET ── --}}
        @php
            $ordersOpen = request()->routeIs('profile.order-history*') ||
                          request()->routeIs('profile.payment-wallet*');
        @endphp
        <div class="nav-group">
            <div class="nav-group-label {{ $ordersOpen ? '' : 'collapsed' }}"
                 data-bs-toggle="collapse"
                 data-bs-target="#group-orders"
                 aria-expanded="{{ $ordersOpen ? 'true' : 'false' }}">
                <span class="group-label-text">Orders & Wallet</span>
                <i class="fas fa-chevron-down nav-chevron"></i>
            </div>
            <div class="collapse {{ $ordersOpen ? 'show' : '' }} nav-group-items" id="group-orders">
                <div class="sidebar-item">
                    <a href="{{ route('profile.order-history') }}"
                       class="{{ request()->routeIs('profile.order-history*') ? 'active' : '' }}">
                        <span class="sidebar-item-icon"><i class="fas fa-history"></i></span>
                        <span class="sidebar-label">Order History</span>
                    </a>
                </div>
                <div class="sidebar-item">
                    <a href="{{ route('profile.payment-wallet') }}"
                       class="{{ request()->routeIs('profile.payment-wallet*') ? 'active' : '' }}">
                        <span class="sidebar-item-icon"><i class="fas fa-wallet"></i></span>
                        <span class="sidebar-label">Payment & Wallet</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- ── SHOPPING FEATURES ── --}}
        @php
            $featuresOpen = request()->routeIs('profile.smart-lists*') ||
                            request()->routeIs('profile.loyalty-rewards*') ||
                            request()->routeIs('chat*');
        @endphp
        <div class="nav-group">
            <div class="nav-group-label {{ $featuresOpen ? '' : 'collapsed' }}"
                 data-bs-toggle="collapse"
                 data-bs-target="#group-features"
                 aria-expanded="{{ $featuresOpen ? 'true' : 'false' }}">
                <span class="group-label-text">Shopping Features</span>
                <i class="fas fa-chevron-down nav-chevron"></i>
            </div>
            <div class="collapse {{ $featuresOpen ? 'show' : '' }} nav-group-items" id="group-features">
                <div class="sidebar-item">
                    <a href="{{ route('profile.smart-lists') }}"
                       class="{{ request()->routeIs('profile.smart-lists*') ? 'active' : '' }}">
                        <span class="sidebar-item-icon"><i class="fas fa-list-ul"></i></span>
                        <span class="sidebar-label">Smart Lists</span>
                    </a>
                </div>
                <div class="sidebar-item">
                    <a href="{{ route('profile.loyalty-rewards') }}"
                       class="{{ request()->routeIs('profile.loyalty-rewards*') ? 'active' : '' }}">
                        <span class="sidebar-item-icon"><i class="fas fa-award"></i></span>
                        <span class="sidebar-label">Loyalty & Rewards</span>
                    </a>
                </div>
                <div class="sidebar-item">
                    <a href="{{ route('chat') }}" target="_blank"
                       class="{{ request()->routeIs('chat*') ? 'active' : '' }}">
                        <span class="sidebar-item-icon"><i class="fas fa-robot"></i></span>
                        <span class="sidebar-label">AI Assistant</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- ── SETTINGS & SUPPORT ── --}}
        @php
            $supportOpen = request()->routeIs('profile.settings*') ||
                           request()->routeIs('profile.help-support*');
        @endphp
        <div class="nav-group">
            <div class="nav-group-label {{ $supportOpen ? '' : 'collapsed' }}"
                 data-bs-toggle="collapse"
                 data-bs-target="#group-support"
                 aria-expanded="{{ $supportOpen ? 'true' : 'false' }}">
                <span class="group-label-text">Settings & Support</span>
                <i class="fas fa-chevron-down nav-chevron"></i>
            </div>
            <div class="collapse {{ $supportOpen ? 'show' : '' }} nav-group-items" id="group-support">
                <div class="sidebar-item">
                    <a href="{{ route('profile.settings') }}"
                       class="{{ request()->routeIs('profile.settings*') ? 'active' : '' }}">
                        <span class="sidebar-item-icon"><i class="fas fa-sliders-h"></i></span>
                        <span class="sidebar-label">Preferences</span>
                    </a>
                </div>
                <div class="sidebar-item">
                    <a href="{{ route('profile.help-support') }}"
                       class="{{ request()->routeIs('profile.help-support*') ? 'active' : '' }}">
                        <span class="sidebar-item-icon"><i class="fas fa-question-circle"></i></span>
                        <span class="sidebar-label">Help & Support</span>
                    </a>
                </div>
            </div>
        </div>

    </nav>

    {{-- Footer --}}
    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="sidebar-avatar">A</div>
            <div class="sidebar-user-info">
                <div class="sidebar-user-name">Admin User</div>
                <div class="sidebar-user-role">Super Admin</div>
            </div>
        </div>
        <div class="d-flex gap-1">
            <button class="sidebar-collapse-btn theme-toggle-btn" title="Toggle theme">
                <i class="fas fa-sun"></i>
            </button>
            <button class="sidebar-collapse-btn" id="sidebar-collapse-btn" title="Collapse sidebar">
                <i class="fas fa-chevron-left"></i>
            </button>
        </div>
    </div>

</aside>

{{-- Mobile overlay --}}
<div class="sidebar-overlay" id="sidebar-overlay"></div>
