@php
    $controllerGroups = [
        'Users & Access' => [
            [
                'label' => 'Users',
                'description' => 'Customer accounts, profile data, authentication state, and loyalty balances.',
                'route' => url('/api/auth/me'),
            ],
            [
                'label' => 'OTP Codes',
                'description' => 'One-time password records used by verification and password reset flows.',
                'route' => url('/api/auth/verify-otp'),
            ],
            [
                'label' => 'User Notification Settings',
                'description' => 'Per-user notification preferences for orders, promotions, insights, and alerts.',
                'route' => url('/api/notification-preferences'),
            ],
        ],
        'Catalog' => [
            [
                'label' => 'Categories',
                'description' => 'Top-level grocery catalog groups and category meal listings.',
                'route' => route('admins.categories.index'),
            ],
            [
                'label' => 'Subcategories',
                'description' => 'Nested catalog groups connected to parent categories and meals.',
                'route' => url('/api/subcategories'),
            ],
            [
                'label' => 'Meals',
                'description' => 'Products, meals, sliders, recommendations, brands, and featured catalog data.',
                'route' => route('admins.meals.index'),
            ],
            [
                'label' => 'Special Notes',
                'description' => 'Reusable special-note records displayed in the application.',
                'route' => url('/api/special-notes'),
            ],
        ],
        'Shopping' => [
            [
                'label' => 'Carts',
                'description' => 'Authenticated user shopping cart containers.',
                'route' => url('/api/cart'),
            ],
            [
                'label' => 'Cart Items',
                'description' => 'Individual meal quantities and options inside a cart.',
                'route' => url('/api/cart/items'),
            ],
            [
                'label' => 'Favorites',
                'description' => 'Saved meals and wishlist-like product preferences for users.',
                'route' => url('/api/favorites'),
            ],
            [
                'label' => 'Smart Lists',
                'description' => 'User-created lists with optional images and attached meals.',
                'route' => url('/api/smart-lists'),
            ],
            [
                'label' => 'Smart List Meals',
                'description' => 'Pivot records connecting smart lists to meals.',
                'route' => url('/api/smart-lists'),
            ],
        ],
        'Orders & Payments' => [
            [
                'label' => 'Orders',
                'description' => 'Checkout orders, status tracking, totals, and payment flow data.',
                'route' => route('admins.orders.index'),
            ],
            [
                'label' => 'Order Items',
                'description' => 'Line items, quantities, and meal details attached to orders.',
                'route' => url('/api/orders'),
            ],
            [
                'label' => 'Order Notes',
                'description' => 'Notes and extra order-level instructions or history.',
                'route' => url('/api/orders'),
            ],
            [
                'label' => 'Offers',
                'description' => 'Discounts, featured offers, and promo-code validation records.',
                'route' => url('/api/offers'),
            ],
        ],
        'Communication' => [
            [
                'label' => 'Notifications',
                'description' => 'In-app notification records, unread counts, and notification history.',
                'route' => url('/api/notifications'),
            ],
            [
                'label' => 'Chatbot Messages',
                'description' => 'Stored chatbot conversation messages and suggestion history.',
                'route' => url('/api/chatbot/history'),
            ],
            [
                'label' => 'Contact Messages',
                'description' => 'Contact form submissions and admin response workflow data.',
                'route' => url('/api/contact'),
            ],
            [
                'label' => 'Support Reports',
                'description' => 'Authenticated customer support and issue report records.',
                'route' => url('/api/support/report'),
            ],
        ],
        'Content' => [
            [
                'label' => 'FAQs',
                'description' => 'Frequently asked questions, categories, ordering, and active status.',
                'route' => url('/api/faqs'),
            ],
            [
                'label' => 'Static Pages',
                'description' => 'CMS-style pages, slugs, metadata, published state, and footer pages.',
                'route' => url('/api/pages'),
            ],
            [
                'label' => 'Reviews',
                'description' => 'Meal reviews, ratings, and user feedback records.',
                'route' => url('/api/v1/reviews'),
            ],
        ],
        'Settings & Shared' => [
            [
                'label' => 'Addresses',
                'description' => 'Saved user delivery addresses and default address handling.',
                'route' => url('/api/addresses'),
            ],
            [
                'label' => 'Settings',
                'description' => 'Application settings, contact information, social links, logo, and favicon.',
                'route' => url('/api/settings'),
            ],
        ],
    ];
@endphp

<aside class="sidebar p-3 p-lg-4" aria-label="Model navigation">
    <div class="d-flex align-items-center gap-3 border-bottom border-secondary-subtle pb-4">
        <span class="sidebar__mark rounded fw-bold text-white">G</span>
        <div>
            <strong class="d-block">Grocery Admin</strong>
            <small class="d-block text-secondary">Model Map</small>
        </div>
    </div>

    <nav class="pt-4">
        @foreach ($controllerGroups as $groupName => $controllers)
            <section class="mb-4">
                <h2 class="sidebar__group-title mb-2 fw-bold text-uppercase">{{ $groupName }}</h2>

                <ul class="nav nav-pills flex-column gap-1">
                    @foreach ($controllers as $controller)
                        <li class="nav-item">
                            <a class="sidebar__link nav-link rounded-2 px-3 py-2" href="{{ $controller['route'] ?? '#' }}" title="{{ $controller['description'] }}">
                                <span class="d-block fw-semibold">{{ $controller['label'] }}</span>
                                <small class="text-wrap sidebar__path d-block text-truncate">{{ $controller['description'] }}</small>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endforeach
    </nav>
</aside>
