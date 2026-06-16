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
