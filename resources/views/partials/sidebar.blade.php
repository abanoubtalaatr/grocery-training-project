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

        {{-- Dashboard (top-level) --}}
        <div class="sidebar-item mb-1">
            <a href="{{ route('dashboard') }}"
               class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="sidebar-item-icon"><i class="fas fa-home"></i></span>
                <span class="sidebar-label">Dashboard</span>
            </a>
        </div>

        {{-- ── CATALOG ── --}}
        <div class="nav-group">
            <div class="nav-group-label"
                 data-bs-toggle="collapse"
                 data-bs-target="#group-catalog"
                 aria-expanded="true">
                <span class="group-label-text">Catalog</span>
                <i class="fas fa-chevron-down nav-chevron"></i>
            </div>
            <div class="collapse show nav-group-items" id="group-catalog">
                <div class="sidebar-item">
                    <a href="#" class="{{ request()->routeIs('products.*') ? 'active' : '' }}">
                        <span class="sidebar-item-icon"><i class="fas fa-box-open"></i></span>
                        <span class="sidebar-label">Products</span>
                    </a>
                </div>
                <div class="sidebar-item">
                    <a href="#" class="{{ request()->routeIs('categories.*') ? 'active' : '' }}">
                        <span class="sidebar-item-icon"><i class="fas fa-tags"></i></span>
                        <span class="sidebar-label">Categories</span>
                    </a>
                </div>
                <div class="sidebar-item">
                    <a href="#" class="{{ request()->routeIs('vendors.*') ? 'active' : '' }}">
                        <span class="sidebar-item-icon"><i class="fas fa-store"></i></span>
                        <span class="sidebar-label">Vendors</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- ── ORDERS ── --}}
        <div class="nav-group">
            <div class="nav-group-label"
                 data-bs-toggle="collapse"
                 data-bs-target="#group-orders"
                 aria-expanded="true">
                <span class="group-label-text">Orders</span>
                <i class="fas fa-chevron-down nav-chevron"></i>
            </div>
            <div class="collapse show nav-group-items" id="group-orders">
                <div class="sidebar-item">
                    <a href="#" class="{{ request()->routeIs('orders.index') ? 'active' : '' }}">
                        <span class="sidebar-item-icon"><i class="fas fa-shopping-cart"></i></span>
                        <span class="sidebar-label">All Orders</span>
                    </a>
                </div>
                <div class="sidebar-item">
                    <a href="#" class="{{ request()->routeIs('orders.pending') ? 'active' : '' }}">
                        <span class="sidebar-item-icon"><i class="fas fa-clock"></i></span>
                        <span class="sidebar-label">Pending</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- ── USERS ── --}}
        <div class="nav-group">
            <div class="nav-group-label"
                 data-bs-toggle="collapse"
                 data-bs-target="#group-users"
                 aria-expanded="false"
                 class="collapsed">
                <span class="group-label-text">Users</span>
                <i class="fas fa-chevron-down nav-chevron"></i>
            </div>
            <div class="collapse nav-group-items" id="group-users">
                <div class="sidebar-item">
                    <a href="#" class="{{ request()->routeIs('customers.*') ? 'active' : '' }}">
                        <span class="sidebar-item-icon"><i class="fas fa-users"></i></span>
                        <span class="sidebar-label">Customers</span>
                    </a>
                </div>
                <div class="sidebar-item">
                    <a href="#" class="{{ request()->routeIs('admins.*') ? 'active' : '' }}">
                        <span class="sidebar-item-icon"><i class="fas fa-user-shield"></i></span>
                        <span class="sidebar-label">Admins</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- ── MY ACCOUNT ── --}}
        <div class="nav-group">
            <div class="nav-group-label"
                 data-bs-toggle="collapse"
                 data-bs-target="#group-account"
                 aria-expanded="true">
                <span class="group-label-text">My Account</span>
                <i class="fas fa-chevron-down nav-chevron"></i>
            </div>
            <div class="collapse show nav-group-items" id="group-account">
                <div class="sidebar-item">
                    <a href="{{ route('profile.personal-info') }}" class="{{ request()->routeIs('profile.personal-info') ? 'active' : '' }}">
                        <span class="sidebar-item-icon"><i class="fas fa-user"></i></span>
                        <span class="sidebar-label">Personal Info</span>
                    </a>
                </div>
                <div class="sidebar-item">
                    <a href="{{ route('profile.payment-wallet') }}" class="{{ request()->routeIs('profile.payment-wallet') ? 'active' : '' }}">
                        <span class="sidebar-item-icon"><i class="fas fa-wallet"></i></span>
                        <span class="sidebar-label">Payment & Wallet</span>
                    </a>
                </div>
                <div class="sidebar-item">
                    <a href="{{ route('profile.order-history') }}" class="{{ request()->routeIs('profile.order-history') ? 'active' : '' }}">
                        <span class="sidebar-item-icon"><i class="fas fa-history"></i></span>
                        <span class="sidebar-label">Order History</span>
                    </a>
                </div>
                <div class="sidebar-item">
                    <a href="{{ route('profile.smart-lists') }}" class="{{ request()->routeIs('profile.smart-lists') ? 'active' : '' }}">
                        <span class="sidebar-item-icon"><i class="fas fa-list-ul"></i></span>
                        <span class="sidebar-label">Smart Lists</span>
                    </a>
                </div>
                <div class="sidebar-item">
                    <a href="{{ route('profile.addresses') }}" class="{{ request()->routeIs('profile.addresses') ? 'active' : '' }}">
                        <span class="sidebar-item-icon"><i class="fas fa-map-marker-alt"></i></span>
                        <span class="sidebar-label">Addresses</span>
                    </a>
                </div>
                <div class="sidebar-item">
                    <a href="{{ route('profile.security') }}" class="{{ request()->routeIs('profile.security') ? 'active' : '' }}">
                        <span class="sidebar-item-icon"><i class="fas fa-shield-alt"></i></span>
                        <span class="sidebar-label">Security & Login</span>
                    </a>
                </div>
                <div class="sidebar-item">
                    <a href="{{ route('profile.loyalty-rewards') }}" class="{{ request()->routeIs('profile.loyalty-rewards') ? 'active' : '' }}">
                        <span class="sidebar-item-icon"><i class="fas fa-award"></i></span>
                        <span class="sidebar-label">Loyalty & Rewards</span>
                    </a>
                </div>
                <div class="sidebar-item">
                    <a href="{{ route('profile.help-support') }}" class="{{ request()->routeIs('profile.help-support') ? 'active' : '' }}">
                        <span class="sidebar-item-icon"><i class="fas fa-question-circle"></i></span>
                        <span class="sidebar-label">Help & Support</span>
                    </a>
                </div>
                <div class="sidebar-item">
                    <a href="{{ route('profile.settings') }}" class="{{ request()->routeIs('profile.settings') ? 'active' : '' }}">
                        <span class="sidebar-item-icon"><i class="fas fa-sliders-h"></i></span>
                        <span class="sidebar-label">Preferences</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- ── SETTINGS ── --}}
        <div class="nav-group">
            <div class="nav-group-label"
                 data-bs-toggle="collapse"
                 data-bs-target="#group-settings"
                 aria-expanded="false"
                 class="collapsed">
                <span class="group-label-text">Settings</span>
                <i class="fas fa-chevron-down nav-chevron"></i>
            </div>
            <div class="collapse nav-group-items" id="group-settings">
                <div class="sidebar-item">
                    <a href="#" class="{{ request()->routeIs('settings.general') ? 'active' : '' }}">
                        <span class="sidebar-item-icon"><i class="fas fa-cog"></i></span>
                        <span class="sidebar-label">General</span>
                    </a>
                </div>
                <div class="sidebar-item">
                    <a href="#" class="{{ request()->routeIs('settings.permissions') ? 'active' : '' }}">
                        <span class="sidebar-item-icon"><i class="fas fa-lock"></i></span>
                        <span class="sidebar-label">Permissions</span>
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
